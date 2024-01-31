<?php
session_start();
include("../conexion/conexion.php");

// Obtén el ID de usuario de la sesión
$userId = $_SESSION["usuarioId"];

// Consulta el máximo de preguntas permitidas desde la tabla tbl_ms_parametros
$sqlParametro = "SELECT VALOR FROM tbl_ms_parametros WHERE PARAMETRO = 'preguntas_seguridad'";
$resultParametro = $conn->query($sqlParametro);
$maxPreguntas = 3; // Valor predeterminado si no se encuentra en la base de datos

if ($resultParametro->num_rows > 0) {
    $rowParametro = $resultParametro->fetch_assoc();
    $maxPreguntas = intval($rowParametro["VALOR"]);
}

// Consulta todas las preguntas de seguridad disponibles desde la tabla tbl_preguntas
$sqlPreguntas = "SELECT ID_PREGUNTA, PREGUNTA FROM tbl_preguntas";
$resultPreguntas = $conn->query($sqlPreguntas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Preguntas de Seguridad</title>
  

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
          background-color: #FFA500; /* Cambia el color de fondo a anaranjado */
          color: #fff;
          border: none;
          padding: 10px 20px;
          border-radius: 4px;
          font-size: 18px;
          cursor: pointer;
        }

        input[type="submit"]:hover {
          background-color: #FF8C00; /* Cambia el color de fondo al pasar el cursor */
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

           /* Estilo para el botón "Cancelar" */
          .cancel-button {
             background-color: #FF0000; /* Cambia el color de fondo a rojo */
             color: #fff;
             border: none;
             padding: 10px 20px;
             border-radius: 4px;
             font-size: 18px;
              cursor: pointer;
            }

    </style>
</head>
<body>
    

    <?php
    if (isset($_SESSION["preguntaSeguridadError"])) {
        echo "<p style='color: red;'>".$_SESSION["preguntaSeguridadError"]."</p>";
        unset($_SESSION["preguntaSeguridadError"]);
    }
    ?>

    <form action="procesar_preguntas.php" method="POST">
        <div class="form-header">
           <h2>Agregar Preguntas de Seguridad</h2>
        </div>
        <p>Seleccione sus <?php echo $maxPreguntas; ?> preguntas de seguridad:</p>

        <?php
        for ($i = 1; $i <= $maxPreguntas; $i++) {
            echo "<div class='form-group'>";
            
            echo "<label for='pregunta_".$i."'>Pregunta #".$i.":</label>";
            echo "<select id='pregunta_".$i."' name='preguntas_seguridad[]' required>";
            echo "<option value=''>Seleccione una pregunta</option>";
            // Aquí puedes agregar las opciones de preguntas
            while ($rowPregunta = $resultPreguntas->fetch_assoc()) {
                echo "<option value='".$rowPregunta["ID_PREGUNTA"]."'>".$rowPregunta["PREGUNTA"]."</option>";
            }
            echo "</select>";
            echo "</div>";
        

            echo "</select>";
            echo "<div class='form-group'>"; // Agrega un contenedor para la respuesta
            echo "<label for='respuesta_".$i."'>Respuesta:</label>";
            echo "<input type='text' id='respuesta_".$i."' name='respuestas_seguridad[]' required>";
            echo "</div>"; // Cierra el contenedor de respuesta
            echo "<br>";
            echo "</div>"; // Cierra el contenedor de pregunta
            // Reiniciar el puntero de resultados para mostrar las preguntas nuevamente
            $resultPreguntas->data_seek(0);
        }
        ?>

        <!-- Botón "Cambiar Contraseña" con la clase CSS "change-password-button" -->
        <input type="submit" name="Guardar" value="Guardar" class="change-password-button">
         <!-- Botón "Cancelar" con la clase CSS "cancel-button" -->
        <input type="button" value="Cancelar" onclick="window.location.href='contraseña_perfil.php';" class="cancel-button">
    
    </form>
</body>
</html>






