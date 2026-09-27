<?php
/**
 * Verificação de login central.
 * Inclua este arquivo (require "verifica_login.php";) no topo de
 * qualquer página que exija usuário logado (cliente OU funcionário).
 *
 * Depois do require, ficam disponíveis:
 *   $tipo  -> "cliente" ou "funcionario"
 *   $id    -> id_cliente ou id_funcionario, conforme o tipo
 *   $nome  -> nome do usuário logado
 */

session_start();
include "conexao.php";

if (!isset($_SESSION["tipo"]) ||
    (!isset($_SESSION["id_cliente"]) && !isset($_SESSION["id_funcionario"]))) {
    header("Location: login.php");
    exit;
}

$tipo = $_SESSION["tipo"];
$id   = ($tipo === "cliente") ? $_SESSION["id_cliente"] : $_SESSION["id_funcionario"];
$nome = $_SESSION["nome"];
