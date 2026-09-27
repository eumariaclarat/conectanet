<?php
require "verifica_login.php";

if ($tipo !== "cliente") {
    header("Location: historico.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: protocolos.php");
    exit;
}

$id_chamado = (int) $_GET["id"];

if (isset($_POST["descricao"])) {

    $descricao = $_POST["descricao"];

    $sql = "UPDATE chamado
            INNER JOIN abre ON chamado.id_chamado = abre.id_chamado
            SET chamado.descricao = ?
            WHERE chamado.id_chamado = ?
            AND abre.id_cliente = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sii", $descricao, $id_chamado, $id);
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
$stmt->bind_param("ii", $id_chamado, $id);
$stmt->execute();

$p = $stmt->get_result()->fetch_assoc();

if (!$p) {
    header("Location: protocolos.php");
    exit;
}

$pagina_atual = "protocolos";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Protocolo | ConectaNet</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<?php include "menu.php"; ?>

<main>

<h1>Editar Protocolo #<?= $id_chamado ?></h1>

<form method="POST">

    <input
        type="text"
        name="descricao"
        value="<?= htmlspecialchars($p["descricao"]) ?>"
        required
    >

    <button type="submit">
        <i class="fa-solid fa-floppy-disk"></i>
        Salvar
    </button>

</form>

<a href="protocolos.php">Voltar</a>

</main>

</body>
</html>
