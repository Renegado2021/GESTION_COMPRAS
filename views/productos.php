<?php
session_start(); // Inicializa la sesión

// Incluye la configuración de la base de datos
include("../views/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuarioId"])) {
    // El usuario no ha iniciado sesión, redirige a la página de inicio de sesión
    header("Location: index.php");
    exit();
}

$usuarioId = $_SESSION["usuarioId"];

// Obtén el rol del usuario desde la base de datos
$sql = "SELECT rol FROM tbl_ms_usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $usuarioRol = $row["rol"];
} else {
    // Manejar el caso en que no se encuentra el rol del usuario
    $usuarioRol = null; // Puedes establecer un valor por defecto o manejarlo según tu lógica
}

// Configuración de paginación
$resultsPerPage = isset($_GET['resultsPerPage']) ? intval($_GET['resultsPerPage']) : 10; // Cantidad de registros por página
$paginaActual = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual

// Consulta SQL para obtener la cantidad total de registros
$sqlTotalRegistros = "SELECT COUNT(*) as total FROM tbl_productos";

$resultTotalRegistros = $conn->query($sqlTotalRegistros);
$rowTotalRegistros = $resultTotalRegistros->fetch_assoc();
$totalRegistros = $rowTotalRegistros['total'];

// Calcula la cantidad total de páginas
$totalPaginas = ceil($totalRegistros / $resultsPerPage);

// Calcula el offset (inicio) para la consulta de paginación
$offset = ($paginaActual - 1) * $resultsPerPage;

// Consulta SQL para obtener los registros de la página actual
$sqlProductos = "SELECT p.id, p.id_solicitud, p.cantidad, p.descripcion, c.categoria 
                 FROM tbl_productos p
                 INNER JOIN tbl_categorias c ON p.categoria = c.id
                 LIMIT ?, ?";

$stmt = $conn->prepare($sqlProductos);
$stmt->bind_param("ii", $offset, $resultsPerPage);
$stmt->execute();
$resultProductos = $stmt->get_result();

