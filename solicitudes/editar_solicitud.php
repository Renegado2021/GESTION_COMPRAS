<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>
    body {
        
        text-align: center;
        font-family: Arial, sans-serif;
        background: rgba(255, 255, 255, 0.10);
        background-image: url('../imagen/background.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center; /* Centra horizontalmente */
        align-items: center; /* Centra verticalmente */
        min-height: 100vh;
    }

    .container {
        width: 200%;
        text-align: center;
        border: 1px solid #ccc;
        padding: 20px;
        background-color: powderblue;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        opacity: 0.9;
        max-width: 800px; /* Ajusta el ancho máximo según tus necesidades */
        margin: 0 auto; /* Centra horizontalmente */
    }

    .table {
       
        box-sizing: border-box;
        background-color: cornsilk;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .centered-message {
        text-align: center;
        margin-bottom: 20px;
    }

    .btn-container {
    display: flex;
    justify-content: space-around; /* Ajusta el espacio entre los botones */
    margin-top: 10px;
}

.btn {
    flex: 1; /* Ocupa el espacio disponible de manera equitativa */
    background-color: #007bff;
    color: #fff;
    padding: 15px; /* Ajusta el padding para hacer los botones más altos */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 0 5px; /* Espacio entre botones */
    box-sizing: border-box; /* Asegura que el padding no afecte el ancho total */
    text-decoration: none; /* Elimina el subrayado del texto */
}


    .form-group {
       
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .form-group label {
        width: 150px; /* Establece un ancho fijo para todos los elementos label */
    display: inline-block; /* Alinea los elementos label */
        flex: 1;
        text-align: right;
        margin-left: 40px;
        font-weight: bold;
    }

    .form-group input, .form-group select, .form-group textarea {
        flex: 2;
        width: 90%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
</style>
</head>
<body>
<div class="container">
    <div class="table">
        <?php
        // Reemplaza estos valores con tus credenciales de base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gestion_compras2";

        function obtenerNombreUsuario($conn, $usuario_id) {
            $sql = "SELECT nombre_usuario FROM tbl_ms_usuario WHERE id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["nombre_usuario"];
        }

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
            $solicitud_id = $_GET["id"];

            // Crear una conexión a la base de datos
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            // Obtener los datos de la solicitud a editar
            $sql = "SELECT * FROM tbl_solicitudes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $solicitud_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();

                // Obtener los datos de la solicitud
                $idDepartamento = $row["idDepartamento"];
                $usuario_id = $row["usuario_id"];
                $codigo = $row["codigo"];

                $estado = $row["estado"];

                // Consultas para obtener información de departamentos y categorías
                $sql_departamentos = "SELECT id_departamento, nombre_departamento FROM tbl_departamentos";
                $result_departamentos = $conn->query($sql_departamentos);

                $sql_categorias = "SELECT id, categoria FROM tbl_categorias";
                $result_categorias = $conn->query($sql_categorias);

                // Generar el formulario para editar la solicitud
                echo "<form method='post' action='guardar_edicion_solicitud.php'>";
                echo '<div class="centered-message"><label>SOLICITUD</label></div>';
                echo "<input type='hidden' name='solicitud_id' value='$solicitud_id'>";

                echo "<div class='form-group'><label for='codigo'>Código:</label><input type='text' name='codigo' value='$codigo' required></div>";
                echo "<div class='form-group'><label for='idDepartamento'>Departamento:</label><select id='idDepartamento' name='idDepartamento' required>";

                while ($row_departamento = $result_departamentos->fetch_assoc()) {
                    $selected = ($row_departamento["id_departamento"] == $idDepartamento) ? "selected" : "";
                    echo "<option value='" . $row_departamento["id_departamento"] . "' $selected>" . $row_departamento["nombre_departamento"] . "</option>";
                }

                echo "</select></div>";

                $usuario_nombre = obtenerNombreUsuario($conn, $usuario_id);
                echo "<div class='form-group'><label for='usuario_nombre'>Usuario:</label><input type='text' id='usuario_nombre' name='usuario_nombre' value='$usuario_nombre' required></div>";

               
                echo "<div class='form-group'><label for='estado'>Estado:</label><input type='text' name='estado' value='$estado' required></div>";

              

                echo '<div class="btn-container">';
                echo '<input type="submit" value="Guardar" class="btn btn-primary" style="width: 20%;" >';
                echo '<a href="solicitudes.php" class="btn btn-secondary" style="width: 20%; background-color: gray; color: white;">Cancelar</a>';
                echo '</div>';

                echo "</form>";
            } else {
                echo "Solicitud no encontrada.";
            }

            // Cerrar la conexión
            $conn->close();
        } else {
            echo "ID de solicitud no proporcionado.";
        }
        ?>
    </div>
</div>
</body>
</html>

