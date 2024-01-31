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

// Inicializa la variable $preguntaActual
$preguntaActual = 1;

// Verificar si el usuario ya tiene preguntas
$sqlUsuarioPreguntas = "SELECT ID_PREGUNTA FROM tbl_user_pregunta WHERE ID_USER = $userId LIMIT 1";
$resultUsuarioPreguntas = $conn->query($sqlUsuarioPreguntas);
$tienePreguntas = $resultUsuarioPreguntas->num_rows > 0;
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
            background-image: url('../imagen/imagen1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            margin: 20px auto;
            text-align: left;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            width: 50%;
            color: #555;
            font-size: 16px;
            margin-right: -60px; /* Agrega margen derecho para separar las etiquetas de los campos */
            text-align: left; /* Alinea el texto de la etiqueta a la derecha */
        }

        .form-group select, .form-group input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
        }

        .form-header {
            text-align: center;
            margin-bottom: 10px;
            font-family: 'TuTipoDeLetra', sans-serif;
        }

        .form-header h2 {
            color: #007BFF;
            margin: 0;
        }

        /* Estilo global para los botones */
        input[type="button"] {
            width: 100px;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            color: white;
            margin-right: 10px; /* Agregar un margen derecho para separar los botones */
        }

        /* Estilo para el botón "Siguiente" */
        .siguiente-button {
            background-color: #007BFF;
        }

        /* Estilo para el botón "Cancelar" en la primera pregunta */
        .cancel-button-1 {
            background-color: #FF5733; /* Color de fondo para el botón "Cancelar" de la primera pregunta */
        }

        /* Estilo para el botón "Guardar" en la segunda pregunta */
        .save-button-2 {
            background-color: #007BFF; /* Color de fondo para el botón "Guardar" de la segunda pregunta */
            color: white; /* Cambia el color del texto a blanco */
            width: 100px; /* Establece el ancho del botón a 100 píxeles (ajusta según tus necesidades) */
            border: none; /* Elimina el contorno del botón */
            margin-right: 10px; /* Agrega un margen derecho para separar los botones */
        }

        /* Estilo para el botón "Cancelar" en la segunda pregunta */
        .cancel-button-2 {
            background-color: #FF5733; /* Color de fondo para el botón "Cancelar" de la segunda pregunta */
        }
    </style>
