<?php
// Código para conectarse a la base de datos

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos enviados del formulario
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];
$correo_electronico = $_POST["correo_electronico"];
$creado_por = $_POST["creado_por"];
$fecha_creacion = date("Y-m-d"); // Obtener la fecha actual

// Insertar los datos en la tabla
$sql = "INSERT INTO tbl_proveedores (nombre, direccion, telefono, correo_electronico, creado_por, fecha_creacion) VALUES ('$nombre', '$direccion', '$telefono', '$correo_electronico','$creado_por', '$fecha_creacion')";

if ($conn->query($sql) === TRUE) {
    $mensaje ="Datos agregados exitosamente.";
} else {
    $mensaje ="Error al agregar los datos: " . $conn->error;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
<script>
    // Redirigir a la página principal después de 3 segundos
    setTimeout(function() {
        window.location.href = './proveedores/proveedores.php';
    }, 0000);
</script>

