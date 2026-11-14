<?php

$db_servidor = 'localhost';
$db_usuario  = 'root';
$db_senha    = '';            
$db_banco    = 'ferrovias';
$db_porta    = 3306;         

mysqli_report(MYSQLI_REPORT_OFF);

$conexao = new mysqli($db_servidor, $db_usuario, $db_senha, $db_banco, $db_porta);

if ($conexao->connect_error) {
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    exit(
        '<p><strong>Não foi possível conectar ao banco de dados.</strong></p>'
        . '<p>Confira se o MySQL está ligado no painel do XAMPP, se o banco "'
        . htmlspecialchars($db_banco, ENT_QUOTES, 'UTF-8')
        . '" existe (execute banco/script.sql) e se usuário e senha estão corretos.</p>'
        . '<p>Detalhe técnico: ' . htmlspecialchars($conexao->connect_error, ENT_QUOTES, 'UTF-8') . '</p>'
    );
}

if (!$conexao->set_charset('utf8mb4')) {
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    exit('<p><strong>Não foi possível definir o conjunto de caracteres utf8mb4 na conexão.</strong></p>');
}

unset($db_servidor, $db_usuario, $db_senha, $db_banco, $db_porta);