// Funciones para verificar los permisos
function tienePermiso($usuarioRol, $conn, $permisoId, $objetoId) {
    // Agrega aquí la lógica para verificar el permiso para el rol del usuario en un objeto específico
    // Consulta la tabla tbl_roles_permisos para verificar si el rol tiene el permiso
    // para el objeto específico
    $sql = "SELECT id_permiso FROM tbl_roles_permisos WHERE id_rol = ? AND id_permiso = ?";

    if ($objetoId !== null) {
        // Si se proporciona un objeto específico, incluye la comprobación del objeto
        $sql .= " AND id_objeto = ?";
    }

    $stmt = $conn->prepare($sql);

    if ($objetoId !== null) {
        // Si se proporciona un objeto específico, pasa también su ID como parámetro
        $stmt->bind_param("iii", $usuarioRol, $permisoId, $objetoId);
    } else {
        $stmt->bind_param("ii", $usuarioRol, $permisoId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

$permisoVerId = 1; // Cambia esto al ID del permiso de "Ver" en tu sistema
$objetoIdVer = 1;

$permisoCrearId = 2; // Cambia esto al ID del permiso de "Crear" en tu sistema
$objetoIdCrear = 1;

$permisoEditarId = 3; // Cambia esto al ID del permiso de "Editar" en tu sistema
$objetoIdEditar = 1;

$permisoEliminarId = 4; // Cambia esto al ID del permiso de "Eliminar" en tu sistema
$objetoIdEliminar = 1;

// Variables para la búsqueda
$buscar_codigo = "";
$condicion_busqueda = "";

// Verificar si se ha enviado el formulario de búsqueda
if (isset($_GET["buscar"])) {
    // Obtener el valor del campo de código
    $buscar_codigo = $_GET["codigo"];

    // Validar que el código de búsqueda tenga un máximo de 10 caracteres
    if (strlen($buscar_codigo) > 10) {
        echo "El código de búsqueda no puede tener más de 10 caracteres.";
        exit(); // Detener la ejecución si el código es demasiado largo
    }

    // Construir la condición de búsqueda por código
    $condicion_busqueda = " WHERE codigo LIKE '%" . $buscar_codigo . "%'";
}

// Consulta SQL para obtener la lista de productos con paginación y búsqueda
$sqlProductos = "SELECT p.id, p.id_solicitud, p.cantidad, p.descripcion, c.categoria 
                 FROM tbl_productos p
                 INNER JOIN tbl_categorias c ON p.categoria = c.id
                 LIMIT ?, ?";
$stmt = $conn->prepare($sqlProductos);
$stmt->bind_param("ii", $offset, $resultsPerPage);
$stmt->execute();
$resultProductos = $stmt->get_result();
?>


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
<body>
    <!-- Contenido de la vista de productos -->
    <div class="content">
        
        <div class="header-container">
           <h1><span class="fas fa-shopping-cart"></span>Productos</h1>
        </div>

        <div class="search-bar" style="margin-top: -25px; margin-bottom: -10px; text-align: right;">
            <!-- Agrega un formulario para la búsqueda -->
            <form method="GET" action="">
                <input type="text" name="codigo" placeholder="Código de búsqueda">
                <button type="submit" name="buscar"><i class="fas fa-search"></i></button>
            </form>
            <a href="pdf.php" class="pdf-button"><i class="fas fa-file-pdf"></i></a>
           
        </div>
        
            <!-- Agrega la paginación -->
            <div class="pagination-left">
            <form method="get">
             <label for="resultsPerPage">Productos por página:</label>
               <select name="resultsPerPage" id="resultsPerPage" onchange="this.form.submit()">
                 <option value="5" <?php if ($resultsPerPage == 5) echo "selected"; ?>>5</option>
                 <option value="10" <?php if ($resultsPerPage == 10) echo "selected"; ?>>10</option>
                 <option value="20" <?php if ($resultsPerPage == 20) echo "selected"; ?>>20</option>
                 <option value="50" <?php if ($resultsPerPage == 50) echo "selected"; ?>>50</option>
                </select>
            </form>
        </div>
        
        <table>
            <tr>
                <th>ID</th>
                <th>solicitud</th>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th>Categorìa</th>
               
            </tr>
            <?php
            // Verificar si se encontraron resultados
            if ($resultProductos) {
            // Mostrar los productos en la tabla
            if ($resultProductos->num_rows > 0) {
                while ($rowProducto = $resultProductos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowProducto["id"] . "</td>";
                    echo "<td>" . $rowProducto["id_solicitud"] . "</td>";
                    echo "<td>" . $rowProducto["cantidad"] . "</td>";
                    echo "<td>" . $rowProducto["descripcion"] . "</td>";
                    echo "<td>" . $rowProducto["categoria"] . "</td>";
                    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No se encontraron productos</td></tr>";
            }

        } else {
            // Maneja cualquier error en la consulta SQL aquí
            echo "Error en la consulta SQL: " . $conn->error;
        }
            ?>

         
        </table>
    
        
        <br>
        <!-- Agrega los enlaces de paginación -->
       <div class="pagination pagination-links">
          <?php
             // Agrega el enlace para la página anterior si no estás en la primera página
              if ($paginaActual > 1) {
                  echo "<a href='?page=" . ($paginaActual - 1) . "&resultsPerPage=$resultsPerPage'>&lt;</a> ";
                }

               // Agrega la numeración de página actual
               echo "<span>$paginaActual</span>";

              // Agrega el enlace para la página siguiente si no estás en la última página
              if ($paginaActual < $totalPaginas) {
                   echo " <a href='?page=" . ($paginaActual + 1) . "&resultsPerPage=$resultsPerPage'>&gt;</a>";
                }
            ?>
        </div>
   </div>
    <!-- Agrega aquí tus scripts JS adicionales si los tienes -->
</body>
</html>
