<?php
// Establecer la conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'gestion_compras2');

// Comprobar la conexión
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Consulta para obtener los datos de la tabla
$consulta = 'SELECT ID_SOLICITUD_COMPRA, NUMERO_SOLICITUD, FECHA_SOLICITUD, MONTO_TOTAL, FECHA_CREACION,CREADO_POR FROM tbl_solicitud_compra';
$resultado = $conexion->query($consulta);

// Generar la tabla HTML con los datos de la base de datos
$tabla_html = '<table>';
$tabla_html .= '<thead><tr><th>ID</th><th>Número Solicitud</th><th>Fecha Solicitud</th><th>Monto Total</th><th>Fecha Creación</th><th>Creado Por</th></tr></thead>';
$tabla_html .= '<tbody>';

while ($fila = $resultado->fetch_assoc()) {
    $tabla_html .= '<tr>';
    $tabla_html .= '<td>' . $fila['ID_SOLICITUD_COMPRA'] . '</td>';
    $tabla_html .= '<td>' . $fila['NUMERO_SOLICITUD'] . '</td>';
    $tabla_html .= '<td>' . $fila['FECHA_SOLICITUD'] . '</td>';
    $tabla_html .= '<td>' . $fila['MONTO_TOTAL'] . '</td>';
    $tabla_html .= '<td>' . $fila['FECHA_CREACION'] . '</td>';
    $tabla_html .= '<td>' . $fila['CREADO_POR'] . '</td>';
    $tabla_html .= '</tr>';
}

$tabla_html .= '</tbody>';
$tabla_html .= '</table>';
?>

</head>
