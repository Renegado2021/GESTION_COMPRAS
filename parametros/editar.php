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
            justify-content: center;
            align-items: center;
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
            max-width: 800px;
            margin: 0 auto;
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
            justify-content: space-around;
            margin-top: 10px;
        }

        .btn {
            flex: 1;
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
            box-sizing: border-box;
            text-decoration: none;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            width: 150px;
            display: inline-block;
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
           <h2>Parámetro</h2>
           <?php
             session_start();

              include('../conexion/conexion.php');

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
                    $parametro_id = $_GET["id"];

                    // Obtener los datos del parámetro a editar
                    $sql = "SELECT * FROM tbl_ms_parametros WHERE ID_PARAMETRO = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $parametro_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                   if ($result->num_rows == 1) {
                      $row = $result->fetch_assoc();

                      // Obtener los datos del parámetro
                      $parametro = $row["PARAMETRO"];
                      $valor = $row["VALOR"];
                      $fecha_creacion = date('Y-m-d', strtotime($row["FECHA_CREACION"]));
                      $fecha_modificacion = date('Y-m-d', strtotime($row["FECHA_MODIFICACION"]));

                      // Generar el formulario para editar el parámetro
                      echo "<form method='post' action='actualizar.php'>";
        
                      echo "<input type='hidden' name='parametro_id' value='$parametro_id'>";

                      echo "<div class='form-group'><label for='parametro'>Parámetro:</label><input type='text' name='parametro' value='$parametro' required></div>";
                       echo "<div class='form-group'><label for='valor'>Valor:</label><input type='text' name='valor' value='$valor' required></div>";

                       echo "<div class='form-group'><label for='fecha_creacion'>Fecha de Creación:</label><input type='text' name='fecha_creacion' value='$fecha_creacion' readonly></div>";

                       // Obtener la fecha actual en formato YYYY-MM-DD
                       $fecha_modificacion_actual = date('Y-m-d');

                      echo "<div class='form-group'><label for='fecha_modificacion'>Fecha de Modificación:</label><input type='text' name='fecha_modificacion' value='$fecha_modificacion_actual'></div>"; 

                      // Campo oculto para el nombre del usuario que inició sesión (MODIFICADO_POR)
                     echo "<input type='hidden' name='modificado_por' value='" . (isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : '') . "'>";

                     // Botones
                     echo '<div class="btn-container">';
                     echo '<input type="submit" value="Guardar" class="btn btn-primary" style="width: 20%;" >';
                      echo '<a href="parametros.php" class="btn btn-secondary" style="width: 20%; background-color: gray; color: white;">Cancelar</a>';
                      echo '</div>';

                      echo "</form>";
                    } else {
                      echo "Parámetro no encontrado.";
                    }
                } else {
                 echo "ID de parámetro no proporcionado.";
                }

                // Cerrar la conexión
                $conn->close();
            ?>

        </div>
    </div>
</body>
</html>
