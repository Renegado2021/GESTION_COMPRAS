<?php
// Incluye el archivo de conexión a la base de datos
include('../conexion/conexion.php');

// Verifica si se ha recibido el ID del parámetro a eliminar
if (isset($_POST['idParametro'])) {
    $idParametro = $_POST['idParametro'];

    // Prepara la consulta SQL para eliminar el parámetro por ID
    $sql = "DELETE FROM tbl_ms_parametros WHERE ID_PARAMETRO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idParametro);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        echo "El parámetro fue eliminado correctamente.";
    } else {
        echo "Error al intentar eliminar el parámetro.";
    }

    // Cierra la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "ID de parámetro no proporcionado.";
}
?>


