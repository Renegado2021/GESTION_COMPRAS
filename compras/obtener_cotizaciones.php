<?php
$proveedorId = isset($_POST['proveedorId']) ? $_POST['proveedorId'] : null;
$cotizacionId = isset($_POST['cotizacionId']) ? $_POST['cotizacionId'] : null;

// Validar el ID del proveedor y de la cotización
if ($proveedorId === null || !is_numeric($proveedorId) || $cotizacionId === null || !is_numeric($cotizacionId)) {
    echo json_encode(['error' => 'ID de proveedor o ID de cotización no válido']);
    exit;
}

// Conectar a la base de datos (ajusta según tus credenciales)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Verificar si el proveedor está asociado con la cotización
$sqlProveedorCotizacion = "SELECT ID_COTIZACION FROM tbl_cotizacion WHERE ID_PROVEEDOR = $proveedorId AND ESTADO = 'Aprobada' AND ID_COTIZACION = $cotizacionId";

$resultProveedorCotizacion = $conn->query($sqlProveedorCotizacion);

// Verificar si se obtuvieron resultados
if ($resultProveedorCotizacion === false) {
    echo json_encode(['error' => 'Error al buscar proveedor y cotización en tbl_cotizacion']);
    exit;
}

// Verificar si se encontró el proveedor y la cotización
if ($resultProveedorCotizacion->num_rows === 0) {
    echo json_encode(['error' => 'Proveedor no asociado a la cotización']);
    exit;
}

// Consultar la información de la cotización en tbl_cotizacion_detalle
$sqlCotizacionDetalle = "SELECT CANTIDAD, DESCRIPCION 
                        FROM tbl_cotizacion_detalle
                        WHERE ID_COTIZACION = $cotizacionId";

$resultCotizacionDetalle = $conn->query($sqlCotizacionDetalle);

// Verificar si se obtuvieron resultados
if ($resultCotizacionDetalle === false) {
    echo json_encode(['error' => 'Error al buscar ítems de cotización en tbl_cotizacion_detalle']);
    exit;
}

// Obtener los datos de las cotizaciones en un array
$cotizacionData = [];

while ($row = $resultCotizacionDetalle->fetch_assoc()) {
    $cotizacion = [
        'CANTIDAD' => $row['CANTIDAD'],
        'DESCRIPCION' => $row['DESCRIPCION'],
    ];

    $cotizacionData[] = $cotizacion;
}

// Liberar los resultados y cerrar la conexión
$resultProveedorCotizacion->free_result();
$resultCotizacionDetalle->free_result();
$conn->close();

// Verificar si hay datos y devolverlos en formato JSON
if (empty($cotizacionData)) {
    echo json_encode(['mensaje' => 'No hay datos disponibles para este proveedor y cotización']);
} else {
    echo json_encode($cotizacionData);
}
?>