</head>
<body>
    <form action="procesar_preguntas.php" method="POST">
        <div class="form-header">
            <h2>Preguntas de Seguridad</h2>
        </div>

        <?php if (!$tienePreguntas): ?>
            <!-- Primera pregunta -->
            <div class="form-group" id="pregunta_1">
                    <label for="pregunta1">Pregunta <?php echo $preguntaActual; ?> de <?php echo $maxPreguntas; ?>:</label>
                    <select id="pregunta1" name="preguntas_seguridad[]" required>
                        <option value="">Seleccione una pregunta</option>
                        <?php
                        // Agregar opciones de preguntas desde la base de datos
                        $resultPreguntas->data_seek(0); // Restablecer el puntero de resultados
                        while ($rowPregunta = $resultPreguntas->fetch_assoc()) {
                            echo "<option value='" . $rowPregunta["ID_PREGUNTA"] . "'>" . $rowPregunta["PREGUNTA"] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" id="respuesta_1">
                    <label for="respuesta1">Respuesta:</label>
                    <input type="text" id="respuesta1" name="respuestas_seguridad[]" required>
                </div>

                <!-- Botones "Next" y "Cancelar" en la misma fila (inicialmente visibles) -->
                <div class="form-buttons" id="botones_1">
                    <input type="button" value="Siguiente" class="siguiente-button" onclick="mostrarSiguientePregunta();">
                    <input type="button" value="Cancelar" class="cancel-button-1" onclick="redirigirAAdmin();">
                </div>

                <!-- Segunda pregunta (inicialmente oculta) -->
                <div class="form-group" id="pregunta_2" style="display: none;">
                    <label for="pregunta2">Pregunta <?php echo $preguntaActual + 1; ?> de <?php echo $maxPreguntas; ?>:</label>
                    <select id="pregunta2" name="preguntas_seguridad[]" required onchange="verificarPreguntasDiferentes();">
                        <option value="">Seleccione una pregunta</option>
                        <?php
                        $resultPreguntas->data_seek(0); // Restablecer el puntero de resultados
                        while ($rowPregunta = $resultPreguntas->fetch_assoc()) {
                            echo "<option value='" . $rowPregunta["ID_PREGUNTA"] . "'>" . $rowPregunta["PREGUNTA"] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" id="respuesta_2" style="display: none;">
                    <label for="respuesta2">Respuesta:</label>
                    <input type="text" id="respuesta2" name="respuestas_seguridad[]" required style="width: 95%;">
                </div>

                <!-- Botones "Guardar" y "Cancelar" en la misma fila (inicialmente ocultos) -->
                <div class="form-buttons" id="botones_2" style="display: none;">
                    <input type="submit" value="Guardar" class="save-button-2">
                    <input type="button" value="Cancelar" class="cancel-button-2" onclick="regresarAPregunta1();">
                </div>
            </div>
        <?php else: ?>
            <p>¿Desea cambiar sus preguntas de seguridad actuales?</p>
           
            <div class="form-group">
                <input type="radio" name="cambiar_preguntas" value="si" id="cambiar_si" onclick="mostrarPreguntas();">
                <label for="cambiar_si">Sí</label>
                <input type="radio" name="cambiar_preguntas" value="no" id="cambiar_no" onclick="redirigirAAdmin();">
                <label for="cambiar_no">No</label>
            </div>
            <div id="preguntas_container" style="display: none;">
                <!-- Primera pregunta -->
                <div class="form-group" id="pregunta_1">
                    <label for="pregunta1">Pregunta <?php echo $preguntaActual; ?> de <?php echo $maxPreguntas; ?>:</label>
                    <select id="pregunta1" name="preguntas_seguridad[]" required>
                        <option value="">Seleccione una pregunta</option>
                        <?php
                        // Agregar opciones de preguntas desde la base de datos
                        $resultPreguntas->data_seek(0); // Restablecer el puntero de resultados
                        while ($rowPregunta = $resultPreguntas->fetch_assoc()) {
                            echo "<option value='" . $rowPregunta["ID_PREGUNTA"] . "'>" . $rowPregunta["PREGUNTA"] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" id="respuesta_1">
                    <label for="respuesta1">Respuesta:</label>
                    <input type="text" id="respuesta1" name="respuestas_seguridad[]" required>
                </div>

                <!-- Botones "Next" y "Cancelar" en la misma fila (inicialmente visibles) -->
                <div class="form-buttons" id="botones_1">
                    <input type="button" value="Siguiente" class="siguiente-button" onclick="mostrarSiguientePregunta();">
                    <input type="button" value="Cancelar" class="cancel-button-1" onclick="redirigirAAdmin();">
                </div>

                <!-- Segunda pregunta (inicialmente oculta) -->
                <div class="form-group" id="pregunta_2" style="display: none;">
                    <label for="pregunta2">Pregunta <?php echo $preguntaActual + 1; ?> de <?php echo $maxPreguntas; ?>:</label>
                    <select id="pregunta2" name="preguntas_seguridad[]" required onchange="verificarPreguntasDiferentes();">
                        <option value="">Seleccione una pregunta</option>
                        <?php
                        $resultPreguntas->data_seek(0); // Restablecer el puntero de resultados
                        while ($rowPregunta = $resultPreguntas->fetch_assoc()) {
                            echo "<option value='" . $rowPregunta["ID_PREGUNTA"] . "'>" . $rowPregunta["PREGUNTA"] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" id="respuesta_2" style="display: none;">
                    <label for="respuesta2">Respuesta:</label>
                    <input type="text" id="respuesta2" name="respuestas_seguridad[]" required style="width: 95%;">
                </div>

                <!-- Botones "Guardar" y "Cancelar" en la misma fila (inicialmente ocultos) -->
                <div class="form-buttons" id="botones_2" style="display: none;">
                    <input type="submit" value="Guardar" class="save-button-2">
                    <input type="button" value="Cancelar" class="cancel-button-2" onclick="regresarAPregunta1();">
                </div>
            </div>
        <?php endif; ?>
       
    </form>
    <script>
        var preguntaActual = 1;

        function redirigirAAdmin() {
           // Redirigir a admin.php
           window.location.href = 'admin.php';
        }

        function mostrarSiguientePregunta() {
                if (preguntaActual === 1) {
                    // Validar que se haya seleccionado una pregunta y se haya ingresado una respuesta
                    var pregunta1 = document.getElementById('pregunta1').value;
                    var respuesta1 = document.getElementById('respuesta1').value;

                    if (pregunta1 === '' || respuesta1 === '') {
                        alert('Por favor, complete la pregunta y la respuesta antes de continuar.');
                        return;
                    }

                    // Oculta los elementos de la pregunta 1
                    document.getElementById('pregunta_1').style.display = 'none';
                    document.getElementById('respuesta_1').style.display = 'none';
                    document.getElementById('botones_1').style.display = 'none';

                    // Muestra los elementos de la pregunta 2
                    document.getElementById('pregunta_2').style.display = 'flex';
                    document.getElementById('respuesta_2').style.display = 'flex';
                    document.getElementById('botones_2').style.display = 'flex';

                    preguntaActual = 2;
                }
            }

        function regresarAPregunta1() {
            document.getElementById('pregunta_2').style.display = 'none';
            document.getElementById('respuesta_2').style.display = 'none';
            document.getElementById('botones_2').style.display = 'none';

            // Muestra los elementos de la pregunta 1
            document.getElementById('pregunta_1').style.display = 'flex';
            document.getElementById('respuesta_1').style.display = 'flex';
            document.getElementById('botones_1').style.display = 'flex';

            preguntaActual = 1;
        }

        function verificarPreguntasDiferentes() {
            var pregunta1 = document.getElementById('pregunta1').value;
            var pregunta2 = document.getElementById('pregunta2').value;

            if (pregunta1 === pregunta2) {
                alert('Las dos preguntas no pueden ser iguales. Por favor, seleccione una pregunta diferente en la segunda lista desplegable.');
                // Restablece la selección en la segunda lista desplegable
                document.getElementById('pregunta2').value = "";
            }
        }

        function mostrarPreguntas() {
            document.getElementById('preguntas_container').style.display = 'block';
        }

       
    </script>
</body>
</html>









