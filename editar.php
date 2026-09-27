<?php
session_start();
include "conexao.php";

if (!isset($_SESSION["id_cliente"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: protocolos.php");
    exit;
}

$id = (int) $_GET["id"];
$id_cliente = $_SESSION["id_cliente"];

if (isset($_POST["descricao"])) {

    $descricao = $_POST["descricao"];

    $sql = "UPDATE chamado
            INNER JOIN abre ON chamado.id_chamado = abre.id_chamado
            SET chamado.descricao = ?
            WHERE chamado.id_chamado = ?
            AND abre.id_cliente = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sii", $descricao, $id, $id_cliente);
    $stmt->execute();

    header("Location: protocolos.php");
    exit;
}

$sql = "SELECT chamado.descricao
        FROM chamado
        INNER JOIN abre ON chamado.id_chamado = abre.id_chamado
        WHERE chamado.id_chamado = ?
        AND abre.id_cliente = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $id, $id_cliente);
$stmt->execute();

$p = $stmt->get_result()->fetch_assoc();

if (!$p) {
    header("Location: protocolos.php");
    exit;
}
?>

<h1>Editar Protocolo #<?= $id ?></h1>

<form method="POST">

    <input
        type="text"
        name="descricao"
        value="<?= htmlspecialchars($p["descricao"]) ?>"
        required
    >

    <button type="submit">Salvar</button>

</form>

<a href="protocolos.php">Voltar</a>