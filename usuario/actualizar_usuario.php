<?php
// Reemplaza estos valores con tus credenciales de base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

// Comprobar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados por el formulario
    $userId = $_POST["user_id"];
    $newUsername = $_POST["new_username"];
    $newRol = $_POST["rol"];
    $newPassword = $_POST["new_password"]; // Asegúrate de que estás recibiendo la nueva contraseña aquí
    $newDate = $_POST["new_date"];
    $newState = $_POST["estado"];
    
    $newState = trim($_POST["estado"]);

    if (empty($newState)) {
      echo "El campo Estado no puede estar vacío";
      // Puedes redirigir de vuelta al formulario de edición o tomar alguna otra acción aquí
      exit;
    }

    $newBloqueado = $_POST["bloqueado"];

    // Crear una conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Preparar y ejecutar la consulta de actualización
    $sql = "UPDATE tbl_ms_usuario SET nombre_usuario = ?, rol = ?, contraseña = ?, fecha_modificacion = ?, estado = ?, bloqueado = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssi", $newUsername, $newRol, $newPassword, $newDate, $newState, $newBloqueado, $userId);

    // Verificar si la consulta está preparada correctamente
    if ($stmt === false) {
        echo "Error en la preparación de la consulta: " . $conn->error;
    } else {
        // Intentar ejecutar la consulta
        if ($stmt->execute()) {
            // La actualización se realizó con éxito
            echo "Actualización exitosa";
            // Redirige a la página deseada después de la actualización
            header("Location: usuario.php");
            exit;
        } else {
            // Ocurrió un error durante la actualización
            echo "Error durante la actualización: " . $stmt->error;
        }
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
