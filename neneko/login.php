<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/cadastro.css">
    <link rel="icon" href="img/gatinho.png" type="image/x-icon">
    <title>Login</title>
</head>

<body>
    <div class="barra">
        <img src="img/gatinho.png" alt="logo" class="img-barra">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </div>

    <div class="conteudo">
        <form method="post" action="logar.php" class="form login">
            <div class="title-login">
                <h1 class="title-card-login">ZenPay</h1>
                <h2 class="subtitle-card-login">Faça o login para ter acesso a sua conta!</h2>
            </div>

            <div class="input-container ic1">
                <label for="login-email" class="placeholder">E-mail</label>
                <input id="login-email" class="input" type="email" name="email" placeholder=" " />
                
            </div>
            <div class="input-container ic2">
            <label for="login-password" class="placeholder">Senha</label>      
            <input id="login-password" class="input" type="password" name="senha" placeholder=" " />
                
            </div>

            <!-- Botão de entrar -->
            <button type="submit" class="submit">Entrar</button>

            <!-- Link para criar uma nova conta -->
            <div class="link-container">
                <a href="cadastro-tela.php" class="create-account-link">Não tem conta ainda? Crie a sua agora!</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const erro = urlParams.get('erro');

            if (erro === 'senha') {
                alert("Senha incorreta!");
            } else if (erro === 'usuario') {
                alert("Usuário não encontrado!");
            }
        });
    </script>
</body>

</html>
