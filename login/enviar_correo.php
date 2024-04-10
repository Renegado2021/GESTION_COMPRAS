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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $metodoRecuperacion = $_POST['metodoRecuperacion'] ?? '';

    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $fecha_expiracion = date("Y-m-d H:i:s", strtotime('+1 day'));

        $update_stmt = $conexion->prepare("UPDATE usuario SET Token = ?, Fecha_Vencimiento_Token = ? WHERE Correo = ?");
        $update_stmt->bind_param("sss", $token, $fecha_expiracion, $email);
        $update_stmt->execute();

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  
            $mail->SMTPAuth   = true;
            $mail->Username   = 'Kronos20242@gmail.com'; 
            $mail->Password   = 'r j c t u b m s v v o z y v z u'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('Kronos20242@gmail.com', 'IHCI'); 
            $mail->addAddress($email); 

            $mail->isHTML(true);
            $mail->Subject = 'Restablecer contraseña de Sistema-CC';
            
            ob_start();
            include 'email_cambiopass.php';
            $mail->Body = ob_get_clean();
            
            $mail->Body = str_replace('href=""', 'href="http://localhost/Sistema-AF/modelos/reset_contrasena.php?token=' . $token . '"', $mail->Body);

            $mail->send();
            echo 'Información procesada correctamente y token generado';
        } catch (Exception $e) {
            echo "El mensaje no se pudo enviar. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Correo no encontrado en la base de datos.';
    }

    $stmt->close();
    $update_stmt->close();
}

// Crear una nueva instancia de PHPMailer
/*$mail = new PHPMailer(true);

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
*/





?>
