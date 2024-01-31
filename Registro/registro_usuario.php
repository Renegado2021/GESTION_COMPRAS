<?php
// Incluir el archivo de conexión
include('../conexion/conexion.php');

// Verificar si se ha enviado el formulario de la primera página
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['siguiente'])) {
    // Validar y recoger los datos del formulario
    $nombres = strtoupper($_POST['nombres']); // Convertir a mayúsculas
    $apellidos = strtoupper($_POST['apellidos']); // Convertir a mayúsculas
    $email = $_POST['email'];
    $usuario = strtoupper($_POST['usuario']); // Convertir a mayúsculas
    $contrasena = $_POST['contrasena']; // Contraseña sin cambios

     // Verificar que la contraseña cumple con los requisitos
     if (strlen($contrasena) >= 8) {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT); // Encriptar la contraseña
        $fecha_creacion = date('Y-m-d H:i:s'); // Obtener la fecha actual

        // Insertar los datos en la tabla tbl_ms_usuario
        $sql = "INSERT INTO tbl_ms_usuario (nombres, apellidos, nombre_usuario, contraseña, correo_electronico, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $nombres, $apellidos, $usuario, $contrasena_hash, $email, $fecha_creacion);
        if ($stmt->execute()) {
            // Redirigir a la segunda página con el ID de usuario
            $id_usuario = $stmt->insert_id;
            header("Location: ../Registro/registro_preguntas.php?id_usuario=$id_usuario");
            exit;
        } else {
            echo "Error al registrar el usuario.";
        }
    } else {
        echo "La contraseña debe tener al menos 8 caracteres y contener letras minúsculas, letras mayúsculas, números y al menos uno de los siguientes signos: @ # $ % ^ & !";
    }
}

// HTML del formulario de la primera página

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro - Paso 1</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-image: url('../imagen/imagen1.jpg'); /* Ruta de tu imagen de fondo */
            background-size: cover; /* Ajusta el tamaño de la imagen al contenedor */
            background-position: center; /* Centra la imagen en el contenedor */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            text-align: center;
            margin: 0;
            padding: 0;
        }

       /* Estilos para el formulario */
       form {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente para el formulario */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            margin: 20px auto; /* Ajusta el margen para centrar el formulario */
            text-align: left;
        }

        .form-header {
          text-align: center;
          margin-bottom: 10px; /* Reduce el margen inferior entre los encabezados */
          font-family: 'TuTipoDeLetra', sans-serif; /* Cambia 'TuTipoDeLetra' por el tipo de letra deseado */
        }

        .form-header h2 {
          color: #007BFF; /* Color azul para el primer encabezado */
          margin: 0; /* Elimina el margen superior e inferior del h2 */
        }

       .form-header h3 {
          color: #87CEEB; /* Color azul cielo para el segundo encabezado */
          margin: 0; /* Elimina el margen superior e inferior del h3 */
        }


      .form-group {
         display: flex;
         align-items: center;
         margin-bottom: 20px; /* Aumenta el espacio entre cada grupo a 20px */
        }

      .form-group label {
         width: 100px;
         color: #555;
        }

       .form-group input[type="text"],
       .form-group input[type="email"],
       .form-group input[type="password"] {
           flex: 1;
           padding: 10px;
            border: 1px solid #ccc;
           border-radius: 4px;
           font-size: 16px;
        }

      /* Elementos de envío y cancelación en la misma fila */
      .form-buttons {
         display: flex;
          justify-content: space-between;
         align-items: center;
        }

       input[type="submit"] {
          background-color: grey; /* Cambia el color de fondo */
          color: #fff;
          border: none;
           padding: 10px 20px;
          border-radius: 4px;
          font-size: 18px;
          cursor: pointer;
        }

        a.cancel-button {
          background-color: grey;
          color: #fff;
          text-decoration: none;
          padding: 10px 20px;
          border-radius: 4px;
          font-size: 18px;
          margin-right: 10px; /* Añade margen derecho para separar los botones */
        }



    </style>
</head>
<body>

    <form method="POST">
        <div class="form-header">
          <h2>Registro de Usuario</h2>
          <h3>Paso 1 </h3>
        </div>

        <div class="form-group">
            <label for="nombres">Nombres:</label>
            <input type="text" name="nombres" id="nombres" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" required>
        </div>

        <input type="submit" name="siguiente" value="Siguiente">
        <a class="cancel-button" href="../index.php">Cancelar</a>
    </form>



</body>
</html>

