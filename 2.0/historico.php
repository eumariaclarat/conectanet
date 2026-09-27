<?php
session_start();
include "conexao.php";

if (!isset($_SESSION["id_cliente"])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION["id_cliente"];

$sql = "SELECT chamado.id_chamado, chamado.descricao,
        chamado.status, chamado.data_abertura,
        agendamento.data, agendamento.horario,
        agendamento.tecnico
        FROM chamado
        JOIN abre ON chamado.id_chamado = abre.id_chamado
        LEFT JOIN gera ON chamado.id_chamado = gera.id_chamado
        LEFT JOIN agendamento
        ON gera.id_agendamento = agendamento.id_agendamento
        WHERE abre.id_cliente = ?
        ORDER BY chamado.data_abertura DESC";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();
$historico = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Histórico | ConectaNet</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
.cronograma{
    background:white;border-radius:10px;box-shadow:0 2px 8px #ddd;overflow:hidden}
.cronograma div{padding:18px;border-bottom:1px solid #eee}
.cronograma div:last-child{border:0}
.cronograma h3{color:#3157d5;margin-bottom:10px}
.cronograma p{margin:6px 0;color:#555}
</style>
</head>

<body>

<header>Central de Atendimento</header>

<nav>

    <a href="index.php">
        <i class="fa-solid fa-sliders"></i> Painel
    </a>

    <a href="protocolos.php">
        <i class="fa-solid fa-file-lines"></i> Protocolos
    </a>

    <a href="agendamento.php">
        <i class="fa-regular fa-calendar"></i> Visitas
    </a>

    <a href="historico.php">
        <i class="fa-regular fa-calendar-days"></i> Histórico
    </a>

    <a href="perfil.php">
        <i class="fa-regular fa-address-card"></i> Perfil
    </a>

    <a href="logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Sair
    </a>

</nav>

<main>

<h1>Histórico de Atendimento</h1>

<div class="cronograma">

<?php while($h = $historico->fetch_assoc()) { ?>

<div>

<h3>
<i class="fa-solid fa-ticket"></i>
Protocolo #<?= $h["id_chamado"] ?>
</h3>

<p>
<strong>Problema:</strong>
<?= htmlspecialchars($h["descricao"]) ?>
</p>

<p>
<strong>Status:</strong>
<?= $h["status"] ?>
</p>

<?php if($h["data"]) { ?>

<p>
<i class="fa-regular fa-calendar"></i>
<strong>Data:</strong>
<?= date("d/m/Y",strtotime($h["data"])) ?>
</p>

<p>
<i class="fa-regular fa-clock"></i>
<strong>Horário:</strong>
<?= date("H:i",strtotime($h["horario"])) ?>
</p>

<p>
<i class="fa-solid fa-user-gear"></i>
<strong>Atendido por:</strong>
<?= htmlspecialchars($h["tecnico"]) ?>
</p>

<?php } else { ?>

<p>
<i class="fa-solid fa-calendar-xmark"></i>
<strong>Visita:</strong>
Ainda não agendada
</p>

<?php } ?>

</div>

<?php } ?>

</div>

</main>

</body>
</html>