<?php

session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax']);
require __DIR__ . '/config/conexao.php';

$h = function ($t): string { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); };
$campo = function (string $n): string { $v = $_POST[$n] ?? ''; return is_string($v) ? $v : ''; };

$token = (isset($_GET['token']) && is_string($_GET['token'])) ? $_GET['token'] : '';
$etapa = ($token !== '') ? 'nova' : 'pedir';
$erro = ''; $msg = ''; $link = '';

if ($etapa === 'pedir' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($campo('login'));
    if ($login === '') {
        $erro = 'Informe o email.';
    } else {
        $stmt = $conexao->prepare('SELECT id FROM usuario WHERE login = ?');
        if ($stmt === false) {
            $erro = 'Erro no banco. Confira se o banco/script.sql foi executado.';
        } else {
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $stmt->bind_result($uid);
            $achou = ($stmt->fetch() === true);
            $stmt->close();

            if ($achou) {
                $novo = bin2hex(random_bytes(32));
                $hash = hash('sha256', $novo);
                $ins = $conexao->prepare('INSERT INTO recuperacao_senha (usuario_id, token_hash, expira_em) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))');
                if ($ins !== false) {
                    $ins->bind_param('is', $uid, $hash);
                    $ins->execute();
                    $ins->close();
                    $link = 'recuperar.php?token=' . $novo;
                }
            }
            $msg = 'Se o email estiver cadastrado, um link de redefinição foi gerado.';
        }
    }
}

if ($etapa === 'nova') {
    $hash = hash('sha256', $token);
    $stmt = $conexao->prepare('SELECT id, usuario_id FROM recuperacao_senha WHERE token_hash = ? AND usado = 0 AND expira_em > NOW()');
    $valido = false;
    if ($stmt !== false) {
        $stmt->bind_param('s', $hash);
        $stmt->execute();
        $stmt->bind_result($rid, $uid);
        $valido = ($stmt->fetch() === true);
        $stmt->close();
    }

    if (!$valido) {
        $etapa = 'invalido';
        $erro = 'Link inválido ou expirado. Peça um novo.';
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $senha = $campo('senha'); $confirmar = $campo('confirmar_senha');
        if (strlen($senha) < 8 || strlen($senha) > 72) {
            $erro = 'A senha precisa ter de 8 a 72 caracteres.';
        } elseif ($senha !== $confirmar) {
            $erro = 'As senhas não são iguais.';
        } else {
            $novo_hash = password_hash($senha, PASSWORD_DEFAULT);
            $up = $conexao->prepare('UPDATE usuario SET senha = ? WHERE id = ?');
            $up->bind_param('si', $novo_hash, $uid);
            $up->execute();
            $up->close();
            $us = $conexao->prepare('UPDATE recuperacao_senha SET usado = 1 WHERE id = ?');
            $us->bind_param('i', $rid);
            $us->execute();
            $us->close();
            $etapa = 'ok';
            $msg = 'Senha atualizada com sucesso.';
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GoMobi — Password recovery</title>
  <link rel="icon" type="image/png" href="assets/img/favicon.png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Lato:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
  <div class="auth-page">
    <div class="auth-card">
      <img src="assets/img/logo.png" alt="GoMobi" class="logo-mark" />
      <h1 class="auth-title">Recover password</h1>

<?php if ($etapa === 'pedir'): ?>
      <p class="auth-subtitle">Enter your email to receive a reset link</p>
      <form class="auth-form" method="post" action="recuperar.php">
        <div class="field"><label for="login">Email</label>
          <input type="email" id="login" name="login" maxlength="100" required /></div>
<?php if ($erro !== ''): ?><p class="form-error"><?= $h($erro) ?></p><?php endif; ?>
<?php if ($msg !== ''): ?><p class="form-success"><?= $h($msg) ?></p><?php endif; ?>
<?php if ($link !== ''): ?>
        <p class="form-success">Modo demonstração (sem servidor de e-mail): <a href="<?= $h($link) ?>">clique aqui para redefinir</a>. O link vale por 30 minutos.</p>
<?php endif; ?>
        <button type="submit" class="btn btn-primary btn-full">Send link</button>
      </form>

<?php elseif ($etapa === 'nova'): ?>
      <p class="auth-subtitle">Choose a new password</p>
      <form class="auth-form" method="post" action="recuperar.php?token=<?= $h(urlencode($token)) ?>">
        <div class="field"><label for="senha">New Password</label>
          <input type="password" id="senha" name="senha" placeholder="At least 8 characters" maxlength="72" required /></div>
        <div class="field"><label for="confirmar_senha">Confirm Password</label>
          <input type="password" id="confirmar_senha" name="confirmar_senha" maxlength="72" required /></div>
<?php if ($erro !== ''): ?><p class="form-error"><?= $h($erro) ?></p><?php endif; ?>
        <button type="submit" class="btn btn-primary btn-full">Update password</button>
      </form>

<?php elseif ($etapa === 'ok'): ?>
      <p class="form-success"><?= $h($msg) ?></p>
<?php else: ?>
      <p class="form-error"><?= $h($erro) ?></p>
<?php endif; ?>

      <p class="auth-footer"><a href="index.php">Back to login</a></p>
    </div>
  </div>
</body>
</html>
