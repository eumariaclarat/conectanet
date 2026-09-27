<?php

session_start();

include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM cliente WHERE email = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {

        $cliente = $resultado->fetch_assoc();

        if (password_verify($senha, $cliente["senha"])) {

            $_SESSION["id_cliente"] = $cliente["id_cliente"];
            $_SESSION["nome"] = $cliente["nome"];
            $_SESSION["email"] = $cliente["email"];

            header("Location: index.php");
            exit;

        } else {
            $erro = "Senha incorreta.";
        }

    } else {
        $erro = "E-mail não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ConectaNet - Login</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login">

    <div class="logo">
        CN
    </div>

    <h1>ConectaNet</h1>

    <h2>Central de Atendimento</h2>

    <p class="bem-vindo">
        Entre para acessar sua conta.
    </p>

    <?php if (isset($erro)) { ?>
        <div class="erro">
            <?php echo $erro; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="E-mail"
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

            <button
                type="button"
                class="mostrar"
                onclick="mostrarSenha()"
            >
                Mostrar
            </button>

        </div>

        <button type="submit" class="entrar">
            Entrar
        </button>

    </form>

    <p class="cadastro">
        Ainda não possui uma conta?
        <a href="cadastro.php">Criar conta</a>
    </p>

    <div class="status">
        Sistema online
    </div>

    <footer>
        © 2026 ConectaNet - Christian
    </footer>

</div>

<script>

function mostrarSenha() {

    let senha = document.getElementById("senha");
    let botao = document.querySelector(".mostrar");

    if (senha.type === "password") {
        senha.type = "text";
        botao.innerText = "Ocultar";
    } else {
        senha.type = "password";
        botao.innerText = "Mostrar";
    }

}

</script>

</body>

</html>