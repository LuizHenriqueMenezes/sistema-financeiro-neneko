<?php
session_start(); // Inicia a sessão para acessar os dados do usuário logado

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Obtém os dados do formulário
$nome = $_POST['nome'];
$tipo_conta = $_POST['tipo_conta'];
$saldo = $_POST['saldo'];
$usuario_id = $_SESSION['usuario_id']; // Obtém o ID do usuário logado

// Prepara a instrução SQL para inserção
$sql = "INSERT INTO contas_bancarias (usuario_id, nome, tipo_conta, saldo) VALUES (?, ?, ?, ?)";

// Prepara a declaração
$stmt = $conn->prepare($sql);

// Associa os parâmetros da declaração
$stmt->bind_param("isss", $usuario_id, $nome, $tipo_conta, $saldo);

// Executa a declaração
if ($stmt->execute()) {
    // Redireciona de volta para a página home após a inserção
    header("Location: home.php");
    exit();
} else {
    // Se houver algum erro, exibe uma mensagem de erro
    echo "Erro ao adicionar conta: " . $stmt->error;
}

// Fecha a declaração e a conexão
$stmt->close();
$conn->close();
?>
