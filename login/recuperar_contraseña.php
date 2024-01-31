<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST["id_usuario"];
    $respuesta = $_POST["respuesta"];
    $nueva_contraseña = $_POST["nueva_contraseña"];

    // Verificar la respuesta de seguridad
    $sql_select_respuesta = "SELECT respuesta_pregunta FROM tbl_ms_usuario WHERE id_usuario = ?";
    $stmt_select_respuesta = $conn->prepare($sql_select_respuesta);
    $stmt_select_respuesta->bind_param("i", $id_usuario);
    $stmt_select_respuesta->execute();
    $stmt_select_respuesta->bind_result($respuesta_guardada);
    $stmt_select_respuesta->fetch();
    $stmt_select_respuesta->close();

    if ($respuesta === $respuesta_guardada) {
        // Hashear la nueva contraseña y actualizarla en la base de datos
        $hash_nueva_contraseña = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        $sql_update_contraseña = "UPDATE tbl_ms_usuario SET contraseña = ? WHERE id_usuario = ?";
        $stmt_update_contraseña = $conn->prepare($sql_update_contraseña);
        $stmt_update_contraseña->bind_param("si", $hash_nueva_contraseña, $id_usuario);
        $stmt_update_contraseña->execute();
        $stmt_update_contraseña->close();

        // Redirigir al usuario a la página de inicio de sesión
        header("Location: login.php"); // Cambia 'login.php' a la página de inicio de sesión que desees
        exit();
    } else {
        // Respuesta incorrecta, muestra un mensaje de error o redirige a otra página
        echo "Respuesta incorrecta. Inténtalo de nuevo.";
    }
}

$conn->close();
?>
