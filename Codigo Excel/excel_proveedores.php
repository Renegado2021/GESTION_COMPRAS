<?php

// Establecer la conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'gestion_compras2');

// Comprobar la conexión
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Consulta para obtener los datos de la tabla
$sql = 'SELECT ID_PROVEEDOR, NOMBRE, DIRECCION, TELEFONO, CORREO_ELECTRONICO, ESTADO_PROVEEDOR FROM tbl_proveedores';
$resultado = $conexion->query($sql);

// Iniciar la salida del archivo Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_proveedores.xls"');
header('Cache-Control: max-age=0');

// Crear un archivo Excel básico
// Crear un archivo Excel básico
echo "<table border='1'>";
echo "<tr style='background-color: #3399FF; color: #FFFFFF;'><th>ID_PROVEEDOR</th><th>NOMBRE</th><th>DIRECCION</th><th>TELEFONO</th><th>CORREO_ELECTRONICO</th><th>ESTADO_PROVEEDOR</th></tr>";
// Llenar la tabla con los datos
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['ID_PROVEEDOR'] . "</td>";
        echo "<td>" . $row['NOMBRE'] . "</td>";
        echo "<td>" . $row['DIRECCION'] . "</td>";
        echo "<td>" . $row['TELEFONO'] . "</td>";
        echo "<td>" . $row['CORREO_ELECTRONICO'] . "</td>";
        echo "<td>" . $row['ESTADO_PROVEEDOR'] . "</td>";
        echo "</tr>";
    }
} else {
    die('No se encontraron registros.');
}

// Cerrar la tabla
echo "</table>";

// Finalizar la ejecución del script
exit;

?>
