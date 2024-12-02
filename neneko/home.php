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

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Carregando os pacotes necessários do Google Charts
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawSaldoChart);

        function drawSaldoChart() {
            // Dados obtidos do backend PHP
            <?php
            require_once 'conexao.php';
            if (isset($_SESSION['usuario_id'])) {
                $usuario_id = $_SESSION['usuario_id'];

                $sql_saldo = "
                SELECT nome, SUM(saldo) AS total
                FROM contas_bancarias
                WHERE usuario_id = ? 
                GROUP BY nome
                ";
                $stmt_saldo = $conn->prepare($sql_saldo);
                $stmt_saldo->bind_param("i", $usuario_id);
                $stmt_saldo->execute();
                $result_saldo = $stmt_saldo->get_result();

                $dados = [];
                while ($row = $result_saldo->fetch_assoc()) {
                    $dados[] = [$row['nome'], (float) $row['total']];
                }
                $stmt_saldo->close();
            } else {
                $dados = [];
            }
            ?>

            // Construa os dados para o gráfico
            var data = google.visualization.arrayToDataTable([
                ['Conta', 'Saldo'],
                <?php
                foreach ($dados as $dado) {
                    echo "['" . $dado[0] . "', " . $dado[1] . "],";
                }
                ?>
            ]);

            // Opções do gráfico
            var options = {
                title: 'Divisão de Saldo',
                pieHole: 0.4, // Torna o gráfico um gráfico de rosca
                backgroundColor: '#f9f9f9',
                chartArea: {
                    width: '90%',
                    height: '80%'
                },
                legend: {
                    position: 'bottom'
                }
            };

            // Renderizando o gráfico
            var chart = new google.visualization.PieChart(document.getElementById('saldoChart'));
            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <div class="barra">
        <h2>Bem vindo(a),
            <strong>
            <?php
            require_once 'conexao.php';
            if (isset($_SESSION['usuario_id'])) {
                $usuario_id = $_SESSION['usuario_id'];

                // SQL para buscar o nome do usuário
                $sql = "SELECT nome FROM usuarios WHERE id_usuario = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $usuario_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8'); // Exibe o nome do usuário de forma segura
                } else {
                    echo "Usuário não encontrado"; // Caso o usuário não seja encontrado
                }
                $stmt->close();
            } else {
                echo "Visitante"; // Caso não haja um usuário logado
            }
            ?></strong>! </h2>

        <nav>
            <ul>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="total-saldo">
             Seu saldo total é de R$
            <?php
            require_once 'conexao.php';
            if (isset($_SESSION['usuario_id'])) {
                $usuario_id = $_SESSION['usuario_id'];

                // SQL para somar os saldos das contas bancárias
                $sql = "SELECT SUM(saldo) as total_saldo FROM contas_bancarias WHERE usuario_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $usuario_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    // Exibe o total do saldo, com formatação monetária
                    echo number_format($row['total_saldo'], 2, ',', '.');
                } else {
                    echo "0,00";  // Caso não haja saldo ou contas
                }
                $stmt->close();
            } else {
                echo "0,00";  // Caso não haja um usuário logado
            }
            ?>
            </div>

    <div class="btn-group">
        <button type="button" id="addContaBtn" class="btn btn-outline-primary">Adicionar Conta Bancária</button>
        <button type="button" id="addReceitaBtn" class="btn btn-outline-primary">Adicionar Nova Receita</button>
        <button type="button" id="addDespesaBtn" class="btn btn-outline-primary">Adicionar Nova Despesa</button>
    </div>

    <!-- Modal para adicionar conta bancária -->
    <div id="addContaModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" id="closeContaModal">&times;</span>
            <h2>Adicionar Nova Conta Bancária</h2>
            <form action="adicionar_conta.php" method="post">
                <label for="nome">Banco:</label>
                <select class="form-select" id="nome" name="nome" required>
                    <option value="" selected disabled>Selecione um banco</option>
                    <option value="Itaú">Itaú</option>
                    <option value="Bradesco">Bradesco</option>
                    <option value="Banco do Brasil">Banco do Brasil</option>
                    <option value="Santander">Santander</option>
                    <option value="BTG Pactual">BTG Pactual</option>
                    <option value="Nubank">Nubank</option>
                    <option value="Inter">Inter</option>
                    <option value="Banco PAN">Banco PAN</option>
                    <option value="Sofisa">Sofisa</option>
                    <option value="Banco de Brasília">Banco de Brasília</option>
                    <option value="Iti">Iti</option>
                    <option value="Picpay">Picpay</option>
                    <option value="BMG">BMG</option>
                    <option value="C6">C6</option>
                    <option value="Banrisul">Banrisul</option>
                </select><br><br>

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

    <div id="contasCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            require_once 'conexao.php';
            if (isset($_SESSION['usuario_id'])) {
                $usuario_id = $_SESSION['usuario_id'];
                $sql = "SELECT nome, saldo FROM contas_bancarias WHERE usuario_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $usuario_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Array associando os bancos às imagens correspondentes
                $bancosLogos = [
                    'ITAú' => 'logo-itau.png',
                    'BRADESCO' => 'logo-bradesco.png',
                    'BANCO DO BRASIL' => 'logo-bb.png',
                    'SANTANDER' => 'logo-santander.png',
                    'BTG PACTUAL' => 'logo-btg.png',
                    'NUBANK' => 'logo-nubank.png',
                    'INTER' => 'logo-inter.png',
                    'BANCO PAN' => 'logo-pan.png',
                    'SOFISA' => 'logo-sofisa.png',
                    'BANCO DE BRASíLIA' => 'logo-brasilia.png',
                    'ITI' => 'logo-iti.png',
                    'PICPAY' => 'logo-picpay.png',
                    'BMG' => 'logo-bmg.png',
                    'C6' => 'logo-c6.png',
                    'BANRISUL' => 'logo-banrisul.png'
                ];

                if ($result->num_rows > 0) {
                    $cardsPorSlide = 3; // Número de cards por slide
                    $cardCount = 0;
                    $isActive = true; // Define o primeiro slide como ativo

                    echo '<div class="carousel-item ' . ($isActive ? 'active' : '') . '"><div class="row">';
                    while ($row = $result->fetch_assoc()) {
                        // Converte o nome do banco para maiúsculas
                        $bancoNome = strtoupper(trim($row['nome'])); // Converte para uppercase e remove espaços extras

                        // Verifica se a chave existe no array de logos e pega a logo correspondente
                        $logo = isset($bancosLogos[$bancoNome]) ? $bancosLogos[$bancoNome] : 'iconExemplo.png'; // Usa logo padrão se não encontrar

                        // Formatação do saldo
                        $saldo = number_format($row['saldo'], 2, ',', '.');

                        // Renderiza o card
                        echo "<div class='col-md-4'>";
                        echo "<div class='card'>";
                        echo "<img src='img/$logo' class='card-img-top' alt='$bancoNome' style='height: 150px; object-fit: cover;'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>{$row['nome']}</h5>";
                        echo "<p class='card-text'>Saldo: R$ $saldo</p>";
                        echo "</div>"; // Fecha card-body
                        echo "</div>"; // Fecha card
                        echo "</div>"; // Fecha col-md-4

                        $cardCount++;

                        // Fecha o slide e inicia um novo quando o limite é atingido
                        if ($cardCount % $cardsPorSlide == 0) {
                            echo '</div></div>'; // Fecha a linha e o item do carrossel
                            if ($result->num_rows > $cardCount) {
                                echo '<div class="carousel-item"><div class="row">';
                            }
                        }
                    }

                    // Fecha a estrutura aberta caso o número de cards não seja múltiplo de 3
                    if ($cardCount % $cardsPorSlide != 0) {
                        echo '</div></div>';
                    }

                    $isActive = false; // Apenas o primeiro slide é ativo
                } else {
                    echo "<p class='text-center'>Nenhuma conta bancária encontrada.</p>";
                }
                $stmt->close();
            } else {
                echo "<p class='text-center'>Nenhum usuário logado.</p>";
            }
            ?>
        </div>

        <!-- Controles do carrossel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#contasCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#contasCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
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

    <!-- Gráfico de Economias -->
    <div class="grafico">
        <h3 class="titulo_centroM">Economias Acumuladas</h3>
        <canvas id="economiaChart"></canvas>
    </div>

    <script>
        // Gráfico de Economias Acumuladas
        var ctxEconomia = document.getElementById('economiaChart').getContext('2d');
        var economiaChart = new Chart(ctxEconomia, {
            type: 'bar',
            data: {
                labels: ['Últimos 7 Dias'],
                datasets: [{
                    label: 'Economias',
                    data: [
                        <?php
                        $sql_economia = "
                        SELECT (
                            (SELECT SUM(valor) FROM receitas_usuario WHERE usuario_id = ? AND data_recebimento >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) -
                            (SELECT SUM(valor) FROM despesas_usuario WHERE usuario_id = ? AND data_despesa >= DATE_SUB(CURDATE(), INTERVAL 7 DAY))
                        ) AS economia";
                        $stmt = $conn->prepare($sql_economia);
                        $stmt->bind_param("ii", $usuario_id, $usuario_id);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        echo $result['economia'] ?? 0;
                        ?>
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL'
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Gráfico de Comparação Mensal -->
    <div class="grafico">
        <h3 class="titulo_centroM">Comparação Mensal</h3>
        <canvas id="comparacaoMensalChart"></canvas>
    </div>

    <script>
        // Gráfico de Comparação Mensal
        var ctxComparacao = document.getElementById('comparacaoMensalChart').getContext('2d');
        var comparacaoMensalChart = new Chart(ctxComparacao, {
            type: 'bar',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                datasets: [{
                        label: 'Receitas',
                        data: [
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                $sql = "
                                SELECT SUM(valor) AS total FROM receitas_usuario 
                                WHERE usuario_id = ? AND MONTH(data_recebimento) = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ii", $usuario_id, $i);
                                $stmt->execute();
                                $result = $stmt->get_result()->fetch_assoc();
                                echo ($result['total'] ?? 0) . ",";
                                $stmt->close();
                            }
                            ?>
                        ],
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Despesas',
                        data: [
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                $sql = "
                                SELECT SUM(valor) AS total FROM despesas_usuario 
                                WHERE usuario_id = ? AND MONTH(data_despesa) = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ii", $usuario_id, $i);
                                $stmt->execute();
                                $result = $stmt->get_result()->fetch_assoc();
                                echo ($result['total'] ?? 0) . ",";
                                $stmt->close();
                            }
                            ?>
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL'
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Divisão de Receitas nos Últimos 7 Dias -->
    <div class="grafico">
        <h3 class="titulo_centroM">Divisão de Receitas (Últimos 7 Dias)</h3>
        <canvas id="receitasPieChart"></canvas>
    </div>

    <script>
        // Gráfico de Divisão de Receitas nos Últimos 7 Dias
        var ctxReceitasPie = document.getElementById('receitasPieChart').getContext('2d');
        var receitasPieChart = new Chart(ctxReceitasPie, {
            type: 'pie',
            data: {
                labels: [
                    <?php
                    // Obter categorias de receita
                    $sql = "SELECT DISTINCT categoria FROM receitas_usuario WHERE usuario_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $usuario_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $categorias = [];
                    while ($row = $result->fetch_assoc()) {
                        $categorias[] = $row['categoria'];
                    }
                    echo "'" . implode("','", $categorias) . "'";
                    $stmt->close();
                    ?>
                ],
                datasets: [{
                    data: [
                        <?php
                        // Somar valores por categoria
                        foreach ($categorias as $categoria) {
                            $sql = "
                            SELECT SUM(valor) AS total FROM receitas_usuario
                            WHERE usuario_id = ? AND categoria = ? AND data_recebimento >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("is", $usuario_id, $categoria);
                            $stmt->execute();
                            $result = $stmt->get_result()->fetch_assoc();
                            echo ($result['total'] ?? 0) . ",";
                            $stmt->close();
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ]
                }]
            }
        });
    </script>

    <!-- Fluxo de Caixa Semanal -->
    <div class="grafico">
        <h3 class="titulo_centroM">Fluxo de Caixa (Últimos 7 Dias)</h3>
        <canvas id="fluxoCaixaChart"></canvas>
    </div>

    <script>
        // Gráfico de Fluxo de Caixa Semanal
        var ctxFluxoCaixa = document.getElementById('fluxoCaixaChart').getContext('2d');
        var fluxoCaixaChart = new Chart(ctxFluxoCaixa, {
            type: 'line',
            data: {
                labels: [<?php
                            $labels = [];
                            for ($i = 6; $i >= 0; $i--) {
                                $labels[] = date('d/m', strtotime("-$i days"));
                            }
                            echo "'" . implode("','", $labels) . "'";
                            ?>],
                datasets: [{
                    label: 'Saldo Diário',
                    data: [
                        <?php
                        for ($i = 6; $i >= 0; $i--) {
                            $data = date('Y-m-d', strtotime("-$i days"));
                            $sql_receitas = "
                            SELECT SUM(valor) AS total FROM receitas_usuario 
                            WHERE usuario_id = ? AND data_recebimento = ?";
                            $stmt = $conn->prepare($sql_receitas);
                            $stmt->bind_param("is", $usuario_id, $data);
                            $stmt->execute();
                            $result_receitas = $stmt->get_result()->fetch_assoc();
                            $stmt->close();

                            $sql_despesas = "
                            SELECT SUM(valor) AS total FROM despesas_usuario 
                            WHERE usuario_id = ? AND data_despesa = ?";
                            $stmt = $conn->prepare($sql_despesas);
                            $stmt->bind_param("is", $usuario_id, $data);
                            $stmt->execute();
                            $result_despesas = $stmt->get_result()->fetch_assoc();
                            $stmt->close();

                            $saldo = ($result_receitas['total'] ?? 0) - ($result_despesas['total'] ?? 0);
                            echo $saldo . ",";
                        }
                        ?>
                    ],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL'
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Comparativo de Categorias de Receita -->
    <div class="grafico">
        <h3 class="titulo_centroM">Comparativo de Categorias de Receita</h3>
        <canvas id="categoriasReceitaChart"></canvas>
    </div>

    <script>
        // Gráfico de Comparativo de Categorias de Receita
        var ctxCategoriasReceita = document.getElementById('categoriasReceitaChart').getContext('2d');
        var categoriasReceitaChart = new Chart(ctxCategoriasReceita, {
            type: 'bar',
            data: {
                labels: [
                    <?php echo "'" . implode("','", $categorias) . "'"; ?>
                ],
                datasets: [{
                    label: 'Receitas por Categoria',
                    data: [
                        <?php
                        foreach ($categorias as $categoria) {
                            $sql = "
                            SELECT SUM(valor) AS total FROM receitas_usuario
                            WHERE usuario_id = ? AND categoria = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("is", $usuario_id, $categoria);
                            $stmt->execute();
                            $result = $stmt->get_result()->fetch_assoc();
                            echo ($result['total'] ?? 0) . ",";
                            $stmt->close();
                        }
                        ?>
                    ],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL'
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>