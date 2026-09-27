<?php
/**
 * Menu (header + nav) compartilhado.
 * Requer que verifica_login.php já tenha sido incluído antes
 * (usa as variáveis $tipo e $pagina_atual).
 */
$pagina_atual = $pagina_atual ?? "";
?>
<header>Central de Atendimento</header>

<nav>

<?php if ($tipo === "cliente") { ?>

    <a href="perfil.php" class="<?= $pagina_atual === 'perfil' ? 'ativo' : '' ?>">
        <i class="fa-regular fa-address-card"></i> Perfil
    </a>

    <a href="painel.php" class="<?= $pagina_atual === 'painel' ? 'ativo' : '' ?>">
        <i class="fa-solid fa-sliders"></i> Painel
    </a>

    <a href="protocolos.php" class="<?= $pagina_atual === 'protocolos' ? 'ativo' : '' ?>">
        <i class="fa-solid fa-file-lines"></i> Protocolos
    </a>

<?php } else if ($tipo === "funcionario") { ?>

    <a href="perfil.php" class="<?= $pagina_atual === 'perfil' ? 'ativo' : '' ?>">
        <i class="fa-regular fa-address-card"></i> Perfil
    </a>

    <a href="agendamento.php" class="<?= $pagina_atual === 'agendamento' ? 'ativo' : '' ?>">
        <i class="fa-regular fa-calendar"></i> Agendamentos
    </a>

    <a href="historico.php" class="<?= $pagina_atual === 'historico' ? 'ativo' : '' ?>">
        <i class="fa-regular fa-calendar-days"></i> Histórico
    </a>

<?php } ?>

    <a href="logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Sair
    </a>

</nav>
