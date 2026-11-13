document.addEventListener('DOMContentLoaded', function () {
  const parametros = new URLSearchParams(window.location.search);
  const idTrem = parametros.get('id');

  const trem = trens.find(function (t) {
    return t.id === idTrem;
  });

  const container = document.getElementById('train-detail-container');

  if (!trem) {
    container.innerHTML = '<p class="no-sensors-text">Train not found.</p>';
    return;
  }

  const statusDetalhe = trem.status === 'Online' ? 'On Time' : 'Delayed';
  const classeBadge = trem.status === 'Online' ? 'badge-online' : 'badge-delayed';

  container.innerHTML = `
    <div class="detail-title-row">
      <h1 class="page-title" style="margin:0;">${trem.id}</h1>
      <span class="badge ${classeBadge}">${statusDetalhe}</span>
    </div>
    <p class="page-subtitle">Live telemetry and predictive diagnostics</p>

    <div class="detail-stats-grid">
      <div class="card detail-stat-card">
        <div class="detail-stat-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.5-7-11a7 7 0 0 1 14 0c0 4.5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
        </div>
        <div>
          <p class="detail-stat-label">Current Line</p>
          <p class="detail-stat-value">${trem.linha.replace(' Line', '')}</p>
        </div>
      </div>
      <div class="card detail-stat-card">
        <div class="detail-stat-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15a8 8 0 1 1 16 0"/><line x1="12" y1="15" x2="15" y2="10"/><circle cx="12" cy="15" r="1"/></svg>
        </div>
        <div>
          <p class="detail-stat-label">Current Speed</p>
          <p class="detail-stat-value">${trem.velocidade}</p>
        </div>
      </div>
      <div class="card detail-stat-card">
        <div class="detail-stat-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21V4"/><path d="M5 4h13l-3 4 3 4H5"/></svg>
        </div>
        <div>
          <p class="detail-stat-label">Destination</p>
          <p class="detail-stat-value">${trem.destino}</p>
        </div>
      </div>
      <div class="card detail-stat-card">
        <div class="detail-stat-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15.5 14"/></svg>
        </div>
        <div>
          <p class="detail-stat-label">ETA</p>
          <p class="detail-stat-value">${trem.eta}</p>
        </div>
      </div>
    </div>

    <div class="sensors-array-title">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"/><path d="M8.5 8.5a5 5 0 0 0 0 7"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/><path d="M5.5 5.5a9 9 0 0 0 0 13"/><path d="M18.5 5.5a9 9 0 0 1 0 13"/></svg>
      Connected Sensors Array
    </div>
    <div class="card">
      <p class="no-sensors-text">No active sensors detected for this train.</p>
    </div>
  `;
});
