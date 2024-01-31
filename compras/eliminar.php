<?php
// eliminar.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el parámetro id está presente
    if (isset($_POST["id"])) {
        // Obtener el ID de la orden a eliminar
        $numero_orden = $_POST["id"];

        // Realizar la conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gestion_compras2";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta SQL para eliminar la orden por el NUMERO_ORDEN
        $sql = "DELETE FROM tbl_orden_compra WHERE NUMERO_ORDEN = $numero_orden";

        if ($conn->query($sql) === TRUE) {
            echo "Orden eliminada exitosamente";
        } else {
            echo "Error al eliminar la orden: " . $conn->error;
        }

        // Cierra la conexión con la base de datos
        $conn->close();
    } else {
        echo "ID no proporcionado";
    }
} else {
    echo "Método no permitido";
}
?>
