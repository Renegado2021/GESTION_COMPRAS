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

// Comprobar si se envió un ID válido
if (isset($_GET['id'])) {
    $idcotizacion = $_GET['id'];
    
    // Comprobar si se ha confirmado la eliminación
    if (isset($_GET['confirmar']) && $_GET['confirmar'] === 'true') {

        // Realizar la eliminación en la base de datos
        $sql = "DELETE FROM tbl_cotizacion WHERE ID_COTIZACION='$idcotizacion'";

        if (mysqli_query($conexion, $sql)) {
            // Respuesta JSON indicando que la eliminación fue exitosa
            header('Content-Type: application/json');
            echo json_encode(array('success' => true, 'message' => 'Registro eliminado exitosamente.'));
            exit(); // Terminar la ejecución del script después de la respuesta JSON
        } else {
            // Respuesta JSON indicando que hubo un error en la eliminación
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'message' => 'Error al eliminar el registro: ' . mysqli_error($conexion)));
            exit(); // Terminar la ejecución del script después de la respuesta JSON
        }
    }
}

// Cerrar la conexión
mysqli_close($conexion);
?>


