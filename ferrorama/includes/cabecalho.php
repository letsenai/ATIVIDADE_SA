<?php

require_once __DIR__ . '/permissao.php';

$raiz = $raiz ?? '';

$nome_logado  = $_SESSION['nome']  ?? 'User';
$login_logado = $_SESSION['login'] ?? '-';
$papel_logado = $_SESSION['papel'] ?? '';
$rotulo_papel = PAPEIS[$papel_logado] ?? $papel_logado;
?>
      <header class="header">
        <div class="header-left">
          <button class="hamburger-btn" data-open-mobile-menu aria-label="Open menu">
            <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
          </button>
          <div class="header-logo">
            <img src="<?= $raiz ?>assets/img/logo.png" alt="GoMobi" />
            <span>GoMobi</span>
          </div>
        </div>

        <div class="user-menu">
          <span class="user-name-label"><?= htmlspecialchars($nome_logado, ENT_QUOTES, 'UTF-8') ?></span>
          <button class="user-icon-btn" data-user-menu-btn aria-label="User menu">
            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.5-6 8-6s8 2 8 6"/></svg>
          </button>
          <div class="user-dropdown">
            <p class="name"><?= htmlspecialchars($nome_logado, ENT_QUOTES, 'UTF-8') ?></p>
            <p class="email"><?= htmlspecialchars($login_logado, ENT_QUOTES, 'UTF-8') ?></p>
            <div class="role-wrap">
              <span class="badge badge-viewer"><?= htmlspecialchars($rotulo_papel, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="divider"></div>
            <a href="<?= $raiz ?>logout.php" class="logout-link">
              <svg class="icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
              Logout
            </a>
          </div>
        </div>
      </header>
