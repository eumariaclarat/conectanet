<?php
/**
 * Cadastro de funcionário.
 * Não fica no menu do sistema — é uma tela administrativa simples
 * para criar contas de funcionário (a tabela "funcionario" começa
 * vazia no banco.sql). Se quiser, restrinja o acesso a este arquivo
 * depois de criar as contas necessárias.
 */

include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome  = $_POST["nome"];
    $cargo = $_POST["cargo"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO funcionario (nome, cargo, email, senha)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssss", $nome, $cargo, $email, $senha);

    if ($stmt->execute()) {
        $sucesso = "Funcionário cadastrado com sucesso.";
    } else {
        $erro = "Erro ao cadastrar. Verifique se o e-mail já está em uso.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ConectaNet - Cadastro de funcionário</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login">

    <h1>ConectaNet</h1>
    <h2>Cadastrar funcionário</h2>

    <?php if (isset($erro)) { ?>
        <div class="erro"><?= $erro ?></div>
    <?php } ?>

    <?php if (isset($sucesso)) { ?>
        <p style="color:#1c8a4f;margin-bottom:15px;"><?= $sucesso ?></p>
    <?php } ?>

    <form method="POST">

        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="text" name="cargo" placeholder="Cargo (ex: Técnico, Suporte)" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>

        <button type="submit">Cadastrar</button>

    </form>

    <p><a href="login.php">Voltar ao login</a></p>

</div>

</body>
</html>
