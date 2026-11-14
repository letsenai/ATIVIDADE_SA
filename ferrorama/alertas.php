<?php
$raiz   = '';
$pagina = 'alerts';
$titulo = 'Alerts';
require __DIR__ . '/includes/dados_simulados.php';
require __DIR__ . '/includes/layout_inicio.php';

$niveis = ['Crítico', 'Atenção', 'Informativo'];
$filtro = (isset($_GET['nivel']) && is_string($_GET['nivel']) && in_array($_GET['nivel'], $niveis, true)) ? $_GET['nivel'] : '';
$lista  = array_filter($alertas_simulados, function ($a) use ($filtro) {
    return $filtro === '' || $a['nivel'] === $filtro;
});
?>
        <h1 class="page-title">Alerts &amp; Notifications</h1>
        <p class="page-subtitle">Occurrences classified by severity</p>

        <div class="filters-row">
          <a class="btn <?= $filtro === '' ? 'btn-primary' : 'btn-secondary' ?>" href="alertas.php">All</a>
<?php foreach ($niveis as $n): ?>
          <a class="btn <?= $filtro === $n ? 'btn-primary' : 'btn-secondary' ?>" href="alertas.php?nivel=<?= urlencode($n) ?>"><?= h($n) ?></a>
<?php endforeach; ?>
        </div>

        <div class="table-wrap">
          <table>
            <thead><tr><th>ID</th><th>Severity</th><th>Description</th><th>Train</th><th>Sensor</th><th>Time</th><th>Status</th></tr></thead>
            <tbody>
<?php if (!$lista): ?>
              <tr><td colspan="7">No alerts found.</td></tr>
<?php else: foreach ($lista as $a): ?>
              <tr>
                <td><?= h($a['id']) ?></td>
                <td><span class="badge <?= classe_badge($a['nivel']) ?>"><?= h($a['nivel']) ?></span></td>
                <td><?= h($a['titulo']) ?></td>
                <td><a href="trem.php?id=<?= h($a['trem']) ?>"><?= h($a['trem']) ?></a></td>
                <td><?= h($a['sensor']) ?></td><td><?= h($a['hora']) ?></td>
                <td><span class="badge <?= classe_badge($a['status']) ?>"><?= h($a['status']) ?></span></td>
              </tr>
<?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
<?php require __DIR__ . '/includes/layout_fim.php'; ?>
