
<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>
    <link rel="stylesheet" href="../css/estilosProducto.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="../estilos.js"></script>
</head>

<title>Gestión de Roles</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>

    <div class="content">
      <h1><span class="fas fa-users"></span>Usuarios</h1>
        <div class="search-bar" style="margin-top: -25px; margin-bottom: -10px; text-align: right;">
            <form method="get" action="">
              <input type="text" name="buscar" placeholder="Buscar">
              <button type="submit"><i class="fas fa-search"></i></button>
            </form>
          <a href="../usuario/agregar_usuario.php" class="print-button plus-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i></a>
           <a href="pdf_usuario.php" class="pdf-button"><i class="fas fa-file-pdf"></i> PDF</a>
          <!-- Agrega el enlace al archivo PDF dentro del atributo "href" del botón -->
          <a href="excel.php"><button class="fa-download"><i class="fas fa-download"></i></button></a>


        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Correo Electrónico</th>
                    <th>Fecha Creación</th>
                    <th>Fecha Modificación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                  // Conexión a la base de datos
                   $servername = "localhost";
                   $username = "root";
                   $password = "";
                   $dbname = "gestion_compras2";
                   $conn = new mysqli($servername, $username, $password, $dbname);
                  if ($conn->connect_error) {
                      die("Error de conexión: " . $conn->connect_error);
                    }

                   // Definir variables de búsqueda
                  $nombreUsuario = "";
                  $buscar = "";

                    // Verificar si se ha enviado el formulario de búsqueda
                  if (isset($_GET['nombre_usuario'])) {
                      $nombreUsuario = $_GET['nombre_usuario'];
                    }

                  // Verificar si se ha enviado el formulario de búsqueda
                  if (isset($_GET['buscar'])) {
                    $buscar = $_GET['buscar'];
                  }


                  // Obtener el número de usuarios por página elegido por el usuario (default: 10)
                 $resultsPerPage = isset($_GET['resultsPerPage']) ? $_GET['resultsPerPage'] : 10;

                  // Calcular la página actual y el índice de inicio
                  $page = isset($_GET['page']) ? $_GET['page'] : 1;
                  $startIndex = ($page - 1) * $resultsPerPage;

                  // Consulta SQL paginada para obtener usuarios con sus roles
                  $sql = "SELECT u.id_usuario, u.nombre_usuario, IFNULL(r.NOMBRE_ROL, 'Sin rol asignado') AS rol, u.correo_electronico, u.fecha_creacion, u.fecha_modificacion, u.estado
                   FROM tbl_ms_usuario u
                   LEFT JOIN tbl_ms_roles r ON u.rol = r.ID_ROL
                   WHERE u.id_usuario LIKE '%$buscar%'
                   OR u.nombre_usuario LIKE '%$buscar%'
                   OR r.NOMBRE_ROL LIKE '%$buscar%'
                   LIMIT $startIndex, $resultsPerPage";
                   $result = $conn->query($sql);

                     // Obtener el total de resultados para calcular el número total de páginas
                    $sqlTotalResults = "SELECT COUNT(*) as total FROM tbl_ms_usuario WHERE nombre_usuario LIKE '%$nombreUsuario%'";
                    $resultTotalResults = $conn->query($sqlTotalResults);
                    $rowTotalResults = $resultTotalResults->fetch_assoc();
                    $totalResults = $rowTotalResults['total'];
                    $totalPages = ceil($totalResults / $resultsPerPage);

                   $estados = array(
                      'A' => 'Activo',
                      'I' => 'Inactivo',
                      'B' => 'Bloqueado',
                      'N' => 'Nuevo'
                    );

                   if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) { 
                          echo "<tr>";
                          echo "<td>" . $row["id_usuario"] . "</td>";
                          echo "<td>" . $row["nombre_usuario"] . "</td>";
                          echo "<td>" . $row["rol"] . "</td>";
                          echo "<td>" . $row["correo_electronico"] . "</td>";
                          $fechaCreacion = date('Y-m-d', strtotime($row["fecha_creacion"]));
                          echo "<td>".$fechaCreacion."</td>";
                          $fechaModificacion = date('Y-m-d', strtotime($row["fecha_modificacion"]));
                          echo "<td>".$fechaModificacion."</td>";
                          // Verifica si $row["estado"] es una clave válida en el array $estados
                          $estado = isset($estados[$row["estado"]]) ? $estados[$row["estado"]] : 'Desconocido';
                          echo "<td>" . $estado . "</td>";
                       
                          echo "<td class='actions-cell'>";
                          echo "<div class='d-flex'>";
                          echo "<a href='../usuario/editar_usuario.php?id=" . $row['id_usuario'] . "' class='styled-button edit-button me-2'><i class='fas fa-edit'></i></a>";
                          echo "<form method='post' action='eliminar_usuario.php' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");'>";
                          echo "<input type='hidden' name='id_usuario' value='" . $row['id_usuario'] . "'>";
                          echo "<button type='submit' name='eliminar' class='styled-button delete-button'><i class='fas fa-trash' ></i></button>";
                          echo "</form>";
                          echo "</div>";
                          echo "</td>";
                
                          echo "</tr>";
                        }
                    } else {
                      echo "<tr><td colspan='7'>No se encontraron usuarios</td></tr>";
                    }
                

                    $conn->close();
                ?>

                <div class="pagination-left">
                    <!-- Formulario para elegir el número de usuarios por página -->
                    <form method="get">
                        <label for="resultsPerPage">Usuarios por página:</label>
                        <select name="resultsPerPage" id="resultsPerPage" onchange="this.form.submit()">
                            <option value="5" <?php if ($resultsPerPage == 5) echo "selected"; ?>>5</option>
                            <option value="10" <?php if ($resultsPerPage == 10) echo "selected"; ?>>10</option>
                            <option value="20" <?php if ($resultsPerPage == 20) echo "selected"; ?>>20</option>
                            <option value="50" <?php if ($resultsPerPage == 50) echo "selected"; ?>>50</option>
                        </select>
                    </form>
                </div>
                <br>
                <div class="pagination pagination-links">
                  <!-- Enlaces de paginación -->
                   <?php
                     // Agrega el enlace para la página anterior si no estás en la primera página
                       if ($page > 1) {
                          echo "<a href='?page=" . ($page - 1) . "&resultsPerPage=$resultsPerPage'>&lt;</a> ";
                        }

                      // Agrega la numeración de página actual
                       echo "<span>$page</span>";

                       // Agrega el enlace para la página siguiente si no estás en la última página
                      if ($page < $totalPages) {
                          echo " <a href='?page=" . ($page + 1) . "&resultsPerPage=$resultsPerPage'>&gt;</a>";
                        }
                   ?>
               </div>

            </tbody>
        </table>

        <button class="styled-button" onclick="window.location.href='../setting/ajustes.php'" style="background-color: #007bff; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">Regresar</button>



    </div> 

</body>

</html>