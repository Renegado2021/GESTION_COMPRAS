<?php
include('db.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM tbl_proveedores WHERE ID_PROVEEDOR = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: listar_proveedores.php');
        exit;
    } else {
        echo "Error al eliminar el proveedor: " . $stmt->error;
    }

    $stmt->close();
}
?>
