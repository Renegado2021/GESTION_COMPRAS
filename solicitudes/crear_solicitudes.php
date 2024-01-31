<?php
// Inicia la sesión antes de cualquier salida hacia el navegador
session_start();
include '../conexion/conexion.php';


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuarioId"])) {
    // El usuario no ha iniciado sesión, redirige a la página de inicio de sesión
    header("Location: index.php");
    exit();
}

// Recuperar el ID del usuario de la sesión
$usuariosId = $_SESSION["usuarioId"];
$usuariosNombre = obtenerNombreUsuario($conn, $usuariosId);

function obtenerNombreUsuario($conn, $usuarioId) {
    $server = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

// Crear conexión
$conn = new mysqli($server, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    // SQL para obtener el nombre del usuario
    $sql = "SELECT nombre_usuario FROM tbl_ms_usuario WHERE id_usuario = ?";

    // Prepara la sentencia SQL
    $stmt = $conn->prepare($sql);

    // Vincula el parámetro a la sentencia preparada
    $stmt->bind_param("i", $usuarioId);

    // Ejecuta la sentencia SQL
    $stmt->execute();

    // Obtiene el resultado de la ejecución
    $result = $stmt->get_result();

    // Verifica si hay filas en el resultado
    if ($result->num_rows > 0) {
        // Obtiene la fila asociada al resultado
        $row = $result->fetch_assoc();
        // Retorna el valor del nombre de usuario
        return $row["nombre_usuario"];
    } else {
        // Manejar el caso en que no se encuentra el nombre del usuario
        return 'Usuario Desconocido';
    }
}

function obtenerNuevoCodigoSolicitud($conn) {
    
    // Obtener el próximo código de solicitud
    $sql = "SELECT MAX(codigo) + 1 AS nuevo_codigo FROM tbl_solicitudes";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $nuevoCodigo = ($row['nuevo_codigo'] != null) ? $row['nuevo_codigo'] : 1;

    return $nuevoCodigo;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" crossorigin="anonymous" />

    
    <title>Crear Solicitud</title>
    <style>
         body {
            text-align: center;
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.20);
            background-image: url('../imagen/IHCIS.jpg');
           background-size: 40%;
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
            display: inline-block;
            text-align: center;
            border: 1px solid #ccc;
            padding: 40px;
            margin: 20px;
            background-color: powderblue; /* Color de fondo azul claro (cielo) */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
            opacity: 0.9; /* Valor de opacidad (menos transparente) */
        }

        .invoice {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: cornsilk; /* Color de fondo  para las tablas */
        }

        .invoice th, .invoice td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            width: 50%; /* Puedes ajustar este valor según tus necesidades */
        }

        .invoice select, .invoice input {
            width: 50%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .codigo-input {
          margin-left: 46px; /* Ajusta el valor según sea necesario para codigo*/
        }

        .user-input {
          margin-left: 40px; /* Ajusta el valor según sea necesario para usuario*/
        }

        .estado-input {
          margin-left: 66px; /* Ajusta el valor según sea necesario para estado*/
        }
        .centered {
            text-align: center !important;
            font-size: 26px; /* Tamaño de fuente más grande, ajusta según tus preferencias */
        }

       
        /* Estilo adicional para separar las secciones */
        .section {
            margin-bottom: 20px;
        }

        /* Clase para la segunda tabla */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
           background-color: cornsilk; /* Color de fondo  para las tablas */
        }


        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
          background-color: bisque;
        }

        .product-table select, .product-table input {
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        /* Ancho personalizado para cada columna */
        .category-column {
            width: 30%; /* Puedes ajustar este valor según tus necesidades */
        }

        .quantity-column {
            width: 10%; /* Puedes ajustar este valor según tus necesidades */
        }

        .description-column {
            width: 50%; /* Puedes ajustar este valor según tus necesidades */
        }

        /* Estilo adicional para el cuadro de texto de la descripción */
    .description-textarea {
        border: 1px solid #ccc; /* Borde de 1 píxel de color gris claro */
        width: 100%; /* Ancho del 100% para ocupar todo el contenedor */
        height: 80px; /* Altura deseada del cuadro de texto */
        resize: vertical; /* Permitir la redimensión vertical */
    }

     /* Estilo para que los elementos tengan el mismo ancho */
     .same-width {
        border: none;
        outline: none; /* Elimina el contorno al enfocar el elemento */
        width: 100%;
        height: 50px; /* Altura deseada del cuadro de texto */
        box-sizing: border-box;
        margin-bottom: 1px;
    }

    .cant-input {
        
        border: 1px solid #ccc; /* Borde de 1 píxel de color gris claro */
        /*outline: none; /* Elimina el contorno al enfocar el elemento */
        width: 100%;
        height: 30px; /* Altura deseada del cuadro de texto */
    }

    .button-container {
        display: flex;
        flex-direction: column; /* Alinea los elementos en columna */
        justify-content: flex-start; /* Alinea los elementos al principio del contenedor */
        align-items: flex-start; /* Alinea los elementos al principio del contenedor */
        margin-top: 20px; /* Ajusta el margen superior según sea necesario */
    }

    /* Estilo para el botón "Agregar" */
    .btn-danger.custom-button {
        height: 30px;
        width: 35px;
        background-color: #28a745; /* Verde */
        border: 1px solid #218838; /* Borde verde más oscuro */
        margin-bottom: 10px; /* Ajusta el espacio entre los botones según sea necesario */
    }

  /* Estilo para alinear a la derecha */
.text-right {
    text-align: right;
}

/* Estilo para el botón con el icono */
.custom-button {
    position: relative;
    
}

/* Estilo para el icono dentro del botón */
.custom-button i {
    color: blue; /* Texto blanco */
    font-size: 1.5em; /* Ajusta el tamaño del icono según sea necesario */
    position: absolute;
    left: 6px; /* Ajusta la posición del icono según sea necesario */
    top: 50%;
    transform: translateY(-50%);
}



   /* Estilos comunes para ambos botones */
.custom-button {
    width: 100px; /* Ajusta el ancho según sea necesario */
    padding: 8px 16px; /* Ajusta el relleno según sea necesario */
    font-size: 14px; /* Ajusta el tamaño de la fuente según sea necesario */
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}

/* Estilos específicos para el botón "Crear" */
.create-button {
    background-color: #007bff; /* Azul */
    color: #fff; /* Texto blanco */
    border: 1px solid #0056b3; /* Borde azul más oscuro */
}

/* Estilos específicos para el botón "Cancelar" */
.cancel-button {
    background-color: #808080; /* Gris */
    color: #fff; /* Texto blanco */
    border: 1px solid #555; /* Borde gris más oscuro */
}


   

    </style>
</head>
<body>

    <div class="container">
    <?php
    // Mostrar mensajes de éxito o error si se pasaron en la sesión
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<p style="color: green;">Solicitud creada con éxito.</p>';
    } elseif (isset($_GET['error_message']) && $_GET['error_message']) {
        $error_message = urldecode($_GET['error_message']);
        echo '<p style="color: red;">Error: ' . htmlspecialchars($error_message) . '</p>';
    }
    ?>
      
        <form method="post" action="procesar_solicitud.php">
            <!-- Sección 1: Información de Solicitud -->
            <div class="section">
             <br><br>
                <table class="invoice">
                    <tr>
                        <th class="centered" colspan="2">SOLICITUD</th>
                    </tr>
                    <tr>
                        <td>
                         <label for="codigo">Código:</label>
                         <input type="text" name="codigo" class="codigo-input" value="<?php echo obtenerNuevoCodigoSolicitud($conn); ?>" readonly required><br>




                            <label for="idDepartamento">Departamento:</label>
                            <select id="idDepartamento" name="idDepartamento" required>
                                <option value="" >Seleccione</option>
                                <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "gestion_compras2";
            
            // Hacer una consulta para obtener los departamentos desde la base de datos
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }
            
            $sql = "SELECT id_departamento, nombre_departamento FROM tbl_departamentos";
            $result = $conn->query($sql);
            
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["id_departamento"] . '">' . $row["nombre_departamento"] . '</option>';
            }
            
            $conn->close();
        ?>
                            </select><br>

                            <label for="usuario_nombre">Usuario:</label>
