<?php
include 'db_connection.php';

// Obtén el ID de la solicitud desde la URL
$idSolicitud = $_GET['id'];

// Consulta para obtener las descripciones de productos asociadas a la solicitud
$sql = "SELECT DISTINCT P.descripcion
        FROM tbl_productos P
        INNER JOIN tbl_solicitudes S ON P.id_solicitud = S.id
        WHERE S.id = $idSolicitud";

$result = $conn->query($sql);

$descripciones = array();

while ($row = $result->fetch_assoc()) {
    $descripciones[] = $row['descripcion'];
}

echo json_encode($descripciones);

$conn->close();
?>

