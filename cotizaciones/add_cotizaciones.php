<?php
include 'db_connection.php';

$idSolicitud = $_GET['id']; // Obtener el ID de solicitud desde la URL

// Recupera y muestra información de la solicitud
$sqlSolicitud = "SELECT S.*, S.codigo, U.nombre_usuario, D.nombre_departamento
FROM tbl_solicitudes S
INNER JOIN tbl_ms_usuario U ON S.usuario_id = U.id_usuario
INNER JOIN tbl_departamentos D ON S.idDepartamento = D.id_departamento
WHERE S.id = $idSolicitud";

$resultSolicitud = $conn->query($sqlSolicitud);

if ($resultSolicitud->num_rows > 0) {
    $rowSolicitud = $resultSolicitud->fetch_assoc();
    $numeroSolicitud = $rowSolicitud['id']; // Asigna el número de solicitud a la variable $numeroSolicitud
    $codigo = $rowSolicitud['codigo'];
    $nombreUsuario = $rowSolicitud['nombre_usuario'];
    $departamentoSolicitud = $rowSolicitud['nombre_departamento'];
    $estadoSolicitud = $rowSolicitud['estado'];
} else {
    echo "Solicitud no encontrada.";
    exit; // Agregamos un exit para detener la ejecución si no se encuentra la solicitud
}

// Obtener las descripciones desde la base de datos
$sqlDescripciones = "SELECT DISTINCT descripcion FROM tbl_productos WHERE id_solicitud = $idSolicitud";
$resultDescripciones = $conn->query($sqlDescripciones);

// Crear un array para almacenar descripciones únicas
$descripciones = array();

while ($rowDescripcion = $resultDescripciones->fetch_assoc()) {
    $descripcion = $rowDescripcion["descripcion"];
    $descripciones[] = $descripcion;
}


?>




