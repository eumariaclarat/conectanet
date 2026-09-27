<?php
require "verifica_login.php";

$status_visita_validos = ["pendente", "em_analise", "realizada"];

// Funcionário edita o técnico responsável e o status da visita
if ($tipo === "funcionario" && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_agendamento"])) {

    $id_agendamento = (int) $_POST["id_agendamento"];
    $tecnico = trim($_POST["tecnico"] ?? "");
    $status = $_POST["status"] ?? "pendente";

    if (!in_array($status, $status_visita_validos, true)) {
        $status = "pendente";
    }

    if ($tecnico === "") {
        $tecnico = "A definir";
    }

    $stmt = $conexao->prepare(
        "UPDATE agendamento SET tecnico = ?, status = ? WHERE id_agendamento = ?"
    );

    if ($stmt) {
        $stmt->bind_param("ssi", $tecnico, $status, $id_agendamento);
        $stmt->execute();
    }

    header("Location: agendamento.php");
    exit;
}

if ($tipo === "cliente" && $_SERVER["REQUEST_METHOD"] == "POST") {

    $data = $_POST["data"];
    $horario = $_POST["horario"];

    $stmt = $conexao->prepare(
        "INSERT INTO agendamento (data, horario, tecnico, status)
         VALUES (?, ?, 'A definir', 'pendente')"
    );
    $stmt->bind_param("ss", $data, $horario);
    $stmt->execute();

    $id_agendamento = $conexao->insert_id;

    $stmt = $conexao->prepare(
        "SELECT chamado.id_chamado
         FROM chamado
         INNER JOIN abre
         ON chamado.id_chamado = abre.id_chamado
         WHERE abre.id_cliente = ?
         ORDER BY chamado.id_chamado DESC
         LIMIT 1"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $chamado = $stmt->get_result()->fetch_assoc();

    if ($chamado) {

        $stmt = $conexao->prepare(
            "INSERT INTO gera (id_chamado, id_agendamento)
             VALUES (?, ?)"
        );

        $stmt->bind_param(
            "ii",
            $chamado["id_chamado"],
            $id_agendamento
        );

        $stmt->execute();
    }

    header("Location: agendamento.php");
    exit;
}

if ($tipo === "cliente") {

    // Cliente vê apenas as próprias visitas
    $sql = "SELECT agendamento.*
            FROM agendamento
            INNER JOIN gera
            ON agendamento.id_agendamento = gera.id_agendamento
            INNER JOIN abre
            ON gera.id_chamado = abre.id_chamado
            WHERE abre.id_cliente = ?
            ORDER BY agendamento.data, agendamento.horario";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

} else {

    // Funcionário vê todas as visitas agendadas, de todos os clientes
    $sql = "SELECT agendamento.*, cliente.nome AS cliente_nome
            FROM agendamento
            INNER JOIN gera
            ON agendamento.id_agendamento = gera.id_agendamento
            INNER JOIN abre
            ON gera.id_chamado = abre.id_chamado
            INNER JOIN cliente
            ON abre.id_cliente = cliente.id_cliente
            ORDER BY agendamento.data, agendamento.horario";

    $stmt = $conexao->prepare($sql);
    $stmt->execute();
}

$visitas = $stmt->get_result();

$rotulo_status_visita = [
    "pendente"   => "Pendente",
    "em_analise" => "Em análise",
    "realizada"  => "Visita realizada",
];

$pagina_atual = "agendamento";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Visitas | ConectaNet</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
.agendamento{background:white;padding:25px;border-radius:10px;box-shadow:0 2px 8px #ddd;max-width:600px;margin-bottom:25px}
.agendamento input{width:100%;margin-bottom:10px}
.visita{background:white;padding:20px;border-radius:10px;box-shadow:0 2px 8px #ddd;margin-bottom:15px}
.visita h2{color:#3157d5;margin-bottom:12px}
.visita p{margin:8px 0}
.badge-visita{display:inline-block;font-size:12px;font-weight:bold;padding:4px 10px;border-radius:20px}
.badge-visita.pendente{background:#fff2d9;color:#a86500}
.badge-visita.em_analise{background:#e4e9ff;color:#3157d5}
.badge-visita.realizada{background:#d9f4e3;color:#1c8a4f}
.editar-visita-form{margin-top:14px;padding-top:14px;border-top:1px solid #eee;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.editar-visita-form input,
.editar-visita-form select{padding:8px 10px;border:1px solid #d5d9e2;border-radius:7px;width:auto}
.editar-visita-form button{font-size:13px;padding:8px 14px}
</style>
</head>

<body>

<?php include "menu.php"; ?>

<main>

<h1>Visitas técnicas</h1>

<?php if ($tipo === "cliente") { ?>

<div class="agendamento">

<form method="POST">

<label>Data:</label>
<input type="date" name="data" required>

<label>Horário:</label>
<input type="time" name="horario" required>

<button type="submit">
<i class="fa-solid fa-calendar-check"></i>
Solicitar agendamento
</button>

</form>

</div>

<h2>Minhas visitas</h2>

<?php } else { ?>

<h2>Todas as visitas agendadas</h2>

<?php } ?>

<?php if ($visitas->num_rows > 0) { ?>

<?php while ($v = $visitas->fetch_assoc()) { ?>

<div class="visita">

<h2>
<i class="fa-solid fa-screwdriver-wrench"></i>
Visita técnica
</h2>

<?php if ($tipo === "funcionario") { ?>
<p>
<i class="fa-solid fa-user"></i>
<strong>Cliente:</strong>
<?= htmlspecialchars($v["cliente_nome"]) ?>
</p>
<?php } ?>

<p>
<i class="fa-regular fa-calendar"></i>
<strong>Data:</strong>
<?= date("d/m/Y", strtotime($v["data"])) ?>
</p>

<p>
<i class="fa-regular fa-clock"></i>
<strong>Horário:</strong>
<?= date("H:i", strtotime($v["horario"])) ?>
</p>

<?php if ($tipo === "cliente") { ?>
<p>
<i class="fa-solid fa-user-gear"></i>
<strong>Atendido por:</strong>
<?= htmlspecialchars($v["tecnico"]) ?>
</p>
<?php } ?>

<p>
<i class="fa-solid fa-circle-check"></i>
<strong>Status:</strong>
<span class="badge-visita <?= $v["status"] ?>">
<?= $rotulo_status_visita[$v["status"]] ?? htmlspecialchars($v["status"]) ?>
</span>
</p>

<?php if ($tipo === "funcionario") { ?>

<form method="POST" class="editar-visita-form">
<input type="hidden" name="id_agendamento" value="<?= $v["id_agendamento"] ?>">

<input
    type="text"
    name="tecnico"
    value="<?= htmlspecialchars($v["tecnico"]) ?>"
    placeholder="Nome do técnico"
>

<select name="status">
    <option value="pendente" <?= $v["status"] === "pendente" ? "selected" : "" ?>>Pendente</option>
    <option value="em_analise" <?= $v["status"] === "em_analise" ? "selected" : "" ?>>Em análise</option>
    <option value="realizada" <?= $v["status"] === "realizada" ? "selected" : "" ?>>Visita realizada</option>
</select>

<button type="submit">
<i class="fa-solid fa-floppy-disk"></i>
Salvar
</button>

</form>

<?php } ?>

</div>

<?php } ?>

<?php } else { ?>

<p>Nenhuma visita agendada.</p>

<?php } ?>

</main>

</body>
</html>
