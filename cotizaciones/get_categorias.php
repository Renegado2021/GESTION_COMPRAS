<?php
include 'db_connection.php';

// Obtén la descripción desde la solicitud POST
$descripcion = $_POST['descripcion'];

/// Consulta para obtener la categoría asociada a la descripción
$sql = "SELECT C.categoria
FROM tbl_productos P
LEFT JOIN tbl_categorias C ON P.categoria = C.id
WHERE P.descripcion = '$descripcion'
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $categoria = $row['categoria'];
    echo $categoria;
} else {
    echo 'Sin categoría'; // O cualquier mensaje que desees mostrar si no hay categoría
}

$conn->close();
?>



