<!DOCTYPE html>
<html>

<head>
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

    <link rel="stylesheet" href="css/dashboard.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>
    <a href="home.php">Voltar para home</a>

    <!-- Divisão de Saldo -->
    <div id="saldoChart" style="width: 900px; height: 500px;"></div>
</body>

</html>