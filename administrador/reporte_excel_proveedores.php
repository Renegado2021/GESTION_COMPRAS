<?php
// Establecer la conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'gestion_compras2');

// Comprobar la conexión
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Consulta para obtener los datos de la tabla
$consulta = 'SELECT id_proveedor, nombre, direccion, telefono, correo_electronico, fecha_creacion, creado_por FROM tbl_proveedores';
$resultado = $conexion->query($consulta);

// Cargar el archivo PhpSpreadsheet
require '../administrador/libreria/vendor/autoload.php';

// Crear una instancia de PhpSpreadsheet
$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Agregar título a la hoja
$sheet->setTitle('REPORTE PROVEEDORES');

// Encabezados de la tabla
$encabezados = array('ID', 'Nombre', 'Dirección', 'Telefono', 'Correo Electronico', 'Fecha Creación', 'Creado Por');

// Escribir los encabezados en el archivo
$sheet->fromArray($encabezados, null, 'A1');

// Llenar la tabla con los datos
if ($resultado->num_rows > 0) {
    $fila = 2;
    while ($data = $resultado->fetch_assoc()) {
        $sheet->setCellValue('A' . $fila, $data['id_proveedor']);
        $sheet->setCellValue('B' . $fila, $data['nombre']);
        $sheet->setCellValue('C' . $fila, $data['direccion']);
        $sheet->setCellValue('D' . $fila, $data['telefono']);
        $sheet->setCellValue('E' . $fila, $data['correo_electronico']);
        $sheet->setCellValue('F' . $fila, $data['fecha_creacion']);
        $sheet->setCellValue('G' . $fila, $data['creado_por']);
        $fila++;
    }
} else {
    die('No se encontraron registros.');
}

// Crear un escritor para guardar el archivo Excel
$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

// Definir el nombre del archivo Excel
$nombre_archivo = 'reporte_proveedores.xlsx';

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
$writer->save('php://output');

// Finalizar la ejecución del script
exit;
