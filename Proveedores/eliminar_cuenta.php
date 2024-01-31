<?php
include('db.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM tbl_cuenta_proveedor WHERE ID_CUENTA_PROVEEDOR = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: listar_cuentas.php');
        exit;
    } else {
        echo "Error al eliminar la cuenta: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID no proporcionado en la URL o no es válido.";
    exit;
}
?>
