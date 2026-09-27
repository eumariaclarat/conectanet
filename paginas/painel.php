<?php
session_start();

if (!isset($_SESSION["id_cliente"])) {
    header("Location: home.php");
    exit;
}

include "conexao.php";

$id = $_SESSION["id_cliente"];
$nome = $_SESSION["nome"];
$sql = "SELECT chamado.*
        FROM chamado
        INNER JOIN abre 
            ON chamado.id_chamado = abre.id_chamado
        WHERE abre.id_cliente = ?
        ORDER BY chamado.id_chamado DESC";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$protocolos = $stmt->get_result();

$sql = "SELECT agendamento.id_agendamento
        FROM agendamento
        INNER JOIN gera
            ON agendamento.id_agendamento = gera.id_agendamento
        INNER JOIN abre
            ON gera.id_chamado = abre.id_chamado
        WHERE abre.id_cliente = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$agendamento = $stmt->get_result();

$sql = "SELECT chamado.id_chamado
        FROM chamado
        INNER JOIN abre
            ON chamado.id_chamado = abre.id_chamado
        WHERE abre.id_cliente = ?
        AND chamado.status = 'finalizado'";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$atendimentos = $stmt->get_result();

require_once "conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConectaNet | Central de Atendimento</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<?php include "menu.php"; ?>

<main>
    <h1>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </h1>

    <!-- Início Cards-->
    <div class="cards">
        <div class="card">
            <h3>Protocolos abertos</h3>
            <h2>
                <?php echo $protocolos->num_rows; ?>
            </h2>
        </div>
        <div class="card">
            <h3>Visitas agendadas</h3>
            <h2>
                <?php echo $agendamento->num_rows; ?>
            </h2>
        </div>
        <div class="card">
            <h3>Atendimentos</h3>
            <h2>
                <?php echo $atendimentos->num_rows; ?>
            </h2>
        </div>
    </div>
    <!-- Fim Cards-->

    <h3>Últimos Protocolos</h3>
    <table>
        <tr>
            <th>Protocolo</th>
            <th>Descrição</th>
            <th>Status</th>
        </tr>
        <?php while ($p = $protocolos->fetch_assoc()) { ?>
            <tr>
                <td>
                    #<?php echo $p["id_chamado"]; ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($p["descricao"]); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($p["status"]); ?>
                </td>
            </tr>
        <?php } ?>
    </table>

</main>

</body>
</html>