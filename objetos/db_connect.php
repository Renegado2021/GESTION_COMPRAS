<?php
$host = 'localhost';
$dbname = 'gestion_compras2';
$username = 'root';
$password = '';
$port = 3306; // Cambia el puerto a 3307

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}

?>


