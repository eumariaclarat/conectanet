<?php
// Evita que uma falha de conexão gere uma exceção não tratada (Fatal error)
mysqli_report(MYSQLI_REPORT_OFF);

$conexao = new mysqli("localhost", "root", "", "conectanet");

if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");
?>