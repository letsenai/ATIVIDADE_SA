<?php

$raiz   = $raiz ?? '';
$titulo = $titulo ?? '';
require_once __DIR__ . '/proteger.php';
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GoMobi — <?= h($titulo) ?></title>
  <link rel="icon" type="image/png" href="<?= $raiz ?>assets/img/favicon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Lato:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= $raiz ?>assets/css/style.css" />
</head>
<body data-page="<?= h($pagina) ?>">
  <div class="app">
<?php require __DIR__ . '/sidebar.php'; ?>
    <div class="main-column">
<?php require __DIR__ . '/cabecalho.php'; ?>
      <main class="content">
