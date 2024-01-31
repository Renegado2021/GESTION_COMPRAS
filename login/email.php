<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
</head>
<body>
    <div id="resetPasswordContainer">
        <?php
            if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["token"])) {
                $token = $_GET["token"];
                
                // Conexión a la base de datos (ajusta estos valores)
                $servername = "localhost";
                $usernameDB = "root";
                $passwordDB = "";
                $dbname = "gestion_compras2";

                $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }
                
                // Verificar el token en la base de datos
                $sql_verify_token = "SELECT nombre_usuario FROM tbl_ms_usuario WHERE token_recuperacion = '$token'";
                $result_verify_token = $conn->query($sql_verify_token);

                if ($result_verify_token->num_rows > 0) {
                    echo '<form method="post" action="guardar_nueva_contraseña.php">';
                    echo '<input type="hidden" name="token" value="' . $token . '">';

                    echo '<label for="nueva_contrasena">Nueva Contraseña:</label>';
                    echo '<input type="password" id="nueva_contrasena" name="nueva_contrasena" required><br>';

                    echo '<label for="confirmar_contrasena">Confirmar Contraseña:</label>';
                    echo '<input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required><br>';

                    echo '<button type="submit">Guardar Contraseña</button>';
                    echo '</form>';
                } else {
                    echo "El enlace de recuperación es inválido o ha expirado.";
                }

                $conn->close();
            } else {
                echo "Acceso denegado.";
            }
        ?>
    </div>
</body>
</html>
