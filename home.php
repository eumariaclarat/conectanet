<?php
session_start();

if (isset($_SESSION["id_cliente"])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ConectaNet | Seu chamado, em boas mãos</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="pagina-inicial">

<header class="topo">
    <a href="home.php" class="logo">
        <i class="fa-solid fa-headset"></i>
        ConectaNet
    </a>

    <div class="botoes-topo">
        <a href="login.php" class="btn-entrar">
            ENTRAR
        </a>

        <a href="login.php" class="btn-criar">
            CADASTRA-SE
        </a>
    </div>
</header>

<section class="hero">
    <div class="container">

        <div>
            <div class="hero-tag">SISTEMA DE GERENCIAMENTO DE CHAMADOS</div>
            <h1>Seu chamado, em <span>boas mãos.</span></h1>
            <p>
                Com o ConectaNet, sua empresa ganha um sistema completo para
                registrar, acompanhar e resolver chamados de forma simples,
                rápida e organizada.
            </p>
            <a class="btn btn-azul" href="cadastro.php">
                Criar chamado <i class="fa-solid fa-arrow-right"></i>
            </a>
            <div class="hero-rodape">É rápido, fácil e seguro.</div>
        </div>

        <div class="mockup-wrap">

            <div class="balao b1">
                <i class="fa-solid fa-circle-check"></i>
                <div>
                    <strong>Chamado registrado!</strong>
                    Nossa equipe já está analisando sua solicitação.
                </div>
            </div>

            <div class="balao b2">
                <i class="fa-regular fa-clock"></i>
                <div>
                    <strong>Acompanhe em tempo real</strong>
                    Veja o status a qualquer momento.
                </div>
            </div>

            <div class="mockup">
                <div class="mockup-topo">
                    <i class="fa-solid fa-headset"></i> ConectaNet
                </div>
                <div class="mockup-corpo">
                    <h4>Meus chamados</h4>

                    <div class="chamado-linha">
                        <span>#5487 &nbsp;Manutenção de equipamento</span>
                        <span class="badge andamento">Em andamento</span>
                    </div>

                    <div class="chamado-linha">
                        <span>#5462 &nbsp;Análise do sistema</span>
                        <span class="badge resolvido">Resolvido</span>
                    </div>

                    <div class="chamado-linha">
                        <span>#5485 &nbsp;Suporte de software</span>
                        <span class="badge aberto">Aberto</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<section class="secao" id="contato">
    <div class="container">

        <div class="titulo-secao">
            <div class="eyebrow">CONTATO</div>
            <h2>Fale com a gente</h2>
        </div>

        <div class="contato-grid">

            <div>
                <div class="contato-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <strong>Endereço</strong>
                        <span>Av. Senador Salgado Filho, 3.100, Lj. 4 e 5<br>
                        Bairro Uberaba - Curitiba - PR</span>
                    </div>
                </div>

                <div class="contato-item">
                    <i class="fa-solid fa-phone"></i>
                    <div>
                        <strong>Telefone</strong>
                        <span>(00) 0000-0000</span>
                    </div>
                </div>

                <div class="contato-item">
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <strong>E-mail</strong>
                        <span>contato@conectanet.com.br</span>
                    </div>
                </div>

                <div class="contato-item">
                    <i class="fa-regular fa-clock"></i>
                    <div>
                        <strong>Horário de atendimento</strong>
                        <span>Segunda a sexta: 8h-12h, 14h-18h30<br>Sábados: 8h-12h</span>
                    </div>
                </div>
            </div>

            <form class="form-contato" method="POST" action="#">

                <label>Nome</label>
                <input type="text" name="nome" placeholder="Seu nome" required>

                <label>E-mail</label>
                <input type="email" name="email" placeholder="seuemail@exemplo.com" required>

                <label>Telefone</label>
                <input type="text" name="telefone" placeholder="(00) 00000-0000">

                <label>Assunto</label>
                <input type="text" name="assunto" placeholder="Sobre o que é o contato?">

                <label>Mensagem</label>
                <textarea name="mensagem" placeholder="Escreva sua mensagem" required></textarea>

                <button type="submit" class="btn btn-azul">Enviar</button>

            </form>

        </div>

    </div>
</section>

<section class="secao cinza">
    <div class="container">

        <div class="titulo-secao">
            <div class="eyebrow">QUEM CONFIA</div>
            <h2>O que nossos usuários dizem</h2>
            <p>Mais de 8.000 pessoas e empresas já utilizam o ConectaNet para gerenciar seus chamados.</p>
        </div>

        <div class="depoimentos-grid">

            <div class="depoimento">
                <i class="fa-solid fa-quote-left aspas"></i>
                <p>O ConectaNet facilitou muito o nosso dia a dia. Agora conseguimos acompanhar todos os chamados sem perder nada.</p>
                <div class="autor">
                    <div class="avatar">JS</div>
                    <div>
                        <strong>Juliana Santos</strong>
                        <span>RH - Empresa Alpha</span>
                    </div>
                </div>
            </div>

            <div class="depoimento">
                <i class="fa-solid fa-quote-left aspas"></i>
                <p>Sistema simples, intuitivo e muito eficiente. Recomendo para qualquer empresa que precise de organização.</p>
                <div class="autor">
                    <div class="avatar">CO</div>
                    <div>
                        <strong>Adriano Lucas</strong>
                        <span>TI - Empresa Beta</span>
                    </div>
                </div>
            </div>

            <div class="depoimento">
                <i class="fa-solid fa-quote-left aspas"></i>
                <p>O suporte é excelente e o tempo de resposta é muito rápido. Mudou a forma como lidamos com as solicitações.</p>
                <div class="autor">
                    <div class="avatar">FL</div>
                    <div>
                        <strong>Rafael Freitas</strong>
                        <span>Administrativo - Empresa Gamma</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<footer class="principal">
    <div class="container">

        <div class="rodape-grid">

            <div>
                <div class="marca">
                    <i class="fa-solid fa-headset"></i> ConectaNet
                </div>
                <p>Chamados mais simples, soluções mais rápidas.</p>
                <div class="redes">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>

            <div>
                <h4>Links</h4>
                <a href="home.php">Início</a>
                <a href="#contato">Sobre</a>
                <a href="#contato">Funcionalidades</a>
                <a href="#contato">Como funciona</a>
            </div>

            <div>
                <h4>Contato</h4>
                <span><i class="fa-solid fa-phone"></i> (00) 0000-0000</span><br>
                <span><i class="fa-solid fa-envelope"></i> contato@conectanet.com.br</span><br>
                <span><i class="fa-solid fa-location-dot"></i> Curitiba - PR</span>
            </div>

            <div>
                <h4>Siga-nos</h4>
                <a href="login.php">Entrar</a>
                <a href="cadastro.php">Criar conta</a>
            </div>

        </div>

        <div class="rodape-baixo">
            <span>&copy; 2026 ConectaNet. Todos os direitos reservados.</span>
            <span>Termos de Uso</span>
        </div>

    </div>
</footer>

</body>
</html>
