<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Órdenes de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            transition: margin-left 0.3s;
        }

        .container {
            margin-left: 40px;
            padding: 40px;
            overflow-x: auto;
        }

        #toggle-menu-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1100;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: center;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        .pdf-link {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h1>Generar órdenes de pago</h1>

<div class="container">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener datos de todas las órdenes de compra y detalles del proveedor, cuentas y cuenta proveedor asociadas
    $sql = "SELECT ocp.ID, ocp.ID_ORDEN, ocp.CANTIDAD, ocp.DESCRIPCION, ocp.PRECIO, ocp.TOTAL, ocp.SUBTOTAL, ocp.ISV, ocp.MONTO,
               oc.ID_ORDEN_COMPRA, p.NOMBRE, cp.NUMERO_CUENTA, cp.BANCO, cp.DESCRIPCION_CUENTA
        FROM tbl_orden_compra_productos ocp
        LEFT JOIN tbl_orden_compra oc ON ocp.ID_ORDEN = oc.ID_ORDEN_COMPRA
        LEFT JOIN tbl_proveedores p ON oc.ID_PROVEEDOR = p.ID_PROVEEDOR
        LEFT JOIN tbl_cuenta_proveedor cp ON p.ID_PROVEEDOR = cp.ID_PROVEEDOR";


    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>
                <th>ID</th>
                <th>ID Orden</th>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Subtotal</th>
                <th>ISV</th>
                <th>Monto</th>
                <th>Proveedor</th>
                <th>N° Cuenta</th>
                <th>Banco</th>
                <th>Tipo de Cuenta</th>
                <th>Acciones</th>
              </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["ID"] . '</td>';
            echo '<td>' . $row["ID_ORDEN_COMPRA"] . '</td>';
            echo '<td>' . $row["CANTIDAD"] . '</td>';
            echo '<td>' . $row["DESCRIPCION"] . '</td>';
            echo '<td>' . $row["PRECIO"] . '</td>';
            echo '<td>' . $row["TOTAL"] . '</td>';
            echo '<td>' . $row["SUBTOTAL"] . '</td>';
            echo '<td>' . $row["ISV"] . '</td>';
            echo '<td>' . $row["MONTO"] . '</td>';
            echo '<td>' . $row["NOMBRE"] . '</td>';
            echo '<td>' . $row["NUMERO_CUENTA"] . '</td>';
            echo '<td>' . $row["BANCO"] . '</td>';
            echo '<td>' . $row["DESCRIPCION_CUENTA"] . '</td>';

            // Agregar columna de acciones para generar PDF
            echo '<td><a class="pdf-link" href="generar_pdf.php?id=' . $row["ID"] . '">PDF</a></td>';

            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>No se encontraron datos de órdenes de compra.</p>';
    }

    $conn->close();
    ?>
</div>

</body>
</html>
