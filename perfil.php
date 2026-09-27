<?php
require "verifica_login.php";

if ($tipo === "cliente") {

    $stmt = $conexao->prepare(
        "SELECT nome, cpf, email, telefone, endereco
         FROM cliente
         WHERE id_cliente = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

} else {

    $stmt = $conexao->prepare(
        "SELECT nome, cargo, email
         FROM funcionario
         WHERE id_funcionario = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
}

$pagina_atual = "perfil";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<title>Meu Perfil | ConectaNet</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

<?php include "menu.php"; ?>

<main>

<h1>Meu Perfil</h1>

<div class="perfil-box">

<div class="perfil-topo">

<div class="avatar">
    <i class="fa-solid fa-user"></i>
</div>

<h2><?= htmlspecialchars($usuario["nome"]) ?></h2>

<p><?= $tipo === "cliente" ? "Cliente ConectaNet" : "Funcionário ConectaNet" ?></p>

</div>

<div class="dados">

<?php if ($tipo === "cliente") { ?>

<div class="dado">
    <i class="fa-solid fa-id-card"></i>
    <div>
        <span>CPF</span>
        <strong><?= htmlspecialchars($usuario["cpf"]) ?></strong>
    </div>
</div>

<div class="dado">
    <i class="fa-solid fa-envelope"></i>
    <div>
        <span>E-mail</span>
        <strong><?= htmlspecialchars($usuario["email"]) ?></strong>
    </div>
</div>

<div class="dado">
    <i class="fa-solid fa-phone"></i>
    <div>
        <span>Telefone</span>
        <strong><?= htmlspecialchars($usuario["telefone"]) ?></strong>
    </div>
</div>

<div class="dado">
    <i class="fa-solid fa-location-dot"></i>
    <div>
        <span>Endereço</span>
        <strong><?= htmlspecialchars($usuario["endereco"]) ?></strong>
    </div>
</div>

<?php } else { ?>

<div class="dado">
    <i class="fa-solid fa-briefcase"></i>
    <div>
        <span>Cargo</span>
        <strong><?= htmlspecialchars($usuario["cargo"]) ?></strong>
    </div>
</div>

<div class="dado">
    <i class="fa-solid fa-envelope"></i>
    <div>
        <span>E-mail</span>
        <strong><?= htmlspecialchars($usuario["email"]) ?></strong>
    </div>
</div>

<?php } ?>

</div>

</div>

</main>

</body>
</html>