<!DOCTYPE html>
<html>
<head>
    <title>Agregar Cotización</title>
    <!-- Agrega esta línea en la sección head de tu HTML -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            text-align: left;
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10);
            background-image: url('../imagen/IHCIS.jpg');
            background-size: 40%;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            text-align: left;
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
            background-color: powderblue;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .table {
        flex: 3; /* 48% para cada tabla con espacio entre ellas */
        margin: -5px 0px; /* Ajusta el margen izquierdo negativo para mover las tablas a la izquierda y margen derecho de 1px */
       
        margin: right 1px; /* Ajusta el margen izquierdo negativo para mover las tablas a la izquierda */
        box-sizing: border-box;
        background-color: cornsilk;
        
    }

        table {
            width: 90%;
            border-collapse: collapse;
            
            
        }
        th, td {
            border: 0px solid black;
            padding: 8px;
        }



        th {
            background-color: bisque;
        }

        button-row {
            text-align: center;
            margin-top: 20px;
        }

        .table-title {
            text-align: center;
            font-weight: bold;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-field {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            padding: 5px;
            
        }

        .form-field label {
            width: 100px;
            text-align: right;
            padding-right: 10px;
        }

        .form-field input,
        .form-field select,
        .form-field textarea {
            flex: 1;
        }

        .right-align {
    text-align: left;
}





 /* Clase para la segunda tabla */
 .product-table {
           
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
            width: 15%; /* Puedes ajustar este valor según tus necesidades */
        }

        .quantity-column {
            width: 20%; /* Puedes ajustar este valor según tus necesidades */
        }

        .description-column {
            width: 40%; /* Puedes ajustar este valor según tus necesidades */
        }

        .URL-column {
            width: 30%; /* Puedes ajustar este valor según tus necesidades */
        }
        .custom-textarea {
    width: 100%; /* O ajusta el ancho según tus preferencias */
    height: 100px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    color: blue; /* Color azul para los enlaces */
    text-decoration: underline; /* Subrayar los enlaces */
}


     /* Estilo para que los elementos tengan el mismo ancho */
     .same-width {
        border: none;
        outline: none; /* Elimina el contorno al enfocar el elemento */
        width: 60%;
        box-sizing: border-box;
        margin-bottom: 1px;
    }

    .cant-input {
        border: 1px solid #ccc; /* Borde de 1 píxel de color gris claro */
        /*outline: none; /* Elimina el contorno al enfocar el elemento */
        width: 60%;
        height: 60px; /* Altura deseada del cuadro de texto */
    }

    /* Estilo adicional para separar las secciones */
    .section {
        margin-bottom: 10px;
        width: 110%;
        
    }

    /* Estilo para los botones "Crear" y "Cancelar" */
    .custom-button {
        width: 150px;
        height: 50px;
        margin-bottom: 15px; /* Ajusta el espacio entre los botones según sea necesario */
       
       
    }

    /* Estilo para el botón "Crear" */
    .custom-button.create-button {
        background-color: #007bff; /* Azul */
    color: #fff; /* Texto blanco */
    border: 1px solid #0056b3; /* Borde azul más oscuro */
    margin-right: 40px;
    
    }

    /* Estilo adicional para el botón "Cancelar" */
.btn-warning.custom-button.cancel-button {
    background-color: #808080; /* Gris */
    color: #fff; /* Texto blanco */
    border: 1px solid #555; /* Borde gris más oscuro */
    margin-top: 10px; /* Ajusta el margen superior según sea necesario */
}
    </style> 


</head>
<body>
<div class="container">
    <div class="table">
    <form method="post" action="">
            <table style="width: 40%; margin: 0 auto;">
                <h2 style="text-align: center;">Información de Solicitud</h2>
               
                <tr>
                    <th>Còdigo:</th>
                    <td><?php echo $codigo; ?></td>
                </tr>
                <tr>
                   <th>Usuario:</th>
                   <td><?php echo $nombreUsuario; ?></td>
                   
                </tr>
                <tr>
                    <th>Departamento:</th>
                    <td><?php echo $departamentoSolicitud; ?></td>
                   
                </tr>
                <tr>
                    <th>Estado:</th>
                    <td><?php echo $estadoSolicitud; ?></td>
                </tr>
            </table>
        </form>
    </div>

   <!-- Tabla 1: Proveedor, Departamento, Monto, Número de Cotización, Fecha y Estado -->
<div class="table" >
    <h2 style="text-align: center;">Cotización</h2>
    <script>
        // Definir un array JavaScript con las descripciones obtenidas desde PHP
        var descripciones = <?php echo json_encode($descripciones); ?>;

        function cargarDescripciones() {
            var select = document.getElementById("descripcion");
            // Limpiar las opciones actuales
            select.innerHTML = '<option value="">Seleccione</option>';
            
            // Agregar las nuevas opciones desde el array JavaScript
            for (var i = 0; i < descripciones.length; i++) {
                select.innerHTML += '<option value="' + descripciones[i] + '">' + descripciones[i] + '</option>';
            }
        }
    </script>
    <form  method="post" action="guardar_cotizacion.php?id=<?php echo $idSolicitud; ?>">

        <table style="margin-right: 300px;" >
            <tr>
                <th>Proveedor:</th>
                <td>
                    <select name="id_proveedor" required style="max-width: 200px;">
                        <option value="">--Seleccione--</option>
                        <?php
                        // Ajusta la consulta para buscar proveedores con estado "A" (activo)
                        $sql = "SELECT ID_PROVEEDOR, NOMBRE FROM tbl_proveedores WHERE ESTADO_PROVEEDOR = 'A'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["ID_PROVEEDOR"] . "'>" . $row["NOMBRE"] . "</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No hay proveedores activos disponibles</option>";
                        }
                        ?>
                    </select>
                </td>
                <th>Número:</th>
                <td><input type="text" name="numero_cotizacion" style="max-width: 100px;"></td>
            </tr>
            <tr>
                <th>Departamento:</th>
                <td ><input required type="text" name="departamento" value="<?php echo $departamentoSolicitud; ?>" ></td>

                <th>Fecha:</th>
                <td><input type="date" name="fecha_cotizacion" style="max-width: 100px;"></td>
            </tr>
            <tr>
                <th>URL:</th>
                <td>
                    <textarea name="url" required class="custom-textarea" rows="2"></textarea>
                </td>
                <th>Estado:</th>
                <td><input type="text" name="ESTADO" value="Proceso" required style="max-width: 100px;"></td>

            </tr>
           
          
        </table>

           <!-- Tabla 2: Cantidad, Categoría y Descripción -->
