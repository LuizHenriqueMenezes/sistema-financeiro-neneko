<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="img/gatinho.png" type="image/x-icon">
    <title>Login</title>
</head>

<body>
    <div class="barra">
        <h1>Neneko <img src="img/gatinho.png" alt="logo"></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </div>
    <div class="conteudo">
        <form method="post" action="logar.php">
            <div class="form login">
                <div class="title">Login</div>
                <div class="input-container ic1">
                    <input id="login-email" class="input" type="email" name="email" placeholder=" " />
                    <div class="cut"></div>
                    <label for="login-email" class="placeholder">E-mail</label>
                </div>
                <div class="input-container ic2">
                    <input id="login-password" class="input" type="password" name="senha" placeholder=" " />
                    <div class="cut cut-short"></div>
                    <label for="login-password" class="placeholder">Senha</label>
                </div>
                <button type="text" class="submit">Entrar</button>
        </form>
    </div>
    <div class="form cadastro">
        <form method="post" action="cadastro.php">
            <div class="title">Cadastro</div>
            <div class="input-container ic1">
                <input id="cadastro-email" class="input" type="email" name="email" placeholder=" " />
                <div class="cut"></div>
                <label for="cadastro-email" class="placeholder">E-mail</label>
            </div>
            <div class="input-container ic2">
                <input id="cadastro-nome" class="input" type="text" name="nome" placeholder=" " />
                <div class="cut"></div>
                <label for="cadastro-nome" class="placeholder">Nome</label>
            </div>
            <div class="input-container ic2">
                <input id="cadastro-password" class="input" type="password" name="senha" placeholder=" " />
                <div class="cut cut-short"></div>
                <label for="cadastro-password" class="placeholder">Senha</label>
            </div>
            <button type="submit" class="submit" id="btn-cadastrar">Cadastrar</button>
    </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica se o parâmetro 'cadastro' está presente na URL
            const urlParams = new URLSearchParams(window.location.search);
            const cadastroSuccess = urlParams.has('cadastro') && urlParams.get('cadastro') === 'sucesso';

            // Se 'cadastroSuccess' for verdadeiro, exibe o alerta
            if (cadastroSuccess) {
                alert("Cadastro realizado com sucesso!");
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica se o parâmetro 'erro' está presente na URL
            const urlParams = new URLSearchParams(window.location.search);
            const erro = urlParams.get('erro');

            // Se 'erro' for 'senha', exibe o alerta de senha incorreta
            if (erro === 'senha') {
                alert("Senha incorreta!");
            }
            // Se 'erro' for 'usuario', exibe o alerta de usuário não encontrado
            else if (erro === 'usuario') {
                alert("Usuário não encontrado!");
            }
        });
    </script>
    </div>



</body>

</html>