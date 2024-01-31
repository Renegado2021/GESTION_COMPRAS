<?php
include 'db_connection.php';

$sql = "SELECT * FROM tbl_cotizacion";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado de Cotizaciones</title>
</head>
<body>
    <h2>Listado de Cotizaciones</h2>
    <a href="read_solicitud.php"><button>Regresar</button></a> <!-- Nuevo botón para crear -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>ID de Proveedor</th>
            <th>Número de Cotización</th>
            <th>Departamento</th>
            <th>Fecha de Cotización</th>
            <th>Nombre de Proveedor</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ID_COTIZACION"] . "</td>";
                echo "<td>" . $row["ID_PROVEEDOR"] . "</td>";
                echo "<td>" . $row["NUMERO_COTIZACION"] . "</td>";
                echo "<td>" . $row["DEPARTAMENTO"] . "</td>";
                echo "<td>" . $row["FECHA_COTIZACION"] . "</td>";
                echo "<td>" . $row["NOMBRE_PROVEEDOR"] . "</td>";
                echo "<td>" . $row["DESCRIPCION"] . "</td>";
                echo "<td>" . $row["CANTIDAD"] . "</td>";
                echo "<td>" . $row["PRECIO"] . "</td>";
                echo "<td>
                      <a href='update_cotizacion.php?id=" . $row["ID_COTIZACION"] . "'>Editar</a> |
                      <a href='delete_cotizacion.php?id=" . $row["ID_COTIZACION"] . "'>Eliminar</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "No se encontraron registros.";
        }
        ?>
    </table>
</body>
</html>
