<!DOCTYPE html>
<html>
<head>
    <title>Editar Cotizacion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <?php
        // Conexión a la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "gestion_compras2");

        // Verificar la conexión
        if (!$conexion) {
            die("Error en la conexión a la base de datos: " . mysqli_connect_error());
        }

        // Función para limpiar los datos enviados por el formulario
        function limpiarDatos($datos) {
            global $conexion;
            $datos = mysqli_real_escape_string($conexion, $datos);
            $datos = htmlspecialchars($datos);
            return $datos;
        }

        // Definir la función mostrarMensaje()
        function mostrarMensaje($mensaje, $tipo) {
            // Aquí puedes implementar la lógica para mostrar el mensaje, por ejemplo, imprimir un mensaje HTML
            echo '<div class="alert alert-' . $tipo . '">' . $mensaje . '</div>';
        }

        // Verificar si se envió la solicitud de actualización
        if (isset($_POST['actualizar'])) {
            $idcotizacion = $_POST['ID_COTIZACION'];
            
            $numerocotizacion = limpiarDatos($_POST['NUMERO_COTIZACION']);
            $departamento = limpiarDatos($_POST['DEPARTAMENTO']);
            $fechacotizacion = limpiarDatos($_POST['FECHA_COTIZACION']);
            $proveedorID = limpiarDatos($_POST['ID_PROVEEDOR']);  // Cambiar a ID_PROVEEDOR
           

            // Consulta para obtener el nombre del proveedor mediante JOIN
            $consultaProveedor = "SELECT tbl_cotizacion.*, tbl_proveedores.NOMBRE AS NOMBRE_PROVEEDOR
                                FROM tbl_cotizacion
                                LEFT JOIN tbl_proveedores ON tbl_cotizacion.ID_PROVEEDOR = tbl_proveedores.ID_PROVEEDOR
                                WHERE tbl_cotizacion.ID_COTIZACION = '$idcotizacion'";
            $resultadoProveedor = mysqli_query($conexion, $consultaProveedor);

            // Verificar si la consulta fue exitosa
            if ($resultadoProveedor) {
                $filaProveedor = mysqli_fetch_assoc($resultadoProveedor);
                $nombreProveedor = $filaProveedor['NOMBRE_PROVEEDOR'];

                // Ahora, $nombreProveedor contiene el nombre del proveedor
            } else {
                // Manejar errores si la consulta falla
                $nombreProveedor = "Proveedor no encontrado";
            }

            $sql = "UPDATE tbl_cotizacion SET  NUMERO_COTIZACION='$numerocotizacion', DEPARTAMENTO='$departamento',
                   FECHA_COTIZACION='$fechacotizacion', ID_PROVEEDOR='$proveedorID',  WHERE ID_COTIZACION='$idcotizacion'";

            if (mysqli_query($conexion, $sql)) {
                // Si la consulta se ejecuta correctamente (sin errores), muestra un mensaje de éxito
                mostrarMensaje("Registro actualizado exitosamente.", "success");
            } else {
                // Si la consulta tiene algún error, muestra un mensaje de error con detalles del error
                mostrarMensaje("Error al actualizar el registro: " . mysqli_error($conexion), "danger");
            }
        }

        // Obtener el ID del registro a editar desde la URL
        if (isset($_GET['id'])) {
            $idcotizacion = $_GET['id'];

            // Consulta para obtener los datos del registro a editar
            $consultaEditar = "SELECT * FROM tbl_cotizacion WHERE ID_COTIZACION='$idcotizacion'";
            $resultadosEditar = mysqli_query($conexion, $consultaEditar);

            if (mysqli_num_rows($resultadosEditar) > 0) {
                $row = mysqli_fetch_assoc($resultadosEditar);

                $numerocotizacion = $row['NUMERO_COTIZACION'];
                $departamento = $row['DEPARTAMENTO'];
                $fechacotizacion = $row['FECHA_COTIZACION'];
                $proveedorID = $row['ID_PROVEEDOR'];  // Cambiar a ID_PROVEEDOR
               

                // Consulta para obtener el nombre del proveedor mediante JOIN
                $consultaProveedor = "SELECT tbl_cotizacion.*, tbl_proveedores.NOMBRE AS NOMBRE_PROVEEDOR
                                    FROM tbl_cotizacion
                                    LEFT JOIN tbl_proveedores ON tbl_cotizacion.ID_PROVEEDOR = tbl_proveedores.ID_PROVEEDOR
                                    WHERE tbl_cotizacion.ID_COTIZACION = '$idcotizacion'";
                $resultadoProveedor = mysqli_query($conexion, $consultaProveedor);

                // Verificar si la consulta fue exitosa
                if ($resultadoProveedor) {
                    $filaProveedor = mysqli_fetch_assoc($resultadoProveedor);
                    $nombreProveedor = $filaProveedor['NOMBRE_PROVEEDOR'];

                    // Ahora, $nombreProveedor contiene el nombre del proveedor
                } else {
                    // Manejar errores si la consulta falla
                    $nombreProveedor = "Proveedor no encontrado";
                }
            } else {
                echo '<div class="alert alert-danger">Registro no encontrado.</div>';
                exit; // Salir del script si el registro no existe
            }
        } else {
            echo '<div class="alert alert-danger">ID de registro no especificado.</div>';
            exit; // Salir del script si no se proporcionó el ID del registro
        }

        // Cerrar conexión a la base de datos
        mysqli_close($conexion);
        ?>
        <style>
            /* Estilo personalizado para el encabezado */
            .blue-background {
                background-color: #1a237e; /* Color azul oscuro */
                color: white; /* Texto en color blanco para contrastar */
            }

            /* Estilo para campos en negrita */
            .campo-negrita {
                font-weight: bold;
            }
            .edit-container {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .edit-text {
                font-weight: bold;
                margin-right: 10px;
            }

            .edit-img {
                width: 150px;
                height: 150px;
            }
        </style>
       
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-header blue-background">
                                <div class="d-flex align-items-center">
                                    <h2 class="mb-0">Editar Cotización</h2>
                                    <div class="ml-auto">
                                        <img src="/ihci.jfif" alt="Logo" style="width: 80px; height: 80px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <input type="hidden" name="ID_COTIZACION" value="<?php echo $idcotizacion; ?>">
                                <div class="form-group">
                                    <label for="NUMERO_COTIZACION" class="campo-negrita">N Cotización:</label>
                                    <input type="text" class="form-control" id="NUMERO_COTIZACION" name="NUMERO_COTIZACION" value="<?php echo $numerocotizacion; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="DEPARTAMENTO" class="campo-negrita">Departamento:</label>
                                    <input type="text" class="form-control" id="DEPARTAMENTO" name="DEPARTAMENTO" value="<?php echo $departamento; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="FECHA_COTIZACION" class="campo-negrita">Fecha Cotizacion:</label>
                                    <input type="text" class="form-control" id="FECHA_COTIZACION" name="FECHA_COTIZACION" value="<?php echo $fechacotizacion; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="ID_PROVEEDOR" class="campo-negrita">Proveedor:</label>
                                    <input type="text" class="form-control" id="ID_PROVEEDOR" name="ID_PROVEEDOR" value="<?php echo isset($nombreProveedor) ? $nombreProveedor : ''; ?>" required>
                                </div>
                                
                                <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                                <a href="../cotizaciones/cotizaciones.php" class="btn btn-secondary">Volver</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>

