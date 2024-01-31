<?php
// buscar_ordenes.php



// Conecta a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén el número de orden desde el formulario de búsqueda
    $buscar_numero_orden = isset($_POST['buscar_numero_orden']) ? $_POST['buscar_numero_orden'] : '';

    // Consulta para obtener los datos de la tabla tbl_orden_compra según el número de orden proporcionado
    $sqlBuscar = "SELECT * FROM tbl_orden_compra WHERE NUMERO_ORDEN = '$buscar_numero_orden'";
    $resultBuscar = $conn->query($sqlBuscar);

    if ($resultBuscar->num_rows > 0) {
        // Guarda los resultados en un array para pasarlos a la página de órdenes_compras
        $resultados = [];

        while ($row = $resultBuscar->fetch_assoc()) {
            $resultados[] = $row;
        }

        // Redirige a la página de órdenes_compras y pasa los resultados como parámetro de consulta
        header("Location: ordenes_compras.php?resultados=" . urlencode(json_encode($resultados)));
        exit();
    } else {
        echo "<p>No se encontraron resultados para el número de orden '$buscar_numero_orden'.</p>";
    }
}

// Cierra la conexión con la base de datos
$conn->close();
?>
