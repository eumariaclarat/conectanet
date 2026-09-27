<?php
require "verifica_login.php";

if ($tipo !== "cliente") {
    header("Location: historico.php");
    exit;
}

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

$rotulo_status = [
    "aberto"       => "Aberto",
    "em_andamento" => "Em andamento",
    "finalizado"   => "Finalizado",
];

$pagina_atual = "protocolos";
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

<select name="descricao" id="descricao" onchange="outroCampo()" required>
    <option value="" disabled selected>
        Escolha o problema
    </option>

    <option value="Internet sem conexão">
        Internet sem conexão
    </option>

    <option value="Internet lenta ou instável">
        Internet lenta ou instável
    </option>

    <option value="Problema no Wi-Fi">
        Problema no Wi-Fi
    </option>

    <option value="Problema na linha telefônica">
        Problema na linha telefônica
    </option>

    <option value="Problema no sinal de TV">
        Problema no sinal de TV
    </option>

    <option value="Dúvidas sobre fatura ou cobrança">
        Dúvidas sobre fatura ou cobrança
    </option>

    <option value="Equipamento com defeito">
        Equipamento com defeito
    </option>

    <option value="Solicitação de visita técnica">
        Solicitação de visita técnica
    </option>

    <option value="Problema no aplicativo">
        Problema no aplicativo
    </option>

    <option value="Outro">
        Outro
    </option>
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

<td><?= $rotulo_status[$p["status"]] ?? htmlspecialchars($p["status"]) ?></td>

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

    let problema = document.getElementById("descricao");
    let outro = document.getElementById("outro");

    outro.style.display = problema.value == "Outro" ? "block" : "none";
    outro.required = problema.value == "Outro";

}

</script>

</body>

</html>
