<?php
// Incluir el archivo de conexión a la base de datos
include '../conexion/conexion.php';

// Mapeo de valores de estado a sus equivalentes completos
$estadosCompletos = array(
    'A' => 'Activo',
    'I' => 'Inactivo',
    'B' => 'Bloqueado'
);

// Consulta para obtener los datos de la tabla con fechas formateadas
$sql = 'SELECT ID_ROL, NOMBRE_ROL, DATE(FECHA_CREACION) AS FECHA_CREACION, DATE(FECHA_MODIFICACION) AS FECHA_MODIFICACION, ESTADO_ROL FROM tbl_ms_roles';

// Ejecutar la consulta
$resultado = $conn->query($sql);

// Iniciar la salida del archivo Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_roles.xls"');
header('Cache-Control: max-age=0');

// Crear un archivo Excel básico
echo "<table border='1'>";
echo "<tr style='background-color: #3399FF; color: #FFFFFF;'><th>Codigo</th><th>Rol</th><th>Fecha Creacion</th><th>Fecha Modificacion</th><th>Estado</th></tr>";

// Llenar la tabla con los datos
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['ID_ROL'] . "</td>";
        echo "<td>" . $row['NOMBRE_ROL'] . "</td>";
        echo "<td>" . $row['FECHA_CREACION'] . "</td>";
        echo "<td>" . $row['FECHA_MODIFICACION'] . "</td>";

        // Mostrar el estado completo utilizando el array de mapeo
        echo "<td>" . $estadosCompletos[$row['ESTADO_ROL']] . "</td>";

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
