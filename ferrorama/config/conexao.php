<?php

require_once __DIR__ . '/erros.php';

$db_servidor = 'localhost';
$db_usuario  = 'root';
$db_senha    = '';           
$db_banco    = 'ferrovias';
$db_porta    = 3306;         

if (is_file(__DIR__ . '/local.php')) {
    include __DIR__ . '/local.php';   
}

mysqli_report(MYSQLI_REPORT_OFF);

$conexao = @new mysqli($db_servidor, $db_usuario, $db_senha, $db_banco, (int) $db_porta);

if ($conexao->connect_error) {
    error_log('[Ferrorama] Falha na conexão: ' . $conexao->connect_error);
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    exit(
        '<p style="font-family:sans-serif;padding:24px"><strong>Não foi possível conectar ao banco de dados.</strong></p>'
        . '<p style="font-family:sans-serif;padding:0 24px">Confira se o MySQL está ligado no XAMPP e se o arquivo '
        . 'banco/script.sql foi importado.</p>'
    );
}

if (!$conexao->set_charset('utf8mb4')) {
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    exit('<p style="font-family:sans-serif;padding:24px"><strong>Não foi possível configurar a conexão com o banco.</strong></p>');
}

unset($db_servidor, $db_usuario, $db_senha, $db_banco, $db_porta);
