<?php
session_start();
include("../conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén el ID de usuario de la sesión
    $userId = $_SESSION["usuarioId"];

    // Verifica si se enviaron preguntas y respuestas
    if (isset($_POST["preguntas_seguridad"]) && isset($_POST["respuestas_seguridad"])) {
        // Obtiene las preguntas seleccionadas y sus respuestas correspondientes
        $preguntasSeguridad = $_POST["preguntas_seguridad"];
        $respuestasSeguridad = $_POST["respuestas_seguridad"];

        // Elimina las preguntas y respuestas anteriores del usuario (si las hubiera)
        $sqlEliminar = "DELETE FROM tbl_user_pregunta WHERE ID_USER = ?";
        $stmtEliminar = $conn->prepare($sqlEliminar);
        $stmtEliminar->bind_param("i", $userId);
        $stmtEliminar->execute();

        // Inserta las nuevas preguntas y respuestas en la tabla tbl_user_preguntas
        $sqlInsertar = "INSERT INTO tbl_user_pregunta (ID_USER, ID_PREGUNTA, RESPUESTA) VALUES (?, ?, ?)";
        $stmtInsertar = $conn->prepare($sqlInsertar);
        $stmtInsertar->bind_param("iis", $userId, $idPregunta, $respuestaEncriptada);

        foreach ($preguntasSeguridad as $key => $idPregunta) {
            $respuesta = $respuestasSeguridad[$key];
            
            // Encripta la respuesta antes de insertarla en la base de datos
            $respuestaEncriptada = password_hash($respuesta, PASSWORD_DEFAULT);
            
            $stmtInsertar->execute();
        }

        $_SESSION["preguntaSeguridadSuccess"] = "Preguntas de seguridad agregadas correctamente.";
        header("Location: ../pantallas/admin.php"); // Redirige a la página de perfil o donde lo necesites.
        exit();
    } else {
        $_SESSION["preguntaSeguridadError"] = "Debes seleccionar preguntas de seguridad y proporcionar respuestas.";
        header("Location: preguntas.php"); // Redirige de nuevo al formulario si hay un error.
        exit();
    }
} else {
    // Si no se envió el formulario por POST, redirige a donde sea necesario
    header("Location: ../index.php");
    exit();
}
?>
