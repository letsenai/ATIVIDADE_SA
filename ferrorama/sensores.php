<?php
$raiz   = '';
$pagina = 'sensors';
$titulo = 'Sensors';
require_once __DIR__ . '/includes/proteger.php';
require __DIR__ . '/config/conexao.php';

$pode = pode_editar('sensors', $_SESSION['papel']);
$campo = function (string $n): string { $v = $_POST[$n] ?? ''; return is_string($v) ? trim($v) : ''; };
$erro = '';
$f = ['id' => 0, 'codigo' => '', 'nome' => '', 'tipo' => 'Velocity', 'localizacao' => '', 'threshold' => '', 'status' => 'Online', 'trem_id' => 0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $erro = 'Sessão expirada. Recarregue a página e tente de novo.';
    } elseif (!$pode) {
        http_response_code(403);
        $erro = 'Seu perfil não pode alterar sensores.';
    } elseif ($campo('acao') === 'excluir') {
        $id = (int) $campo('id');
        $st = $conexao->prepare('DELETE FROM sensor WHERE id = ?');
        $st->bind_param('i', $id);
        $st->execute();
        $st->close();
        flash('Sensor excluído.');
        header('Location: sensores.php'); exit;
    } elseif ($campo('acao') === 'salvar') {
        $f = ['id' => (int) $campo('id'), 'codigo' => $campo('codigo'), 'nome' => $campo('nome'), 'tipo' => $campo('tipo'),
              'localizacao' => $campo('localizacao'), 'threshold' => str_replace(',', '.', $campo('threshold')),
              'status' => $campo('status'), 'trem_id' => (int) $campo('trem_id')];
        if ($f['codigo'] === '' || $f['nome'] === '') {
            $erro = 'Informe o código e o nome.';
        } elseif (mb_strlen($f['codigo']) > 20 || mb_strlen($f['nome']) > 100 || mb_strlen($f['localizacao']) > 100) {
            $erro = 'Algum campo passou do tamanho máximo.';
        } elseif (!in_array($f['tipo'], TIPOS_SENSOR, true) || !in_array($f['status'], STATUS_LISTA, true)) {
            $erro = 'Tipo ou status inválido.';
        } elseif (!is_numeric($f['threshold'])) {
            $erro = 'O threshold deve ser um número (ex.: 75.5).';
        } else {
            $limite = (float) $f['threshold'];
            $trem = $f['trem_id'] > 0 ? $f['trem_id'] : null;
            if ($f['id'] > 0) {
                $st = $conexao->prepare('UPDATE sensor SET codigo=?, nome=?, tipo=?, localizacao=?, threshold=?, status=?, trem_id=? WHERE id=?');
                $st->bind_param('ssssdsii', $f['codigo'], $f['nome'], $f['tipo'], $f['localizacao'], $limite, $f['status'], $trem, $f['id']);
            } else {
                $st = $conexao->prepare('INSERT INTO sensor (codigo, nome, tipo, localizacao, threshold, status, trem_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $st->bind_param('ssssdsi', $f['codigo'], $f['nome'], $f['tipo'], $f['localizacao'], $limite, $f['status'], $trem);
            }
            if ($st->execute()) {
                $st->close();
                flash($f['id'] > 0 ? 'Sensor atualizado.' : 'Sensor cadastrado.');
                header('Location: sensores.php'); exit;
            }
            $erro = ($st->errno === 1062) ? 'Já existe um sensor com esse código.' : 'Não foi possível salvar (confira o trem escolhido).';
            $st->close();
        }
    }
} elseif ($pode && isset($_GET['editar'])) {
    $id = (int) $_GET['editar'];
    $st = $conexao->prepare('SELECT id, codigo, nome, tipo, localizacao, threshold, status, IFNULL(trem_id, 0) FROM sensor WHERE id = ?');
    $st->bind_param('i', $id);
    $st->execute();
    $st->bind_result($f['id'], $f['codigo'], $f['nome'], $f['tipo'], $f['localizacao'], $f['threshold'], $f['status'], $f['trem_id']);
    $st->fetch();
    $st->close();
}

// ---- filtros (busca, tipo, status)
$q  = (isset($_GET['q']) && is_string($_GET['q'])) ? trim($_GET['q']) : '';
$ft = (isset($_GET['tipo']) && in_array($_GET['tipo'], TIPOS_SENSOR, true)) ? $_GET['tipo'] : '';
$fs = (isset($_GET['status']) && in_array($_GET['status'], STATUS_LISTA, true)) ? $_GET['status'] : '';

$sql = 'SELECT s.id, s.codigo, s.nome, s.tipo, s.localizacao, s.threshold, s.status, t.codigo AS trem
        FROM sensor s LEFT JOIN trem t ON t.id = s.trem_id WHERE 1=1';
$tipos = ''; $params = [];
if ($q !== '')  { $sql .= ' AND (s.nome LIKE ? OR s.codigo LIKE ?)'; $tipos .= 'ss'; $params[] = "%$q%"; $params[] = "%$q%"; }
if ($ft !== '') { $sql .= ' AND s.tipo = ?';   $tipos .= 's'; $params[] = $ft; }
if ($fs !== '') { $sql .= ' AND s.status = ?'; $tipos .= 's'; $params[] = $fs; }
$sql .= ' ORDER BY s.codigo';

