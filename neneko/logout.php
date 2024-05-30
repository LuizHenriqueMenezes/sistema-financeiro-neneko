<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['id_usuario'])) {
    // Destroi a sessão
    session_destroy();
}

// Redireciona para a página de login
header("Location: login.php");
exit;
?>
