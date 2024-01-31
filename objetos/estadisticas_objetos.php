<?php
// Incluye tu archivo de conexión a la base de datos
include 'db_connect.php';

// Consulta SQL para obtener la cantidad de objetos creados por fecha
$stmt = $conn->query("SELECT DATE(FECHA_CREACION) AS fecha, COUNT(*) AS cantidad FROM tbl_objetos GROUP BY DATE(FECHA_CREACION)");
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepara los datos para el gráfico
$fechas = [];
$cantidades = [];

foreach ($datos as $dato) {
    $fechas[] = $dato['fecha'];
    $cantidades[] = $dato['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Objetos por Fecha</title>
    <!-- Agrega las bibliotecas de Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Estilo para centrar el contenido en la pantalla */
        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        /* Estilo para los contenedores de gráficos */
        .chart-container {
            width: 50%; /* Ancho de los gráficos */
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>

    <script type="text/javascript">
        // Carga la biblioteca de visualización y configura el gráfico
        google.charts.load('current', { 'packages': ['bar'] });
        google.charts.setOnLoadCallback(dibujarGrafico);

        function dibujarGrafico() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Fecha');
            data.addColumn('number', 'Cantidad');
            
            // Inserta los datos desde PHP
            data.addRows([
                <?php
                // Genera los datos del gráfico a partir de las variables PHP
                $numDatos = count($fechas);
                for ($i = 0; $i < $numDatos; $i++) {
                    echo "['" . $fechas[$i] . "', " . $cantidades[$i] . "]";
                    if ($i < $numDatos - 1) {
                        echo ", "; // Agrega una coma si no es el último dato
                    }
                }
                ?>
            ]);

            // Configura las opciones del gráfico
            var options = {
                chart: {
                    title: 'Estadísticas de Objetos por Fecha',
                    subtitle: 'Cantidad de objetos creados por fecha',
                },
                bars: 'vertical',
                vAxis: { title: 'Cantidad de Objetos' },
                hAxis: { title: 'Fecha' },
            };

            // Dibuja el gráfico en el elemento con el ID 'chart_div'
            var chart = new google.charts.Bar(document.getElementById('chart_div'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
</head>
<body>
    <div class="center-content">
        <div class="chart-container">
            <h1>Estadísticas de Objetos por Fecha</h1>
            <div id="chart_div" style="width: 100%; height: 400px;"></div>

    
            <a href="listar_objetos.php" class="return-button">Regresar a la lista de objetos</a>
        </div>
    </div>
</body>
</html>
