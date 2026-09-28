<?php
require "verifica_login.php";

if ($tipo === "cliente" && $_SERVER["REQUEST_METHOD"] == "POST") {

    $data = $_POST["data"];
    $horario = $_POST["horario"];

    $stmt = $conexao->prepare(
        "INSERT INTO agendamento (data, horario, tecnico)
         VALUES (?, ?, 'A definir')"
    );

    $stmt->bind_param("ss", $data, $horario);
    $stmt->execute();

    $id_agendamento = $conexao->insert_id;

    // Busca o último chamado do cliente
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

    header("Location: agendamentoC.php");
    exit;
}

$pagina_atual = "agendamentoC";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<title>Agendar visita | ConectaNet</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.agendamento {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 8px #ddd;
    max-width: 600px;
}

.agendamento input {
    width: 100%;
    margin-bottom: 15px;
}

</style>

</head>

<body>

<?php include "menu.php"; ?>

<main>

<h1>Agendar visita técnica</h1>

<div class="agendamento">

<form method="POST">

<label>Data:</label>

<input
    type="date"
    name="data"
    required
>

<label>Horário:</label>

<input
    type="time"
    name="horario"
    required
>

<button type="submit">

<i class="fa-solid fa-calendar-check"></i>

Solicitar agendamento

</button>

</form>

</div>

</main>

</body>

</html>
