<?php
include('../conexion/conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parametro = $_POST['parametro'];
    $valor = $_POST['valor'];
    $fecha_creacion = $_POST['fecha_creacion'];

    // Obtén el nombre del usuario desde la sesión
    $nombre_usuario = $_SESSION['nombre_usuario'];

    // Prepara la consulta SQL para la inserción
    $sql = "INSERT INTO tbl_ms_parametros (PARAMETRO, VALOR, FECHA_CREACION, CREADO_POR) 
            VALUES (?, ?, ?, ?)";
    
    // Prepara y ejecuta la sentencia
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $parametro, $valor, $fecha_creacion, $nombre_usuario);
    $stmt->execute();

    // Verifica si la inserción fue exitosa
    if ($stmt->affected_rows > 0) {
        // Redirige a parametros.php después del éxito
        header('Location: parametros.php');
        exit();
    } else {
        echo "Error al guardar el parámetro: " . $stmt->error;
    }

    // Cierra la sentencia y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Error: El formulario no fue enviado correctamente.";
}
?>
