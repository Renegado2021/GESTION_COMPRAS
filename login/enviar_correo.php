<?php

$server = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";
// Create connection
$conn = new mysqli($server, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer\src\PHPMailer.php';
require '../PHPMailer\src\SMTP.php';
require '../PHPMailer\src\Exception.php';

// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Puedes cambiarlo a DEBUG_SERVER o DEBUG_CLIENT para depuración
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'Kronos20242@gmail.com';
    $mail->Password = 'r j c t u b m s v v o z y v z u';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Puedes cambiarlo a ENCRYPTION_SSL si es necesario
    $mail->Port = 587; // Puerto SMTP

    // Remitente y destinatario
    $mail->setFrom('Kronos20242@gmail.com', 'IHCI');
    $mail->addAddress($email,); // La dirección de correo del usuario

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de Contraseña';
    $mail->Body = 'Su nueva contraseña es: ' . $nuevaContrasena;

    // Enviar el correo
    $mail->send();
    echo "Se ha enviado una nueva contraseña a su correo electrónico.";
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
