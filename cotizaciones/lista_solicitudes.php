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

$consulta_solicitudes = "SELECT s.*, u.nombre_usuario, d.nombre_departamento, c.categoria
                        FROM tbl_solicitudes s
                        INNER JOIN tbl_ms_usuario u ON s.usuario_id = u.id_usuario
                        INNER JOIN tbl_departamentos d ON s.idDepartamento = d.id_departamento
                        INNER JOIN tbl_categorias c ON s.categoria_productos = c.id";
$resultado_solicitudes = $conexion->query($consulta_solicitudes);

$conexion->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Solicitudes</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../Js/estilos.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        /* Estilos adicionales */
        .floating-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .floating-form .close-button {
            position: relative;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #dc3545;
        }

        .floating-form .close-button:hover {
            color: #721c24;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            justify-content: right;
        }
        /* Estilo para el botón */
#btnSolicitarAprobacion {
    padding: 8px 16px;
    background-color: #007bff; /* Color de fondo azul */
    color: #f2f2f2; /* Color de letra blanco */
    border: none; /* Sin borde */
    cursor: pointer; /* Cambiar el cursor al pasar sobre el botón */
    border-radius: 5px; /* Bordes redondeados */
    font-size: 16px; /* Tamaño de letra */
    float: right; /* Alineación del botón a la derecha */
}

/* Estilo para el botón al pasar el cursor sobre él */
#btnSolicitarAprobacion:hover {
    background-color: #0056b3; /* Color de fondo azul más oscuro al pasar el cursor */
}
    </style>

<style>
    .menu li {
        margin-bottom: -1px;
    }

    .menu li a {
        padding: 0px 0px;
    }

   
</style>
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
        <li><a href="../pantallas/admin.php"><i class="fas fa-home"></i><span> Inicio </span></a></li>
            <li><a href="../cotizaciones/solicitudes.php"><i class="fas fa-envelope"></i><span> Solicitudes</span></a></li>
            <li><a href="./views/crear_solicitud.php"><i class="fas fa-plus"></i><span> Crear Solicitud</span></a></li>
            <li><a href="../cotizaciones/cotizaciones.php"><i class="fas fa-file-alt"></i><span> Cotizaciones</span></a></li>
            <li><a href="../proveedores/proveedores.php"><i class="fas fa-users"></i><span> Proveedores</span></a></li>
            <li><a href="../views/productos.php"><i class="fas fa-cubes"></i><span> Productos</span></a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i><span> Reportes</span></a></li>
            <li><a href="../setting/ajustes.php"><i class="fas fa-cog"></i><span> Settings</span></a></li>
            <li><a href="#"><i class="fas fa-bell"></i><span> Mis Notificaciones</span></a></li>
            <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i><span> Salir</span></a></li>
            <li><div><a href="#"><i class="fas fa-question-circle"></i><span> Help</span></a></div></li>
        </ul>
    </div>
      
    <h2 class="text-center">Lista de Solicitudes</h2>
    <div class="content">
   
    <?php if ($resultado_solicitudes->num_rows > 0) : ?>
        
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Descripción</th>
                <th>Departamento</th>
                <th>Categoría</th>
                <th>Fecha de Ingreso</th>
                <th>Estado</th>
            </tr>
            <?php while ($solicitud = $resultado_solicitudes->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $solicitud['id']; ?></td>
                    <td><?php echo $solicitud['nombre_usuario']; ?></td>
                    <td><?php echo $solicitud['descripcion']; ?></td>
                    <td><?php echo $solicitud['nombre_departamento']; ?></td>
                    <td><?php echo $solicitud['categoria']; ?></td>
                    <td><?php echo $solicitud['fecha_ingreso']; ?></td>
                    <td><?php echo $solicitud['estado']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>No hay solicitudes en la lista.</p>
    <?php endif; ?>
    </div>
</body>
</html>
