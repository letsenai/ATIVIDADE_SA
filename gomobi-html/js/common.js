document.addEventListener('DOMContentLoaded', function () {
  marcarLinkAtivo();
  preencherDadosUsuario();
  configurarMenuMobile();
  configurarDropdownUsuario();
  configurarBotoesLogout();
});

function marcarLinkAtivo() {
  const paginaAtual = document.body.dataset.page;
  if (!paginaAtual) return;

  const links = document.querySelectorAll('.nav-link');
  links.forEach(function (link) {
    if (link.dataset.page === paginaAtual) {
      link.classList.add('active');
    }
  });
}

// Preenche o email do user logado no dropdown do header
function preencherDadosUsuario() {
  const usuario = getUsuarioLogado();
  if (!usuario) return;

  const emailEl = document.querySelector('[data-user-email]');
  const roleEl = document.querySelector('[data-user-role]');
  if (emailEl) emailEl.textContent = usuario.email;
  if (roleEl) roleEl.textContent = usuario.role;
}

function configurarMenuMobile() {
  const botaoAbrir = document.querySelector('[data-open-mobile-menu]');
  const botaoFechar = document.querySelector('[data-close-mobile-menu]');
  const overlay = document.querySelector('.mobile-menu-overlay');
  const backdrop = document.querySelector('.mobile-menu-backdrop');

  if (!overlay) return;

  if (botaoAbrir) {
    botaoAbrir.addEventListener('click', function () {
      overlay.classList.add('open');
    });
  }
  if (botaoFechar) {
    botaoFechar.addEventListener('click', function () {
      overlay.classList.remove('open');
    });
  }
  if (backdrop) {
    backdrop.addEventListener('click', function () {
      overlay.classList.remove('open');
    });
  }

  // fecha o menu automaticamente se clicar em algum link dele
  const linksDoMenu = overlay.querySelectorAll('.nav-link');
  linksDoMenu.forEach(function (link) {
    link.addEventListener('click', function () {
      overlay.classList.remove('open');
    });
  });
}

// icone de user no canto superior direito abre/fecha o dropdown
function configurarDropdownUsuario() {
  const botaoUsuario = document.querySelector('[data-user-menu-btn]');
  const dropdown = document.querySelector('.user-dropdown');
  if (!botaoUsuario || !dropdown) return;

  botaoUsuario.addEventListener('click', function (e) {
    e.stopPropagation();
    dropdown.classList.toggle('open');
  });

  // fecha o dropdown se clicar em qualquer outro lugar da pag
  document.addEventListener('click', function (e) {
    if (!dropdown.contains(e.target) && !botaoUsuario.contains(e.target)) {
      dropdown.classList.remove('open');
    }
  });
}

// qualquer botão/link com o atributo data logout faz logout ao clicar
function configurarBotoesLogout() {
  const botoesLogout = document.querySelectorAll('[data-logout]');
  botoesLogout.forEach(function (botao) {
    botao.addEventListener('click', function () {
      fazerLogout();
      window.location.href = 'login.html';
    });
  });
}
