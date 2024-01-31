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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"])) {
    $userId = $_POST["user_id"];
    $newPassword = $_POST["new_password"];

    // Verificar si la contraseña cumple con los requisitos
    if (
        preg_match('/[a-z]/', $newPassword) && // Al menos una letra minúscula
        preg_match('/[A-Z]/', $newPassword) && // Al menos una letra mayúscula
        preg_match('/[0-9]/', $newPassword) && // Al menos un número
        preg_match('/[^a-zA-Z0-9\s]/', $newPassword) && // Al menos un carácter especial
        !preg_match('/\s/', $newPassword) // No contiene espacios en blanco
    ) {
        // La contraseña cumple con los requisitos, puedes proceder a encriptarla y actualizarla en la base de datos
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Eliminar la contraseña temporal
        $deleteTempPasswordSql = "UPDATE tbl_ms_usuario SET contraseñaTemp = NULL WHERE id_usuario = ?";
        $stmtDelete = $conn->prepare($deleteTempPasswordSql);
        $stmtDelete->bind_param("i", $userId);

        if ($stmtDelete->execute()) {
            // Guardar la nueva contraseña encriptada en la columna "contraseña"
            $updatePasswordSql = "UPDATE tbl_ms_usuario SET contraseña = ? WHERE id_usuario = ?";
            $stmtUpdate = $conn->prepare($updatePasswordSql);
            $stmtUpdate->bind_param("si", $newPasswordHash, $userId);

            if ($stmtUpdate->execute()) {
                echo "Contraseña guardada exitosamente en la columna 'contraseña' y contraseña temporal eliminada.";

                header("Location: ../index.php");
                // Si lo deseas, aquí podrías redirigir al usuario a una página de confirmación.
            } else {
                echo "Error al guardar la contraseña: " . $stmtUpdate->error;
            }
        } else {
            echo "Error al eliminar la contraseña temporal: " . $stmtDelete->error;
        }
    } else {
        // La contraseña no cumple con los requisitos, almacenar el mensaje de error en una variable de sesión
        $_SESSION["error_message"] = "La contraseña no cumple con los requisitos: Debe contener 8 caracteres entre letras, número, signos." ;
        
       
        header("Location: cambiar_contraseña.php?id=" . $userId); // Cambia "formulario_anterior.php" al nombre de tu formulario anterior
        exit();
        
    }
}

$conn->close();
?>
