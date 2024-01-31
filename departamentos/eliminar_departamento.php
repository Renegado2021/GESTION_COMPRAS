<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tbl_departamentos WHERE id_departamento = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: listar_departamentos.php');
    } else {
        echo 'Error al eliminar el departamento.';
    }
} else {
    echo 'ID de departamento no proporcionado.';
}
?>
