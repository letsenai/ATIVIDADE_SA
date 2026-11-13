<?php

ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

set_exception_handler(function (Throwable $e) {
    error_log('[Ferrorama] ' . $e);
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
    }
    echo '<p style="font-family:sans-serif;padding:24px"><strong>Ocorreu um erro inesperado.</strong> '
       . 'Tente novamente em instantes ou avise o administrador do sistema.</p>';
});
