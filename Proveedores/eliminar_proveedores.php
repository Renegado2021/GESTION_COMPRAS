<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "root";
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

// Comprobar si se envió un ID válido
if (isset($_GET['id'])) {
    $idproveedor = $_GET['id'];

    // Realizar la eliminación en la base de datos
    $sql = "DELETE FROM tbl_proveedores WHERE ID_PROVEEDOR='$idproveedor'";

    if (mysqli_query($conexion, $sql)) {
        mostrarMensaje("Registro eliminado exitosamente.", "success");
        // Redirigir al usuario a la pantalla anterior
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit(); // Terminar la ejecución del script después de la redirección
    } else {
        mostrarMensaje("Error al eliminar el registro: " . mysqli_error($conexion), "danger");
    }
}

// Cerrar la conexión
mysqli_close($conexion);
?>

