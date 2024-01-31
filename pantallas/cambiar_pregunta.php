<?php
session_start();

// Incluye la conexión a la base de datos
include("../conexion/conexion.php");

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene las respuestas y las nuevas preguntas de seguridad
    $respuesta1 = $_POST["respuesta1"];
    $respuesta2 = $_POST["respuesta2"];

    // Obtén las nuevas preguntas seleccionadas desde el formulario
    $nuevaPregunta1 = $_POST["pregunta1"];
    $nuevaPregunta2 = $_POST["pregunta2"];

    // Obtén el ID de usuario de la sesión
    $usuarioId = $_SESSION["usuarioId"];

    // Elimina las preguntas y respuestas anteriores en la tabla tbl_user_pregunta
    $deleteSql = "DELETE FROM tbl_user_pregunta WHERE ID_USER = ?";
    $stmtDelete = $conn->prepare($deleteSql);
    $stmtDelete->bind_param("i", $usuarioId);
    $stmtDelete->execute();

    // Prepara las respuestas encriptadas utilizando password_hash()
    $hashRespuesta1 = password_hash($respuesta1, PASSWORD_DEFAULT);
    $hashRespuesta2 = password_hash($respuesta2, PASSWORD_DEFAULT);

    // Inserta las nuevas preguntas y respuestas en la tabla tbl_user_pregunta
    $insertSql = "INSERT INTO tbl_user_pregunta (ID_USER, ID_PREGUNTA, RESPUESTA) VALUES (?, ?, ?)";
    $stmtInsert = $conn->prepare($insertSql);

     // Inserta la Pregunta 1 y su respuesta encriptada
     $stmtInsert->bind_param("iis", $usuarioId, $nuevaPregunta1, $hashRespuesta1);
     $stmtInsert->execute();
 
     // Inserta la Pregunta 2 y su respuesta encriptada
     $stmtInsert->bind_param("iis", $usuarioId, $nuevaPregunta2, $hashRespuesta2);
     $stmtInsert->execute();

    // Verifica si se realizaron las operaciones correctamente
    if ($stmtDelete->affected_rows > 0 && $stmtInsert->affected_rows > 0) {
        
        // Redirige al usuario a la página de perfil o a donde desees
        header("Location: ../pantallas/admin.php");
        exit();

    } else {
        $_SESSION["questionsChangeError"] = "Error al cambiar las preguntas de seguridad.";
        // Redirige al usuario a la página de perfil o a donde desees
        header("Location: ../pantallas/cambiar_pregunta.php");
        exit();
    
    }

    
}

    // Obtén la lista de preguntas de seguridad desde la base de datos (ajusta esto según tu estructura)
    $sqlPreguntas = "SELECT ID_PREGUNTA, PREGUNTA FROM tbl_preguntas";
    $resultadoPreguntas = $conn->query($sqlPreguntas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Preguntas de Seguridad</title>
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

/* Estilo para el botón "Cambiar Contraseña" */
.change-password-button {
    background-color: #FFA500; /* Cambia el color de fondo a anaranjado */
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 18px;
    cursor: pointer;
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


input[type="submit"]:hover {
    background-color: #FF8C00; /* Cambia el color de fondo al pasar el cursor */
}



    </style>
</head>
<body>
    

    <?php
    // Muestra mensajes de éxito o error, si los hay
    if (isset($_SESSION["questionsChangeSuccess"])) {
        echo "<p style='color: green;'>".$_SESSION["questionsChangeSuccess"]."</p>";
        unset($_SESSION["questionsChangeSuccess"]);
    }
    if (isset($_SESSION["questionsChangeError"])) {
        echo "<p style='color: red;'>".$_SESSION["questionsChangeError"]."</p>";
        unset($_SESSION["questionsChangeError"]);
    }
    ?>

    <!-- Formulario para cambiar las preguntas de seguridad -->
    <form action="cambiar_pregunta.php" method="POST">
        <div class="form-header">
        <h2>Cambiar Preguntas de Seguridad</h2>
        </div>
        <div class="form-group">
            <label for="pregunta1">Pregunta 1:</label>
            <select id="pregunta1" name="pregunta1">
             <option value="">-- Seleccione --</option>
                <?php
                // Genera opciones para la lista desplegable con las preguntas de seguridad
                while ($filaPregunta = $resultadoPreguntas->fetch_assoc()) {
                    echo "<option value='{$filaPregunta["ID_PREGUNTA"]}'>{$filaPregunta["PREGUNTA"]}</option>";
                }
                ?>
            </select><br><br>
           
        </div>
        <div class="form-group">
            <label for="respuesta1">Respuesta:</label>
            <input type="text" id="respuesta1" name="respuesta1" required placeholder="Nueva respuesta "><br><br>
        </div>
        <div class="form-group">
            <label for="pregunta2">Pregunta 2:</label>
            <select id="pregunta2" name="pregunta2">
             <option value="">-- Seleccione --</option>
                <?php
                // Reinicia el cursor del resultado de preguntas
                $resultadoPreguntas->data_seek(0);
                
                // Genera opciones para la lista desplegable con las preguntas de seguridad
                while ($filaPregunta = $resultadoPreguntas->fetch_assoc()) {
                    echo "<option value='{$filaPregunta["ID_PREGUNTA"]}'>{$filaPregunta["PREGUNTA"]}</option>";
                }
                ?>
            </select><br><br>
            
        </div>

        <div class="form-group">
         <label for="respuesta2">Respuesta:</label>
         <input type="text" id="respuesta2" name="respuesta2" required placeholder="Nueva respuesta "><br><br>
        </div>
        <!-- Puedes agregar más preguntas y respuestas según sea necesario -->

        <!-- Botón para enviar el formulario y cambiar las preguntas de seguridad -->
        <input type="submit" name="Cambiar" value="Cambiar" class="change-password-button">

         <!-- Botón "Cancelar" con la clase CSS "cancel-button" -->
       <input type="button" value="Cancelar" onclick="window.location.href='contraseña_perfil.php';" class="cancel-button"><br><br>
    </form>
</body>
</html>

