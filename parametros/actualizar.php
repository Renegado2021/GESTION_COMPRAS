<?php
include('../conexion/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $parametro_id = $_POST["parametro_id"];
    $nuevo_parametro = $_POST["parametro"];
    $nuevo_valor = $_POST["valor"];
    $fecha_modificacion = $_POST["fecha_modificacion"];
    $modificado_por = $_POST["modificado_por"];

    // Actualizar la información en la base de datos
    $sql = "UPDATE tbl_ms_parametros SET PARAMETRO=?, VALOR=?, FECHA_MODIFICACION=?, MODIFICADO_POR=? WHERE ID_PARAMETRO=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nuevo_parametro, $nuevo_valor, $fecha_modificacion, $modificado_por, $parametro_id);

    if ($stmt->execute()) {
        // Éxito al actualizar
        header("Location: parametros.php");
        exit();
    } else {
        // Error al actualizar
        echo "Error al actualizar el parámetro: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    // Redireccionar si se intenta acceder directamente a este archivo
    header("Location: parametros.php");
    exit();
}
?>
