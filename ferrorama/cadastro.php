<?php
/*
  cadastro.php — cadastro público. Cria SEMPRE o papel "operador":
  administrador e técnico só podem ser criados por um administrador (usuarios/cadastrar.php).
*/
session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax']);
require __DIR__ . '/config/conexao.php';

if (isset($_SESSION['usuario_id'])) { header('Location: principal.php'); exit; }

$h = function ($t): string { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); };
$campo = function (string $n): string { $v = $_POST[$n] ?? ''; return is_string($v) ? $v : ''; };

$erro = ''; $nome = ''; $login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($campo('nome')); $login = trim($campo('login'));
    $senha = $campo('senha'); $confirmar = $campo('confirmar_senha');

    if ($nome === '' || $login === '' || $senha === '' || $confirmar === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um email válido.';
    } elseif (mb_strlen($nome, 'UTF-8') > 100 || mb_strlen($login, 'UTF-8') > 100) {
        $erro = 'Nome e email aceitam no máximo 100 caracteres.';
    } elseif (strlen($senha) < 8 || strlen($senha) > 72) {
        $erro = 'A senha precisa ter de 8 a 72 caracteres.';
    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas não são iguais.';
    } else {
        $hash  = password_hash($senha, PASSWORD_DEFAULT);
        $papel = 'operador';
        $stmt  = $conexao->prepare('INSERT INTO usuario (nome, login, senha, papel) VALUES (?, ?, ?, ?)');
        if ($stmt === false) {
            $erro = 'Erro ao gravar. Confira se o banco/script.sql foi executado.';
        } else {
            $stmt->bind_param('ssss', $nome, $login, $hash, $papel);
            if ($stmt->execute()) {
                $stmt->close();
                header('Location: index.php?cadastrado=1');
                exit;
            }
            $erro = ($stmt->errno === 1062) ? 'Já existe um usuário com esse email.' : 'Não foi possível cadastrar. Tente novamente.';
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GoMobi — Sign up</title>
  <link rel="icon" type="image/png" href="assets/img/favicon.png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Lato:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
  <div class="auth-page">
    <div class="auth-card">
      <img src="assets/img/logo.png" alt="GoMobi" class="logo-mark" />
      <h1 class="auth-title">Create account</h1>
      <p class="auth-subtitle">Sign up for GoMobi</p>
      <form class="auth-form" method="post" action="cadastro.php">
        <div class="field"><label for="nome">Name</label>
          <input type="text" id="nome" name="nome" maxlength="100" required value="<?= $h($nome) ?>" /></div>
        <div class="field"><label for="login">Email</label>
          <input type="email" id="login" name="login" maxlength="100" required value="<?= $h($login) ?>" /></div>
        <div class="field"><label for="senha">Password</label>
          <input type="password" id="senha" name="senha" placeholder="At least 8 characters" maxlength="72" required /></div>
        <div class="field"><label for="confirmar_senha">Confirm Password</label>
          <input type="password" id="confirmar_senha" name="confirmar_senha" maxlength="72" required /></div>
<?php if ($erro !== ''): ?>
        <p class="form-error"><?= $h($erro) ?></p>
<?php endif; ?>
        <button type="submit" class="btn btn-primary btn-full">Sign up</button>
      </form>
      <p class="auth-footer">Already have an account? <a href="index.php">Login</a></p>
    </div>
  </div>
</body>
</html>
