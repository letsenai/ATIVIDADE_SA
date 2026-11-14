<?php
/*
  includes/permissao.php — papéis do sistema e regras de acesso.
  Só define constantes e funções: nada é executado ao incluí-lo.
  A lista de papéis precisa ser a mesma do ENUM da coluna papel em banco/script.sql.
*/

const PAPEIS = [
    'administrador' => 'Administrador',
    'operador'      => 'Operador',
    'tecnico'       => 'Técnico',
];

// Quem pode abrir cada página (chave = valor de $pagina / data-page).
const ACESSO = [
    'dashboard' => ['administrador', 'operador', 'tecnico'],
    'trains'    => ['administrador', 'operador', 'tecnico'],
    'sensors'   => ['administrador', 'tecnico'],
    'routes'    => ['administrador', 'operador'],
    'alerts'    => ['administrador', 'operador', 'tecnico'],
    'users'     => ['administrador'],
    'settings'  => ['administrador', 'operador', 'tecnico'],
];

function pode_acessar(string $pagina, string $papel): bool
{
    return in_array($papel, ACESSO[$pagina] ?? [], true);
}

// Administrador cria qualquer papel; os demais não criam usuários.
function papeis_que_pode_criar(string $papel_logado): array
{
    return $papel_logado === 'administrador' ? PAPEIS : [];
}

function pode_cadastrar(string $papel_logado): bool
{
    return count(papeis_que_pode_criar($papel_logado)) > 0;
}

// Quem pode criar/editar/excluir em cada área (os demais só consultam).
const EDICAO = [
    'trains'  => ['administrador', 'operador'],
    'sensors' => ['administrador', 'tecnico'],
];

function pode_editar(string $pagina, string $papel): bool
{
    return in_array($papel, EDICAO[$pagina] ?? [], true);
}

const STATUS_LISTA = ['Online', 'Warning', 'Offline'];
const TIPOS_SENSOR = ['Velocity', 'Temperature', 'Vibration', 'Brake', 'Energy', 'Doors', 'GPS', 'Rails'];
