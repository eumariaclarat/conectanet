<?php

include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $endereco = $_POST["endereco"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

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
        header("Location: login.php");
        exit;
    } else {
        $erro = "Erro ao criar a conta. Verifique se o CPF já está cadastrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ConectaNet - Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login">

    <h1>ConectaNet</h1>

    <h2>Criar conta</h2>

    <?php if (isset($erro)) { ?>
        <p><?php echo $erro; ?></p>
    <?php } ?>

    <form method="POST">

        <input type="text" name="nome" placeholder="Nome completo" required>

        <input type="text" name="cpf" placeholder="CPF" required>

        <input type="email" name="email" placeholder="E-mail" required>

        <input type="text" name="telefone" placeholder="Telefone">

        <input type="text" name="endereco" placeholder="Endereço">

        <input type="password" name="senha" placeholder="Senha" required>

        <button type="submit">Criar conta</button>

    </form>

    <p>
        Já possui uma conta?
        <a href="login.php">Entrar</a>
    </p>

</div>

</body>
</html>