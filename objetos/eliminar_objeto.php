<?php
include 'db_connect.php';

// Obtener el ID del objeto de la URL
if (isset($_GET['id'])) {
    $idObjeto = $_GET['id'];

    // Consulta SQL para eliminar el objeto por su ID
    $stmt = $conn->prepare("DELETE FROM tbl_objetos WHERE ID_OBJETO = :idObjeto");
    $stmt->bindParam(':idObjeto', $idObjeto);

    if ($stmt->execute()) {
        header('Location: listar_objetos.php'); // Redirige a la lista de objetos después de la eliminación
        exit;
    } else {
        echo 'Error al eliminar el objeto.';
    }
}
?>
