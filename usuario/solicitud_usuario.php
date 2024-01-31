<?php
$host = 'localhost';
$dbname = 'gestion_compras2';
$username = 'root';
$password = '';

// Conexión a la base de datos
$conexion = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contraseña'];

    // Buscar el ID y la contraseña del usuario por su nombre
    $consulta_usuario = "SELECT id_usuario, contraseña FROM tbl_ms_usuario WHERE nombre_usuario = ?";
    $statement_usuario = $conexion->prepare($consulta_usuario);
    $statement_usuario->bind_param("s", $nombre_usuario);
    $statement_usuario->execute();
    $resultado_usuario = $statement_usuario->get_result();
    $row_usuario = $resultado_usuario->fetch_assoc();

    if ($row_usuario && password_verify($contrasena, $row_usuario['contraseña'])) {
        $usuario_id = $row_usuario['id_usuario'];

        // Consultar las solicitudes del usuario con detalles adicionales
        $consulta_solicitudes = "SELECT s.*, d.nombre_departamento, c.categoria FROM tbl_solicitudes s
                                INNER JOIN tbl_departamentos d ON s.idDepartamento = d.id_departamento
                                INNER JOIN tbl_categorias c ON s.categoria_productos = c.id
                                WHERE s.usuario_id = ?";
        $statement_solicitudes = $conexion->prepare($consulta_solicitudes);
        $statement_solicitudes->bind_param("i", $usuario_id);
        $statement_solicitudes->execute();
        $resultado_solicitudes = $statement_solicitudes->get_result();
        $solicitudes = $resultado_solicitudes->fetch_all(MYSQLI_ASSOC);
    } else {
        $mensaje = "Usuario o contraseña incorrectos.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../estilos.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <style>
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 800px; /* Ajusta el ancho según tus preferencias */
            margin: 0 auto; /* Centra el formulario horizontalmente */
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container label,
        .form-container input {
            width: 100%; /* Llena el ancho disponible */
            padding: 5px;
            margin-bottom: 10px;
            box-sizing: border-box; /* Incluye el padding en el ancho total */
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <title>Solicitudes</title>
</head>
<body>

<div class="sidebar">
           <div class="sidebar-header">
               <element class="logo">
                <img src="../imagen/ihci.jfif">
                </element>
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;
                <span class="menu-icon"></span>
                </button>
           </div>

           <ul class="menu">
            <li><a href="../pantallas/usuarios.php"><i class="fas fa-home"></i><span> Inicio </span></a></li>
            <li><a href="../usuario/solicitud_usuario.php"><i class="fas fa-envelope"></i><span> Solicitudes</span></a></li>
            <li><a href="./views/crear_solicitud.php"><i class="fas fa-plus"></i><span> Crear Solicitud</span></a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i><span> Reportes</span></a></li>
            <li><a href="#"><i class="fas fa-bell"></i><span> Mis Notificaciones</span></a></li>
            <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i><span> Salir</span></a></li>
            <li>
                <div><a href="#"><i class="fas fa-question-circle"></i><span> Help</span></a></div>
            </li>
        </ul>
    </div>

    <div class="form-container" >
    <h4>Búsqueda de Solicitudes del Usuario</h4>
    <form method="POST" action="ver_solicitudes.php">
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" required>
        <br>
        <button type="submit">Buscar</button>
    </form>
    </div>

    
    
</body>
</html>
