<?php
session_start();
include "conexao.php";

if (!isset($_SESSION["id_cliente"])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION["id_cliente"];

$stmt = $conexao->prepare(
    "SELECT nome,cpf,email,telefone,endereco 
     FROM cliente 
     WHERE id_cliente=?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$cliente = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil | ConectaNet</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.perfil-box{
    max-width:650px;
    background:white;
    border-radius:12px;
    box-shadow:0 3px 12px #ddd;
    overflow:hidden
}

.perfil-topo{
    background:#3157d5;
    color:white;
    padding:30px;
    text-align:center
}

.avatar{
    width:80px;
    height:80px;
    margin:auto auto 12px;
    border-radius:50%;
    background:white;
    color:#3157d5;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:35px
}

.perfil-topo h2{
    margin:0
}

.perfil-topo p{
    margin-top:5px;
    opacity:.9
}

.dados{
    padding:25px
}

.dado{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px 5px;
    border-bottom:1px solid #eee
}

.dado:last-child{
    border-bottom:none
}

.dado i{
    width:35px;
    height:35px;
    border-radius:50%;
    background:#e8edff;
    color:#3157d5;
    display:flex;
    align-items:center;
    justify-content:center
}

.dado span{
    color:#777;
    font-size:13px
}

.dado strong{
    display:block;
    margin-top:3px
}
</style>

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
        Agendamento
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

<main>
    <h1>Meu Perfil</h1>
    <div class="perfil-box">

        <!-- Início Perfil -->
        <div class="perfil-topo">
            <div class="avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <h2><?=htmlspecialchars($cliente["nome"])?></h2>
            <p>Cliente ConectaNet</p>
        </div>

        <!-- Início Dados -->
        <div class="dados">
            <div class="dado">
                <i class="fa-solid fa-id-card"></i>
                <div>
                    <span>CPF</span>
                    <strong><?=htmlspecialchars($cliente["cpf"])?></strong>
                </div>
            </div>
            <div class="dado">
                <i class="fa-solid fa-envelope"></i>
                <div>
                    <span>E-mail</span>
                    <strong><?=htmlspecialchars($cliente["email"])?></strong>
                </div>
            </div>
            <div class="dado">
                <i class="fa-solid fa-phone"></i>
                <div>
                    <span>Telefone</span>
                    <strong><?=htmlspecialchars($cliente["telefone"])?></strong>
                </div>
            </div>
            <div class="dado">
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <span>Endereço</span>
                    <strong><?=htmlspecialchars($cliente["endereco"])?></strong>
                </div>
            </div>
        </div>
        <!-- Fim Dados -->

    </div>

</main>

</body>
</html>