<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "gestion_compras2";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
