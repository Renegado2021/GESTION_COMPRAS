<?php
// Incluir el archivo de conexión
include("../conexion/conexion.php");

// Declarar una variable para almacenar el mensaje de error
$errorMsg = '';

// ...
// Verificar si se ha enviado el formulario de edición
if (isset($_POST['editar'])) {
    // Recuperar los datos del formulario
    $idUsuario = $_POST['id_usuario'];
    $nuevoNombre = strtoupper($_POST['nombre_usuario']); // Convertir a mayúsculas
    $nuevoRol = $_POST['rol'];
    $nuevoCorreo = $_POST['correo_electronico'];
    $nuevoEstado = $_POST['estado'];

    // Inicializar la variable $hashContraseñaTemp
    $hashContraseñaTemp = '';

    // Verificar si se proporcionó una nueva contraseña temporal
    if (!empty($_POST['contraseñaTemp'])) {
        // Obtener la nueva contraseña temporal desde el formulario
        $nuevaContraseñaTemp = $_POST['contraseñaTemp'];

        // Verificar si la nueva contraseña cumple con los requisitos
        if (preg_match('/^(?=.*[A-Za-z\d$@$!%*?&]).{8,}$/', $nuevaContraseñaTemp)) {
            // La contraseña cumple con los requisitos

            // Encriptar la nueva contraseña temporal
            $hashContraseñaTemp = password_hash($nuevaContraseñaTemp, PASSWORD_DEFAULT);
        } else {
            // La contraseña no cumple con los requisitos
            $errorMsg = "La contraseña temporal debe contener al menos 8 caracteres, incluyendo letras mayúsculas, letras minúsculas, números y caracteres especiales.";
        }
    }

    // Construir la consulta SQL de actualización
    $sql = "UPDATE tbl_ms_usuario SET 
            nombre_usuario = '$nuevoNombre',
            rol = '$nuevoRol',
            correo_electronico = '$nuevoCorreo',
            estado = '$nuevoEstado'";
    
    // Si se proporcionó una nueva contraseña temporal válida, agregarla a la consulta
    if (!empty($hashContraseñaTemp)) {
        $sql .= ", contraseñaTemp = '$hashContraseñaTemp'";
    }
    
    $sql .= " WHERE id_usuario = $idUsuario";

    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página de detalle de usuarios después de la actualización
        header("Location: ../usuario/usuario.php");
        exit();
    } else {
        // Manejar errores en caso de falla en la actualización
        $errorMsg = "Error en la actualización: " . $conn->error;
    }
}
// ...


// Obtener el ID del usuario a editar desde la URL
if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];

    // Construir la consulta SQL para obtener los datos del usuario con el nombre del rol
    $sqlUsuario = "SELECT u.*, IFNULL(r.NOMBRE_ROL, 'Sin rol asignado') AS nombre_rol FROM tbl_ms_usuario u LEFT JOIN tbl_ms_roles r ON u.rol = r.ID_ROL WHERE u.id_usuario = $idUsuario";

    // Ejecutar la consulta SQL
    $resultUsuario = $conn->query($sqlUsuario);

    if ($resultUsuario->num_rows > 0) {
        // Obtener los datos del usuario con el nombre del rol
        $rowUsuario = $resultUsuario->fetch_assoc();

        // Asignar los valores a variables
        $nombreUsuario = $rowUsuario['nombre_usuario'];
        $nombreRolActual = $rowUsuario['nombre_rol'];
        $correoElectronico = $rowUsuario['correo_electronico'];
        $fechaCreacion = $rowUsuario['fecha_creacion']; // Agregar esta línea para obtener la fecha de creación
        $estado = $rowUsuario['estado'];
        $contraseñaTemp = $rowUsuario['contraseñaTemp'];

        // A continuación, muestra los datos en el formulario de edición
    } else {
        // Manejar el caso en que no se encuentre ningún usuario con el ID proporcionado
        header("Location: ../usuario/usuarios.php");
        exit();
    }
} else {
    // Si no se proporciona un ID de usuario válido, muestra un mensaje de error o redirige a la página de detalle de usuarios
    header("Location: ../usuario/usuarios.php");
    exit();
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
    
       <form method="post" action="">
          <input type="hidden" name="id_usuario" value="<?php echo $idUsuario; ?>">
        
            <div class="form-row">
              <label for="nombre_usuario">Usuario:</label>
               <input type="text" name="nombre_usuario" id="nombre_usuario" value="<?php echo $nombreUsuario; ?>"><br>
            </div>

            <div class="form-row">
              <label for="rol">Rol:</label>
                <select name="rol" id="rol">
                  <option value="0" <?php if ($nombreRolActual == 'Sin rol asignado') echo 'selected'; ?>>Sin rol</option>
                    <?php
                        // Construir una consulta SQL para obtener la lista de roles disponibles
                        $sqlRoles = "SELECT ID_ROL, NOMBRE_ROL FROM tbl_ms_roles";

                        // Ejecutar la consulta SQL
                        $resultRoles = $conn->query($sqlRoles);

                        // Verificar si hay roles disponibles
                       if ($resultRoles->num_rows > 0) {
                            while ($rowRol = $resultRoles->fetch_assoc()) {
                               $idRol = $rowRol['ID_ROL'];
                               $nombreRol = $rowRol['NOMBRE_ROL'];

                               // Comprobar si este es el rol actualmente seleccionado
                               $selected = ($nombreRol == $nombreRolActual) ? 'selected' : '';

                              // Mostrar una opción para cada rol disponible
                              echo "<option value='$idRol' $selected>$nombreRol</option>";
                            }
                        }
                    ?>
                </select><br>
            </div>

            <div class="form-row">
               <label for="correo_electronico">Email:</label>
               <input type="email" name="correo_electronico" id="correo_electronico" value="<?php echo $correoElectronico; ?>"><br>
            </div>

           <div class="form-row">
                <label for="fecha_creacion">Fecha Creación:</label>
                <input type="text" name="fecha_creacion" id="fecha_creacion" value="<?php echo $fechaCreacion; ?>" readonly><br>
            </div>

            <div class="form-row">
                <label for="fecha_modificacion">Fecha Modificación:</label>
                <input type="text" name="fecha_modificacion" id="fecha_modificacion" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly><br>
            </div>

           <div class="form-row">
              <label for="estado">Estado:</label>
               <select name="estado" id="estado">
                  <option value="" <?php if (empty($estado)) echo 'selected'; ?>>--Seleccione--</option>
                  <option value="A" <?php if ($estado == 'A') echo 'selected'; ?>>Activo</option>
                   <option value="I" <?php if ($estado == 'I') echo 'selected'; ?>>Inactivo</option>
                   <option value="B" <?php if ($estado == 'B') echo 'selected'; ?>>Bloqueado</option>
                   <option value="N" <?php if ($estado == 'N') echo 'selected'; ?>>Nuevo</option>
                </select><br>

            </div>

            <div class="form-row">
              <label for="contraseñaTemp">Contraseña Temporal:</label>
               <input type="password" name="contraseñaTemp" id="contraseñaTemp" value="<?php echo isset($nuevaContraseñaTemp) ? $nuevaContraseñaTemp : '' ?>"><br>
            </div>

        
          <!-- Agrega otros campos de formulario según tus necesidades -->
        
            <div class="form-row button-container">
              <button type="submit" name="editar">Guardar</button>
              <a href="usuario.php" class="btn btn-secondary">Cancelar</a>
            </div>
    
        </form>

    </div> 
</body>

</html>
