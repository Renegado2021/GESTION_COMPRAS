<?php
include('db.php');

// Realizar la consulta SQL
$query = "SELECT * FROM tbl_direcciones_proveedores";
$result = $conexion->query($query);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado de Direcciones de Proveedores</title>
</head>
<body>
    <h2>Listado de Direcciones de Proveedores</h2>
    <a href="crear.php"><button>Crear Nuevo Registro</button></a>
    <table>
        <tr>
            <th>ID</th>
            <th>ID Proveedor</th>
            <th>Departamento</th>
            <th>Municipio</th>
            <th>Colonia</th>
            <th>Estado</th>
            <th>Creado por</th>
            <th>Fecha Creación</th>
            <th>Modificado por</th>
            <th>Fecha Modificación</th>
            <th>Acciones</th>
        </tr>

        <?php
        while ($row = $result->fetch_assoc()) {
            // El código dentro del bucle se mantiene igual
            echo "<tr>";
            echo "<td>" . $row['ID_DIRECCION_PROVEEDOR'] . "</td>";
            echo "<td>" . $row['ID_PROVEEDOR'] . "</td>";
            echo "<td>" . $row['DEPARTAMENTO'] . "</td>";
            echo "<td>" . $row['MUNICIPIO'] . "</td>";
            echo "<td>" . $row['COLONIA'] . "</td>";
            echo "<td>" . $row['ESTADO'] . "</td>";
            echo "<td>" . $row['CREADO_POR'] . "</td>";
            echo "<td>" . $row['FECHA_CREACION'] . "</td>";
            echo "<td>" . $row['MODIFICADO_POR'] . "</td>";
            echo "<td>" . $row['FECHA_MODIFICACION'] . "</td>";
            echo "<td><a href='actualizar.php?id=" . $row['ID_DIRECCION_PROVEEDOR'] . "'>Editar</a> | ";
            echo "<a href='eliminar.php?id=" . $row['ID_DIRECCION_PROVEEDOR'] . "'>Eliminar</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
