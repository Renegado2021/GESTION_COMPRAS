<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recuperación de Contraseña</title>
    <style>
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


        .container {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            max-width: 400px; /* Ajusta el ancho a tu preferencia */
            width: 100%;
        }

        h3 {

            font-size: 14px; /* Ajusta el tamaño de la fuente a tu preferencia */
            color: #555;
        }

        h2 {
            text-align: center;
         font-size: 24px; /* Ajusta el tamaño de la fuente a tu preferencia */
          color: black;
        }

        p {
            text-align: center;
            font-size: 20px; /* Ajusta el tamaño de la fuente a tu preferencia */
            color: #333;
        }

        form {
            text-align: center;
        }

        input[type="text"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .error {
            color: red; /* Estilo de color rojo para mensajes de error */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        

        <?php
require_once("../conexion/conexion.php"); // Asegúrate de que este archivo tenga la conexión a la base de datos.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];

    // Consulta para verificar si existen preguntas de seguridad para el usuario
    $query = "SELECT COUNT(*) as count FROM tbl_user_pregunta up 
              INNER JOIN tbl_ms_usuario u ON up.ID_USER = u.id_usuario 
              WHERE u.nombre_usuario = '$nombre_usuario'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row['count'] > 0) {
            // Consulta para obtener las preguntas de seguridad del usuario
            $query = "SELECT up.ID_USER, up.ID_PREGUNTA, p.PREGUNTA, up.RESPUESTA 
                      FROM tbl_user_pregunta up 
                      INNER JOIN tbl_ms_usuario u ON up.ID_USER = u.id_usuario 
                      INNER JOIN tbl_preguntas p ON up.ID_PREGUNTA = p.ID_PREGUNTA 
                      WHERE u.nombre_usuario = '$nombre_usuario'";
            $result = mysqli_query($conn, $query);
            
            if ($result) {
                $preguntas = mysqli_fetch_all($result, MYSQLI_ASSOC);

                if (!empty($preguntas)) { // Comprobar si $preguntas contiene datos
                    $pregunta_actual = isset($_POST['pregunta_actual']) ? $_POST['pregunta_actual'] : 0;

                    if ($pregunta_actual < count($preguntas)) {
                        if (isset($_POST['respuesta'])) {
                            $respuesta = strtolower($_POST['respuesta']); // Convertir la respuesta a minúsculas
                            $respuesta_correcta = password_verify($respuesta, $preguntas[$pregunta_actual]['RESPUESTA']);

                            if ($respuesta_correcta) {
                                $pregunta_actual++;
                            } else {
                                echo "<h4 class='error'>Respuesta incorrecta. Inténtalo de nuevo.</h4>";
                            }
                        }

                        if ($pregunta_actual < count($preguntas)) { // Comprobar si hay preguntas disponibles
                            echo "<h3>Pregunta " . ($pregunta_actual + 1) . " de " . count($preguntas) . ":</h3>";
                            echo "<p>{$preguntas[$pregunta_actual]['PREGUNTA']}</p>";
                            echo "<form method='post' action=''>";
                            echo "<input type='hidden' name='nombre_usuario' value='$nombre_usuario'>";
                            echo "<input type='hidden' name='pregunta_actual' value='$pregunta_actual'>";
                            echo "<input type='text' name='respuesta' placeholder='Respuesta'>";
                            echo "<input type='submit' value='Siguiente'>";
                            echo "</form>";
                        } else {
                            echo "Todas las preguntas se han respondido correctamente. Puedes cambiar tu contraseña aquí.";
                            header("Location: contraseñaNueva.php?nombre_usuario=" . urlencode($nombre_usuario));
                        }
                    } else {
                        echo "Todas las preguntas se han respondido correctamente. Puedes cambiar tu contraseña aquí.";
                       
                    }
                } else {
                    echo "No hay preguntas de seguridad asociadas a este usuario en la tabla tbl_user_pregunta.";
                }
            } else {
                echo "Error en la consulta a la base de datos.";
            }
        } else {
            echo "No hay preguntas de seguridad asociadas a este usuario en la tabla tbl_user_pregunta.";
        }
    } else {
        echo "Error en la consulta a la base de datos.";
    }
}
?>
        

    </div>
</body>
</html>











