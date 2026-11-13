<?php
$raiz   = '';
$pagina = 'trains';
$titulo = 'Train Detail';
require_once __DIR__ . '/includes/proteger.php';
require __DIR__ . '/config/conexao.php';

$busca = (isset($_GET['id']) && is_string($_GET['id'])) ? trim($_GET['id']) : '';
$id_num = (int) $busca;
$st = $conexao->prepare('SELECT id, codigo, linha, status, proxima_estacao, velocidade FROM trem WHERE codigo = ? OR id = ?');
$st->bind_param('si', $busca, $id_num);
$st->execute();
$st->bind_result($tid, $codigo, $linha, $status, $prox, $vel);
$achou = ($st->fetch() === true);
$st->close();

$sensores = [];
if ($achou) {
    $st = $conexao->prepare('SELECT codigo, nome, tipo, localizacao, threshold, status FROM sensor WHERE trem_id = ? ORDER BY codigo');
    $st->bind_param('i', $tid);
    $st->execute();
    $res = $st->get_result();
    while ($l = $res->fetch_assoc()) { $sensores[] = $l; }
    $st->close();
}
require __DIR__ . '/includes/layout_inicio.php';
?>
        <a href="trens.php" class="back-link">← Back</a>
<?php if (!$achou): ?>
        <p class="no-sensors-text">Train not found.</p>
<?php else: ?>
        <h1 class="page-title"><?= h($codigo) ?> — <?= h($linha) ?></h1>
        <p class="page-subtitle"><span class="badge badge-<?= h(strtolower($status)) ?>"><?= h($status) ?></span>
          &nbsp; Next station: <?= h($prox ?: '-') ?> &nbsp; Speed: <?= (int) $vel ?> km/h</p>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Sensor</th><th>Name</th><th>Type</th><th>Location</th><th>Threshold</th><th>Status</th></tr></thead>
            <tbody>
<?php if (!$sensores): ?>
              <tr><td colspan="6">No sensors linked to this train.</td></tr>
<?php else: foreach ($sensores as $s): ?>
              <tr><td><?= h($s['codigo']) ?></td><td><?= h($s['nome']) ?></td><td><?= h($s['tipo']) ?></td><td><?= h($s['localizacao']) ?></td>
                  <td><?= h($s['threshold']) ?></td><td><span class="badge badge-<?= h(strtolower($s['status'])) ?>"><?= h($s['status']) ?></span></td></tr>
<?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/layout_fim.php'; ?>
