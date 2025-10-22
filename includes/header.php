<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';
?><!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>VerdeZero - Saúde e Bem-estar</title>
<meta name="description" content="Descubra receitas saudáveis, exercícios personalizados e ferramentas para uma vida mais equilibrada.">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo h(BASE_URL); ?>assets/css/style.css">
</head>
<body>
<header class="vz-header">
  <div class="vz-header__brand">
    <a class="vz-logo" href="<?php echo h(BASE_URL); ?>index.php">
      <img src="<?php echo h(BASE_URL); ?>assets/img/verdezero.logo.png" width="32" height="32">
    </a>
  </div>
  
  <form class="vz-search" action="<?php echo h(BASE_URL); ?>ebooks.php" method="get" role="search">
    <input type="text" name="q" placeholder="O que você quer aprender hoje?" aria-label="Pesquisar ebooks, receitas e exercícios">
    <button type="submit" aria-label="Buscar">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.35-4.35"></path>
      </svg>
      Procurar
    </button>
  </form>
  
  <nav class="vz-nav" role="navigation" aria-label="Menu principal">
    <a href="<?php echo h(BASE_URL); ?>ebooks.php">Ebooks</a>

    <a href="<?php echo h(BASE_URL); ?>receitas.php">Receitas</a>
    <a href="<?php echo h(BASE_URL); ?>exercicios.php">Exercícios</a>
    <a href="<?php echo h(BASE_URL); ?>imc.php">IMC</a>
    <a href="<?php echo h(BASE_URL); ?>agua.php">Água</a>
  </nav>
  
  <div class="vz-user">
    <?php if (!empty($_SESSION['user'])): ?>
      <span class="vz-user__hello">Olá, <?php echo h($_SESSION['user']['name']); ?></span>
      <a class="vz-btn-outline" href="<?php echo h(BASE_URL); ?>login.php">Perfil</a>
      <a class="vz-btn-outline" href="<?php echo h(BASE_URL); ?>login.php?logout=1">Sair</a>
    <?php else: ?>
      <a class="vz-btn" href="<?php echo h(BASE_URL); ?>login.php">Entrar</a>
    <?php endif; ?>
  </div>
</header>
<main class="vz-main">