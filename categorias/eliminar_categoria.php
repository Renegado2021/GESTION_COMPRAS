<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Elimina la categoría de la base de datos
    $stmt = $conn->prepare("DELETE FROM tbl_categorias WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: listar_categorias.php');
    } else {
        echo 'Error al eliminar la categoría.';
    }
} else {
    echo 'ID de categoría no proporcionado.';
}
?>
