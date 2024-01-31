<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="../estilos.js"></script>
    <!-- Incluye jQuery desde un CDN (Content Delivery Network) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluye la librería DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <title>Listado de Preguntas</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Estilo para el contenido principal */
        .content {
            margin-left: 10%;
            transition: margin-left 0.5s;
            padding: 0px;
            width: 80%;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .edit-link {
            background-color: #28a745; /* Estilo para Editar */
            color: #fff;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px; /* Agregamos margen derecho para separar los enlaces */
        }

        .delete-link {
            background-color: #dc3545; /* Estilo para Eliminar */
            color: #fff;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            border: none; /* Elimina el borde */
        }


        /* Estilos al pasar el ratón sobre los enlaces */
        .edit-link:hover {
            background-color: #1e7e34;
        }

        .delete-link:hover {
            background-color: #c82333;
        }

        /* Estilo para el contenedor de búsqueda y botones */
        .search-bar {
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Alinea los elementos a la derecha */
            margin-top: -25px;
            margin-bottom: -10px;
        }

        .search-bar a.print-button {
            background-color: #3b2ad3; /* Color verde para "Agregar" */
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Estilo para el fondo de los encabezados */
        .header-background {
            background-color: #f2f2f2 !important; /* !important para forzar el estilo */
        }

        #preguntasTable {
            border-collapse: collapse;
            width: 100%;
        }

        #preguntasTable th,
        #preguntasTable td {
            border: 1px solid #f2f2f2;
            padding: 8px;
            text-align: left;
            background-color: white; /* Fondo blanco en todas las celdas */
        }

        #preguntasTable th {
            background-color: #f2f2f2;
        }

        #preguntasTable tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="content">
        <?php
        include '../categorias/db_connect.php';

        // Procesar la búsqueda
        if (isset($_GET['busqueda'])) {
            $busqueda = $_GET['busqueda'];
            $stmt = $conn->prepare("SELECT * FROM tbl_preguntas WHERE ID_PREGUNTA = :busqueda OR PREGUNTA LIKE :busqueda");
            $stmt->bindValue(':busqueda', $busqueda);
            $stmt->execute();
            $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si no se ha realizado una búsqueda, muestra todas las preguntas
            $stmt = $conn->query("SELECT * FROM tbl_preguntas");
            $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        ?>

        <h1><span class="fas fa-question"></span>Preguntas</h1>

        <!-- Tu tabla HTML -->
        <table id="preguntasTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pregunta</th>
                    <th>Creado</th>
                    <th>
                        Fecha de Creación
                        <span class="filter-icon" id="filterIcon"><i class="fas fa-filter"></i></span>
                        <div id="filterContainer" style="display: none;">
                            <input type="text" id="filterFecha" placeholder="YYYY-MM" />
                        </div>
                    </th>
                    <th>Modificado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($preguntas as $pregunta) { ?>
                    <tr>
                        <td><?php echo $pregunta['ID_PREGUNTA']; ?></td>
                        <td><?php echo strtoupper($pregunta['PREGUNTA']); ?></td>
                        <td><?php echo strtoupper($pregunta['CREADO_POR']); ?></td>

                        <td class="fecha-creacion" data-fecha="<?php echo date("Y-m-d", strtotime($pregunta['FECHA_CREACION'])); ?>">
                            <?php echo date("Y-m-d", strtotime($pregunta['FECHA_CREACION'])); ?>
                        </td>
                        <td><?php echo $pregunta['MODIFICADO_POR']; ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $pregunta['ID_PREGUNTA']; ?>" class="edit-link"><i class="fas fa-edit"></i></a>
                             <!-- Formulario de confirmación para eliminar -->
                           <form action="eliminar.php" method="post" style="display: inline;">
                             <input type="hidden" name="ID_PREGUNTA" value="<?php echo $pregunta['ID_PREGUNTA']; ?>">
                             <button type="submit" name="eliminar" class="delete-link"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Botón "plus" pegado a la tabla -->
        <div class="search-bar" style="text-align: right; margin-top: -207px;">
            <a href="crear.php" class="print-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i></a>
        </div>

        <!-- Script para inicializar DataTables y agregar la funcionalidad de filtrado -->
        <script>
            $(document).ready(function () {
                var table = $('#preguntasTable').DataTable({
                    "dom": 'lBfrtip',
                    "buttons": ['copy', 'excel', 'pdf', 'print'],
                    "ordering": false, // Deshabilitar la ordenación inicial
                    "paging": false, // Deshabilitar la paginación
                    "info": false, // Deshabilitar el mensaje de información
                    "language": {
                        "search": "Buscar" // Cambiar el texto del cuadro de búsqueda
                    },
                    "columnDefs": [
                        {
                            "targets": 'thead th', // Aplicar a todas las celdas del encabezado
                            "className": 'header-background' // Clase de estilo para el fondo
                        }
                    ]
                });

                // Mostrar/ocultar el campo de filtro al hacer clic en el icono
                $('#filterIcon').on('click', function () {
                    $('#filterContainer').toggle();
                });

                // Aplicar el filtro al cambiar el valor del campo de entrada
                $('#filterFecha').on('keyup', function () {
                    var filterValue = $(this).val();
                    table.column(3).search(filterValue).draw();
                });
            });
        </script>

        <script>
            // Espera a que el documento esté completamente cargado
            $(document).ready(function () {
                // Espera un breve momento para asegurarte de que DataTables haya terminado de inicializarse
                setTimeout(function () {
                    // Ajusta la posición del cuadro de búsqueda
                    $('div.dataTables_filter').css({
                        'text-align': 'left',
                        'margin-top': '10px',
                        'margin-right': '40px' // Puedes ajustar este valor según tus necesidades
                    });

                    // Ajusta la posición del icono plus
                    $('.search-bar .print-button').css({
                        'position': 'absolute',
                        'right': '1px', // Ajusta este valor según tus necesidades
                        'top': '65px' // Ajusta este valor según tus necesidades
                    });
                }, 100);
            });
        </script>

    </div>
</body>

</html>

