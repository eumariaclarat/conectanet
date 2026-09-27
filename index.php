<?php
session_start();
require_once "conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConectaNet | Central de Atendimento</title>
    <link rel="stylesheet" href="style.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    
<header>
    Central de Atendimento
</header>
<nav>
    <a href="perfil.php">
        <i class="fa-regular fa-address-card"></i>
        Perfil
    </a>
    <a href="painel.php">
        <i class="fa-solid fa-sliders"></i>
        Painel
    </a>
    <a href="protocolos.php">
        <i class="fa-solid fa-file-lines"></i>
        Protocolos
    </a>
    <a href="agendamento.php">
        <i class="fa-regular fa-calendar"></i>
        Visitas
    </a>
    <a href="historico.php">
        <i class="fa-regular fa-calendar-days"></i>
        Histórico
    </a>
    <a href="logout.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        Sair
    </a>
</nav>

</body>
</html>