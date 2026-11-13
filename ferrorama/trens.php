<?php
$raiz   = '';
$pagina = 'trains';
$titulo = 'Train Monitoring';
require_once __DIR__ . '/includes/proteger.php';
require __DIR__ . '/config/conexao.php';

$pode = pode_editar('trains', $_SESSION['papel']);
$campo = function (string $n): string { $v = $_POST[$n] ?? ''; return is_string($v) ? trim($v) : ''; };
$erro = '';
$f = ['id' => 0, 'codigo' => '', 'linha' => '', 'status' => 'Online', 'proxima_estacao' => '', 'velocidade' => 0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $erro = 'Sessão expirada. Recarregue a página e tente de novo.';
    } elseif (!$pode) {
        http_response_code(403);
        $erro = 'Seu perfil não pode alterar trens.';
    } elseif ($campo('acao') === 'excluir') {
        $id = (int) $campo('id');
        $st = $conexao->prepare('DELETE FROM trem WHERE id = ?');
        $st->bind_param('i', $id);
        $st->execute();
        $st->close();
        flash('Trem excluído.');
        header('Location: trens.php'); exit;
    } elseif ($campo('acao') === 'salvar') {
        $f = ['id' => (int) $campo('id'), 'codigo' => $campo('codigo'), 'linha' => $campo('linha'),
              'status' => $campo('status'), 'proxima_estacao' => $campo('proxima_estacao'), 'velocidade' => (int) $campo('velocidade')];
        if ($f['codigo'] === '' || $f['linha'] === '') {
            $erro = 'Informe o código e a linha.';
        } elseif (mb_strlen($f['codigo']) > 20 || mb_strlen($f['linha']) > 50 || mb_strlen($f['proxima_estacao']) > 80) {
            $erro = 'Algum campo passou do tamanho máximo.';
        } elseif (!in_array($f['status'], STATUS_LISTA, true)) {
            $erro = 'Status inválido.';
        } elseif ($f['velocidade'] < 0 || $f['velocidade'] > 400) {
            $erro = 'A velocidade deve ficar entre 0 e 400 km/h.';
        } else {
            if ($f['id'] > 0) {
                $st = $conexao->prepare('UPDATE trem SET codigo=?, linha=?, status=?, proxima_estacao=?, velocidade=? WHERE id=?');
                $st->bind_param('ssssii', $f['codigo'], $f['linha'], $f['status'], $f['proxima_estacao'], $f['velocidade'], $f['id']);
            } else {
                $st = $conexao->prepare('INSERT INTO trem (codigo, linha, status, proxima_estacao, velocidade) VALUES (?, ?, ?, ?, ?)');
                $st->bind_param('ssssi', $f['codigo'], $f['linha'], $f['status'], $f['proxima_estacao'], $f['velocidade']);
            }
            if ($st->execute()) {
                $st->close();
                flash($f['id'] > 0 ? 'Trem atualizado.' : 'Trem cadastrado.');
                header('Location: trens.php'); exit;
            }
            $erro = ($st->errno === 1062) ? 'Já existe um trem com esse código.' : 'Não foi possível salvar.';
            $st->close();
        }
    }
} elseif ($pode && isset($_GET['editar'])) {
    $id = (int) $_GET['editar'];
    $st = $conexao->prepare('SELECT id, codigo, linha, status, proxima_estacao, velocidade FROM trem WHERE id = ?');
    $st->bind_param('i', $id);
    $st->execute();
    $st->bind_result($f['id'], $f['codigo'], $f['linha'], $f['status'], $f['proxima_estacao'], $f['velocidade']);
    $st->fetch();
    $st->close();
}

$trens = [];
$res = $conexao->query('SELECT id, codigo, linha, status, proxima_estacao, velocidade FROM trem ORDER BY codigo');
if ($res) { while ($l = $res->fetch_assoc()) { $trens[] = $l; } $res->free(); }

require __DIR__ . '/includes/layout_inicio.php';
?>
        <h1 class="page-title">Live Fleet Tracking</h1>
        <p class="page-subtitle">Manage and monitor the trains of the fleet</p>
        <?= flash_html() ?>
<?php if ($erro !== ''): ?><p class="form-error"><?= h($erro) ?></p><?php endif; ?>

<?php if ($pode): ?>
        <div class="card add-sensor-form">
          <div class="add-sensor-form-header"><h3><?= $f['id'] > 0 ? 'Edit Train' : 'Add New Train' ?></h3></div>
          <form class="form-grid" method="post" action="trens.php">
            <?= csrf_campo() ?>
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
            <div class="field"><label for="codigo">Code</label><input type="text" id="codigo" name="codigo" maxlength="20" placeholder="TR-013" required value="<?= h($f['codigo']) ?>"></div>
            <div class="field"><label for="linha">Line</label><input type="text" id="linha" name="linha" maxlength="50" placeholder="A Line" required value="<?= h($f['linha']) ?>"></div>
            <div class="field"><label for="proxima_estacao">Next station</label><input type="text" id="proxima_estacao" name="proxima_estacao" maxlength="80" value="<?= h($f['proxima_estacao']) ?>"></div>
            <div class="field"><label for="velocidade">Speed (km/h)</label><input type="number" id="velocidade" name="velocidade" min="0" max="400" value="<?= (int) $f['velocidade'] ?>"></div>
            <div class="field"><label for="status">Status</label>
              <select id="status" name="status">
<?php foreach (STATUS_LISTA as $s): ?>
                <option<?= $f['status'] === $s ? ' selected' : '' ?>><?= h($s) ?></option>
<?php endforeach; ?>
              </select></div>
            <div class="form-actions" style="grid-column: 1 / -1;">
              <button type="submit" class="btn btn-primary"><?= $f['id'] > 0 ? 'Save Changes' : 'Create Train' ?></button>
<?php if ($f['id'] > 0): ?><a class="btn btn-secondary" href="trens.php">Cancel</a><?php endif; ?>
            </div>
          </form>
        </div>
<?php endif; ?>

        <div class="table-wrap">
          <table>
            <thead><tr><th>Code</th><th>Line</th><th>Status</th><th>Next station</th><th>Speed</th><th>Actions</th></tr></thead>
            <tbody>
<?php if (!$trens): ?>
              <tr><td colspan="6">No trains registered.</td></tr>
<?php else: foreach ($trens as $t): ?>
              <tr>
                <td><a href="trem.php?id=<?= (int) $t['id'] ?>"><?= h($t['codigo']) ?></a></td>
                <td><?= h($t['linha']) ?></td>
                <td><span class="badge badge-<?= h(strtolower($t['status'])) ?>"><?= h($t['status']) ?></span></td>
                <td><?= h($t['proxima_estacao']) ?></td>
                <td><?= (int) $t['velocidade'] ?> km/h</td>
                <td class="table-actions">
                  <a class="btn btn-secondary" href="trem.php?id=<?= (int) $t['id'] ?>">View</a>
<?php if ($pode): ?>
                  <a class="btn btn-secondary" href="trens.php?editar=<?= (int) $t['id'] ?>">Edit</a>
                  <form method="post" action="trens.php" style="display:inline" onsubmit="return confirm('Excluir este trem? Os sensores dele ficam sem trem.');">
                    <?= csrf_campo() ?><input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
<?php endif; ?>
                </td>
              </tr>
<?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
<?php require __DIR__ . '/includes/layout_fim.php'; ?>
