<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['id_usuario'];
    $newUserName = $_POST['nombre_usuario'];
    $newUserRole = $_POST['rol'];
    $newUserEmail = $_POST['correo_electronico'];

    // Realiza la conexión a la base de datos y actualiza los datos del usuario
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $sql = "UPDATE tbl_ms_usuario SET nombre_usuario='$newUserName', rol='$newUserRole', correo_electronico='$newUserEmail' WHERE id_usuario='$userId'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin.php"); // Redirige después de guardar los cambios
        exit();
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }

    $conn->close();
}
?>
