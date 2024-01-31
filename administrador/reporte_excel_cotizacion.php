<?php
// Establecer la conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'gestion_compras2');

// Comprobar la conexión
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Consulta para obtener los datos de la tabla
$consulta = 'SELECT ID_COTIZACION, NUMERO_COTIZACION, DEPARTAMENTO, FECHA_COTIZACION, NOMBRE_PROVEEDOR, DESCRIPCION, CANTIDAD, PRECIO, TOTAL FROM tbl_cotizacion';
$resultado = $conexion->query($consulta);

// Cargar el archivo PhpSpreadsheet
require './libreria/vendor/autoload.php';

// Crear una instancia de PhpSpreadsheet
$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


// Agregar título a la hoja
$sheet->setTitle('REPORTE COTIZACIONES');

// Título del reporte en negrita
$titulo = 'REPORTE DE COTIZACIONES';
$sheet->setCellValue('A1', $titulo);
$sheet->getStyle('A1')->getFont()->setBold(true); // Establecer la fuente en negrita


// Encabezados de la tabla
$encabezados = array('ID', 'Número Cotizacion','Departamento', 'Fecha Cotizacion','Proveedor', 'Descripcion', 'Cantidad', 'Precio', 'Total');

// Agregar estilos a la tabla
$styleArray = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], // Establecer color blanco para el texto en negrita
    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E88E5']], // Establecer color de fondo azul claro
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'], // Color del borde (en este caso, negro)
        ],
    ],
];

// Escribir los encabezados en el archivo con los estilos aplicados
$sheet->fromArray($encabezados, null, 'A1');
$sheet->getStyle('A1:I1')->applyFromArray($styleArray); // Aplicar estilos al rango de la primera fila

// Llenar la tabla con los datos
if ($resultado->num_rows > 0) {
    $fila = 2;
    while ($data = $resultado->fetch_assoc()) {
        $sheet->setCellValue('A' . $fila, $data['ID_COTIZACION']);
        $sheet->setCellValue('B' . $fila, $data['NUMERO_COTIZACION']);
        $sheet->setCellValue('C' . $fila, $data['DEPARTAMENTO']);
        $sheet->setCellValue('D' . $fila, $data['FECHA_COTIZACION']);
        $sheet->setCellValue('E' . $fila, $data['PROVEEDOR']);
        $sheet->setCellValue('F' . $fila, $data['DESCRIPCION']);
        $sheet->setCellValue('G' . $fila, $data['CANTIDAD']);
        $sheet->setCellValue('H' . $fila, $data['PRECIO']);
        $sheet->setCellValue('I' . $fila, $data['TOTAL']);
        
   
        // Establecer estilos de borde para las celdas de la fila
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Color del borde (en este caso, negro)
                ],
            ],
        ];

        $sheet->getStyle('A' . $fila . ':I' . $fila)->applyFromArray($styleArray);

        $fila++;
    }
} else {
    die('No se encontraron registros.');
}

// Crear un escritor para guardar el archivo Excel
$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

// Definir el nombre del archivo Excel
$nombre_archivo = 'reporte_cotizacion.xlsx';

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
$writer->save('php://output');

// Finalizar la ejecución del script
exit;
