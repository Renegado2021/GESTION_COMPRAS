<?php
if (isset($_POST['eliminar']) && isset($_POST['ID_ROL'])) {
    $ID_ROL = $_POST['ID_ROL'];

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
    $sql = "DELETE FROM tbl_ms_roles WHERE ID_ROL = $ID_ROL";
    if ($conn->query($sql) === TRUE) {
        // Redireccionar o mostrar un mensaje de éxito
        header("Location: ../roles/roles.php"); // Cambia "roles.php" por la página donde se muestra la lista de roles
        exit();

        
    } else {
        echo "Error al eliminar el rol: " . $conn->error;
    }

    $conn->close();
}
?>

