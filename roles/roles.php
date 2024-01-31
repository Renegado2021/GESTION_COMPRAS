<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../estilos.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>


    <style>
        /* Estilo para el contenido principal */
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

        /* Estilo para el fondo de los encabezados */
        .header-background {
            background-color: #f2f2f2 !important;
        }

        #rolTable {
            border-collapse: collapse;
            width: 100%;
            border-bottom: 1px solid transparent; /* Borde inferior transparente */
        }

        #rolTable th,
        #rolTable td {
          border: 1px solid #f2f2f2;
          padding: 8px;
          text-align: left;
          background-color: white;
        }

       #rolTable th {
         background-color: #f2f2f2;
   
        }

       /* Ajusta el ancho del borde vertical */
       #rolTable td {
          border-right: 1px solid #f2f2f2; /* Puedes ajustar el valor según sea necesario */
        }

       #rolTable tr:last-child td {
         border-bottom: 1px solid #f2f2f2; /* Borde inferior de la última fila */
        }

        /* Estilo para el contenedor de botones */
        .button-container {
          display: flex;
          justify-content: flex-end; /* Alinea los elementos al final del contenedor (derecha) */
          margin-right: 25px;
        }

        /* Estilo para el botón de editar */
        .styled-button.edit-button {
          background-color: #4CAF50; /* Fondo verde para "Editar" */
          color: #fff; /* Color del texto blanco */
          border: 1px solid #4CAF50; /* Borde del mismo color que el fondo */
          margin-right: 5px; /* Espacio entre botones */
          padding: 6px 10px;
          border-radius: 5px; /* Bordes redondeados */

        }

        /* Estilo para el botón de eliminar */
        .styled-button.delete-button {
          background-color: #ff3333; /* Color de fondo rojo */
          color: #fff; /* Color del texto blanco */
          border: 1px solid #ff3333; /* Borde del mismo color que el fondo */
          margin-right: 5px; /* Espacio entre botones */
          padding: 6px 10px;
          border-radius: 5px; /* Bordes redondeados */
        }

        /* Estilo para el botón de llave (asignar permisos) */
        .styled-button.assign-button {
          background-color: brown; /* Color de fondo cafe */
          color: #fff; /* Color del texto blanco */
          border: 1px solid brown; /* Borde del mismo color que el fondo */
          margin-right: 5px; /* Espacio entre botones */
          padding: 6px 10px;
          border-radius: 5px; /* Bordes redondeados */
        }

        .button-search {
         display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-right: -10px;
            margin-bottom:-45px; /* Ajusta este valor según sea necesario para la posición vertical */
           
        }

        .button-search a {
            margin-left: 10px;
            text-decoration: none;
            padding: 8px 8px;
            border-radius: 4px;
            color: #fff;
            z-index: 1; /* Asegura que el botón esté por encima de la tabla */
        }

        .print-button {
            background-color: blue; /* Ajusta según sea necesario */
        }

        .pdf-button {
            background-color: orange; /* Ajusta según sea necesario */
            
        }

        .fa-download {
            background-color: yellow; /* Ajusta según sea necesario */
            color: #000; /* Color del texto negro */
        }
    </style>
