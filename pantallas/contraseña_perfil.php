<?php
session_start();

// Incluye la conexión a la base de datos
include("../conexion/conexion.php");

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene las entradas del usuario
    $inputOldPassword = trim($_POST["contraseña_anterior"]);
    $inputNewPassword = trim($_POST["contraseña_nueva"]);
    $inputConfirmPassword = trim($_POST["confirmar_contraseña"]);


    // Validar que la contraseña nueva cumple con los requisitos
    if (
        !preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/", $inputNewPassword) ||
        strpos($inputNewPassword, ' ') !== false
    ) {
        $_SESSION["passwordChangeError"] = "La contraseña debe tener al menos 8 caracteres, incluir al menos una letra mayúscula, una letra minúscula, un número y un carácter especial, y no debe contener espacios.";
        header("Location: contraseña_perfil.php");
        exit();
    }

    // Obtén el ID de usuario de la sesión
    $usuarioId = $_SESSION["usuarioId"];

  

    // Verifica la contraseña anterior en la base de datos
    $sql = "SELECT contraseña FROM tbl_ms_usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $dbPasswordHash = $row["contraseña"];
       

        // Verifica si la contraseña anterior coincide con la contraseña en la base de datos
        if (password_verify($inputOldPassword, $dbPasswordHash)) {
            // Verifica si la nueva contraseña y la confirmación coinciden
            if ($inputNewPassword === $inputConfirmPassword) {
                // Encripta la nueva contraseña
                $hashedPassword = password_hash($inputNewPassword, PASSWORD_DEFAULT);
                
                // Actualiza la contraseña en la base de datos
                $updateSql = "UPDATE tbl_ms_usuario SET contraseña = ? WHERE id_usuario = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("si", $hashedPassword, $usuarioId);
                if ($updateStmt->execute()) {
                    $_SESSION["passwordChangeSuccess"] = "Contraseña cambiada exitosamente.";
                    header("Location: ../index.php");
                    exit();
                } else {
                    $_SESSION["passwordChangeError"] = "Error al cambiar la contraseña.";
                    header("Location: contraseña_perfil.php");
                    exit();
                }
            } else {
                $_SESSION["passwordChangeError"] = "La nueva contraseña y la confirmación no coinciden.";
                header("Location: contraseña_perfil.php");
                exit();
            }
        } else {
            $_SESSION["passwordChangeError"] = "La contraseña anterior no es correcta.";
            header("Location: contraseña_perfil.php");
            exit();
        }
    } else {
        $_SESSION["passwordChangeError"] = "Usuario no encontrado.";
        header("Location: contraseña_perfil.php");
        exit();
    }

   

}
 // Obtén el ID de usuario de la sesión
 $usuarioId = $_SESSION["usuarioId"];

 // Verifica si el usuario tiene preguntas de seguridad registradas
$sql = "SELECT ID_PREGUNTA, RESPUESTA 
FROM tbl_user_pregunta
WHERE ID_USER = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

// Verifica si el usuario tiene preguntas de seguridad
$hasSecurityQuestions = $result->num_rows > 0; 

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
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
    
    if (isset($_SESSION["passwordChangeSuccess"])) {
        echo "<p style='color: green;'>".$_SESSION["passwordChangeSuccess"]."</p>";
        unset($_SESSION["passwordChangeSuccess"]);
    }
    if (isset($_SESSION["passwordChangeError"])) {
        echo "<p style='color: red;'>".$_SESSION["passwordChangeError"]."</p>";
        unset($_SESSION["passwordChangeError"]);
    }
    ?>

    <form action="contraseña_perfil.php" method="POST">
        <div class="form-header">
          <h2>Cambio de Contraseña</h2>
        </div>

        <div class="form-group">
          <label for="contraseña_anterior">Contraseña Anterior:</label>
          <input type="password" name="contraseña_anterior" placeholder="Contraseña anterior"><br><br>
        </div>

        <div class="form-group">
         <label for="contraseña_nueva">Contraseña Nueva:</label>
         <input type="password" name="contraseña_nueva" placeholder="Nueva contraseña"><br><br>
        </div>

        <div class="form-group">
          <label for="confirmar_contraseña">Confirmar Contraseña:</label>
          <input type="password" name="confirmar_contraseña" placeholder="Confirmar nueva contraseña"><br><br>
        </div>


        <!-- Botón "Cambiar Contraseña" con la clase CSS "change-password-button" -->
       <input type="submit" name="Cambiar Contraseña" value="Cambiar Contraseña" class="change-password-button">
    
       <!-- Botón "Cancelar" con la clase CSS "cancel-button" -->
       <input type="button" value="Cancelar" onclick="window.location.href='../pantallas/admin.php';" class="cancel-button"><br><br>
       
        <!-- Enlace para cambiar o agregar preguntas de seguridad según corresponda -->
        <?php if ($hasSecurityQuestions) : ?>
            <a href="../pantallas/cambiar_pregunta.php">Cambiar Preguntas de Seguridad</a>
        
            
        <?php endif; ?>

    </form>
</body>
</html>
