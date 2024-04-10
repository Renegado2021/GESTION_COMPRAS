<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

// Crear la conexión
$conexion = mysqli_connect($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Definir la función mostrarMensaje()
function mostrarMensaje($mensaje, $tipo) {
    // Aquí puedes implementar la lógica para mostrar el mensaje, por ejemplo, imprimir un mensaje HTML
    echo '<div class="alert alert-' . $tipo . '">' . $mensaje . '</div>';
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['actualizar'])) {
    $idproveedor = $_GET['id'];
    $nombre = $_GET['NOMBRE'];
    $direccion = $_GET['DIRECCION'];
    $telefono = $_GET['TELEFONO'];
    $correoelectronico = $_GET['CORREO_ELECTRONICO'];
    $fechacreacion = $_GET['FECHA_CREACION'];
    $creadopor = $_GET['CREADO_POR'];

    // Realiza la actualización en la base de datos
    $sql = "UPDATE tbl_proveedores SET NOMBRE='$nombre', DIRECCION='$direccion',
    TELEFONO='$telefono', CORREO_ELECTRONICO='$correoelectronico', FECHA_CREACION='$fechacreacion', CREADO_POR= '$creadopor'  WHERE ID_PROVEEDOR='$idproveedor'";

    if (mysqli_query($conexion, $sql)) {
        mostrarMensaje("Registro actualizado exitosamente.", "success");
    } else {
        mostrarMensaje("Error al actualizar el registro: " . mysqli_error($conexion), "danger");
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Página principal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos adicionales personalizados */
        /* ... */
    </style>
</head>
<body>
<?php
// Obtener los datos del registro a editar
$idproveedor = 1; // Ejemplo: ID del registro a editar
$nombre = '';
$direccion = '';
$telefono  = '';
$correoelectronico = '';
$fechacreacion = '';
$creadopor = ''; // Ejemplo: Valor actual del campo 'nombre' en el registro

// Código para obtener los datos del registro desde la base de datos

?>

<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="id" value="<?php echo $idproveedor; ?>">
    <label for="nombre">nombre:</label>
    <input type="text" name="nombre" id="nombre" value="<?php echo $$nombre; ?>">
    <label for="direccion">direccion:</label>
    <input type="text" name="direccion" id="direccion" value="<?php echo $direccion; ?>">
    <label for="telefono">telefono:</label>
    <input type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>">
    <label for="correo_electronico">correo electronico:</label>
    <input type="text" name="correo_electronico" id="correo_electronico" value="<?php echo $correoelectronico; ?>">
    <label for="fecha_creacion">fecha creacion:</label>
    <input type="text" name="fecha_creacion" id="fecha_creacion" value="<?php echo $fechacreacion; ?>">
    <label for="creado_por">creado por:</label>
    <input type="text" name="creado_por" id="creado_por" value="<?php echo $creadopor; ?>">
    <input type="submit" name="actualizar" value="Actualizar">
</form>

</body>
</html>
