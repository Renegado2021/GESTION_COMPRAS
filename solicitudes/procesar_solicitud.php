<?php
// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener datos generales de la solicitud
    $codigo = $_POST["codigo"];
    $idDepartamento = $_POST["idDepartamento"];
    $usuarioNombre = $_POST["usuario_nombre"];
    $fechaIngreso = $_POST["fecha_ingreso"];
    $estado = $_POST["estado"];

    // Buscar el ID del usuario basándote en el nombre proporcionado
$sqlUsuario = "SELECT id_usuario FROM tbl_ms_usuario WHERE nombre_usuario = ?";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("s", $usuarioNombre);
$stmtUsuario->execute();
$stmtUsuario->bind_result($idUsuario);

  // Verificar si se encontró el usuario
  if ($stmtUsuario->fetch()) {
    // El usuario fue encontrado, proceder con la inserción en tbl_solicitudes
    $stmtUsuario->close();

    // Proceder con la inserción en tbl_solicitudes
    $sqlSolicitud = "INSERT INTO tbl_solicitudes (codigo, usuario_id, idDepartamento, fecha_ingreso, estado)
                    VALUES (?, ?, ?, ?, ?)";
    $stmtSolicitud = $conn->prepare($sqlSolicitud);
    $stmtSolicitud->bind_param("ssiss", $codigo, $idUsuario, $idDepartamento, $fechaIngreso, $estado);

    if (!$stmtSolicitud->execute()) {
        die("Error al insertar solicitud: " . $stmtSolicitud->error);
    }

    // Obtener el ID de la solicitud recién insertada
    $idSolicitud = $conn->insert_id;

    // Procesar los productos asociados a la solicitud
    $cantidades = $_POST["cantidad"];
    $descripciones = $_POST["descripcion"];
    $categorias = $_POST["categoria"];

    // Iterar sobre los productos e insertar en la tabla de productos
    for ($i = 0; $i < count($cantidades); $i++) {
        $cantidad = intval($_POST["cantidad"][$i]);
        $descripcion = $descripciones[$i];
        $categoria = $categorias[$i];

        // Insertar producto en la tabla de productos asociados a la solicitud
        $sqlProducto = "INSERT INTO tbl_productos (id_solicitud, descripcion, categoria, cantidad)
                        VALUES (?, ?, ?, ?)";
        $stmtProducto = $conn->prepare($sqlProducto);
        $stmtProducto->bind_param("issi", $idSolicitud, $descripcion, $categoria, $cantidad);

        if (!$stmtProducto->execute()) {
            die("Error al insertar producto: " . $stmtProducto->error);
        }

        // Cerrar la declaración preparada de la inserción de productos
        $stmtProducto->close();
    }

    // Cerrar la conexión
    $stmtSolicitud->close();
    $conn->close();

    // Redirigir a la página de solicitudes con un mensaje de éxito
    header("Location: solicitudes.php?success=1");
    exit();
} else {
    // El usuario no fue encontrado, redirigir a la página del formulario con un mensaje de error
    $error_message = urlencode("Usuario no encontrado.");
    header("Location: crear_solicitudes.php?error_message=$error_message");
    exit();
}
}
?>











