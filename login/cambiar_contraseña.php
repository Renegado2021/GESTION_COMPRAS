<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../estilos.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    
<style>

    body {
        margin: 0;
        padding: 0;
        background-image: url('../imagen/imagen4.jpg'); /* Ruta de la imagen de fondo */
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }  
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

        .error-container {
            text-align: center; /* Centra el contenido horizontalmente */
            margin-top: 20px; /* Ajusta el margen superior según sea necesario */
        }
    </style>
    <title>Cambiar Contraseña</title>
</head>
<body>
   <div class="error-container">
    <?php
     session_start();
       // Verificar si se recibió el ID de usuario
        if (isset($_GET["id"])) {
           $userId = $_GET["id"];
        } else {
           echo "ID de usuario no especificado.";
           exit;
        }

        // Verificar si hay un mensaje de error en la variable de sesión
      if (isset($_SESSION["error_message"])) {
          echo '<p style="color: red;">' . $_SESSION["error_message"] . '</p>';
          // Luego, limpia la variable de sesión para que el mensaje no se muestre nuevamente
          unset($_SESSION["error_message"]);
        }

    ?>
   </div>
    <div class="form-container">
   
        <h1>Cambiar Contraseña</h1>
        <form action="../login/procesar_cambio_contraseña.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
            
            <!-- Campo para la nueva contraseña -->
            <div class="form-row">
                <label class="form-label" for="new_password">Nueva Contraseña:</label>
                <input class="form-input" type="password" name="new_password" required><br>
            </div>
            
            <div class="form-row button-container">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>
