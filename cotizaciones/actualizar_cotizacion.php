<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = " ";
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
    $idcotizacion = $_GET['id'];
    
    $numerocotizacion = $_GET['NUMERO_COTIZACION'];
    $departamento = $_GET['DEPARTAMENTO'];
    $fechacotizacion = $_GET['FECHA_COTIZACION'];
    $proveedor = $_GET['NOMBRE_PROVEEDOR'];
    $descripcion = $_GET['DESCRIPCION'];
    $cantidad = $_GET['CANTIDAD'];
    $precio = $_GET['PRECIO'];
    $total = $_GET['TOTAL'];

    // Realiza la actualización en la base de datos
    $sql = "UPDATE tbl_cotizacion SET  NUMERO_COTIZACION='$numerocotizacion', DEPARTAMENTO='$departamento',
    FECHA_COTIZACION='$fechacotizacion', NOMBRE_PROVEEDOR='$proveedor', DESCRIPCION='$descripcion',
     CANTIDAD= '$cantidad', PRECIO= '$precio', TOTAL= '$total' WHERE ID_COTIZACION='$idcotizacion'";

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
$idcotizacion = 1; // Ejemplo: ID del registro a editar

$numerocotizacion = '';
$departamento = '';
$fechacotizacion = '';
$proveedor = '';
$descripcion = '';
$cantidad = '';
$precio = '';
$total = ''; 

// Código para obtener los datos del registro desde la base de datos

?>

<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="id" value="<?php echo $idcotizacion; ?>">
    
    <label for="numero_cotizacion">numero cotizacion:</label>
    <input type="text" name="numero_cotizacion" id="numero_cotizacion" value="<?php echo $numerocotizacion; ?>">
    <label for="departamento">Departamento:</label>
    <input type="text" name="departamento" id="departamento" value="<?php echo $departamento; ?>">
    <label for="fecha_cotizacion">fecha cotizacion:</label>
    <input type="text" name="fecha_cotizacion" id="fecha_cotizacion" value="<?php echo $fechacotizacion; ?>">
    <label for="nombre_proveedor">Proveedor:</label>
    <input type="text" name="nombre_proveedor" id="nombre_proveedor" value="<?php echo $proveedor; ?>">
    <label for="descripcion">Descripcion:</label>
    <input type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion; ?>">
    <label for="cantidad">Cantidad:</label>
    <input type="text" name="cantidad" id="cantidad" value="<?php echo $cantidad; ?>">
    <label for="precio">Precio:</label>
    <input type="text" name="precio" id="precio" value="<?php echo $precio; ?>">
    <label for="total">Total:</label>
    <input type="text" name="total" id="total" value="<?php echo $total; ?>">
    <input type="submit" name="actualizar" value="Actualizar">
</form>

</body>
</html>
