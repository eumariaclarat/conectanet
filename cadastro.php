<?php
session_start();
include "conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tipo = $_POST["tipo"] ?? "";
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senhaDigitada = $_POST["senha"] ?? "";

    // Campos exclusivos de cliente
    $cpf = trim($_POST["cpf"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");

    // Campo exclusivo de funcionário
    $cargo = trim($_POST["cargo"] ?? "");

    if ($tipo !== "cliente" && $tipo !== "funcionario") {

        $erro = "Selecione um tipo de usuário válido.";

    } elseif (empty($nome) || empty($email) || empty($senhaDigitada)) {

        $erro = "Preencha todos os campos.";

    } elseif ($tipo === "cliente" && (empty($cpf) || empty($telefone) || empty($endereco))) {

        $erro = "Preencha todos os campos.";

    } elseif ($tipo === "funcionario" && empty($cargo)) {

        $erro = "Preencha todos os campos.";

    } else {

        $senha = password_hash($senhaDigitada, PASSWORD_DEFAULT);

        if ($tipo === "cliente") {

            $sql = "SELECT id_cliente FROM cliente
                    WHERE cpf = ? OR email = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ss", $cpf, $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $erro = "CPF ou e-mail já cadastrado.";
            } else {

                $sql = "INSERT INTO cliente
                        (nome, cpf, email, telefone, endereco, senha)
                        VALUES (?, ?, ?, ?, ?, ?)";

                $stmt = $conexao->prepare($sql);

                $stmt->bind_param(
                    "ssssss",
                    $nome,
                    $cpf,
                    $email,
                    $telefone,
                    $endereco,
                    $senha
                );

                if ($stmt->execute()) {

                    session_regenerate_id(true);

                    $_SESSION["tipo"] = "cliente";
                    $_SESSION["id_cliente"] = $conexao->insert_id;
                    $_SESSION["nome"] = $nome;
                    $_SESSION["email"] = $email;

                    header("Location: perfil.php");
                    exit;
                } else {
                    $erro = "Erro ao cadastrar cliente.";
                }
            }

        } else {

            $sql = "SELECT id_funcionario FROM funcionario WHERE email = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $erro = "E-mail já cadastrado.";
            } else {

                $sql = "INSERT INTO funcionario (nome, cargo, email, senha)
                        VALUES (?, ?, ?, ?)";

                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ssss", $nome, $cargo, $email, $senha);

                if ($stmt->execute()) {

                    session_regenerate_id(true);

                    $_SESSION["tipo"] = "funcionario";
                    $_SESSION["id_funcionario"] = $conexao->insert_id;
                    $_SESSION["nome"] = $nome;
                    $_SESSION["email"] = $email;

                    header("Location: perfil.php");
                    exit;
                } else {
                    $erro = "Erro ao cadastrar funcionário.";
                }
            }
        }
    }
}

$tipo_selecionado = $_POST["tipo"] ?? "cliente";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro | ConectaNet</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="login">

    <div class="logo">CN</div>

    <h1>ConectaNet</h1>
    <h2>Criar conta</h2>
    <p class="bem-vindo">Preencha os dados para se cadastrar</p>

    <?php if ($erro) { ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php } ?>

    <form method="POST" id="form-cadastro">

        <div class="campo-tipo">
            <label>Tipo de usuário</label>
            <div class="select-tipo">
                <i class="fa-solid fa-user"></i>
                <select name="tipo" id="tipo" onchange="alternarCampos()" required>
                    <option value="cliente" <?= $tipo_selecionado === "cliente" ? "selected" : "" ?>>Cliente</option>
                    <option value="funcionario" <?= $tipo_selecionado === "funcionario" ? "selected" : "" ?>>Funcionário</option>
                </select>
            </div>
        </div>

        <input
            type="text"
            name="nome"
            placeholder="Nome completo"
            value="<?= htmlspecialchars($_POST["nome"] ?? "") ?>"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="E-mail"
            value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
            required
        >

        <div class="senha-container">
            <input
                type="password"
                name="senha"
                id="senha"
                placeholder="Senha"
                required
            >
            <button type="button" class="mostrar" onclick="mostrarSenha()">Mostrar</button>
        </div>

        <div id="campos-cliente" style="display:flex;flex-direction:column;gap:13px">
            <input
                type="text"
                name="cpf"
                placeholder="CPF"
                value="<?= htmlspecialchars($_POST["cpf"] ?? "") ?>"
            >

            <input
                type="text"
                name="telefone"
                placeholder="Telefone"
                value="<?= htmlspecialchars($_POST["telefone"] ?? "") ?>"
            >

            <input
                type="text"
                name="endereco"
                placeholder="Endereço"
                value="<?= htmlspecialchars($_POST["endereco"] ?? "") ?>"
            >
        </div>

        <div id="campos-funcionario" style="display:none">
            <input
                type="text"
                name="cargo"
                placeholder="Cargo (ex: Técnico, Suporte)"
                value="<?= htmlspecialchars($_POST["cargo"] ?? "") ?>"
            >
        </div>

        <button type="submit" class="entrar">Cadastrar</button>

    </form>

    <p class="cadastro">
        Já tem conta? <a href="login.php">Entrar</a>
    </p>

</div>

<script>
function alternarCampos() {
    const tipo = document.getElementById("tipo").value;
    const cliente = document.getElementById("campos-cliente");
    const funcionario = document.getElementById("campos-funcionario");

    const camposCliente = cliente.querySelectorAll("input");
    const camposFuncionario = funcionario.querySelectorAll("input");

    if (tipo === "funcionario") {
        cliente.style.display = "none";
        funcionario.style.display = "block";
        camposCliente.forEach(c => c.required = false);
        camposFuncionario.forEach(c => c.required = true);
    } else {
        cliente.style.display = "flex";
        funcionario.style.display = "none";
        camposCliente.forEach(c => c.required = true);
        camposFuncionario.forEach(c => c.required = false);
    }
}

function mostrarSenha() {
    const campo = document.getElementById("senha");
    const botao = document.querySelector(".mostrar");
    if (campo.type === "password") {
        campo.type = "text";
        botao.textContent = "Ocultar";
    } else {
        campo.type = "password";
        botao.textContent = "Mostrar";
    }
}

alternarCampos();
</script>

</body>
</html>