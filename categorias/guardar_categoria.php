<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connect.php'; // Asegúrate de incluir el archivo de conexión a la base de datos.

    $categoria = strtoupper($_POST['categoria']); // Convierte a mayúsculas
    // Verifica si el usuario está autenticado antes de acceder a $_SESSION['nombre_usuario']
    $nombre_usuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : 'Usuario Desconocido';
    $creado = $_POST['creado'];
    $fecha_creacion = $_POST['fecha_creacion']; // Asegúrate de que este campo se envíe desde el formulario.

    // Prepara una consulta SQL para insertar la categoría en la base de datos.
    $stmt = $conn->prepare("INSERT INTO tbl_categorias (categoria, creado, fecha_creacion) VALUES (:categoria, :creado, :fecha_creacion)");
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':creado', $creado);
    $stmt->bindParam(':fecha_creacion', $fecha_creacion);

    if ($stmt->execute()) {
        // Redirige a la página de listado de categorías si la inserción se realizó con éxito.
        header('Location: listar_categorias.php');
    } else {
        // Muestra un mensaje de error si la inserción falla.
        echo 'Error al guardar la categoría: ' . print_r($stmt->errorInfo(), true);
    }
} else {
    echo 'Método de solicitud no válido.';
}
?>
