<?php
/*
  index.php — tela de login, porta de entrada do sistema.
  Mesmo visual do login.html original; a conferência agora acontece no servidor.
*/

// A sessão começa antes de qualquer saída na tela
session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax']);

require __DIR__ . '/config/conexao.php';

// Quem já entrou vai direto para a tela principal
if (isset($_SESSION['usuario_id'])) {
    header('Location: principal.php');
    exit;
}

$erro  = '';
$login = '';

// A conferência só roda quando o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login          = is_string($_POST['login'] ?? null) ? trim($_POST['login']) : '';
    $senha_digitada = is_string($_POST['senha'] ?? null) ? $_POST['senha'] : '';

    if ($login === '' || $senha_digitada === '') {
        $erro = 'Preencha email e senha.';
    } else {
        // Consulta preparada: o que veio do formulário nunca é concatenado no SQL
        $stmt = $conexao->prepare('SELECT id, nome, login, senha, papel FROM usuario WHERE login = ?');

        if ($stmt === false) {
            $erro = 'Erro ao consultar o banco de dados. Confira se o script banco/script.sql foi executado.';
        } else {
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $stmt->bind_result($id_banco, $nome_banco, $login_banco, $hash_banco, $papel_banco);
            $achou = ($stmt->fetch() === true);   // false/null quando o login não existe
            $stmt->close();

            if ($achou && password_verify($senha_digitada, $hash_banco)) {
                // Senha confere: troca o id da sessão e guarda quem entrou e com qual papel
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $id_banco;
                $_SESSION['nome']       = $nome_banco;
                $_SESSION['login']      = $login_banco;
                $_SESSION['papel']      = $papel_banco;

                header('Location: principal.php');
                exit;   // sem o exit o código continuaria rodando
            }

            // Mesma mensagem para login inexistente e senha errada
            $erro = 'Email ou senha incorretos.';
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GoMobi — Login</title>
  <link rel="icon" type="image/png" href="assets/img/favicon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Lato:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

  <div class="auth-page">
    <div class="auth-card">
      <img src="assets/img/logo.png" alt="GoMobi" class="logo-mark" />
      <h1 class="auth-title">Welcome back</h1>
      <p class="auth-subtitle">Login to your GoMobi account</p>

      <form class="auth-form" id="form-login" method="post" action="index.php">
        <div class="field">
          <label for="login">Email</label>
          <input type="email" id="login" name="login" placeholder="admin@gomobi.com" maxlength="100" required
                 value="<?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?>" />
        </div>
        <div class="field">
          <label for="senha">Password</label>
          <input type="password" id="senha" name="senha" placeholder="Enter your password" required />
        </div>

        <?php if ($erro !== ''): ?>
        <p class="form-error" id="mensagem-erro"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary btn-full">Login</button>
      </form>

      <p class="auth-footer"><a href="recuperar.php">Forgot your password?</a></p>
      <p class="auth-footer">Don't have an account? <a href="cadastro.php">Sign up</a></p>
    </div>
  </div>

</body>
</html>
