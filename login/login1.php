<?php
session_start();

// Replace these values with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

// Obtener el número máximo de intentos permitidos desde la tabla de parámetros
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql_parametro = "SELECT VALOR FROM tbl_ms_parametros WHERE PARAMETRO = 'intento'";
$result_parametro = $conn->query($sql_parametro);

$max_intentos = 3; // Valor predeterminado en caso de que no se encuentre el parámetro en la base de datos

if ($result_parametro->num_rows > 0) {
    $row_parametro = $result_parametro->fetch_assoc();
    $max_intentos = intval($row_parametro["VALOR"]);
}

// Comprobar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener user input
    $inputUsername = $_POST["nombre_usuario"];
    $inputPassword = $_POST["contraseña"];

    // Validar que el nombre de usuario esté en mayúsculas
    if ($inputUsername !== strtoupper($inputUsername)) {
        $_SESSION["loginError"] = "El nombre de usuario debe estar en mayúsculas.";
        header("Location: ../index.php");
        exit();
    }

    // Validar que la contraseña cumple con los requisitos
    if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/", $inputPassword) || strpos($inputPassword, ' ') !== false) {
        $_SESSION["loginError"] = "La contraseña debe tener al menos 8 caracteres, incluir números, letras mayúsculas, signos y no contener espacios.";
        header("Location: ../index.php");
        exit();
    }

    // Resto del código de inicio de sesión aquí...
    // Verificar credenciales, redirigir si es exitoso, manejar errores, etc.
    
    // Crear una base de datos connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Prepare and execute the query to check if the user exists and is active
    $sql = "SELECT * FROM tbl_ms_usuario WHERE nombre_usuario = ? AND estado = 'A'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

   

    // Verificar si el usuario está bloqueado
    if ($row["estado"] == 'B') {
        $_SESSION["loginError"] = "El usuario está bloqueado. Comunícate con el administrador.";
        header("Location: ../index.php");
        exit();
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
            // Verificar si se trata de una contraseña temporal
            if (!empty($row["contraseñaTemp"]) && password_verify($inputPassword, $row["contraseñaTemp"])) {
                $_SESSION["temp_password_login"] = true; // Establecer una bandera en la sesión
                echo "Contraseña temporal válida. Redirigiendo a cambiar_contraseña.php...";
                header("Location: cambiar_contraseña.php?id=" . $row["id_usuario"]); // Redirigir a cambiar la contraseña
                exit;
             }
 
             // Verificar la contraseña encriptada
             if (password_verify($inputPassword, $row["contraseña"])) {
             
                 // La contraseña es correcta, cree una sesión y configure el rol de usuario
                $_SESSION["nombre_usuario"] = $row["nombre_usuario"];
 
                // Obtener el ID del usuario
                $usuarioId = $row["id_usuario"];
                $_SESSION["usuarioId"] = $usuarioId;
 
                 //Consulta la tabla de roles para obtener el rol del usuario
                 $sql_roles = "SELECT NOMBRE_ROL FROM tbl_ms_roles WHERE ID_ROL = ?";
                 $stmt_roles = $conn->prepare($sql_roles);
                 $stmt_roles->bind_param("i", $row["rol"]); // Asumiendo que "rol" es la columna en "tbl_ms_usuario" que contiene el ID de rol
                 $stmt_roles->execute();
                 $result_roles = $stmt_roles->get_result();
 
 
                 if ($result_roles->num_rows > 0) {
                     $role_row = $result_roles->fetch_assoc();
                     $_SESSION["rol"] = $role_row["NOMBRE_ROL"];
                 } else {
                     // Si no se encuentra el rol, establezca un rol predeterminado (e.g., "usuario")
                     $_SESSION["rol"] = "usuario";
                 }
     
                 // Redirigir al tablero apropiado o a la página protegida según el rol del usuario
     
                 
                 // Cuando el usuario inicia sesión con éxito, restablecer el contador de intentos
                 $_SESSION["intentos"] = 0;
 
                  // Verificar si la contraseña es la temporal
        
 
                  // Resto del código de redirección y manejo de inicio de sesión exitoso aquí...
                // Redirigir a od.php después de iniciar sesión
                header("Location: ../pantallas/l.php");
                exit();
  
             } else {
                   // Contraseña incorrecta, aumentar el contador de intentos solo en este caso
                    if (!isset($_SESSION["intentos"])) {
                       $_SESSION["intentos"] = 1;
                     } else {
                          $_SESSION["intentos"]++;
                         }
 
                     if ($_SESSION["intentos"] >= $max_intentos) {
                      // Bloquear al usuario después del tercer intento fallido
                      $sql_bloquear = "UPDATE tbl_ms_usuario SET estado = 'B' WHERE nombre_usuario = ?";
                      $stmt_bloquear = $conn->prepare($sql_bloquear);
                      $stmt_bloquear->bind_param("s", $inputUsername);
                       $stmt_bloquear->execute();
                 
                        $_SESSION["loginError"] = "Has excedido el número máximo de intentos. El usuario ha sido bloqueado.";
                        header("Location: ../index.php");
                        exit();
                     }  else {
                      // Mostrar un mensaje de error estándar
                       $_SESSION["loginError"] = "Nombre de usuario o contraseña incorrectos. Intento #" . $_SESSION["intentos"];
                       header("Location: ../index.php");
                       exit();
                     }
                 }     

                 
               
                
          


    } else {
        // Usuario no encontrado o no está activo
        $_SESSION["loginError"] = "El usuario no existe o no tiene permiso para iniciar sesión.";
        header("Location: ../index.php");
        exit();   
    }

   



}
?>