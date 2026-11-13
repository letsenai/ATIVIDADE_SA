const trens = [
  { id: 'TR-001', linha: 'A Line', status: 'Warning', proximaEstacao: '125th St', eta: '13 min', velocidade: '24 km/h', destino: '125th St', temperatura: '25°C', umidade: '97%', freio: '75%', check: 'CHK' },
  { id: 'TR-002', linha: 'C Line', status: 'Offline', proximaEstacao: '96th St', eta: '14 min', velocidade: '0 km/h', destino: '96th St', temperatura: '25°C', umidade: '67%', freio: '85%', check: 'CHK' },
  { id: 'TR-003', linha: 'E Line', status: 'Online', proximaEstacao: '72nd St', eta: '12 min', velocidade: '48 km/h', destino: '72nd St', temperatura: '25°C', umidade: '89%', freio: '85%', check: 'CHK' },
  { id: 'TR-004', linha: '1 Line', status: 'Warning', proximaEstacao: '42nd St', eta: '5 min', velocidade: '18 km/h', destino: '42nd St', temperatura: '25°C', umidade: '87%', freio: '55%', check: 'OK' },
  { id: 'TR-005', linha: '2 Line', status: 'Offline', proximaEstacao: '34th St', eta: '6 min', velocidade: '0 km/h', destino: '34th St', temperatura: '25°C', umidade: '95%', freio: '88%', check: 'CHK' },
  { id: 'TR-006', linha: '3 Line', status: 'Offline', proximaEstacao: '14th St', eta: '15 min', velocidade: '0 km/h', destino: '14th St', temperatura: '37°C', umidade: '73%', freio: '89%', check: 'CHK' },
  { id: 'TR-007', linha: '4 Line', status: 'Online', proximaEstacao: '125th St', eta: '13 min', velocidade: '52 km/h', destino: '125th St', temperatura: '25°C', umidade: '65%', freio: '55%', check: 'CHK' },
  { id: 'TR-008', linha: '5 Line', status: 'Offline', proximaEstacao: '86th St', eta: '10 min', velocidade: '0 km/h', destino: '86th St', temperatura: '25°C', umidade: '64%', freio: '85%', check: 'CHK' },
  { id: 'TR-009', linha: 'A Line', status: 'Online', proximaEstacao: '72nd St', eta: '13 min', velocidade: '45 km/h', destino: '72nd St', temperatura: '25°C', umidade: '95%', freio: '57%', check: 'CHK' },
  { id: 'TR-010', linha: 'C Line', status: 'Offline', proximaEstacao: '42nd St', eta: '8 min', velocidade: '0 km/h', destino: '42nd St', temperatura: '25°C', umidade: '88%', freio: '88%', check: 'CHK' },
  { id: 'TR-011', linha: 'E Line', status: 'Warning', proximaEstacao: '34th St', eta: '4 min', velocidade: '15 km/h', destino: '34th St', temperatura: '38°C', umidade: '62%', freio: '85%', check: 'CHK' },
  { id: 'TR-012', linha: '1 Line', status: 'Warning', proximaEstacao: '14th St', eta: '5 min', velocidade: '20 km/h', destino: '14th St', temperatura: '25°C', umidade: '86%', freio: '85%', check: 'OK' },
];

function classeBadgeStatus(status) {
  return 'badge-' + status.toLowerCase();
}
