<?php
/*
  includes/dados_simulados.php — dados de exemplo (a 3ª entrega permite dados simulados).
  Nas entregas 6 e 7 estes arrays serão trocados por tabelas do MySQL.
*/

$alertas_simulados = [
    ['id' => 'AL-001', 'nivel' => 'Crítico',     'titulo' => 'Superaquecimento detectado',     'trem' => 'TR-011', 'sensor' => 'Temperatura', 'hora' => '08:12', 'status' => 'Ativo'],
    ['id' => 'AL-002', 'nivel' => 'Crítico',     'titulo' => 'Falha no sistema de freio',      'trem' => 'TR-001', 'sensor' => 'Freio',       'hora' => '08:40', 'status' => 'Ativo'],
    ['id' => 'AL-003', 'nivel' => 'Atenção',     'titulo' => 'Vibração acima do normal',       'trem' => 'TR-004', 'sensor' => 'Vibração',    'hora' => '09:05', 'status' => 'Ativo'],
    ['id' => 'AL-004', 'nivel' => 'Atenção',     'titulo' => 'Umidade elevada na cabine',      'trem' => 'TR-009', 'sensor' => 'Umidade',     'hora' => '09:30', 'status' => 'Resolvido'],
    ['id' => 'AL-005', 'nivel' => 'Informativo', 'titulo' => 'Trem fora de operação (offline)', 'trem' => 'TR-002', 'sensor' => 'GPS',         'hora' => '10:02', 'status' => 'Resolvido'],
];

$rotas_simuladas = [
    ['id' => 'RT-01', 'origem' => 'Estação Central', 'destino' => 'Porto Norte',   'trem' => 'TR-003', 'distancia' => '42 km', 'duracao' => '55 min', 'status' => 'Em operação'],
    ['id' => 'RT-02', 'origem' => 'Porto Norte',     'destino' => 'Vila Industrial', 'trem' => 'TR-007', 'distancia' => '27 km', 'duracao' => '38 min', 'status' => 'Em operação'],
    ['id' => 'RT-03', 'origem' => 'Estação Central', 'destino' => 'Terminal Sul',  'trem' => 'TR-001', 'distancia' => '63 km', 'duracao' => '1h 20min', 'status' => 'Atrasada'],
    ['id' => 'RT-04', 'origem' => 'Vila Industrial', 'destino' => 'Terminal Sul',  'trem' => 'TR-006', 'distancia' => '35 km', 'duracao' => '47 min', 'status' => 'Suspensa'],
];

// classe de cor do badge para cada situação
function classe_badge(string $texto): string
{
    switch ($texto) {
        case 'Crítico':
        case 'Suspensa':      return 'badge-offline';
        case 'Atenção':
        case 'Atrasada':
        case 'Ativo':         return 'badge-warning';
        case 'Em operação':
        case 'Resolvido':     return 'badge-online';
        default:              return 'badge-viewer';
    }
}
