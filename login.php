<?php
session_start();
include "conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if (empty($email) || empty($senha)) {

        $erro = "Preencha todos os campos.";

    } else {

        // Procura primeiro entre os clientes
        $sql = "SELECT * FROM cliente WHERE email = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {

            $cliente = $resultado->fetch_assoc();

            if (password_verify($senha, $cliente["senha"])) {

                session_regenerate_id(true);

                $_SESSION["tipo"] = "cliente";
                $_SESSION["id_cliente"] = $cliente["id_cliente"];
                $_SESSION["nome"] = $cliente["nome"];
                $_SESSION["email"] = $cliente["email"];

                header("Location: perfil.php");
                exit;

            } else {
                $erro = "Senha incorreta.";
            }

        } else {

            // Se não encontrou como cliente, procura como funcionário
            $sql = "SELECT * FROM funcionario WHERE email = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows == 1) {

                $funcionario = $resultado->fetch_assoc();

                if (password_verify($senha, $funcionario["senha"])) {

                    session_regenerate_id(true);

                    $_SESSION["tipo"] = "funcionario";
                    $_SESSION["id_funcionario"] = $funcionario["id_funcionario"];
                    $_SESSION["nome"] = $funcionario["nome"];
                    $_SESSION["email"] = $funcionario["email"];

                    header("Location: perfil.php");
                    exit;

                } else {
                    $erro = "Senha incorreta.";
                }

            } else {
                $erro = "E-mail não encontrado.";
            }
        }
    }
}

$mensagem = $_SESSION["mensagem"] ?? "";
unset($_SESSION["mensagem"]);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Entrar | ConectaNet</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="login">

    <div class="logo">CN</div>

    <h1>ConectaNet</h1>
    <h2>Acesse sua conta</h2>
    <p class="bem-vindo">Informe seus dados para continuar</p>

    <?php if ($erro) { ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php } ?>

    <?php if ($mensagem) { ?>
        <div class="erro" style="background:#e9f9ef;color:#1c8a4f;border-color:#bdeccd;">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php } ?>

    <form method="POST">

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

        <button type="submit" class="entrar">Entrar</button>

    </form>

    <p class="cadastro">
        Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a>
    </p>

</div>

<script>
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
</script>

</body>
</html>