<input type="text" id="usuario_nombre" name="usuario_nombre" class="user-input" value="<?php echo obtenerNombreUsuario($conn, $usuariosId); ?>" required>


                        
                        </td>
                        <td>
                            <label for="fecha_ingreso">Fecha de Ingreso:</label>
                            <input type="text" name="fecha_ingreso" value="<?php echo date("Y-m-d"); ?>" readonly><br>

                            <label for="estado">Estado:</label>
                            <input type="text" name="estado" value="Proceso" class="estado-input"required>
                            <div class="text-right">
                              <button class="btn btn-danger custom-button " onclick="agregarProducto()">
                                 <i class="fas fa-plus-circle "></i>
                              </button>
                            </div>






                        </td>
                    </tr>
                </table>
            </div>

            <!-- Sección 2: Productos -->
            <div class="section">
                <table class="product-table" id="productosTable">
               
                    <tr>
                        <th class="quantity-column">Cantidad</th>
                        <th class="category-column">Categoría</th>
                        <th class="description-column">Descripción</th>
                        <th class="action-column">Acción</th>
                      
                    </tr>
                 
                    <tr>
                       
                  
                    </tr>
          
                </table>
        
            </div>

           
            <div class="button-container">
    <!-- Botones para enviar la solicitud y cancelar -->
    <div>
    <input type="submit" value="Crear" class="custom-button create-button">
    <input type="button" value="Cancelar" class="custom-button cancel-button" onclick="window.location.href='../solicitudes/solicitudes.php';">
