<?php
session_start();
include "conexao.php";

if (!isset($_SESSION["id_cliente"])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION["id_cliente"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = $_POST["data"];
    $horario = $_POST["horario"];

    $stmt = $conexao->prepare(
        "INSERT INTO agendamento (data, horario, tecnico)
         VALUES (?, ?, 'A definir')"
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

$visitas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visitas | ConectaNet</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<?php include "menu.php"; ?>

<main>
    <h1>Agendar visita técnica</h1>
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
    
    <!-- Iní
    <h2>Minhas visitas</h2>
    <?php if ($visitas->num_rows > 0) { ?>
    <?php while ($v = $visitas->fetch_assoc()) { ?>
    <div class="visita">
        <h2>
            <i class="fa-solid fa-screwdriver-wrench"></i>
            Visita técnica
        </h2>

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

    <p>
    <i class="fa-solid fa-user-gear"></i>
    <strong>Atendido por:</strong>
    <?= htmlspecialchars($v["tecnico"]) ?>
    </p>

    </div>

    <?php } ?>

    <?php } else { ?>

    <p>Nenhuma visita agendada.</p>

    <?php } ?>

</main>

<style>

.agendamento {
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 2px 8px #ddd;
    max-width:600px;
    margin-bottom:25px
}

.agendamento input {
    width:100%;
    margin-bottom:10px
}

.visita {
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 8px #ddd;
    margin-bottom:15px
}

.visita h2 {
    color:#3157d5;
    margin-bottom:12px
}

.visita p {
    margin:8px 0
}
</style>

</body>
</html>