<div class="section">
    <table class="product-table" id="productosTable">
        <tr>
        <th class="quantity-column">Cantidad</th>
        <th class="description-column">Descripción</th>
        <th class="category-column">Categoría</th>
      
            <div>
           <!-- Aquí se agregarán dinámicamente los productos -->


    <!-- Agregamos un llamado a cargarDescripciones() al cargar la página -->
    <script>
        window.onload = function() {
            cargarDescripciones();
        };
    </script>

        </tr>
        <!-- Aquí se agregarán dinámicamente los productos -->
    </table>

    <!-- Botón "Agregar Producto" para la segunda tabla -->
    <div class="section">
    <button type="button" id="agregarProductoBtn" style="margin-right: -45px;">Agregar Producto</button>
    <div>
    <input type="submit" value="Crear" class="custom-button create-button">

    <input type="button" value="Cancelar" class="btn btn-warning custom-button cancel-button" onclick="window.location.href='cotizaciones.php';">

    </div>
            </div>
    </form>

</div>
</div>



</div>


<!-- Agregamos un llamado a cargarDescripciones() al cargar la página -->
<script>
    window.onload = function () {
        cargarDescripciones();

        // Agregar evento al botón "Agregar Producto"
        $("#agregarProductoBtn").on("click", function () {
            agregarProducto();
        });
    };

    function cargarDescripciones() {
        // Realizar la solicitud AJAX
        $.ajax({
            url: 'get_descripciones.php?id=' + <?php echo $idSolicitud; ?>,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Limpiar y agregar las nuevas opciones al contenedor
                var listaDescContainer = $('#listaDescripcionesContainer');
                listaDescContainer.empty();

                // Agregar etiqueta y select de descripciones
                listaDescContainer.append('<label for="descripcion">Descripción:</label>');
                var selectDescHTML = '<select name="descripcion[]" class="same-width" required onchange="actualizarCategorias(this)">';
                selectDescHTML += '<option value="">Seleccione</option>';
                $.each(data, function (index, value) {
                    selectDescHTML += '<option value="' + value + '">' + value + '</option>';
                });
                selectDescHTML += '</select>';
                listaDescContainer.append(selectDescHTML);
            },
            error: function (error) {
                console.log('Error al cargar las descripciones:', error);
            }
        });
    }

    function agregarProducto() {
    var table = document.getElementById("productosTable");
    var newRow = table.insertRow(-1);

    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
   

    // Lógica PHP para obtener las descripciones desde la base de datos
    <?php
    $idSolicitud = $_GET['id']; // Reemplaza con el ID de la solicitud correspondiente

    // Obtén las descripciones para la solicitud
    $sqlDescripciones = "SELECT DISTINCT descripcion FROM tbl_productos WHERE id_solicitud = $idSolicitud";
    $resultDescripciones = $conn->query($sqlDescripciones);

    echo 'var descripciones = [];';
    while ($rowDescripcion = $resultDescripciones->fetch_assoc()) {
        $descripcion = $rowDescripcion["descripcion"];
        echo 'descripciones.push("' . $descripcion . '");';
    }
    ?>

    // Construir el campo de texto para la cantidad
    var cantidadHTML = '<input type="number" name="cantidad[]" class="cant-input centered" required>';

    // Construir el select de descripciones con opciones desde JavaScript
    var selectDescHTML = '<select name="descripcion[]" class="same-width" required onchange="actualizarCategorias(this)">';
    selectDescHTML += '<option value="">Seleccione</option>';
    <?php
    foreach ($descripciones as $descripcion) {
        echo 'selectDescHTML += \'<option value="' . $descripcion . '">' . $descripcion . '</option>\';';
    }
    ?>
    selectDescHTML += '</select>';

    // Construir el campo de texto para la categoría
    var categoriaHTML = '<input type="text" name="categoria[]" class="same-width" value="">';

   

    // Asignar el HTML generado al elemento de las celdas
    cell1.innerHTML = cantidadHTML;
    cell2.innerHTML = selectDescHTML;
    cell3.innerHTML = categoriaHTML;
  
}



function actualizarCategorias(selectDesc) {
    var selectedDesc = selectDesc.value;
    
    // Realiza una nueva solicitud AJAX para obtener la categoría correspondiente a la descripción seleccionada
    $.ajax({
        url: 'get_categorias.php',
        type: 'POST',
        data: { descripcion: selectedDesc },
        success: function (data) {
            // Actualiza el campo de categoría con el valor devuelto por la solicitud AJAX
            selectDesc.parentNode.nextElementSibling.querySelector('input[name="categoria[]"]').value = data;
            categoriaInput.value = data;
        },
        error: function (error) {
            console.log('Error al cargar la categoría:', error);
        }
    });
}
</script>


</body>
</html>


