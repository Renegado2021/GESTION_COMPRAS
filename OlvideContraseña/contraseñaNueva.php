<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar los valores de nueva_contrasena y confirmar_contrasena
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Realizar validaciones adicionales si es necesario
    if (strlen($nueva_contrasena) < 8) {
        echo "La contraseña debe tener al menos 8 caracteres.";
    } elseif (!preg_match("/[A-Z]/", $nueva_contrasena)) {
        echo "La contraseña debe contener al menos una letra mayúscula.";
    } elseif (!preg_match("/[a-z]/", $nueva_contrasena)) {
        echo "La contraseña debe contener al menos una letra minúscula.";
    } elseif (!preg_match("/[0-9]/", $nueva_contrasena)) {
        echo "La contraseña debe contener al menos un número.";
    } elseif (!preg_match("/[^a-zA-Z0-9]/", $nueva_contrasena)) {
        echo "La contraseña debe contener al menos un carácter especial.";
    } elseif ($nueva_contrasena !== $confirmar_contrasena) {
        echo "Las contraseñas no coinciden. Inténtalo de nuevo.";
    } else {
        // La contraseña cumple con los requisitos, encriptarla
        $nombre_usuario = $_POST['nombre_usuario'];
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        
        // Actualizar la contraseña en la base de datos
        require_once("../conexion/conexion.php"); // Asegúrate de tener la conexión a la base de datos.
        
        $update_query = "UPDATE tbl_ms_usuario SET contraseña = ? WHERE nombre_usuario = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $nombre_usuario);
        
        if (mysqli_stmt_execute($stmt)) {
            // Contraseña actualizada con éxito, puedes redirigir al usuario a una página de éxito o inicio de sesión
            header("Location: ../index.php");
        } else {
            echo "Error al actualizar la contraseña en la base de datos.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cambiar Contraseña</title>
    <style>

       /* Estilos para el contenedor principal */

       body {
    font-family: Arial, sans-serif;
    background-image: url('../imagen/imagen1.jpg'); /* Reemplaza 'ruta_de_la_imagen.jpg' con la ruta de tu imagen de fondo */
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

        #recoveryContainer {
            max-width: 500px; /* Ajusta el ancho máximo del contenedor */
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: center;
        }

        /* Estilos para el encabezado */
        #recoveryContainer h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #007bff; /* Cambia este valor al color que desees */
        }

     


        /* Estilos para el botón */
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }






    /* Agrega estos estilos para alinear etiquetas y campos horizontalmente */
.form-group {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

/* Aplicar estilos a todas las etiquetas de formulario para que tengan el mismo tamaño */
.form-group label {
    flex-basis: 30%; /* Ancho fijo para etiquetas */
    text-align: right; /* Alinea el texto de las etiquetas a la derecha */
    padding-right: 10px; /* Espacio entre la etiqueta y el campo */
    width: 150px; /* Ancho fijo para las etiquetas */
    display: inline-block; /* Hace que las etiquetas tengan el mismo tamaño y estén en línea */
}


/* Estilo para hacer los campos de contraseña más anchos */
.form-group input[type="password"] {
    width: 300%; /* Ancho al 100% del contenedor */
    padding: 10px; /* Espacio alrededor del campo */
    box-sizing: border-box; /* Incluye el padding en el ancho total */
}




    </style>
</head>
<body>
  <div id="recoveryContainer">
        <h2>Cambiar Contraseña</h2>
        <form method="POST" action="">
            <input type="hidden" name="nombre_usuario" value="<?php echo $_GET['nombre_usuario']; ?>">
            <div class="form-group">         
             <label for="nueva_contrasena">Nueva Contraseña:</label>
             <input type="password" name="nueva_contrasena" required>
            </div>
         
            <div class="form-group"> 
             <label for="confirmar_contrasena">Confirmar Contraseña:</label>
             <input type="password" name="confirmar_contrasena" required>
            </div>      
             
            <input type="submit" name="cambiar_contraseña" value="Guardar">
        </form>

    </div>
</body>
</html>