<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Dirección de Proveedor</title>
</head>
<body>
    <h2>Eliminar Dirección de Proveedor</h2>
    <?php
    include('db.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = "DELETE FROM tbl_direcciones_proveedores WHERE ID_DIRECCION_PROVEEDOR = $id";

        if ($conexion->query($query) === TRUE) {
            echo "Registro eliminado correctamente.";
        } else {
            echo "Error al eliminar el registro: " . $conexion->error;
        }
    }
    ?>

    <a href="listar.php">Volver al listado</a>
</body>
</html>
