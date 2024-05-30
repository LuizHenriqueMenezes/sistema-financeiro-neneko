<?php
// Iniciar a sessão no início do arquivo
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Bem-vindo!</title>
    <link rel="icon" href="img/gatinho.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="barra">
        <h1>Neneko <img src="img/gatinho.png" alt="logo"></h1>
        <nav>
            <ul>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="btn-group">
        <button type="button" id="addContaBtn" class="btn btn-outline-primary">Adicionar Conta Bancária</button>
        <button type="button" id="addReceitaBtn" class="btn btn-outline-primary">Adicionar Nova Receita</button>
        <button type="button" id="addDespesaBtn" class="btn btn-outline-primary">Adicionar Nova Despesa</button>
    </div>


    <!-- Modal para adicionar conta bancária -->
    <div id="addContaModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeContaModal">&times;</span>
            <h2>Adicionar Nova Conta Bancária</h2>
            <form action="adicionar_conta.php" method="post">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required><br><br>

                <label for="tipo_conta">Tipo de Conta:</label>
                <input type="text" id="tipo_conta" name="tipo_conta" required><br><br>

                <label for="saldo">Saldo:</label>
                <input type="text" id="saldo" name="saldo" required><br><br>

                <input type="submit" class="btn btn-outline-primary" value="Adicionar Conta">
            </form>
        </div>
    </div>

    <!-- Modal para adicionar receita -->
    <div id="addReceitaModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeReceitaModal">&times;</span>
            <h2>Adicionar Nova Receita</h2>
            <form action="processar_receita.php" method="post">
                <label for="valor">Valor:</label>
                <input type="text" id="valor" name="valor" required><br><br>

                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria" class="estilo-select">
                    <option value="Salário">Salário</option>
                    <option value="Outra fonte de renda">Outra fonte de renda</option>
                </select><br><br>

                <label for="conta_destino">Conta de Destino:</label>
                <select id="conta_destino" class="estilo-select" name="conta_destino" required>
                    <?php
                    require_once 'conexao.php';
                    if (isset($_SESSION['usuario_id'])) {
                        $usuario_id = $_SESSION['usuario_id'];
                        $sql = "SELECT id, nome FROM contas_bancarias WHERE usuario_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $usuario_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                        }
                        $stmt->close();
                    } else {
                        echo "<option value=''>Nenhuma conta encontrada</option>";
                    }
                    ?>
                </select><br><br>

                <input type="submit" class="btn btn-outline-primary" value="Adicionar Receita">
            </form>
        </div>
    </div>

    <!-- Modal para adicionar despesa -->
    <div id="addDespesaModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeDespesaModal">&times;</span>
            <h2>Adicionar Nova Despesa</h2>
            <form action="processar_despesa.php" method="post">
                <label for="valor">Valor:</label>
                <input type="text" id="valor" name="valor" required><br><br>

                <label for="categoria">Categoria:</label>
                <input type="text" id="categoria" name="categoria" required><br><br>

                <label for="conta_id">Conta de Origem:</label>
                <select id="conta_id" class="estilo-select" name="conta_id" required>
                    <?php
                    require_once 'conexao.php';
                    if (isset($_SESSION['usuario_id'])) {
                        $usuario_id = $_SESSION['usuario_id'];
                        $sql = "SELECT id, nome FROM contas_bancarias WHERE usuario_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $usuario_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                        }
                        $stmt->close();
                    } else {
                        echo "<option value=''>Nenhuma conta encontrada</option>";
                    }
                    ?>
                </select><br><br>

                <input type="submit" class="btn btn-outline-primary" value="Adicionar Despesa">
            </form>
        </div>
    </div>

    <div class="list-group">
        <?php
        require_once 'conexao.php';
        //echo "<li class='list-group-item active' aria-current='true'>Contas Bancárias</li>";
        if (isset($_SESSION['usuario_id'])) {
            $usuario_id = $_SESSION['usuario_id'];
            $sql = "SELECT nome, saldo FROM contas_bancarias WHERE usuario_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<a class='list-group-item list-group-item-action'>";
                    echo "<h5 class='mb-1'>{$row['nome']}</h5>";
                    echo "<p class='mb-1'>Saldo: R$ {$row['saldo']}</p>";
                    echo "</a>";
                }
            } else {
                echo "<p class='list-group-item'>Nenhuma conta bancária encontrada.</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='list-group-item'>Nenhum usuário logado.</p>";
        }
        ?>
    </div>

    <!-- Extrato -->
    <div class="extrato">
        <div class="fundo-extrato">
            <h2 class="bv-home">Extrato</h2>
        </div>
        <br>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="filtro">Mostrar transações dos últimos:</label>
            <select name="filtro" id="filtro">
                <option value="5">5 dias</option>
                <option value="10">10 dias</option>
                <option value="30">30 dias</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <hr>
        <?php
        require_once 'conexao.php';
        if (isset($_SESSION['usuario_id'])) {
            $usuario_id = $_SESSION['usuario_id'];

            // Verificar se o filtro foi enviado
            $filtro_dias = isset($_POST['filtro']) ? $_POST['filtro'] : 5;

            $sql = "
            SELECT 'despesa' AS tipo, valor, categoria, data_despesa AS data, cb.nome AS conta
            FROM despesas_usuario du
            JOIN contas_bancarias cb ON du.conta_id = cb.id
            WHERE du.usuario_id = ? AND data_despesa >= DATE_SUB(CURDATE(), INTERVAL $filtro_dias DAY)
            UNION
            SELECT 'receita' AS tipo, valor, categoria, data_recebimento AS data, cb.nome AS conta
            FROM receitas_usuario ru
            JOIN contas_bancarias cb ON ru.conta_destino_id = cb.id
            WHERE ru.usuario_id = ? AND data_recebimento >= DATE_SUB(CURDATE(), INTERVAL $filtro_dias DAY)
            ORDER BY data DESC
        ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $usuario_id, $usuario_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['tipo'] == 'despesa') {
                        echo "<p class='despesa'>Despesa: - R$ {$row['valor']} ({$row['categoria']})</p>";
                    } elseif ($row['tipo'] == 'receita') {
                        echo "<p class='receita'>Receita: + R$ {$row['valor']} ({$row['categoria']})</p>";
                    }
                    echo "<p class='conta'>Conta: {$row['conta']}</p>";
                    echo "<p class='data'>Data: {$row['data']}</p>";
                    echo "<hr>";
                }
            } else {
                echo "<p class='bv-home'>Nenhum registro de transação nos últimos $filtro_dias dias.</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='bv-home'>Nenhum usuário logado.</p>";
        }
        ?>
    </div>



    <!-- Gráfico -->
    <div class="grafico">
        <h3 class="titulo_centroM">Receitas x Despesas</h3>
        <canvas id="myChart"></canvas>
    </div>

    <script>
        // Gráfico de Receitas e Despesas
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Despesas', 'Receitas'],
                datasets: [{
                    label: 'Última semana',
                    data: [
                        <?php
                        require_once 'conexao.php';
                        if (isset($_SESSION['usuario_id'])) {
                            $usuario_id = $_SESSION['usuario_id'];
                            // Query para obter despesas nos últimos 7 dias
                            $sql_despesas = "
                        SELECT SUM(valor) AS total_despesas
                        FROM despesas_usuario
                        WHERE usuario_id = ? AND data_despesa >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    ";
                            $stmt_despesas = $conn->prepare($sql_despesas);
                            $stmt_despesas->bind_param("i", $usuario_id);
                            $stmt_despesas->execute();
                            $result_despesas = $stmt_despesas->get_result();
                            $total_despesas = $result_despesas->fetch_assoc()['total_despesas'] ?? 0;
                            $stmt_despesas->close();

                            // Query para obter receitas nos últimos 7 dias
                            $sql_receitas = "
                        SELECT SUM(valor) AS total_receitas
                        FROM receitas_usuario
                        WHERE usuario_id = ? AND data_recebimento >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    ";
                            $stmt_receitas = $conn->prepare($sql_receitas);
                            $stmt_receitas->bind_param("i", $usuario_id);
                            $stmt_receitas->execute();
                            $result_receitas = $stmt_receitas->get_result();
                            $total_receitas = $result_receitas->fetch_assoc()['total_receitas'] ?? 0;
                            $stmt_receitas->close();

                            echo "$total_despesas, $total_receitas";
                        } else {
                            echo "0, 0";
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)', // Vermelho para despesas
                        'rgba(75, 192, 192, 0.2)', // Verde para receitas
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 600, // Define o incremento dos ticks
                            callback: function(value, index, values) {
                                return value.toLocaleString('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL'
                                }); // Formatação para reais
                            }
                        }
                    }
                }
            }
        });
    </script>

    <div class="grafico-pizza-container">
        <div class="grafico-pizza">
            <h3 class="titulo_centro ">Divisão de Despesas (7 dias)</h3>
            <canvas id="pieChart" width="200" height="200"></canvas>
        </div>
        <div class="grafico-pizza">
            <h3 class="titulo_centro ">Divisão de Saldo</h3>
            <canvas id="pizzaChart" width="200" height="200"></canvas>
        </div>
    </div>

    <script>
        // Dados para o gráfico de pizza
        <?php
        // SQL para obter os totais de despesas por categoria
        $sql_despesas = "
        SELECT categoria, SUM(valor) AS total
        FROM despesas_usuario
        WHERE usuario_id = ? AND data_despesa >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY categoria
    ";
        $stmt_despesas = $conn->prepare($sql_despesas);
        $stmt_despesas->bind_param("i", $usuario_id);
        $stmt_despesas->execute();
        $result_despesas = $stmt_despesas->get_result();

        // Preparar os dados para o gráfico de pizza
        $categorias = [];
        $totais = [];
        while ($row = $result_despesas->fetch_assoc()) {
            $categorias[] = $row['categoria'];
            $totais[] = (float) $row['total']; // Manter positivo para despesas
        }

        $stmt_despesas->close();
        ?>

        var ctxPie = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($categorias); ?>,
                datasets: [{
                    data: <?php echo json_encode($totais); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 300, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(74, 99, 132, 0.9)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 200, 64, 0.7)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 300, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(74, 99, 132, 0.9)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 200, 64, 0.7)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': R$ ' + tooltipItem.raw.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        <?php
        $sql_despesas = "
        SELECT nome, SUM(saldo) AS total
        FROM contas_bancarias
        WHERE usuario_id = ? 
        GROUP BY nome
    ";
        $stmt_despesas = $conn->prepare($sql_despesas);
        $stmt_despesas->bind_param("i", $usuario_id);
        $stmt_despesas->execute();
        $result_despesas = $stmt_despesas->get_result();

        // Preparar os dados para o gráfico de pizza
        $categorias = [];
        $totais = [];
        while ($row = $result_despesas->fetch_assoc()) {
            $categorias[] = $row['nome'];
            $totais[] = (float) $row['total']; // Manter positivo para despesas
        }

        $stmt_despesas->close();
        ?>

        var ctxPie = document.getElementById('pizzaChart').getContext('2d');
        var pizzaChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($categorias); ?>,
                datasets: [{
                    data: <?php echo json_encode($totais); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 300, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(74, 99, 132, 0.9)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 200, 64, 0.7)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 300, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(74, 99, 132, 0.9)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 200, 64, 0.7)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': R$ ' + tooltipItem.raw.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script src="js/home.js"></script>
    <script>
        // Funções para abrir e fechar os modais
        var addContaModal = document.getElementById('addContaModal');
        var addReceitaModal = document.getElementById('addReceitaModal');
        var addDespesaModal = document.getElementById('addDespesaModal');

        var addContaBtn = document.getElementById('addContaBtn');
        var addReceitaBtn = document.getElementById('addReceitaBtn');
        var addDespesaBtn = document.getElementById('addDespesaBtn');

        var closeContaModal = document.getElementById('closeContaModal');
        var closeReceitaModal = document.getElementById('closeReceitaModal');
        var closeDespesaModal = document.getElementById('closeDespesaModal');

        addContaBtn.onclick = function() {
            addContaModal.style.display = 'block';
        }

        addReceitaBtn.onclick = function() {
            addReceitaModal.style.display = 'block';
        }

        addDespesaBtn.onclick = function() {
            addDespesaModal.style.display = 'block';
        }

        closeContaModal.onclick = function() {
            addContaModal.style.display = 'none';
        }

        closeReceitaModal.onclick = function() {
            addReceitaModal.style.display = 'none';
        }

        closeDespesaModal.onclick = function() {
            addDespesaModal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == addContaModal) {
                addContaModal.style.display = 'none';
            }
            if (event.target == addReceitaModal) {
                addReceitaModal.style.display = 'none';
            }
            if (event.target == addDespesaModal) {
                addDespesaModal.style.display = 'none';
            }
        }
    </script>

</body>

</html>