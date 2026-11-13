<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax']);
}

header('Cache-Control: no-store');

if (!function_exists('h')) {
    function h($texto): string
    {
        return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
    }
}

if (!isset($_SESSION['usuario_id'], $_SESSION['login'], $_SESSION['papel'])) {
    header('Location: ' . ($raiz ?? '') . 'index.php');
    exit;
}

require_once __DIR__ . '/permissao.php';

if (isset($pagina) && !pode_acessar($pagina, $_SESSION['papel'])) {
    http_response_code(403);
    exit('<!doctype html><html lang="pt-BR"><meta charset="UTF-8"><title>Acesso negado</title>'
        . '<body style="font-family:sans-serif;padding:40px"><h2>Acesso negado</h2>'
        . '<p>Seu perfil não tem permissão para abrir esta página.</p>'
        . '<p><a href="' . h($raiz ?? '') . 'principal.php">Voltar ao dashboard</a></p></body></html>');
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . h($_SESSION['csrf']) . '">';
}
function csrf_ok(): bool
{
    $t = $_POST['csrf'] ?? '';
    return is_string($t) && hash_equals($_SESSION['csrf'], $t);
}

function flash(string $msg, string $tipo = 'sucesso'): void
{
    $_SESSION['flash'] = ['msg' => $msg, 'tipo' => $tipo];
}
function flash_html(): string
{
    if (empty($_SESSION['flash'])) { return ''; }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $classe = ($f['tipo'] === 'erro') ? 'form-error' : 'form-success';
    return '<p class="' . $classe . '">' . h($f['msg']) . '</p>';
}
