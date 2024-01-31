<?php
// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "gestion_compras2");

if ($mysqli->connect_error) {
    die("Error en la conexión: " . $mysqli->connect_error);
}

// Obtener el correo electrónico proporcionado por el usuario
$correo = $_POST['correo'];

// Verificar si el correo existe en la base de datos
$query = "SELECT * FROM tbl_ms_usuario WHERE correo_electronico = '$correo'";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    // Generar una nueva contraseña temporal
    $nuevaContrasena = generarNuevaContrasena();

    // Actualizar la contraseña en la base de datos
    $hashedContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
    $updateQuery = "UPDATE tbl_ms_usuario SET contraseña = '$hashedContrasena' WHERE correo_electronico = '$correo'";
    $mysqli->query($updateQuery);

    // Enviar la nueva contraseña por correo electrónico (debes implementar esta parte)
    // Incluir el código para enviar el correo
    require 'enviar_correo.php';

    // Mostrar un mensaje de éxito
    echo "";
} else {
    echo "El correo electrónico proporcionado no está registrado en nuestro sistema.";
}

// Función para generar una nueva contraseña aleatoria
function generarNuevaContrasena() {
    $longitud = 10;
    $caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $nuevaContrasena = "";

    for ($i = 0; $i < $longitud; $i++) {
        $indice = rand(0, strlen($caracteres) - 1);
        $nuevaContrasena .= $caracteres[$indice];
    }

    return $nuevaContrasena;
}

$mysqli->close();
?>
