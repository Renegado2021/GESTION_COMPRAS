<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Agregar Rol con Permisos a Objetos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row; /* Alinea los elementos en fila */
        }

        .left {
            flex: 1;
            padding: 20px;
        }

        .right {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 1px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        td {
            background-color: #f4f4f4;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        button[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Para los botones agregar y cancelar */
        .btn-container {
            text-align: right;
        }

        .btn-container button {
            width: 100px; /* Ancho fijo para los botones */
            margin-left: 10px;
            padding: 10px;
            font-size: 14px; /* Tamaño de fuente opcional */
        }
    </style>
</head>

<body>
<?php
    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";

    // Crear una conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    $message = "";

    // Obtener los objetos de la tabla tbl_objetos
    $sql_objetos = "SELECT ID_OBJETO, NOMBRE_OBJETO FROM tbl_objetos";
    $result_objetos = $conn->query($sql_objetos);

    // Obtener los permisos de la tabla tbl_permisos
    $sql_permisos = "SELECT id_permiso, nombre_permiso FROM tbl_permisos";
    $result_permisos = $conn->query($sql_permisos);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombreRol = $_POST["nombre_rol"];
        $descripcionRol = $_POST["descripcion_rol"];
    
        // Verificar si se han asignado permisos a objetos
        if (isset($_POST['objeto_permisos']) && is_array($_POST['objeto_permisos'])) {
            // Verificar si el rol ya existe
            $sql_check_rol = "SELECT ID_ROL FROM tbl_ms_roles WHERE NOMBRE_ROL = ?";
            $stmt_check_rol = $conn->prepare($sql_check_rol);
            $stmt_check_rol->bind_param("s", $nombreRol);
            $stmt_check_rol->execute();
            $stmt_check_rol->store_result();
    
            if ($stmt_check_rol->num_rows > 0) {
                $message = "Error: El rol ya existe. Intente con un nombre de rol diferente.";
            } else {
                // Insertar el nuevo rol en la tabla tbl_ms_roles
                $estadoActivo = "A";  // Estado por defecto "Activo"
                $sql_insert_rol = "INSERT INTO tbl_ms_roles (NOMBRE_ROL, DESCRIPCION, ESTADO_ROL) VALUES (?, ?, ?)";
                $stmt_rol = $conn->prepare($sql_insert_rol);
                $stmt_rol->bind_param("sss", $nombreRol, $descripcionRol, $estadoActivo);
    
                if ($stmt_rol->execute()) {
                    $message = "Rol agregado correctamente.";
                    
                    // Obtener el ID del rol recién insertado
                    $idRol = $stmt_rol->insert_id;
    
                    // Insertar las asignaciones de permisos a objetos en la tabla tbl_roles_permisos
                    foreach ($_POST['objeto_permisos'] as $idObjeto => $permisos) {
                        foreach ($permisos as $idPermiso => $valor) {
                            if ($valor == 1) {
                                $sql_insert_asignacion = "INSERT INTO tbl_roles_permisos (id_rol, id_objeto, id_permiso) VALUES (?, ?, ?)";
                                $stmt_asignacion = $conn->prepare($sql_insert_asignacion);
                                $stmt_asignacion->bind_param("iii", $idRol, $idObjeto, $idPermiso);
                                $stmt_asignacion->execute();
                            }
                        }
                    }
    
                    // Redirigir a roles.php
                    header("Location: roles.php");
                    exit(); // Detener la ejecución del script después de redirigir
                } else {
                    $message = "Error al agregar el rol: " . $stmt_rol->error;
                }
    
                $stmt_rol->close();
            }
    
            $stmt_check_rol->close();
        } else {
            $message = "Debe asignar permisos a al menos un objeto antes de agregar el rol.";
        }
    }
    
    
    ?>
    <div class="container">
        <div class="left">
            <h2 style="text-align: center;">Nuevo Rol  </h2>
            <p><?php echo $message; ?></p>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="nombre_rol">Rol:</label>
                <input type="text" name="nombre_rol" required><br><br>

                <label for="descripcion_rol">Descripción:</label>
                <textarea name="descripcion_rol" rows="4" cols="50"></textarea><br><br>
                
                <div class="btn-container">
                    <button type="submit" name="agregar" >Guardar</button>
                    <button type="button" onclick="window.location.href='roles.php'">Cancelar</button>
                </div>
            </div>
            <div class="right">
                <table>
                    <thead>
                       <h3 style="text-align: center;">Permisos</h3>
                        <tr>
                            <th>Nº</th>
                            <th>Pantallas</th>
                            <?php
                            if ($result_permisos->num_rows > 0) {
                                while ($permiso = $result_permisos->fetch_assoc()) {
                                    echo '<th>' . $permiso["nombre_permiso"] . '</th>';
                                }
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $numeroObjeto = 1; // Variable para la numeración de objetos
                        if ($result_objetos->num_rows > 0) {
                            while ($objeto = $result_objetos->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td class="numeracion">' . $numeroObjeto . '</td>';
                                echo '<td class="alineado-izquierda">' . $objeto["NOMBRE_OBJETO"] . '</td>';

                                if ($result_permisos->num_rows > 0) {
                                    mysqli_data_seek($result_permisos, 0); // Reiniciar el puntero del resultado de permisos

                                    while ($permiso = $result_permisos->fetch_assoc()) {
                                        echo '<td>';
                                        echo '<input type="checkbox" name="objeto_permisos[' . $objeto["ID_OBJETO"] . '][' . $permiso["id_permiso"] . ']" value="1">';
                                        echo '</td>';
                                    }
                                }

                                echo '</tr>';
                                $numeroObjeto++; // Incrementar el número de objeto
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</body>

</html>
