// lista de sensores cadastrados
let sensores = [];

let textoBusca = '';
let filtroTipo = 'All Types';
let filtroStatus = 'All Status';

const opcoesTipo = ['All Types', 'Velocity', 'Temperature', 'Vibration', 'Brake', 'Energy', 'Doors', 'GPS', 'Rails'];
const opcoesStatus = ['All Status', 'Online', 'Warning', 'Offline'];

document.addEventListener('DOMContentLoaded', function () {
  montarDropdownFiltro('dropdown-tipo', opcoesTipo, filtroTipo, function (valor) {
    filtroTipo = valor;
    renderTabela();
  });

  montarDropdownFiltro('dropdown-status', opcoesStatus, filtroStatus, function (valor) {
    filtroStatus = valor;
    renderTabela();
  });

  document.getElementById('campo-busca').addEventListener('input', function (e) {
    textoBusca = e.target.value;
    renderTabela();
  });

  document.getElementById('btn-abrir-form').addEventListener('click', function () {
    alternarFormulario();
  });
  document.getElementById('btn-cancelar-form').addEventListener('click', function () {
    esconderFormulario();
  });
  document.getElementById('btn-fechar-form').addEventListener('click', function () {
    esconderFormulario();
  });

  document.getElementById('form-novo-sensor').addEventListener('submit', function (e) {
    e.preventDefault();
    criarSensor();
  });

  renderTabela();
});

function montarDropdownFiltro(idElemento, opcoes, valorInicial, onSelecionar) {
  const dropdown = document.getElementById(idElemento);
  const botao = dropdown.querySelector('.dropdown-btn span');
  const painel = dropdown.querySelector('.dropdown-panel');

  function desenharOpcoes(valorSelecionado) {
    painel.innerHTML = '';
    opcoes.forEach(function (opcao) {
      const item = document.createElement('button');
      item.type = 'button';
      item.className = 'dropdown-option' + (opcao === valorSelecionado ? ' selected' : '');
      item.innerHTML =
        '<span>' + opcao + '</span>' +
        '<svg class="icon check-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
      item.addEventListener('click', function () {
        botao.textContent = opcao;
        dropdown.classList.remove('open');
        desenharOpcoes(opcao);
        onSelecionar(opcao);
      });
      painel.appendChild(item);
    });
  }

  desenharOpcoes(valorInicial);

  dropdown.querySelector('.dropdown-btn').addEventListener('click', function (e) {
    e.stopPropagation();
    dropdown.classList.toggle('open');
  });

  document.addEventListener('click', function (e) {
    if (!dropdown.contains(e.target)) {
      dropdown.classList.remove('open');
    }
  });
}

function alternarFormulario() {
  const painel = document.getElementById('add-sensor-panel');
  const estaAberto = painel.style.display !== 'none';
  painel.style.display = estaAberto ? 'none' : 'block';
}

function esconderFormulario() {
  document.getElementById('add-sensor-panel').style.display = 'none';
  document.getElementById('form-novo-sensor').reset();
}

function criarSensor() {
  const nome = document.getElementById('input-nome').value.trim();
  const sensorId = document.getElementById('input-sensor-id').value.trim();
  const tipo = document.getElementById('select-tipo').value;
  const localizacao = document.getElementById('input-localizacao').value.trim();
  const threshold = document.getElementById('input-threshold').value.trim();
  const status = document.getElementById('select-status').value;

  if (!nome || !sensorId || !localizacao || !threshold) {
    return; 
  }

  sensores.unshift({
    sensorId: sensorId,
    nome: nome,
    tipo: tipo,
    localizacao: localizacao,
    status: status,
    valor: '--',
    threshold: threshold,
  });

  esconderFormulario();
  renderTabela();
}

function excluirSensor(sensorId) {
  sensores = sensores.filter(function (s) {
    return s.sensorId !== sensorId;
  });
  renderTabela();
}

function renderTabela() {
  const tbody = document.getElementById('sensors-tbody');
  const texto = textoBusca.toLowerCase();

  const filtrados = sensores.filter(function (s) {
    const combinaBusca =
      texto.trim() === '' ||
      s.nome.toLowerCase().includes(texto) ||
      s.sensorId.toLowerCase().includes(texto);
    const combinaTipo = filtroTipo === 'All Types' || s.tipo === filtroTipo;
    const combinaStatus = filtroStatus === 'All Status' || s.status === filtroStatus;
    return combinaBusca && combinaTipo && combinaStatus;
  });

  if (filtrados.length === 0) {
    tbody.innerHTML = '<tr class="empty-row"><td colspan="8">No sensors found</td></tr>';
    return;
  }

  let html = '';
  filtrados.forEach(function (s) {
    html += `
      <tr>
        <td>${s.sensorId}</td>
        <td>${s.nome}</td>
        <td>${s.tipo}</td>
        <td>${s.localizacao}</td>
        <td><span class="badge ${classeBadgeStatus(s.status)}"><span class="dot"></span>${s.status}</span></td>
        <td>${s.valor}</td>
        <td>${s.threshold}</td>
        <td>
          <div class="table-actions">
            <button class="icon-btn" aria-label="Edit sensor">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </button>
            <button class="icon-btn danger" aria-label="Delete sensor" onclick="excluirSensor('${s.sensorId}')">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
          </div>
        </td>
      </tr>
    `;
  });
  tbody.innerHTML = html;
}
