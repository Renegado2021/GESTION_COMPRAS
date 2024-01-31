<?php
$proveedorId = $_POST["proveedorId"];

// Realiza una consulta a la base de datos para obtener los contactos del proveedor
// Reemplaza las siguientes líneas con tu código de consulta
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT ID_CONTACTO_PROVEEDOR, NOMBRE FROM tbl_contactos_proveedores WHERE ID_PROVEEDOR = $proveedorId";
$result = $conn->query($sql);

if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($contacto = $result->fetch_assoc()) {
        echo '<option value="' . $contacto["ID_CONTACTO_PROVEEDOR"] . '">' . $contacto["NOMBRE"] . '</option>';
    }
} else {
    echo '<option value="">No se encontraron contactos para este proveedor</option>';
}

$conn->close();
?>
