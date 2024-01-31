<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $categoria = $_POST['categoria'];
    $creado = $_POST['creado'];
    $modificado = $_POST['modificado'];
    $fecha_modificacion = date("Y-m-d"); // Obtiene la fecha actual.

    // Prepara una consulta SQL para actualizar la categoría en la base de datos.
    $stmt = $conn->prepare("UPDATE tbl_categorias SET categoria = :categoria, creado = :creado, modificado = :modificado, fecha_modificacion = :fecha_modificacion WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':creado', $creado);
    $stmt->bindParam(':modificado', $modificado);
    $stmt->bindParam(':fecha_modificacion', $fecha_modificacion);

    if ($stmt->execute()) {
        header('Location: listar_categorias.php');
    } else {
        echo 'Error al actualizar la categoría.';
    }
}
?>
