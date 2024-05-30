<?php 

require_once "conexao.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?);";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $hashed_password);

    if($stmt->execute()){
        header('Location: login.php?cadastro=sucesso');
    }else{
        echo "Erro: ". $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
