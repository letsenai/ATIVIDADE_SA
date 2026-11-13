document.addEventListener('DOMContentLoaded', function () {
  marcarLinkAtivo();
  configurarMenuMobile();
  configurarDropdownUsuario();
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

  const linksDoMenu = overlay.querySelectorAll('.nav-link');
  linksDoMenu.forEach(function (link) {
    link.addEventListener('click', function () {
      overlay.classList.remove('open');
    });
  });
}

function configurarDropdownUsuario() {
  const botaoUsuario = document.querySelector('[data-user-menu-btn]');
  const dropdown = document.querySelector('.user-dropdown');
  if (!botaoUsuario || !dropdown) return;

  botaoUsuario.addEventListener('click', function (e) {
    e.stopPropagation();
    dropdown.classList.toggle('open');
  });

  document.addEventListener('click', function (e) {
    if (!dropdown.contains(e.target) && !botaoUsuario.contains(e.target)) {
      dropdown.classList.remove('open');
    }
  });
}
