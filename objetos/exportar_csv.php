<?php
// Conecta a la base de datos y realiza tu consulta para obtener los datos
include 'db_connect.php';

// Consulta SQL para obtener los datos de tu tabla
$stmt = $conn->query("SELECT * FROM tbl_ms_roles");
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nombre del archivo CSV que se generará
$filename = 'datos.csv';

// Abre un flujo de salida para el archivo CSV
$output = fopen('php://output', 'w');

// Convertir encabezados a UTF-8 si es necesario
$header = array_map('utf8_decode', array_keys($registros[0]));

// Agrega los encabezados al archivo CSV
fputcsv($output, $header);

// Agrega los registros al archivo CSV
foreach ($registros as $registro) {
    // Asegúrate de que los datos estén en la codificación UTF-8 para caracteres especiales
    $registro = array_map('utf8_decode', $registro);
    $data = array_values($registro);
    fputcsv($output, $data);
}

// Cierra el flujo de salida
fclose($output);

// Encabezado HTTP para indicar que se está descargando un archivo CSV
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
?>
