<?php
require_once 'conexao.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valor = $_POST['valor'];
    $categoria = $_POST['categoria'];
    $conta_id = $_POST['conta_id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Insere a despesa na tabela despesas_usuario
    $sql = "INSERT INTO despesas_usuario (usuario_id, valor, conta_id, categoria, data_despesa) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idis", $usuario_id, $valor, $conta_id, $categoria);

    if ($stmt->execute()) {
        // Atualiza o saldo da conta bancária
        $sql_update = "UPDATE contas_bancarias SET saldo = saldo - ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("di", $valor, $conta_id);
        if ($stmt_update->execute()) {
            header('Location: home.php?despesa=sucesso');
        } else {
            echo "Erro ao atualizar saldo: " . $stmt_update->error;
        }
        $stmt_update->close();
    } else {
        echo "Erro ao inserir despesa: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
