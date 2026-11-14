<?php
/*
  usuarios/cadastrar.php — cadastro de usuários com papéis.

  A regra de quem pode cadastrar o quê vale AQUI, no servidor, antes de gravar.
  Esconder o link ou o campo na tela é só conforto; quem protege é este arquivo.

  Sequência de verificações (a ordem importa):
    1. existe sessão?                         → includes/proteger.php
    2. o papel de quem está logado cadastra?  → pode_cadastrar()
    3. o papel pedido é permitido a ele?      → papeis_que_pode_criar()
    4. campos preenchidos e no tamanho certo
    5. as duas senhas conferem
    6. o login já existe?
    7. só então: password_hash e gravação
*/

$raiz = '../';   // esta página está dentro de uma pasta (usuarios/)

$pagina = 'users';
require_once __DIR__ . '/../includes/proteger.php';    // 1
require_once __DIR__ . '/../includes/permissao.php';
require __DIR__ . '/../config/conexao.php';

$papel_logado = $_SESSION['papel'];
$pode_cadastrar = pode_cadastrar($papel_logado);   // 2

$h = function (string $texto): string {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
};

// Lê um campo do POST sempre como texto (campo[]=x chegaria como array)
$campo = function (string $nome): string {
    $valor = $_POST[$nome] ?? '';
    return is_string($valor) ? $valor : '';
};

$erro    = '';
$sucesso = isset($_GET['ok']);
$nome    = '';
$login   = '';
$papel   = '';

if (!$pode_cadastrar) {
    http_response_code(403);   // sem permissão: não lê o POST e não grava nada
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($campo('nome'));
    $login     = trim($campo('login'));
    $papel     = $campo('papel');
    $senha     = $campo('senha');
    $confirmar = $campo('confirmar_senha');

    $papeis_permitidos = papeis_que_pode_criar($papel_logado);

    if (!array_key_exists($papel, $papeis_permitidos)) {                       // 3
        $erro = 'Escolha um papel permitido para o seu perfil.';
    } elseif ($nome === '' || $login === '' || $senha === '' || $confirmar === '') {   // 4
        $erro = 'Preencha todos os campos.';
    } elseif (mb_strlen($nome, 'UTF-8') > 100 || mb_strlen($login, 'UTF-8') > 100) {
        $erro = 'Nome e email aceitam no máximo 100 caracteres.';
    } elseif (strlen($senha) < 8) {
        $erro = 'A senha precisa ter pelo menos 8 caracteres.';
    } elseif (strlen($senha) > 72) {   // o bcrypt só considera os 72 primeiros bytes
        $erro = 'A senha aceita no máximo 72 caracteres.';
    } elseif ($senha !== $confirmar) {                                         // 5
        $erro = 'As senhas não são iguais.';
    } else {
        // 6: o login já existe?
        $stmt = $conexao->prepare('SELECT id FROM usuario WHERE login = ?');

        if ($stmt === false) {
            $erro = 'Erro ao consultar o banco de dados. Confira se o script banco/script.sql foi executado.';
        } else {
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $stmt->store_result();
            $ja_existe = ($stmt->num_rows > 0);
            $stmt->close();

            if ($ja_existe) {
                $erro = 'Já existe um usuário com esse email.';
            } else {
                // 7: hash da senha e gravação
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $conexao->prepare('INSERT INTO usuario (nome, login, senha, papel) VALUES (?, ?, ?, ?)');

                if ($stmt === false) {
                    $erro = 'Erro ao gravar no banco de dados.';
                } else {
                    $stmt->bind_param('ssss', $nome, $login, $hash, $papel);

                    if ($stmt->execute()) {
                        $stmt->close();
                        // Redireciona para que atualizar a página não envie o formulário de novo
                        header('Location: cadastrar.php?ok=1');
                        exit;
                    }

                    // 1062 = chave duplicada (alguém cadastrou o mesmo login entre a verificação e a gravação)
                    $erro = ($stmt->errno === 1062)
                        ? 'Já existe um usuário com esse email.'
                        : 'Não foi possível gravar o usuário. Tente novamente.';
                    $stmt->close();
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GoMobi — Register User</title>
  <link rel="icon" type="image/png" href="<?= $raiz ?>assets/img/favicon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Lato:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= $raiz ?>assets/css/style.css" />
</head>

<body data-page="users">

  <div class="app">

<?php require __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="main-column">
<?php require __DIR__ . '/../includes/cabecalho.php'; ?>

      <main class="content">
        <h1 class="page-title">Register User</h1>
        <p class="page-subtitle">Create an account and choose its role</p>

<?php if (!$pode_cadastrar): ?>
        <div class="card alerts-card">
          <p class="alerts-title">Access denied</p>
          <p class="no-alerts">Seu perfil não tem permissão para cadastrar usuários.</p>
        </div>
<?php else: ?>
        <div class="card add-sensor-form">
          <form class="form-grid" id="form-usuario" method="post" action="cadastrar.php" autocomplete="off">
            <div class="field">
              <label for="nome">Name</label>
              <input type="text" id="nome" name="nome" placeholder="Anika Bergström" maxlength="100" required
                     value="<?= $h($nome) ?>" />
            </div>
            <div class="field">
              <label for="login">Email</label>
              <input type="email" id="login" name="login" placeholder="anika@example.com" maxlength="100" required
                     value="<?= $h($login) ?>" />
            </div>
            <div class="field">
              <label for="papel">Role</label>
              <select id="papel" name="papel" required>
                <option value="">Select a role</option>
<?php foreach (papeis_que_pode_criar($papel_logado) as $valor => $rotulo): ?>
                <option value="<?= $h($valor) ?>"<?= $papel === $valor ? ' selected' : '' ?>><?= $h($rotulo) ?></option>
<?php endforeach; ?>
              </select>
            </div>
            <div class="field"></div>
            <div class="field">
              <label for="senha">Password</label>
              <input type="password" id="senha" name="senha" placeholder="At least 8 characters" maxlength="72" required />
            </div>
            <div class="field">
              <label for="confirmar-senha">Confirm Password</label>
              <input type="password" id="confirmar-senha" name="confirmar_senha" placeholder="Re-enter password" maxlength="72" required />
            </div>

<?php if ($erro !== ''): ?>
            <p class="form-error" style="grid-column: 1 / -1;"><?= $h($erro) ?></p>
<?php elseif ($sucesso): ?>
            <p class="form-success" style="grid-column: 1 / -1;">Usuário cadastrado com sucesso.</p>
<?php endif; ?>

            <div class="form-actions" style="grid-column: 1 / -1;">
              <button type="submit" class="btn btn-primary">Register User</button>
            </div>
          </form>
        </div>
<?php endif; ?>
      </main>
    </div>
  </div>

  <script src="<?= $raiz ?>assets/js/common.js"></script>
</body>
</html>
