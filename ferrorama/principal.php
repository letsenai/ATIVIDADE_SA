<?php
$raiz   = '';
$pagina = 'dashboard';
$titulo = 'Dashboard';
require __DIR__ . '/includes/dados_simulados.php';
require_once __DIR__ . '/includes/proteger.php';
require __DIR__ . '/config/conexao.php';
require __DIR__ . '/includes/layout_inicio.php';

$contar = function (string $sql) use ($conexao): int {
    $r = $conexao->query($sql);
    if (!$r) { return 0; }
    $n = (int) $r->fetch_row()[0];
    $r->free();
    return $n;
};
$n_trens    = $contar("SELECT COUNT(*) FROM trem WHERE status = 'Online'");
$n_sensores = $contar("SELECT COUNT(*) FROM sensor WHERE status = 'Online'");
$vel_media  = $contar("SELECT IFNULL(ROUND(AVG(velocidade)), 0) FROM trem WHERE status <> 'Offline'");

$ativos = array_filter($alertas_simulados, function ($a) { return $a['status'] === 'Ativo'; });
$icone = function (string $caminhos): string {
    return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $caminhos . '</svg>';
};
?>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Real-time overview of railway operations</p>

        <div class="stats-grid">
          <div class="card stat-card"><div><p class="stat-label">Active Trains</p><p class="stat-value"><?= $n_trens ?></p></div>
            <div class="stat-icon blue"><?= $icone('<rect x="5" y="3" width="14" height="12" rx="3"/><line x1="5" y1="10" x2="19" y2="10"/><circle cx="8.5" cy="18" r="1.5"/><circle cx="15.5" cy="18" r="1.5"/>') ?></div></div>
          <div class="card stat-card"><div><p class="stat-label">Online Sensors</p><p class="stat-value"><?= $n_sensores ?></p></div>
            <div class="stat-icon green"><?= $icone('<circle cx="12" cy="12" r="2"/><path d="M8.5 8.5a5 5 0 0 0 0 7"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/>') ?></div></div>
          <div class="card stat-card"><div><p class="stat-label">Active Alerts</p><p class="stat-value"><?= count($ativos) ?></p></div>
            <div class="stat-icon red"><?= $icone('<path d="M12 9v4"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>') ?></div></div>
          <div class="card stat-card"><div><p class="stat-label">Average Speed</p><p class="stat-value"><?= $vel_media ?> km/h</p></div>
            <div class="stat-icon purple"><?= $icone('<polyline points="3 12 8 12 10 6 14 18 16 12 21 12"/>') ?></div></div>
        </div>

        <div class="card alerts-card">
          <p class="alerts-title">Recent Alerts</p>
<?php if (!$ativos): ?>
          <p class="no-alerts">No active alerts</p>
<?php else: foreach ($ativos as $a): ?>
          <p><span class="badge <?= classe_badge($a['nivel']) ?>"><?= h($a['nivel']) ?></span>
             <strong><?= h($a['titulo']) ?></strong> — <?= h($a['trem']) ?> (<?= h($a['hora']) ?>)</p>
<?php endforeach; endif; ?>
        </div>
<?php require __DIR__ . '/includes/layout_fim.php'; ?>