</head>
<body>
    <div class="content">
        <h1><span class="fas fa-key"></span>Roles</h1>
    
        <div class="button-search">
            <a href="../roles/agregar_roles.php" class="print-button plus-button" onclick="toggleFloatingForm()">
                <i class="fas fa-plus"></i>
            </a>
            <a href="pdf_roles.php" class="pdf-button">
                <i class="fas fa-file-pdf"></i>
            </a>
            <a href="excel.php" class="fa-download">
                <i class="fas fa-download"></i>
            </a>
        </div>

        <table id="rolTable" class="display">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Rol</th>
                    <th>Fecha Creación
                     <span class="filter-icon" id="filterIcon"><i class="fas fa-filter"></i></span>
                        <div id="filterContainer" style="display: none;">
                            <input type="text" id="filterFecha" placeholder="YYYY-MM" />
                        </div>
                    </th>
                    <th>Fecha de Modificación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                   // Conexión a la base de datos
                   include('../conexion/conexion.php');

                   // Consulta para obtener los roles
                   $consulta = mysqli_query($conn, "SELECT * FROM tbl_ms_roles");

                   // Arreglo para mapear las letras a las palabras completas
                   $estados = array(
                      'A' => 'Activo',
                      'I' => 'Inactivo',
                      'B' => 'Bloqueado'
                    );

                    // Recorremos los resultados
                   while ($rol = mysqli_fetch_assoc($consulta)) {
                      echo "<tr>";
                       echo "<td style='padding: 10px;'>" . $rol['ID_ROL'] . "</td>";
                      echo "<td>" . $rol['NOMBRE_ROL'] . "</td>";
                      echo '<td class="fecha-creacion" data-fecha="' . date("Y-m-d", strtotime($rol['FECHA_CREACION'])) . '">'
                      . date("Y-m-d", strtotime($rol['FECHA_CREACION'])) . '</td>';
                      $fechaModificacion = date('Y-m-d', strtotime($rol["FECHA_MODIFICACION"]));
                      echo "<td>".$fechaModificacion."</td>";
                      // Verifica si $rol['ESTADO_ROL'] es una clave válida en el array $estados
                      $estado = isset($estados[$rol['ESTADO_ROL']]) ? $estados[$rol['ESTADO_ROL']] : 'Desconocido';
                      echo "<td>" . $estado . "</td>";

                      echo "<td class='button-container'>";
                      echo "<a href='../roles/editar_roles.php?id=" . $rol['ID_ROL'] . "' class='styled-button edit-button'><i class='fas fa-edit'></i></a>";
                      echo "<form method='post' action='eliminar_roles.php'>";
                      echo "<input type='hidden' name='ID_ROL' value='" . $rol['ID_ROL'] . "'>";
                      echo "<button type='submit' name='eliminar' class='styled-button delete-button' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este rol?')\"><i class='fas fa-trash'></i></button>";
                      echo "</form>";
                      echo "<a href='../roles/asignar_permisos.php?id_rol=" . $rol['ID_ROL'] . "' class='styled-button assign-button'><i class='fas fa-key key-icon'></i></a>";
                      echo "</td>";
                
                      echo "</tr>";
                    }

                  // Cerramos la conexión
                  mysqli_close($conn);
                ?>
            </tbody>
        </table>
        <br>
        <button class="styled-button" onclick="window.location.href='../setting/ajustes.php'" style="background-color: #007bff; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">Regresar</button>
    </div>

    <script>
       $(document).ready(function () {
           var table = $('#rolTable').DataTable({
              "dom": 'lBfrtip',
              "buttons": ['copy', 'excel', 'pdf', 'print'],
              "ordering": false,
               "paging": false,
              "info": false,
              "language": {
                  "search": "Buscar",
                  "zeroRecords": "No se encontraron registros",
                  "infoEmpty": "Mostrando 0 de 0 registros",
                  "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                  "emptyTable": "No hay datos disponibles en la tabla",
                  "infoPostFix": "",
                  "thousands": ",",
                  "lengthMenu": "Mostrar _MENU_ registros por página",
                  "loadingRecords": "Cargando...",
                  "processing": "Procesando...",
                  "sEmptyTable": "No hay datos disponibles en la tabla",
                  "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                  "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                   "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix": "",
                  "sInfoThousands": ",",
                  "sLengthMenu": "Mostrar _MENU_ registros",
                  "sLoadingRecords": "Cargando...",
                  "sProcessing": "Procesando...",
                  "sSearch": "Buscar:",
                  "sZeroRecords": "No se encontraron registros",
                  "paginate": {
                      "first": "Primero",
                      "last": "Último",
                      "next": "Siguiente",
                       "previous": "Anterior"
                    }
                },
               "columnDefs": [
                   {
                      "targets": 'thead th',
                      "className": 'header-background'
                    }
                ]
            });

           $('#filterIcon').on('click', function () {
              $('#filterContainer').toggle();
            });

            $('#filterFecha').on('keyup', function () {
               var filterValue = $(this).val();
               table.column(2).search(filterValue).draw();
            });
       
        });
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('div.dataTables_filter').css({
                    'text-align': 'left',
                    'margin-top': '10px',
                    'margin-right': '120px'
                });

                $('.search-bar .print-button').css({
                    'position': 'absolute',
                    'right': '1px',
                    'top': '65px'
                });
            }, 100);
        });
    </script>

</body>
</html>
