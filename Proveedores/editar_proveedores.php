<!DOCTYPE html>
<html>
<head>

    <title>Editar Proveedor</title>
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

        // Verificar si se envió la solicitud de actualización
        if (isset($_POST['actualizar'])) {
            $idproveedor = $_POST['id_proveedor'];
            $nombre = limpiarDatos($_POST['Nombre']);
            $direccion = limpiarDatos($_POST['direccion']);
            $telefono = limpiarDatos($_POST['telefono']);
            $correoelectronico = limpiarDatos($_POST['correo_electronico']);
            $fechacreacion = limpiarDatos($_POST['fecha_creacion']);
            $creadopor = limpiarDatos($_POST['creado_por']);
            

            $sql = "UPDATE tbl_proveedores SET NOMBRE='$nombre', DIRECCION='$direccion',
             TELEFONO='$telefono',CORREO_ELECTRONICO='$correoelectronico', FECHA_CREACION='$fechacreacion', CREADO_POR='$creadopor' WHERE ID_PROVEEDOR='$idproveedor'";

            if (mysqli_query($conexion, $sql)) {
                echo '<div class="alert alert-success">Registro actualizado exitosamente.</div>';
            } else {
                echo '<div class="alert alert-danger">Error al actualizar el registro: ' . mysqli_error($conexion) . '</div>';
            }
        }

         // Obtener el ID del registro a editar desde la URL
         if (isset($_GET['id'])) {
            $idproveedor = $_GET['id'];

            // Consulta para obtener los datos del registro a editar
            $sql = "SELECT * FROM tbl_proveedores WHERE ID_PROVEEDOR='$idproveedor'";
            $resultados = mysqli_query($conexion, $sql);

            if (mysqli_num_rows($resultados) > 0) {
                $row = mysqli_fetch_assoc($resultados);
                $nombre = $row['NOMBRE'];
                $direccion = $row['DIRECCION'];
                $telefono = $row['TELEFONO'];
                $correoelectronico = $row['CORREO_ELECTRONICO'];
                $fechacreacion = $row['FECHA_CREACION'];
                $creadopor = $row['CREADO_POR'];
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
                <h2 class="mb-0">Editar Proveedor</h2>
                <div class="ml-auto">
                <img src="/ihci.jfif" alt="Logo" style="width: 80px; height: 80px;">
            </div>
                </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id_proveedor" value="<?php echo $idproveedor; ?>">
                        <div class="form-group">
                            <label for="Nombre" class="campo-negrita">Nombre:</label>
                            <input type="text" class="form-control" id="Nombre" name="Nombre" value="<?php echo $nombre; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion" class="campo-negrita">Direccion:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono" class="campo-negrita">Telefono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="correo_electronico" class="campo-negrita">Correo Electronico:</label>
                            <input type="text" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo $correoelectronico; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="creado_por" class="campo-negrita">Creado por:</label>
                            <textarea class="form-control" id="creado_por" name="creado_por" required><?php echo $creadopor; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fecha_creacion" class="campo-negrita">Fecha Creacion:</label>
                            <input type="text" class="form-control" id="fecha_creacion" name="fecha_creacion" value="<?php echo $fechacreacion; ?>" required>
                        </div>
                        <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                        <a href="../proveedores.php" class="btn btn-secondary">Volver</a>
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
