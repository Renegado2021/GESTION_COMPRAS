<?php
if (isset($_POST['eliminar']) && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "gestion_compras2";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta para eliminar el usuario
    $sql = "DELETE FROM tbl_ms_usuario WHERE id_usuario = $id_usuario";
    if ($conn->query($sql) === TRUE) {
        // Redireccionar o mostrar un mensaje de éxito
        header("Location: ../usuario/usuario.php"); // Cambia "usuarios.php" por la página donde se muestra la lista de usuarios
        exit();
    } else {
        echo "Error al eliminar el usuario: " . $conn->error;
    }

    $conn->close();
}
?>
