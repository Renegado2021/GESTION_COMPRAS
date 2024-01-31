<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI - Crear Pregunta</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
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

        .container {
           background-color: rgba(245, 245, 220, 0.9); /* Beige con transparencia */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center; /* Centra el contenido horizontalmente */
            margin: 0 auto; /* Centra el formulario en la página */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2><i class="fas fa-question"></i> Crear Pregunta</h2>
        
        <?php
           // Iniciar la sesión si aún no se ha iniciado
          session_start();

         // Procesar el formulario cuando se envía
           if ($_SERVER["REQUEST_METHOD"] == "POST") {
              // Incluir el archivo de conexión a la base de datos
              include_once('../conexion/conexion.php');

              // Obtener la pregunta del formulario y normalizarla (convertir a minúsculas)
              $pregunta = strtolower($_POST["pregunta"]);

              // Verifica si el usuario está autenticado antes de acceder a $_SESSION['nombre_usuario']
              $nombre_usuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : 'Usuario Desconocido';

              // Consulta SQL para verificar si la pregunta ya existe
              $verificarDuplicadoSql = "SELECT ID_PREGUNTA FROM tbl_preguntas WHERE LOWER(PREGUNTA) = ?";
              $stmtVerificarDuplicado = $conn->prepare($verificarDuplicadoSql);
              $stmtVerificarDuplicado->bind_param("s", $pregunta);
              $stmtVerificarDuplicado->execute();
              $stmtVerificarDuplicado->store_result();

               if ($stmtVerificarDuplicado->num_rows > 0) {
                 echo "<div class='alert alert-danger' role='alert'>La pregunta ya existe en la base de datos.</div>";
                } else {
                  // La pregunta no existe, proceder con la inserción
                  // Consulta SQL para insertar la pregunta en la base de datos con la fecha de creación automática
                  $sql = "INSERT INTO tbl_preguntas (PREGUNTA, CREADO_POR, FECHA_CREACION) VALUES (?, ?, NOW())";

                  // Preparar la consulta
                  $stmt = $conn->prepare($sql);

                  // Vincular los parámetros
                  $stmt->bind_param("ss", $_POST["pregunta"], $nombre_usuario);

                 // Ejecutar la consulta
                   if ($stmt->execute()) {
                       echo "<div class='alert alert-success' role='alert'>Pregunta agregada correctamente.</div>";
                      // Redirigir a preguntas.php después de 2 segundos
                      header("refresh:2;url=preguntas.php");
                    } else {
                     echo "<div class='alert alert-danger' role='alert'>Error al agregar la pregunta: " . $stmt->error . "</div>";
                    }

                  // Cerrar la declaración
                  $stmt->close();
                }

             // Cerrar la conexión
              $conn->close();
            }
        ?>
        
        <!-- Formulario para crear una nueva pregunta -->
        <form method="POST">
            <div class="mb-3">
                <label for="pregunta" class="form-label">Pregunta:</label>
                <textarea class="form-control" id="pregunta" name="pregunta" rows="4" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="../preguntas/preguntas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-lUbWEJ9B6VCWfZz3D0l2Gl04S8JdZ6wDma/5H5APqJ8/0pP5t3FTca5wE3W2ZbqJ" crossorigin="anonymous"></script>
</body>

</html>