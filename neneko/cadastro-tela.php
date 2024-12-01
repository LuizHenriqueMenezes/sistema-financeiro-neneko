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
        <div class="title-login">
            <h1 class="title-card-login"><strong>ZenPay</strong></h1>
            <h1 class="subtitle-card-login">Cadastre-se e tenha <br>controle total do seu dinheiro</h1>
        </div>
        <form method="POST" action="cadastro.php" class="form login">
            <div class="input-container ic1">
                <label for="user-name" class="placeholder">Nome</label><br>
                <input id="user-name" class="input" type="text" name="nome" placeholder=" " />
            </div>

            <div class="input-container ic2">
                <label for="login-email" class="placeholder">E-mail</label><br>
                <input id="login-email" class="input" type="email" name="email" placeholder="" />
            </div>



            <div class="input-container ic3">
                <label for="login-password" class="placeholder">Senha</label><br>
                <input id="login-password" class="input" type="password" name="senha" placeholder=" " />
            </div>

            <div class="input-container ic4">
                <label for="confirm-login-password" class="placeholder">Confirmação de senha</label><br>
                <input id="confirm-login-password" class="input" type="password" name="confirmacaoSenha" placeholder=" " />
            </div>


            <button type="submit" class="submit">Cadastrar</button>

            <div class="link-container">
                <a href="login.php" class="create-account-link">Já tem conta ainda?Entre por aqui!</a>
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