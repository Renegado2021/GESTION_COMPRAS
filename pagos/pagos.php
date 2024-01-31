<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $idOrdenCompraProducto = $_GET['id'];

    // Consulta para obtener datos de la orden de compra y detalles del proveedor, cuentas y cuenta proveedor asociadas
    $sql = "SELECT ocp.ID, ocp.ID_ORDEN, ocp.CANTIDAD, ocp.DESCRIPCION, ocp.PRECIO, ocp.TOTAL, ocp.SUBTOTAL, ocp.ISV, ocp.MONTO,
               oc.ID_ORDEN_COMPRA, p.NOMBRE, cp.NUMERO_CUENTA, cp.BANCO, cp.DESCRIPCION_CUENTA
        FROM tbl_orden_compra_productos ocp
        LEFT JOIN tbl_orden_compra oc ON ocp.ID_ORDEN = oc.ID_ORDEN_COMPRA
        LEFT JOIN tbl_proveedores p ON oc.ID_PROVEEDOR = p.ID_PROVEEDOR
        LEFT JOIN tbl_cuenta_proveedor cp ON p.ID_PROVEEDOR = cp.ID_PROVEEDOR
        WHERE ocp.ID = $idOrdenCompraProducto";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Obtener el nombre del proveedor
        $proveedorNombre = $row["NOMBRE"];

        // Obtener información de la cuenta del proveedor
        $infoCuentaProveedor = [
            'NUMERO_CUENTA' => $row["NUMERO_CUENTA"],
            'BANCO' => $row["BANCO"],
            'DESCRIPCION_CUENTA' => $row["DESCRIPCION_CUENTA"]
        ];

        // Formatear la fecha en español
        $fechaFormateada = new DateTime(null, new DateTimeZone('America/Tegucigalpa'));
        $fechaFormateada = $fechaFormateada->format('d \d\e F \d\e Y');
        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Solicitud de Transferencia</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                .form-section {
                    margin: 20px;
                }
                label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                input {
                    width: 100%;
                    padding: 8px;
                    margin-bottom: 10px;
                }
                button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                button:hover {
                    background-color: #45a049;
                }
            </style>
        </head>
        <body>
            <div class="form-section">
                <h2>INSTITUTO HONDUREÑO DE CULTURA INTERAMERICANA</h2>
                <h2>SOLICITUD DE TRANSFERENCIA L.</h2>

                <label>Lugar y Fecha:</label>
                <p>Tegucigalpa, M.D.C. <?php echo $fechaFormateada; ?></p>

                <label>A Favor:</label>
                <p><?php echo $proveedorNombre; ?></p>

                <label>Cantidad a transferir:</label>
                <p>L. <?php echo $row["MONTO"]; ?></p>

                <label>Número de cuenta:</label>
                <p><?php echo $infoCuentaProveedor['NUMERO_CUENTA']; ?> | Tipo de cuenta: <?php echo $infoCuentaProveedor['DESCRIPCION_CUENTA']; ?></p>

                <label>Banco:</label>
                <p><?php echo $infoCuentaProveedor['BANCO']; ?></p>

                <label>Concepto de la solicitud:</label>
                <p>Compra de <?php echo $row["DESCRIPCION"]; ?></p>

                <label>Nombre del solicitante:</label>
                <input type="text" name="nombre_solicitante">

                <label>Firma supervisor de solicitante:</label>
                <input type="text" name="firma_supervisor">

                <label>Firma departamento solicitante:</label>
                <input type="text" name="firma_departamento">

                <label>Observaciones:</label>
                <textarea name="observaciones"></textarea>

                <button type="submit">Guardar</button>
            </div>
        </body>
        </html>

        <?php
    } else {
        echo "No se encontraron datos de la orden de compra.";
    }
} else {
    echo "ID de orden de compra no proporcionado.";
}

$conn->close();
?>
