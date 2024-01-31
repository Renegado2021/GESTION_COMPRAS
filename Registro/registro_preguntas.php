<?php
 // Incluir el archivo de conexión
 include('../conexion/conexion.php');

  // Obtener el número de preguntas de seguridad desde la tabla tbl_ms_parametros
   $sql = "SELECT VALOR FROM tbl_ms_parametros WHERE PARAMETRO = 'preguntas_seguridad'";
   $result = $conn->query($sql);

   if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $num_preguntas_seguridad = intval($row['VALOR']);
    } else {
      // Si no se encuentra el valor en la tabla de parámetros, puedes asignar un valor predeterminado
      $num_preguntas_seguridad = 3; // Por ejemplo, 3 preguntas de seguridad por defecto
    }

    // Obtener todas las preguntas de seguridad desde la tabla tbl_preguntas
    $sql = "SELECT ID_PREGUNTA, PREGUNTA FROM tbl_preguntas";
    $result = $conn->query($sql);

    // Verificar si se ha enviado el formulario de la segunda página
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
        $id_usuario = $_POST['id_usuario'];
    
       // Recoger las preguntas de seguridad y respuestas
        $preguntas_seguridad = $_POST['preguntas_seguridad'];
         $respuestas = $_POST['respuestas'];

        // Insertar las preguntas y respuestas en la tabla tbl_user_pregunta
    
        $sql = "INSERT INTO tbl_user_pregunta (ID_USER, ID_PREGUNTA, RESPUESTA) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iis', $id_usuario, $pregunta_id, $respuesta_hash);
        

    
       foreach ($preguntas_seguridad as $index => $pregunta_id) {
          $respuesta = $respuestas[$index];
        
          // Realizar el hash de la respuesta usando SHA-256
          $respuesta_hash = password_hash($respuesta, PASSWORD_DEFAULT);

          $stmt->execute();
        }

        // Redirigir a una página de éxito o cualquier otra página que desees
        header("Location: ../index.php");
        exit;
    }

    // Obtener el ID de usuario de la URL
    $id_usuario = $_GET['id_usuario'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro - Paso 2</title>
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

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px; /* Espaciado entre grupos */
        }

        .form-group label {
            width: 40%; /* Ancho fijo para las etiquetas */
            color: #555;
            font-size: 16px; /* Tamaño de fuente para etiquetas */
        }

        .form-group select, .form-group input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px; /* Tamaño de fuente para campos de entrada y select */
            margin-left: -50px; /* Margen izquierdo para los campos de entrada */
            width: 100%; /* Ancho del 100% para los campos de entrada y select */
        }

       /* Elementos de envío y cancelación en la misma fila */
      .form-buttons {
         display: flex;
         justify-content: space-between;
         align-items: center;
        }

       input[type="submit"] {
         background-color: grey; /* Cambia el color de fondo a gris */
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

    </style>
</head>
<body>
    
    <form method="POST">
        <div class="form-header">
            <h2>Registro de Usuario</h2>
           <h3>Paso 2</h3>
        </div>

        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <p>Selecciona  <?php echo $num_preguntas_seguridad; ?> preguntas de seguridad:</p>
        
        <?php for ($counter = 1; $counter <= $num_preguntas_seguridad; $counter++) : ?>
            <div class="form-group">
                <label><?php echo "Pregunta #" . $counter . ":"; ?></label>
                <select name="preguntas_seguridad[]">
                    <option value="" selected>--Seleccione--</option>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <option value="<?php echo $row['ID_PREGUNTA']; ?>"><?php echo $row['PREGUNTA']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Respuesta:</label>
                <input type="text" name="respuestas[]" required>
            </div>
            
            <?php // Reiniciar el puntero de resultados para mostrar las preguntas nuevamente
                $result->data_seek(0);
            ?>
        <?php endfor; ?>
        
        <input type="submit" name="guardar" value="Guardar">
        <a class="cancel-button" href="../index.php">Cancelar</a>
    </form>
</body>
</html>


