document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('trains-container');
  if (!container) return;

  let html = '';

  trens.forEach(function (trem) {
    html += `
      <div class="train-card">
        <div class="train-card-top">
          <div>
            <p class="train-card-id">${trem.id}</p>
            <p class="train-card-line">${trem.linha}</p>
          </div>
          <span class="badge ${classeBadgeStatus(trem.status)}">
            <span class="dot"></span>${trem.status}
          </span>
        </div>

        <div class="train-card-row">
          <span class="muted">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.5-7-11a7 7 0 0 1 14 0c0 4.5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
            Next Station
          </span>
          <span class="muted">ETA</span>
        </div>
        <div class="train-card-values">
          <span>${trem.proximaEstacao}</span>
          <span>${trem.eta}</span>
        </div>

        <div class="train-sensors-row">
          <div class="sensor-item">
            <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 14.76V4a2 2 0 0 0-4 0v10.76a4 4 0 1 0 4 0Z"/></svg>
            <span>${trem.temperatura}</span>
          </div>
          <div class="sensor-item">
            <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2s6 7 6 11a6 6 0 0 1-12 0c0-4 6-11 6-11Z"/></svg>
            <span>${trem.umidade}</span>
          </div>
          <div class="sensor-item">
            <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="2.5"/></svg>
            <span>${trem.freio}</span>
          </div>
          <div class="sensor-item">
            <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="8.5 12 11 14.5 15.5 9.5"/></svg>
            <span>${trem.check}</span>
          </div>
        </div>

        <a href="trem.php?id=${trem.id}" class="train-card-link">
          View Sensor Telemetry
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
        </a>
      </div>
    `;
  });

  container.innerHTML = html;
});
