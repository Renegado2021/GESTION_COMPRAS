<!DOCTYPE html>
<html>
<head>
    <title>Crear Proveedor</title>
</head>
<body>
    <h2>Crear Proveedor</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesar el formulario de datos del proveedor
        $nombreProveedor = $_POST['NOMBRE'];
        $direccionProveedor = $_POST['DIRECCION'];
        $telefonoProveedor = $_POST['TELEFONO'];
        $correoProveedor = $_POST['CORREO_ELECTRONICO'];
        // Otros campos de datos del proveedor

        // Insertar el proveedor en la base de datos
        include('db.php'); // Incluye la conexión a la base de datos

        $query = "INSERT INTO tbl_proveedores (NOMBRE, DIRECCION, TELEFONO, CORREO_ELECTRONICO) 
                  VALUES ('$nombreProveedor', '$direccionProveedor', '$telefonoProveedor', '$correoProveedor')";
        
        if ($conexion->query($query) === TRUE) {
            // Obtener el ID del proveedor recién insertado
            $idProveedor = $conexion->insert_id;

            // Redirigir al siguiente paso (crear_cuenta.php) con el ID del proveedor
            header("Location: crear_cuenta.php?id=$idProveedor");
            exit;
        } else {
            echo "Error al insertar el proveedor: " . $conexion->error;
        }
    }
    ?>

    <form method="POST" action="crear_proveedor.php">
        <label>Nombre del Proveedor:</label>
        <input type="text" name="NOMBRE" required>
        
        <label>Dirección del Proveedor:</label>
        <input type="text" name="DIRECCION">
        
        <label>Teléfono del Proveedor:</label>
        <input type="text" name="TELEFONO">
        
        <label>Correo Electrónico del Proveedor:</label>
        <input type="text" name="CORREO_ELECTRONICO">
        
        <!-- Otros campos para datos del proveedor -->
        
        <input type="submit" value="Guardar Proveedor">
    </form>

    <a href="listar_proveedores.php?nombre=<?php echo $nombreProveedor; ?>"><button>Siguiente</button></a>
</body>
</html>


