<?php
require_once "conexao.php";

// Verifica se o usuário está logado

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// Obtém o ID do usuário logado
$id_usuario = $_SESSION['usuario_id'];

// Verifica se o formulário de meta foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meta_gastos = $_POST['meta_gastos'];
    $meta_poupanca = $_POST['meta_poupanca'];

    // Verifica se o usuário existe na tabela despesas_usuario antes de inserir ou atualizar na tabela metas
    $verifica_usuario = "SELECT usuario_id FROM despesas_usuario WHERE usuario_id = ?";
    $stmt_verifica = $conn->prepare($verifica_usuario);
    $stmt_verifica->bind_param("i", $id_usuario);
    $stmt_verifica->execute();
    $result_verifica = $stmt_verifica->get_result();

    if ($result_verifica->num_rows > 0) {
        // Prepara e executa a consulta SQL para salvar as metas
        $sql_meta = "INSERT INTO metas (usuario_id, meta_gastos, meta_poupanca) 
                     VALUES (?, ?, ?)
                     ON DUPLICATE KEY UPDATE meta_gastos=?, meta_poupanca=?";
        $stmt = $conn->prepare($sql_meta);
        $stmt->bind_param("idddd", $id_usuario, $meta_gastos, $meta_poupanca, $meta_gastos, $meta_poupanca);
        $stmt->execute();
        $stmt->close();

        header('Location: home.php?meta=salva');
        exit;
    } else {
        echo "Usuário não encontrado na tabela despesas_usuario.";
    }

    $stmt_verifica->close();
}

// Obtém a meta atual do usuário
$sql_obter_meta = "SELECT meta_gastos, meta_poupanca FROM metas WHERE usuario_id = ?";
$stmt = $conn->prepare($sql_obter_meta);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result_meta = $stmt->get_result();
$meta = $result_meta->fetch_assoc();
$meta_gastos = $meta['meta_gastos'] ?? 0;
$meta_poupanca = $meta['meta_poupanca'] ?? 0;
$stmt->close();

// Obtém o total de despesas do usuário
$sql_despesas = "SELECT SUM(valor) AS total_despesas FROM despesas_usuario WHERE usuario_id = ?";
$stmt = $conn->prepare($sql_despesas);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result_despesas = $stmt->get_result();
$total_despesas = $result_despesas->fetch_assoc()['total_despesas'] ?? 0;
$stmt->close();

// Calcula o saldo restante
$saldo_restante = $meta_gastos - $total_despesas;
$cor_alerta = 'green';
if ($saldo_restante < ($meta_gastos * 0.1)) {
    $cor_alerta = 'red';
} elseif ($saldo_restante < ($meta_gastos * 0.25)) {
    $cor_alerta = 'yellow';
}

// Calcula o saldo de poupança
$saldo_poupanca = $meta_poupanca - $total_despesas;
$cor_poupanca = 'green';
if ($saldo_poupanca < ($meta_poupanca * 0.1)) {
    $cor_poupanca = 'red';
} elseif ($saldo_poupanca < ($meta_poupanca * 0.25)) {
    $cor_poupanca = 'yellow';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta de Gastos e Poupança</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .content {
            padding: 20px;
        }
        .alerta {
            padding: 20px;
            margin: 20px 0;
            color: white;
        }
        .green {
            background-color: green;
        }
        .yellow {
            background-color: yellow;
            color: black;
        }
        .red {
            background-color: red;
        }
    </style>
</head>
<body>
<div class="content">
    <h1>Definir Metas</h1>
    <form method="post" action="meta.php">
        <label for="meta_gastos">Meta de Gastos Mensal:</label>
        <input type="number" id="meta_gastos" name="meta_gastos" value="<?php echo htmlspecialchars($meta_gastos); ?>" required><br>
        <label for="meta_poupanca">Meta de Poupança Mensal:</label>
        <input type="number" id="meta_poupanca" name="meta_poupanca" value="<?php echo htmlspecialchars($meta_poupanca); ?>" required><br>
        <input type="submit" value="Salvar Metas">
    </form>
    
    <div class="alerta <?php echo $cor_alerta; ?>">
        Saldo Restante para Meta de Gastos: R$ <?php echo number_format($saldo_restante, 2); ?>
    </div>
    <div class="alerta <?php echo $cor_poupanca; ?>">
        Saldo Restante para Meta de Poupança: R$ <?php echo number_format($saldo_poupanca, 2); ?>
    </div>
</div>
</body>
</html>
