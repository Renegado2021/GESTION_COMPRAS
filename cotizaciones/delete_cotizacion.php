<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $idCotizacion = $_GET['id'];
    
    $sql = "DELETE FROM tbl_cotizacion WHERE ID_COTIZACION=$idCotizacion";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: read_cotizacion.php");
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }
}
?>
