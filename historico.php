<?php
require "verifica_login.php";

$status_validos = ["aberto", "em_andamento", "finalizado"];

// Funcionário pode atualizar o status de um chamado
if ($tipo === "funcionario" && $_SERVER["REQUEST_METHOD"] == "POST") {

    $id_chamado  = (int) $_POST["id_chamado"];
    $novo_status = $_POST["novo_status"];

    if (in_array($novo_status, $status_validos, true)) {
        $stmt = $conexao->prepare("UPDATE chamado SET status = ? WHERE id_chamado = ?");
        $stmt->bind_param("si", $novo_status, $id_chamado);
        $stmt->execute();
    }

    header("Location: historico.php");
    exit;
}

if ($tipo === "cliente") {

    // Cliente vê apenas o próprio histórico
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
    $stmt->bind_param("i", $id);
    $stmt->execute();

} else {

    // Funcionário vê o histórico de todos os clientes, com nome do cliente
    $sql = "SELECT chamado.id_chamado, chamado.descricao,
            chamado.status, chamado.data_abertura,
            cliente.nome AS cliente_nome,
            agendamento.data, agendamento.horario,
            agendamento.tecnico
            FROM chamado
            JOIN abre ON chamado.id_chamado = abre.id_chamado
            JOIN cliente ON abre.id_cliente = cliente.id_cliente
            LEFT JOIN gera ON chamado.id_chamado = gera.id_chamado
            LEFT JOIN agendamento
            ON gera.id_agendamento = agendamento.id_agendamento
            ORDER BY chamado.data_abertura DESC";

    $stmt = $conexao->prepare($sql);
    $stmt->execute();
}

$historico = $stmt->get_result();

$rotulo_status = [
    "aberto"       => "Aberto",
    "em_andamento" => "Em andamento",
    "finalizado"   => "Finalizado",
];

$pagina_atual = "historico";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Histórico | ConectaNet</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
.cronograma{background:white;border-radius:10px;box-shadow:0 2px 8px #ddd;overflow:hidden}
.cronograma div.item{padding:18px;border-bottom:1px solid #eee}
.cronograma div.item:last-child{border:0}
.cronograma h3{color:#3157d5;margin-bottom:10px}
.cronograma p{margin:6px 0;color:#555}
.status-form{display:flex;align-items:center;gap:10px;margin-top:12px}
.status-form select{padding:8px 10px;border:1px solid #d5d9e2;border-radius:7px}
.status-form button{padding:8px 14px;font-size:13px}
</style>
</head>

<body>

<?php include "menu.php"; ?>

<main>

<h1>Histórico de Atendimento</h1>

<div class="cronograma">

<?php while($h = $historico->fetch_assoc()) { ?>

<div class="item">

<h3>
<i class="fa-solid fa-ticket"></i>
Protocolo #<?= $h["id_chamado"] ?>
</h3>

<?php if ($tipo === "funcionario") { ?>
<p>
<i class="fa-solid fa-user"></i>
<strong>Cliente:</strong>
<?= htmlspecialchars($h["cliente_nome"]) ?>
</p>
<?php } ?>

<p>
<strong>Problema:</strong>
<?= htmlspecialchars($h["descricao"]) ?>
</p>

<p>
<strong>Status:</strong>
<?= $rotulo_status[$h["status"]] ?? htmlspecialchars($h["status"]) ?>
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

<?php if ($tipo === "funcionario") { ?>

<form method="POST" class="status-form">
    <input type="hidden" name="id_chamado" value="<?= $h["id_chamado"] ?>">

    <select name="novo_status">
        <?php foreach ($rotulo_status as $valor => $rotulo) { ?>
            <option value="<?= $valor ?>" <?= $valor === $h["status"] ? "selected" : "" ?>>
                <?= $rotulo ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">
        <i class="fa-solid fa-floppy-disk"></i>
        Atualizar status
    </button>
</form>

<?php } ?>

</div>

<?php } ?>

</div>

</main>

</body>
</html>
