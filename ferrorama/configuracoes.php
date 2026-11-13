<?php
$raiz   = '';
$pagina = 'settings';
$titulo = 'Settings';
require_once __DIR__ . '/includes/proteger.php';
require __DIR__ . '/config/conexao.php';

$erro = ''; $ok = '';
$campo = function (string $n): string { $v = $_POST[$n] ?? ''; return is_string($v) ? $v : ''; };

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atual = $campo('senha_atual'); $nova = $campo('nova_senha'); $conf = $campo('confirmar_nova_senha');

    if ($atual === '' || $nova === '' || $conf === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (strlen($nova) < 8 || strlen($nova) > 72) {
        $erro = 'A nova senha precisa ter de 8 a 72 caracteres.';
    } elseif ($nova !== $conf) {
        $erro = 'As senhas não conferem.';
    } else {
        $id = (int) $_SESSION['usuario_id'];
        $stmt = $conexao->prepare('SELECT senha FROM usuario WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($hash_atual);
        $achou = ($stmt->fetch() === true);
        $stmt->close();

        if (!$achou || !password_verify($atual, $hash_atual)) {
            $erro = 'Senha atual incorreta.';
        } else {
            $novo_hash = password_hash($nova, PASSWORD_DEFAULT);
            $up = $conexao->prepare('UPDATE usuario SET senha = ? WHERE id = ?');
            $up->bind_param('si', $novo_hash, $id);
            $up->execute();
            $up->close();
            $ok = 'Senha atualizada com sucesso.';
        }
    }
}

require __DIR__ . '/includes/layout_inicio.php';
?>
        <h1 class="page-title">Settings</h1>
        <p class="page-subtitle">Your profile and account</p>
        <div class="settings-wrap">
          <div class="card">
            <div class="settings-card-header">Profile Information</div>
            <div class="profile-grid">
              <div class="profile-field"><p class="label">Name</p><p class="value"><?= h($_SESSION['nome'] ?? '-') ?></p></div>
              <div class="profile-field"><p class="label">Email</p><p class="value"><?= h($_SESSION['login']) ?></p></div>
              <div class="profile-field"><p class="label">Role</p><p class="value"><span class="badge badge-viewer"><?= h(PAPEIS[$_SESSION['papel']] ?? $_SESSION['papel']) ?></span></p></div>
            </div>
          </div>

          <div class="card">
            <div class="settings-card-header">Change Password</div>
            <form class="password-form" method="post" action="configuracoes.php">
              <div class="field"><label for="senha_atual">Current Password</label><input type="password" id="senha_atual" name="senha_atual" maxlength="72" /></div>
              <div class="field"><label for="nova_senha">New Password</label><input type="password" id="nova_senha" name="nova_senha" maxlength="72" /></div>
              <div class="field"><label for="confirmar_nova_senha">Confirm New Password</label><input type="password" id="confirmar_nova_senha" name="confirmar_nova_senha" maxlength="72" /></div>
<?php if ($erro !== ''): ?><p class="form-error"><?= h($erro) ?></p><?php endif; ?>
<?php if ($ok !== ''): ?><p class="form-success"><?= h($ok) ?></p><?php endif; ?>
              <button type="submit" class="btn btn-primary" style="align-self: flex-start;">Update Password</button>
            </form>
          </div>

          <div class="card">
            <div class="settings-card-header">Account Actions</div>
            <a class="btn btn-danger" href="logout.php">Logout</a>
          </div>
        </div>
<?php require __DIR__ . '/includes/layout_fim.php'; ?>
