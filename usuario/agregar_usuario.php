
<?php
// Incluir el archivo de conexión a la base de datos
require_once('../conexion/conexion.php');

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $nombreUsuario = strtoupper($_POST['nombre_usuario']);
    $rol = $_POST['rol'];
    $correoElectronico = $_POST['correo_electronico'];
    $contrasenaTemp = $_POST['contraseñaTemp'];
    $estado = strtoupper($_POST['estado']);
    
    // Validar los campos según tus necesidades
    // Consultar si ya existe un usuario con el mismo nombre (ignorando mayúsculas/minúsculas)
    $sqlConsultaUsuario = "SELECT COUNT(*) FROM tbl_ms_usuario WHERE UPPER(nombre_usuario) = UPPER(?)";
    $stmtConsultaUsuario = $conn->prepare($sqlConsultaUsuario);
    $stmtConsultaUsuario->bind_param('s', $nombreUsuario);
    $stmtConsultaUsuario->execute();
    $stmtConsultaUsuario->bind_result($contadorUsuarios);
    $stmtConsultaUsuario->fetch();
    $stmtConsultaUsuario->close();



     if ($contadorUsuarios > 0) {
        // Ya existe un usuario con el mismo nombre
        $errorMsg = "Ya existe un usuario con ese nombre.";
    } else {
        // La contraseña cumple con los requisitos
        if (strlen($contrasenaTemp) >= 8) {
            // Encriptar la contraseña
            $hashedPassword = password_hash($contrasenaTemp, PASSWORD_BCRYPT);
            
            // Insertar el nuevo usuario en la base de datos
            $fechaCreacion = date('Y-m-d H:i:s'); // Fecha y hora actual
            $sql = "INSERT INTO tbl_ms_usuario (nombre_usuario, rol, correo_electronico, fecha_creacion, contraseñaTemp, estado)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $nombreUsuario, $rol, $correoElectronico, $fechaCreacion, $hashedPassword, $estado);
            
            if ($stmt->execute()) {
               // Redirigir a la página de usuarios después de agregar
               header("Location: ../usuario/usuario.php");
            } else {
                // Error: no se pudo agregar el usuario
                echo "Error al agregar el usuario: " . $stmt->error;
            }
            
            // Cerrar la conexión
            $stmt->close();
            $conn->close();
           
        } else {
            
            // La contraseña no cumple con los requisitos
            $errorMsg = "La contraseña temporal debe contener al menos 8 caracteres, incluyendo letras mayúsculas, letras minúsculas, números y caracteres especiales.";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-image: url('../imagen/solicitud.jpg' ); /* Ruta de la imagen de fondo */
            background-size: cover;
            font-family: Arial, sans-serif;
        }
 /* Estilos para el formulario */
 .form-container {
    background-color: rgba(245, 245, 220, 0.9); /* Beige con transparencia */
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    text-align: center; /* Centra el contenido horizontalmente */
    margin: 0 auto; /* Centra el formulario en la página */
}



/* Estilos para las filas del formulario */
.form-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

/* Estilos para las etiquetas */
.form-row label {
    flex: 1; /* Hace que las etiquetas ocupen una parte igual del espacio disponible */
    text-align: right;
    font-weight: bold;
    margin-right: 10px; /* Espacio entre la etiqueta y el campo de entrada */
}

/* Estilos para los campos de entrada y selección */
.form-row input[type="text"],
.form-row input[type="email"],
.form-row input[type="password"],
.form-row select {
    flex: 2; /* Hace que los campos ocupen una parte igual del espacio disponible */
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Estilos para el botón "Guardar Cambios" */
.button-container button[type="submit"] {
    flex: 2; /* Hace que los botones ocupen una parte igual del espacio disponible */
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background-color: #007BFF; /* Fondo azul */
    color: white; /* Texto blanco */
}

/* Estilos para el botón "Cancelar" */
.button-container a.btn-secondary {
    flex: 2; /* Hace que los botones ocupen una parte igual del espacio disponible */
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background-color: #ccc; /* Fondo gris */
    color: #333; /* Texto oscuro */
    text-decoration: none; /* Evita la subrayado del enlace Cancelar */
    text-align: center;
    display: inline-block;
}

/* Estilo para el mensaje de error */
.error-message {
            color: red;
            font-weight: bold;
        }


    </style>


    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>Agregar Usuario</title>
</head>
<body>
   <div class="form-container">
   <?php
    // Verificar si hay un error de contraseña
    if (!empty($errorMsg)) {
        echo '<div class="error-message">' . $errorMsg . '</div>';
    }
    ?>
    <h1>Usuario</h1>
    <form method="POST" >

        <div class="form-row">
        <label for="nombre_usuario">Usuario:</label>
        <input type="text" name="nombre_usuario" required><br>
        </div>

        <div class="form-row">
        <label for="rol">Rol:</label>
        <select name="rol" required>
            <!-- Aquí debes cargar los roles disponibles desde la tabla tbl_ms_roles -->
            <option value="" disabled selected>---Seleccione---</option>
            <?php
            // Incluir el archivo de conexión a la base de datos
            require_once('../conexion/conexion.php');

            $sqlRoles = "SELECT ID_ROL, NOMBRE_ROL FROM tbl_ms_roles";
            $resultRoles = $conn->query($sqlRoles);
            while ($row = $resultRoles->fetch_assoc()) {
                echo '<option value="' . $row['ID_ROL'] . '">' . $row['NOMBRE_ROL'] . '</option>';
            }
            ?>
        </select><br>
        </div>

        <div class="form-row">
        <label for="correo_electronico">Email:</label>
        <input type="email" name="correo_electronico" required><br>
        </div>

        <div class="form-row">
        <label>Fecha de Creación:</label>
        <input type="text" name="fecha_creacion" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly><br>
        </div>

        <div class="form-row">
        <label for="estado">Estado:</label>
        <select name="estado" required>
            <option value="">---Seleccione---</option>
            <option value="A">Activo</option>
            <option value="I">Inactivo</option>
            <option value="B">Bloqueado</option>
        </select><br>
        </div>

        <div class="form-row">
        <label for="contraseñaTemp">Contraseña Temporal:</label>
        <input type="password" name="contraseñaTemp" required><br>
        </div>

        <div class="form-row button-container">
        <button type="submit" name="submit">Guardar</button>
        <a href="usuario.php" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>

    </div> 
</body>
</html>
