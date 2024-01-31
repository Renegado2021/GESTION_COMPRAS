<?php

// Incluir el archivo de conexión a la base de datos
include '../conexion/conexion.php';

// Consulta para obtener los datos de la tabla, incluyendo el nombre del rol
$sql = 'SELECT u.id_usuario, u.nombre_usuario, r.NOMBRE_ROL as rol, u.correo_electronico, u.fecha_creacion, u.fecha_modificacion, u.estado
        FROM tbl_ms_usuario u
        INNER JOIN tbl_ms_roles r ON u.rol = r.ID_ROL';

$resultado = $conn->query($sql);

// Mapeo de valores de estado a sus equivalentes completos
$estadosCompletos = array(
    'A' => 'Activo',
    'I' => 'Inactivo',
    'B' => 'Bloqueado'
);

// Iniciar la salida del archivo Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_usuarios.xls"');
header('Cache-Control: max-age=0');

// Crear un archivo Excel básico
echo "<table border='1'>";
echo "<tr style='background-color: #3399FF; color: #FFFFFF;'><th>ID</th><th>Usuario</th><th>Rol</th><th>CORREO ELECTRONICO</th><th>Fecha Creacion</th><th>Fecha Modificacion</th><th>ESTADO</th></tr>";

// Llenar la tabla con los datos
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_usuario'] . "</td>";
        echo "<td>" . $row['nombre_usuario'] . "</td>";
        echo "<td>" . $row['rol'] . "</td>";
        echo "<td>" . $row['correo_electronico'] . "</td>";
        echo "<td>" . $row['fecha_creacion'] . "</td>";
        echo "<td>" . $row['fecha_modificacion'] . "</td>";

        // Mostrar el estado completo utilizando el array de mapeo
        echo "<td>" . $estadosCompletos[$row['estado']] . "</td>";

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