</div>

</div>


           
        </form>
    </div>
    <script>
  
  function agregarProducto() {
    var table = document.getElementById("productosTable");
    var newRow = table.insertRow(-1);

    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3); // Nueva celda para el botón de eliminar

    // Construir el select con opciones desde PHP
    var selectHTML = '<select name="categoria[]" class="same-width" required>';
    selectHTML += '<option value="">Seleccione</option>';

    // Lógica PHP para obtener las categorías
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gestion_compras2";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $sql = "SELECT id, categoria FROM tbl_categorias";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo 'selectHTML += \'<option value="' . $row["id"] . '">' . $row["categoria"] . '</option>\';';
    }

    $conn->close();
    ?>
    selectHTML += '</select>';

    // Limpiar el contenido actual antes de agregar el nuevo select
    cell2.innerHTML = '';
    // Asignar el HTML generado al elemento de la celda
    cell2.innerHTML = selectHTML;

    // El resto de la construcción de celdas puede ir aquí
    cell1.innerHTML = '<input type="number" name="cantidad[]" class="cant-input " required>';
    cell3.innerHTML = '<textarea name="descripcion[]" class="description-textarea" required>';

    // Construir el botón de eliminar
    var eliminarBtn = document.createElement('button');
    eliminarBtn.type = 'button';
    eliminarBtn.className = 'eliminar-btn';
    eliminarBtn.innerHTML = '<img src="../imagen/delete.png" alt="Eliminar" width="20" height="20">';
    eliminarBtn.onclick = function () {
        eliminarProducto(newRow);
    };

    cell4.appendChild(eliminarBtn);
    cell4.className = 'text-right'; // Agregar la clase para alinear a la derecha

    // Añadir clase text-right al botón "Agregar" para alinearlo a la derecha
    document.querySelector('.custom-button').style.float = 'right';
}

// Función para eliminar una fila
function eliminarProducto(row) {
    var table = document.getElementById("productosTable");
    table.deleteRow(row.rowIndex);
}

</script>

 <!-- Agregar script JavaScript para limpiar el mensaje después de un tiempo -->
 <script>
        // Limpiar la URL después de 3 segundos (3000 milisegundos)
        setTimeout(function() {
            var urlWithoutError = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, urlWithoutError);
        }, 3000);
    </script>

  

</body>
</html>