<?php
/*
  includes/sidebar.php — menu lateral (desktop) e menu mobile.
  Usa a mesma marcação e as mesmas classes das telas HTML originais,
  mas desenha a lista de links uma vez só e a repete nos dois menus.

  Espera: $raiz definido pela página ('' na raiz, '../' dentro de uma pasta).
  O link "Users" só aparece para quem pode cadastrar usuários. Isso é conforto
  de tela: quem protege de verdade é a verificação em usuarios/cadastrar.php.
*/

require_once __DIR__ . '/permissao.php';

$raiz = $raiz ?? '';

$itens_menu = [
    ['pagina' => 'dashboard', 'href' => 'principal.php',        'rotulo' => 'Dashboard',        'icone' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>'],
    ['pagina' => 'trains',    'href' => 'trens.php',            'rotulo' => 'Train Monitoring', 'icone' => '<rect x="5" y="3" width="14" height="12" rx="3"/><line x1="5" y1="10" x2="19" y2="10"/><circle cx="8.5" cy="18" r="1.5"/><circle cx="15.5" cy="18" r="1.5"/><line x1="8" y1="15" x2="6" y2="18"/><line x1="16" y1="15" x2="18" y2="18"/>'],
    ['pagina' => 'sensors',   'href' => 'sensores.php',         'rotulo' => 'Sensors',          'icone' => '<circle cx="12" cy="12" r="2"/><path d="M8.5 8.5a5 5 0 0 0 0 7"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/><path d="M5.5 5.5a9 9 0 0 0 0 13"/><path d="M18.5 5.5a9 9 0 0 1 0 13"/>'],
    ['pagina' => 'routes',    'href' => 'rotas.php',            'rotulo' => 'Routes',           'icone' => '<circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M8 19h6a4 4 0 0 0 0-8h-4a4 4 0 0 1 0-6h6"/>'],
    ['pagina' => 'alerts',    'href' => 'alertas.php',          'rotulo' => 'Alerts',           'icone' => '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/>'],
    ['pagina' => 'users',     'href' => 'usuarios/index.php',   'rotulo' => 'Users',            'icone' => '<circle cx="9" cy="8" r="4"/><path d="M1 20c0-4 3.5-6 8-6s8 2 8 6"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/>'],
    ['pagina' => 'settings',  'href' => 'configuracoes.php',    'rotulo' => 'Settings',         'icone' => '<circle cx="12" cy="12" r="3"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6l1.4 1.4M17 17l1.4 1.4M18.4 5.6L17 7M7 17l-1.4 1.4"/>'],
];

// Cada perfil só vê no menu o que pode abrir (a proteção real está em proteger.php)
$papel_menu = $_SESSION['papel'] ?? '';
$itens_menu = array_values(array_filter($itens_menu, function ($item) use ($papel_menu) {
    return pode_acessar($item['pagina'], $papel_menu);
}));
?>
    <!-- sidebar -->
    <aside class="sidebar">
      <div class="logo-area">
        <a href="<?= $raiz ?>principal.php" class="logo-link">
          <img src="<?= $raiz ?>assets/img/logo.png" alt="GoMobi" />
          <span>GoMobi</span>
        </a>
      </div>
      <nav class="nav-list">
        <?php foreach ($itens_menu as $item): ?>
        <a href="<?= $raiz . $item['href'] ?>" class="nav-link" data-page="<?= $item['pagina'] ?>">
          <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $item['icone'] ?></svg>
          <?= $item['rotulo'] ?>
        </a>
        <?php endforeach; ?>
      </nav>
    </aside>

    <!-- menu mobile -->
    <div class="mobile-menu-overlay">
      <div class="mobile-menu-backdrop"></div>
      <div class="mobile-menu-panel">
        <div class="mobile-menu-close">
          <button data-close-mobile-menu aria-label="Close menu">
            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
          </button>
        </div>
        <nav class="nav-list">
          <?php foreach ($itens_menu as $item): ?>
          <a href="<?= $raiz . $item['href'] ?>" class="nav-link" data-page="<?= $item['pagina'] ?>">
            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $item['icone'] ?></svg>
            <?= $item['rotulo'] ?>
          </a>
          <?php endforeach; ?>
        </nav>
      </div>
    </div>
