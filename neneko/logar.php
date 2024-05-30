<?php 
require_once "conexao.php";

session_start(); // Inicia a sessão (se ainda não estiver iniciada)

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar a autenticidade do usuário
    $sql = "SELECT * FROM usuarios WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if(password_verify($senha, $row['senha'])) {
            // Autenticação bem-sucedida, define a variável de sessão para identificar o usuário logado
            $_SESSION['usuario_id'] = $row['id_usuario']; // Use 'id_usuario' como o identificador do usuário

            // Redireciona para a página principal
            header('Location: home.php');
            exit;
        } else {
            // Senha incorreta
            header('Location: login.php?erro=senha');
            exit;
        }
    } else {
        // Usuário não encontrado
        header('Location: login.php?erro=usuario');
    }

    $stmt->close();
}

$conn->close();
?>
