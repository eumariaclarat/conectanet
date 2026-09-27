<?php
session_start();
include "conexao.php";

if (!isset($_SESSION["id_cliente"])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION["id_cliente"];

if (isset($_GET["excluir"])) {
    $chamado = (int) $_GET["excluir"];

    $stmt = $conexao->prepare("DELETE FROM abre WHERE id_cliente = ? AND id_chamado = ?");
    $stmt->bind_param("ii", $id, $chamado);
    $stmt->execute();

    $stmt = $conexao->prepare("DELETE FROM chamado WHERE id_chamado = ?");
    $stmt->bind_param("i", $chamado);
    $stmt->execute();

    header("Location: protocolos.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $descricao = $_POST["descricao"];

    if ($descricao == "Outro")
        $descricao = $_POST["outro"];

    $stmt = $conexao->prepare(
        "INSERT INTO chamado (descricao,data_abertura,status)
         VALUES (?,NOW(),'aberto')"
    );

    $stmt->bind_param("s", $descricao);
    $stmt->execute();

    $chamado = $conexao->insert_id;

    $stmt = $conexao->prepare(
        "INSERT INTO abre VALUES (?,?)"
    );

    $stmt->bind_param("ii", $id, $chamado);
    $stmt->execute();

    header("Location: protocolos.php");
    exit;
}

$sql = "SELECT chamado.*
        FROM chamado
        JOIN abre ON chamado.id_chamado=abre.id_chamado
        WHERE abre.id_cliente=?
        ORDER BY chamado.id_chamado DESC";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Protocolos | ConectaNet</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<?php include "menu.php"; ?>

<main>
    <h1>Meus Protocolos</h1>
    <form method="POST">
        <select name="descricao" id="tipo" onchange="outroCampo()" required>
            <option value="">Escolha o problema</option>
            <option>Problema com internet</option>
            <option>Internet lenta</option>

<option>Sem sinal</option>

<option>Problema com ligação</option>

<option>Problema com TV</option>

<option>Problema com fatura</option>

<option>Problema no aplicativo</option>

<option value="Outro">Outro</option>

</select>

<input
type="text"
name="outro"
id="outro"
placeholder="Digite o problema"
style="display:none">

<button>Abrir protocolo</button>

</form>

<table>

<tr>

<th>Protocolo</th>
<th>Problema</th>
<th>Data e hora</th>
<th>Status</th>
<th>Ações</th>

</tr>

<?php while ($p = $resultado->fetch_assoc()) { ?>

<tr>

<td>#<?= $p["id_chamado"] ?></td>

<td><?= htmlspecialchars($p["descricao"]) ?></td>

<td><?= date("d/m/Y H:i", strtotime($p["data_abertura"])) ?></td>

<td><?= $p["status"] ?></td>

<td>

<a href="editar.php?id=<?= $p["id_chamado"] ?>">Editar</a> |

<a href="protocolos.php?excluir=<?= $p["id_chamado"] ?>"
onclick="return confirm('Excluir protocolo?')">Excluir</a>

</td>

</tr>

<?php } ?>

</table>

</main>

<script>

function outroCampo() {

    let tipo = document.getElementById("tipo");
    let outro = document.getElementById("outro");

    outro.style.display = tipo.value == "Outro" ? "block" : "none";

}

</script>

</body>

</html>