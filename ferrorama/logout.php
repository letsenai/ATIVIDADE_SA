<?php
/*
  logout.php — encerra a sessão e volta para o login.
*/

session_start();

// Esvazia os dados da sessão
$_SESSION = [];

// Apaga também o cookie da sessão no navegador
if (ini_get('session.use_cookies')) {
    $cookie = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookie['path'],
        $cookie['domain'],
        $cookie['secure'],
        $cookie['httponly']
    );
}

// Destrói a sessão no servidor
session_destroy();

header('Location: index.php');
exit;
