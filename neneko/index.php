<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <title>Bem-vindo!!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="img/gatinho.png" type="image/x-icon">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="barra">
        <img class="logotipo" src="img/gatinho.png" alt="logo">

        <!-- Menu Central -->
        <nav class="menu-central">
            <ul>
                <li><a href="#section-home">Início</a></li>
                <li><a href="#section-about">Sobre</a></li>
            </ul>
        </nav>

        <!-- Menu Direito -->
        <nav class="menu-direita">
            <ul>
                <li><a href="cadastro-tela.php">Cadastrar</a></li>
                <li><a href="login.php">Entrar</a></li>
            </ul>
        </nav>
    </div>

    <!-- Página 1: Início -->
    <div id="section-home" class="section section--home">
        <div class="content-home">
            <h1 class="title-main">ZenPay</h1>
            <h2 class="subtitle-main">Gestão financeira sem complicação, seu dinheiro no controle com praticidade</h2>
           
            <div class="img-container">
                <h2 class="text-example"><strong>Praticidade</strong> com seu dinheiro,<br>
                    em qualquer <strong>lugar</strong> e a qualquer <strong>momento</strong></h2>
            </div>
        </div>
        
    </div>

    <!-- Página 2: Sobre -->
    <div id="section-about" class="section section--about">

        <div id="section-about-intro" class="section-about__intro">
            <h1 class="intro-title"><strong>Não sabe como gerenciar seu dinheiro?</strong></h1>
            <h2 class="intro-text">O <strong>ZenPay</strong> é o sistema perfeito para você que chega ao final <br> do mês sem saber onde foi parar o seu dinheiro.</h2>
              
            <div class="img-moeda"> <img src="img/porquinho.png" alt="moeda" class="moeda-intro">
            </div>
        </div>
        </div>
        <div id="section-about-cards" class="section-about__cards">
        <img src="img/card2.png" alt="exemplo" class="img-example-cards">
        <div class="text-container-cards">
        
            <h1 class="cards-title"><strong>Gerencie seus<br> cartões de forma simples</strong></h1>
            <h2 class="cards-text">Com o ZenPay, você tem<br> o controle total sobre seus <br> cartões em um só lugar.</h2>
        </div>
      
    </div>

        <div id="section-about-expenses" class="section-about__expenses">
        
        <div class="text-container-expenses">
        <h1 class="expenses-title"><strong>Simples, rápido e sem complicação</strong></h1>
        <h2 class="expenses-text">Acompanhe seus gastos, <br>organize suas faturas e evite <br>surpresas no final do mês.</h2>
          
    </div>
    <img src="img/din2.png" alt="exemplo" class="img-example-expenses">
        </div>

        <div id="section-about-start" class="section-about__start">
         <div class="text-container-start">
        <h1 class="start-title"><strong>Não perca tempo, comece já!</strong></h1>
        <div class="menu-iniciar">
            <ul>
                <li><a href="login.php">Entrar</a></li>
                <li><a href="cadastro-tela.php">Cadastrar</a></li>
            </ul>
            
        </div>

        
        <img src="img/din1.png" alt="exemplo" class="img-example-start">
    
</div>
<!--
<footer class="site-footer">
        <div class="footer-content">
            <p>&copy; 2024 ZenPay. Todos os direitos reservados.</p>
            <nav class="footer-nav">
                <ul>
                    <li><a href="#about">Sobre</a></li>
                    <li><a href="#contact">Contato</a></li>
                    <li><a href="#terms">Termos de Uso</a></li>
                </ul>
            </nav>
        </div>
    </footer>-->
    
</body>

</html>
