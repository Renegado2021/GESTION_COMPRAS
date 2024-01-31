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

// Obtener el proveedor desde la base de datos
$sql = "SELECT ID_PROVEEDOR FROM tbl_proveedores";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ID_proveedor = $row["ID_PROVEEDOR"];
} else {
    
    $ID_proveedor = 0; // Change this to an appropriate default value or handle the error accordingly.
}

// Obtener los datos enviados del formulario
$numero_cotizacion = $_POST["NUMERO_COTIZACION"];
$departamento = $_POST["DEPARTAMENTO"];
$fecha_cotizacion = $_POST["FECHA_COTIZACION"];
$proveedor = $_POST["NOMBRE_PROVEEDOR"];
$descripcion = $_POST["DESCRIPCION"];
$cantidad = $_POST["CANTIDAD"];
$precio = $_POST["PRECIO"];
$total = $_POST["TOTAL"]; 

// Insertar los datos en la tabla
$sql = "INSERT INTO tbl_cotizacion (NUMERO_COTIZACION,DEPARTAMENTO, FECHA_COTIZACION, NOMBRE_PROVEEDOR, DESCRIPCION, CANTIDAD, PRECIO, TOTAL ) 
VALUES ('$numero_cotizacion', '$departamento','$fecha_cotizacion', '$proveedor', '$descripcion', '$cantidad','$precio','$total')";

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
        window.location.href = '../cotizaciones/cotizaciones.php';
    }, 000);
</script>
