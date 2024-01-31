<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $solicitudId = $_GET['id'];
    
    // Aquí puedes mostrar los detalles de la solicitud seleccionada y proporcionar enlaces para agregar cotizaciones
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalles de Solicitud</title>
</head>
<body>
    <h2>Detalles de Solicitud</h2>
    <!-- Mostrar detalles de la solicitud seleccionada -->
</body>
</html>
