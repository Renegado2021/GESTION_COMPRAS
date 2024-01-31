
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Compras</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px; /* Ajusta el margen superior según sea necesario */
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            text-align: center;
            margin-bottom: 20px; /* Agregado un margen inferior al formulario */
        }

        canvas {
            margin: auto; /* Centra el gráfico horizontalmente */
            display: block;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 80%;
            margin: 20px auto; /* Centra la tabla horizontalmente */
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php

$mesSeleccionado = isset($_POST['mes']) ? $_POST['mes'] : date('n'); // Predeterminar al mes actual
$anioSeleccionado = isset($_POST['anio']) ? $_POST['anio'] : date('Y'); // Predeterminar al año actual



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];

    $mesTexto = date('F', mktime(0, 0, 0, $mes, 1));
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT p.NOMBRE as nombre_proveedor, COUNT(ocp.ID) as total_compras, SUM(ocp.MONTO) as monto_total
            FROM tbl_orden_compra_productos ocp
            LEFT JOIN tbl_orden_compra oc ON ocp.ID_ORDEN = oc.ID_ORDEN_COMPRA
            LEFT JOIN tbl_proveedores p ON oc.ID_PROVEEDOR = p.ID_PROVEEDOR
            WHERE MONTH(oc.FECHA_ORDEN) = $mes AND YEAR(oc.FECHA_ORDEN) = $anio
            GROUP BY p.ID_PROVEEDOR";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    $labels = [];
    $data = [];
    $comprasPorProveedor = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['nombre_proveedor'];
        $data[] = $row['monto_total'];
        $comprasPorProveedor[$row['nombre_proveedor']] = $row['total_compras'];
    }

    $conn->close();
} else {
    header('Location: formulario_estadisticas.php');
    exit();
}
?>

<h2>Estadísticas para <?php echo $mesTexto; ?>/<?php echo $anio; ?></h2>

<form method="POST" action="">
    <label for="mes">Mes:</label>
    <select name="mes" id="mes">
        <?php
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        foreach ($meses as $numeroMes => $nombreMes) {
            $selected = ($numeroMes == $mesSeleccionado) ? 'selected' : '';
            echo "<option value='$numeroMes' $selected>$nombreMes</option>";
        }
        ?>
    </select>

    <label for="anio">Año:</label>
    <select name="anio" id="anio">
        <?php
        $currentYear = date("Y");
        for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
            $selected = ($i == $anioSeleccionado) ? 'selected' : '';
            echo "<option value='$i' $selected>$i</option>";
        }
        ?>
    </select>

    <button type="submit">Aceptar</button>
</form>
<canvas id="myChart" width="800" height="400"></canvas>

<table border='1'>
    <tr>
        <th>Proveedor</th>
        <th>N° de Órdenes</th>
       
        <th>Monto Total</th>
    </tr>
    <?php
    $totalCompras = 0;
    $totalMonto = 0;

    for ($i = 0; $i < count($labels); $i++) {
        $totalCompras += $comprasPorProveedor[$labels[$i]];
        $totalMonto += $data[$i];
        
        echo "<tr>
                <td>" . $labels[$i] . "</td>
                <td>" . $comprasPorProveedor[$labels[$i]] . "</td>
                
                <td>L" . $data[$i] . "</td>
            </tr>";
    }
    ?>
    <tr class="total-row">
        <td>Total General</td>
        
        <td><?php echo count($labels); ?></td>
        <td>L.<?php echo $totalMonto; ?></td>
    </tr>
</table>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Monto Total',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
