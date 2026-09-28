<?php
require "verifica_login.php";

if ($tipo === "cliente") {

    // Cliente vê somente suas visitas
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

    // Funcionário vê todas as visitas
    $sql = "SELECT agendamento.*,
                   cliente.nome AS cliente_nome
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

$pagina_atual = "agendamentoF";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<title>Minhas visitas | ConectaNet</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

.visita {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px #ddd;
    margin-bottom: 15px;
    max-width: 700px;
}

.visita h2 {
    color: #3157d5;
    margin-bottom: 12px;
}

.visita p {
    margin: 8px 0;
}

</style>

</head>

<body>

<?php include "menu.php"; ?>

<main>

<?php if ($tipo === "cliente") { ?>

<h1>Minhas visitas técnicas</h1>

<?php } else { ?>

<h1>Todas as visitas técnicas</h1>

<?php } ?>


<?php if ($visitas->num_rows > 0) { ?>

    <?php while ($v = $visitas->fetch_assoc()) { ?>

        <div class="visita">

            <h2>

                <i class="fa-solid fa-screwdriver-wrench"></i>

                Visita técnica

            </h2>


            <?php if ($tipo !== "cliente") { ?>

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


            <p>

                <i class="fa-solid fa-user-gear"></i>

                <strong>Atendido por:</strong>

                <?= htmlspecialchars($v["tecnico"]) ?>

            </p>

        </div>

    <?php } ?>

<?php } else { ?>

    <p>Nenhuma visita técnica agendada.</p>

<?php } ?>

</main>

</body>

</html>