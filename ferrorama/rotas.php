<?php
$raiz   = '';
$pagina = 'routes';
$titulo = 'Routes';
require __DIR__ . '/includes/dados_simulados.php';
require __DIR__ . '/includes/layout_inicio.php';
?>
        <h1 class="page-title">Routes</h1>
        <p class="page-subtitle">Railway routes and the trains assigned to them</p>
        <div class="table-wrap">
          <table>
            <thead><tr><th>ID</th><th>Origin</th><th>Destination</th><th>Train</th><th>Distance</th><th>Duration</th><th>Status</th></tr></thead>
            <tbody>
<?php foreach ($rotas_simuladas as $r): ?>
              <tr>
                <td><?= h($r['id']) ?></td><td><?= h($r['origem']) ?></td><td><?= h($r['destino']) ?></td>
                <td><a href="trem.php?id=<?= h($r['trem']) ?>"><?= h($r['trem']) ?></a></td>
                <td><?= h($r['distancia']) ?></td><td><?= h($r['duracao']) ?></td>
                <td><span class="badge <?= classe_badge($r['status']) ?>"><?= h($r['status']) ?></span></td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
<?php require __DIR__ . '/includes/layout_fim.php'; ?>