$sensores = [];
$st = $conexao->prepare($sql);
if ($st !== false) {
    if ($params) { $st->bind_param($tipos, ...$params); }
    $st->execute();
    $res = $st->get_result();
    while ($l = $res->fetch_assoc()) { $sensores[] = $l; }
    $st->close();
}

$trens = [];
$res = $conexao->query('SELECT id, codigo FROM trem ORDER BY codigo');
if ($res) { while ($l = $res->fetch_assoc()) { $trens[] = $l; } $res->free(); }

require __DIR__ . '/includes/layout_inicio.php';
?>
        <h1 class="page-title">Sensor Management</h1>
        <p class="page-subtitle">Monitor and configure railway sensors</p>
        <?= flash_html() ?>
<?php if ($erro !== ''): ?><p class="form-error"><?= h($erro) ?></p><?php endif; ?>

<?php if ($pode): ?>
        <div class="card add-sensor-form">
          <div class="add-sensor-form-header"><h3><?= $f['id'] > 0 ? 'Edit Sensor' : 'Add New Sensor' ?></h3></div>
          <form class="form-grid" method="post" action="sensores.php">
            <?= csrf_campo() ?>
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
            <div class="field"><label for="nome">Name</label><input type="text" id="nome" name="nome" maxlength="100" placeholder="Temperature Sensor A1" required value="<?= h($f['nome']) ?>"></div>
            <div class="field"><label for="codigo">Sensor ID</label><input type="text" id="codigo" name="codigo" maxlength="20" placeholder="SENS-010" required value="<?= h($f['codigo']) ?>"></div>
            <div class="field"><label for="tipo">Type</label>
              <select id="tipo" name="tipo">
<?php foreach (TIPOS_SENSOR as $t): ?><option<?= $f['tipo'] === $t ? ' selected' : '' ?>><?= h($t) ?></option>
<?php endforeach; ?>
              </select></div>
            <div class="field"><label for="trem_id">Train</label>
              <select id="trem_id" name="trem_id">
                <option value="0">— none —</option>
<?php foreach ($trens as $t): ?><option value="<?= (int) $t['id'] ?>"<?= (int) $f['trem_id'] === (int) $t['id'] ? ' selected' : '' ?>><?= h($t['codigo']) ?></option>
<?php endforeach; ?>
              </select></div>
            <div class="field"><label for="localizacao">Location</label><input type="text" id="localizacao" name="localizacao" maxlength="100" placeholder="Car 3" value="<?= h($f['localizacao']) ?>"></div>
            <div class="field"><label for="threshold">Threshold</label><input type="text" id="threshold" name="threshold" placeholder="75.5" required value="<?= h($f['threshold']) ?>"></div>
            <div class="field"><label for="status">Status</label>
              <select id="status" name="status">
<?php foreach (STATUS_LISTA as $s): ?><option<?= $f['status'] === $s ? ' selected' : '' ?>><?= h($s) ?></option>
<?php endforeach; ?>
              </select></div>
            <div class="form-actions" style="grid-column: 1 / -1;">
              <button type="submit" class="btn btn-primary"><?= $f['id'] > 0 ? 'Save Changes' : 'Create Sensor' ?></button>
<?php if ($f['id'] > 0): ?><a class="btn btn-secondary" href="sensores.php">Cancel</a><?php endif; ?>
            </div>
          </form>
        </div>
<?php endif; ?>

        <form class="filters-row" method="get" action="sensores.php">
          <div class="search-box"><input type="text" name="q" placeholder="Search by name or ID..." value="<?= h($q) ?>"></div>
          <select name="tipo"><option value="">All Types</option>
<?php foreach (TIPOS_SENSOR as $t): ?><option<?= $ft === $t ? ' selected' : '' ?>><?= h($t) ?></option>
<?php endforeach; ?>
          </select>
          <select name="status"><option value="">All Status</option>
<?php foreach (STATUS_LISTA as $s): ?><option<?= $fs === $s ? ' selected' : '' ?>><?= h($s) ?></option>
<?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn-primary">Filter</button>
          <a class="btn btn-secondary" href="sensores.php">Clear</a>
        </form>

        <div class="table-wrap">
          <table>
            <thead><tr><th>Sensor ID</th><th>Name</th><th>Type</th><th>Train</th><th>Location</th><th>Status</th><th>Threshold</th><th>Actions</th></tr></thead>
            <tbody>
<?php if (!$sensores): ?>
              <tr><td colspan="8">No sensors found.</td></tr>
<?php else: foreach ($sensores as $s): ?>
              <tr>
                <td><?= h($s['codigo']) ?></td><td><?= h($s['nome']) ?></td><td><?= h($s['tipo']) ?></td>
                <td><?= h($s['trem'] ?? '-') ?></td><td><?= h($s['localizacao']) ?></td>
                <td><span class="badge badge-<?= h(strtolower($s['status'])) ?>"><?= h($s['status']) ?></span></td>
                <td><?= h($s['threshold']) ?></td>
                <td class="table-actions">
<?php if ($pode): ?>
                  <a class="btn btn-secondary" href="sensores.php?editar=<?= (int) $s['id'] ?>">Edit</a>
                  <form method="post" action="sensores.php" style="display:inline" onsubmit="return confirm('Excluir este sensor?');">
                    <?= csrf_campo() ?><input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
<?php else: ?>—<?php endif; ?>
                </td>
              </tr>
<?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
<?php require __DIR__ . '/includes/layout_fim.php'; ?>
