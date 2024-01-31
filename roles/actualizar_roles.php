<?php
$conexion = mysqli_connect("localhost", "root", "", "gestion_compras2");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rol_id'])) {
    $rolId = $_POST['rol_id'];
    $nuevoNombre = $_POST['nombre'];
    $nuevoEstado = $_POST['estado']; // Nuevo estado del rol
    
    // Actualizar el nombre del rol, el estado y la fecha de modificación en la base de datos
    $actualizar = mysqli_query($conexion, "UPDATE tbl_ms_roles SET NOMBRE_ROL = '$nuevoNombre', ESTADO_ROL = '$nuevoEstado', FECHA_MODIFICACION = NOW() WHERE ID_ROL = '$rolId'");
    
    if ($actualizar) {
        // Obtener la fecha de modificación actualizada
        $consultaFechaModificacion = mysqli_query($conexion, "SELECT FECHA_MODIFICACION FROM tbl_ms_roles WHERE ID_ROL = '$rolId'");
        $filaFechaModificacion = mysqli_fetch_assoc($consultaFechaModificacion);
        $nuevaFechaModificacion = $filaFechaModificacion['FECHA_MODIFICACION'];
        
        echo "Rol actualizado exitosamente.";
    } else {
        echo "Error al actualizar el rol: " . mysqli_error($conexion);
    }
} else {
    echo "Parámetros inválidos.";
}

header("Location: ../roles/roles.php"); // Redirige a admin.php después de la actualización
exit();
?>
