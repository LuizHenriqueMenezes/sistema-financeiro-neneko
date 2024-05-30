<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'conexao.php';

    // Obter os dados do formulário
    $valor = $_POST['valor'];
    $categoria = $_POST['categoria'];
    $conta_destino_id = $_POST['conta_destino'];

    // Inserir a nova receita na tabela receitas_usuario
    $sql = "INSERT INTO receitas_usuario (usuario_id, valor, conta_destino_id, categoria, data_recebimento) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $_SESSION['usuario_id'], $valor, $conta_destino_id, $categoria);
    if ($stmt->execute()) {
        // Atualizar o saldo da conta bancária correspondente
        $sql_update = "UPDATE contas_bancarias SET saldo = saldo + ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("di", $valor, $conta_destino_id);
        if ($stmt_update->execute()) {
            header('Location: home.php?receita=sucesso');
            exit;
        } else {
            echo "Erro ao atualizar o saldo da conta bancária: " . $conn->error;
        }
        $stmt_update->close();
    } else {
        echo "Erro ao adicionar a receita: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>