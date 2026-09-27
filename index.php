<?php
/**
 * Ponto de entrada do site.
 * Quem não está logado vê a página inicial (home.php).
 * Quem já está logado (cliente ou funcionário) vai direto para o perfil.
 */
session_start();

if (isset($_SESSION["tipo"])) {
    header("Location: perfil.php");
} else {
    header("Location: home.php");
}
exit;
