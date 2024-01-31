<?php
include 'db_connection.php';

// Consulta para recuperar las solicitudes con al menos tres cotizaciones
$sqlSolicitudes = "SELECT s.id, s.descripcion, COUNT(c.id) AS cotizaciones_count
                 FROM tbl_solicitudes s
                 LEFT JOIN tbl_cotizacion c ON s.id = c.id
                 GROUP BY s.id, s.descripcion
                 HAVING cotizaciones_count >= 3";
$resultSolicitudes = $conn->query($sqlSolicitudes);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Solicitudes para Autorización</title>
</head>
<body>
    <h2>Solicitudes para Autorización</h2>
    <table border="1">
        <tr>
            <th>Número de Solicitud</th>
            <th>Descripción de la Solicitud</th>
            <th>Acciones</th>
        </tr>
        <?php
        while ($rowSolicitud = $resultSolicitudes->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rowSolicitud['id'] . "</td>";
            echo "<td>" . $rowSolicitud['descripcion'] . "</td>";
            echo '<td><a href="view_solicitud.php?id=' . $rowSolicitud['id'] . '">Ver</a></td>';
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
