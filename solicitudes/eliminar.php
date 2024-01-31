<?php
// Conexión a la base de datos (reemplaza con tus credenciales)
$conn = new mysqli("localhost", "root", "", "gestion_compras2");

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibe el ID de la solicitud a eliminar
if (isset($_POST['id'])) { 
    $id = $_POST['id'];
    
    // Consulta de eliminación
    $sql = "DELETE FROM tbl_solicitudes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Solicitud eliminada exitosamente";
        
    } else {
        echo "Error al eliminar la solicitud: " . $stmt->error;
    }
    
    // Cierra la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "ID de solicitud no proporcionado";
}
?>
