<?php
include('db.php');

$proveedor = null;
$cuentas = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_proveedor = $_GET['id'];

    // Obtener información del proveedor
    $query_proveedor = "SELECT * FROM tbl_proveedores WHERE ID_PROVEEDOR = $id_proveedor";
    $result_proveedor = $conexion->query($query_proveedor);

    if ($result_proveedor && $result_proveedor->num_rows > 0) {
        $proveedor = $result_proveedor->fetch_assoc();

        // Obtener cuentas asociadas a ese proveedor
        $query_cuentas = "SELECT * FROM tbl_cuenta_proveedor WHERE ID_PROVEEDOR = $id_proveedor";
        $result_cuentas = $conexion->query($query_cuentas);

        if ($result_cuentas && $result_cuentas->num_rows > 0) {
            while ($cuenta = $result_cuentas->fetch_assoc()) {
                $cuentas[] = $cuenta;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalles del Proveedor</title>
    <style>
          body {
            text-align: center;
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10); /* Cambia el valor de "0.7" para ajustar la transparencia */
            background-image: url('../imagen/background.jpg'); /* Reemplaza con la ruta de tu imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
           
        
        }

        .container {
            display: inline-block;
            text-align: left;
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
            background-color: powderblue; /* Color de fondo azul claro (cielo) */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
            opacity: 0.9; /* Valor de opacidad (menos transparente) */
        }

        .table-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .table {
            width: 48%; /* El 2% es para el espacio entre las tablas */
            box-sizing: border-box; /* Para evitar que los bordes agreguen más ancho */
            background-color: cornsilk; /* Color de fondo  para las tablas */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            
        }

        th {
            background-color: bisque;
        }

        .button-row {
            text-align: center;
            margin-top: 20px;
        }

        .table-title {
            text-align: center;
            font-weight: bold;
        }

        /* Estilo del botón */
        button {
            background-color: #4CAF50; /* Color verde para el botón */
            color: white; /* Texto en color blanco */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

      
         /* Estilo del botón */
        button {
            background-color: blue; /* Color verde para el botón */
            color: white; /* Texto en color blanco */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }





    </style>
</head>
<body>
    <div class="container">
        <div class="table-title">
            <h2>Detalles del Proveedor</h2>
        </div>
        <div class="table-container">
            <div class="table"> 
                <?php if ($proveedor) { ?>
                <table >
                    <tr>
                        <th>Detalle</th>
                        <td><?php echo $proveedor['NOMBRE']; ?></td>
                    </tr>
                    <tr>
                        <th>Dirección</th>
                        <td><?php echo $proveedor['DIRECCION']; ?></td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td><?php echo $proveedor['TELEFONO']; ?></td>
                    </tr>
                    <tr>
                        <th>Correo Electrónico</th>
                        <td><?php echo $proveedor['CORREO_ELECTRONICO']; ?></td>
                    </tr>
                </table>
                <?php } else {
                    echo "<p>Proveedor no encontrado.</p>";
                } ?>
            </div>
            <div class="table">
                <table>
                    <tr>
                        <th colspan="3" style="text-align: center;">Cuentas Asociadas</th>
                    </tr>
                    <?php if (!empty($cuentas)) { ?>
                    <tr>
                        <th>Nº de Cuenta</th>
                        <th>Banco</th>
                        <th>Descripción</th>
                    </tr>
                    <?php foreach ($cuentas as $cuenta) { ?>
                    <tr>
                        <td><?php echo $cuenta['NUMERO_CUENTA']; ?></td>
                        <td><?php echo $cuenta['BANCO']; ?></td>
                        <td><?php echo $cuenta['DESCRIPCION_CUENTA']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else {
                        echo "<tr><td>No se encontraron cuentas asociadas a este proveedor.</td></tr>";
                    } ?>
                </table>
            </div>
        </div>

        <div class="button-row">
         <a href="listar_proveedores.php"><button>Regresar</button></a>
        </div>
    </div>

    
</body>
</html>
