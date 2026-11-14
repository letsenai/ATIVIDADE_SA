<?php
$raiz   = '../';
$pagina = 'users';
$titulo = 'Users';
require __DIR__ . '/../config/conexao.php';
require __DIR__ . '/../includes/layout_inicio.php';

$usuarios = [];
$res = $conexao->query('SELECT id, nome, login, papel FROM usuario ORDER BY nome');
if ($res) {
    while ($linha = $res->fetch_assoc()) { $usuarios[] = $linha; }
    $res->free();
}
?>
        <div class="sensors-header">
          <div>
            <h1 class="page-title">Users</h1>
            <p class="page-subtitle">Accounts registered in the system</p>
          </div>
          <a class="btn btn-primary" href="cadastrar.php">Register User</a>
        </div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr></thead>
            <tbody>
<?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?= (int) $u['id'] ?></td><td><?= h($u['nome']) ?></td><td><?= h($u['login']) ?></td>
                <td><span class="badge badge-viewer"><?= h(PAPEIS[$u['papel']] ?? $u['papel']) ?></span></td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
<?php require __DIR__ . '/../includes/layout_fim.php'; ?>
