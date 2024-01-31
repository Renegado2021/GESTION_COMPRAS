<?php
   include('db.php');

  $search = isset($_GET['search']) ? $_GET['search'] : '';

    $query = "SELECT * FROM tbl_proveedores WHERE 
          (ID_PROVEEDOR = ? OR NOMBRE LIKE ? OR DIRECCION LIKE ? OR TELEFONO = ? OR CORREO_ELECTRONICO LIKE ? OR LOWER(ESTADO_PROVEEDOR) = ?)";

    if (!empty($search)) {
       $searchParam = "%$search%";
       $searchLower = strtolower($search); // Convertir a minúsculas
       $stmt = $conexion->prepare($query);
       $stmt->bind_param("isssss", $search, $searchParam, $searchParam, $search, $searchParam, $searchLower);
       $stmt->execute();
       $result = $stmt->get_result();
    } else {
      $result = $conexion->query("SELECT * FROM tbl_proveedores");
    }

?>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <title>Listado de Proveedores</title>
    <style>
       
       body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
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

       /* Estilo para el fondo de los encabezados */
       .header-background {
         background-color: #f2f2f2 !important; /* !important para forzar el estilo */
        
        }

        #proveedoresTable {
          border-collapse: collapse;
          width: 100%;
        }

        #proveedoresTable th, #proveedoresTable td {
         border: 1px solid #f2f2f2;
         padding: 8px;
         text-align: left;
         background-color: white; /* Fondo blanco en todas las celdas */
        }

        #proveedoresTable th {
          background-color: #f2f2f2;
        }

        #proveedoresTable tr:nth-child(even) {
          background-color: #f2f2f2;
        
        }

        .button-container {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 10px;
    
}
.btn-crear {
    background-color: blue;
    color: #fff;
    padding: 6px 10px;
    border: none;
    cursor: pointer;
    position: relative;
    right: 0; /* Ajusta la posición hacia la derecha */
    top: 55px; /* Ajusta la posición relativa hacia abajo */
    margin-right: -10px; /* Ajusta el margen derecho para separar del borde de la tabla */
    z-index: 1; /* Asegura que el botón esté por encima de la tabla */
}








        /* Estilo para los botones de acción */
       .action-link-blue,
       .action-link-green,
       .action-link-red {
           text-decoration: none;
           color: #fff; /* Color de texto blanco */
           padding: 8px 12px; /* Relleno del botón */
           border-radius: 5px; /* Bordes redondeados */
           margin-right: 5px; /* Margen derecho para separar los botones */
           display: inline-block;
        }

        /* Cambia el fondo y el icono para el botón "Ver" */
        .action-link-blue {
          background-color: #0078d4; /* Fondo azul para "Ver" */
        }

       /* Cambia el fondo y el icono para el botón "Editar" */
       .action-link-green {
         background-color: #4CAF50; /* Fondo verde para "Editar" */
        }

        /* Cambia el fondo y el icono para el botón "Eliminar" */
       .action-link-red {
           background-color: #FF0000; /* Fondo rojo para "Eliminar" */
        }

       /* Agrega un estilo de hover común para los botones de acción */
        .action-link-blue:hover,
        .action-link-green:hover,
        .action-link-red:hover {
          text-decoration: none;
          opacity: 0.8; /* Reduce la opacidad al pasar el ratón para indicar interactividad */
        }

       /* Estilo específico para los números del pie de página */
       #proveedoresTable_paginate span a,
        #proveedoresTable_paginate .paginate_button a {
         color: #0078d4 !important; /* Azul para el texto del número y los signos de paginación */
           font-size: 10px; /* Tamaño de fuente más pequeño */
         margin: 0 2px; /* Añade un margen a los lados de los números y los signos de paginación */
       }   

       /* Estilo para el número de página activo */
       #proveedoresTable_paginate span.current a,
       #proveedoresTable_paginate .paginate_button.current a {
         font-weight: bold; /* Texto en negrita para indicar la página actual */
         color: #0078d4 !important; /* Azul para el texto del número activo y los signos de paginación */
        }

       /* Ajustar el tamaño de los signos de paginación al pasar el cursor */
        #proveedoresTable_paginate span a:hover,
        #proveedoresTable_paginate .paginate_button a:hover {
          color: blue !important; /* Color más oscuro al pasar el cursor */
          font-size: 1px; /* Mantener el mismo tamaño de fuente al pasar el cursor */
        }

    </style>
</head>
<body>

    <div class="content">
       <h2><i class="fas fa-truck"></i> Proveedores</h2>
        
        <div class="button-container" >
            <a href="crear_proveedor.php"><button class="btn-crear"><i class="fas fa-plus"></i></button></a>
        </div>
   
      <table id="proveedoresTable" class="display">
           <thead>
               <tr>  
                  <th>Nombre</th>
                   <th>Correo Electrónico</th>
                   <th>Fecha Creación
                     <span class="filter-icon" id="filterIcon"><i class="fas fa-filter"></i></span>
                        <div id="filterContainer" style="display: none;">
                            <input type="text" id="filterFecha" placeholder="YYYY-MM" />
                        </div>
                    </th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
              <?php
                  while ($row = $result->fetch_assoc()) {
                      echo '<tr>';
                
                       echo '<td>' . $row['NOMBRE'] . '</td>';
                       echo '<td>' . $row['CORREO_ELECTRONICO'] . '</td>';
                       echo '<td class="fecha-creacion" data-fecha="' . date("Y-m-d", strtotime($row['FECHA_CREACION'])) . '">'
                      . date("Y-m-d", strtotime($row['FECHA_CREACION'])) . '</td>';
              
                       $estado_proveedor = '';
                       if ($row['ESTADO_PROVEEDOR'] == 'A') {
                           $estado_proveedor = 'Activo';
                        } elseif ($row['ESTADO_PROVEEDOR'] == 'I') {
                          $estado_proveedor = 'Inactivo';
                        } elseif ($row['ESTADO_PROVEEDOR'] == 'B') {
                          $estado_proveedor = 'Bloqueado';
                        }
                       echo '<td>' . $estado_proveedor . '</td>';
                       echo "<td><a href='ver_proveedor.php?id=" . $row['ID_PROVEEDOR'] . "' class='action-link-blue'><i class='fas fa-eye'></i></a>";
                       echo "<a href='actualizar_proveedor.php?id=" . $row['ID_PROVEEDOR'] . "' class='action-link-green'><i class='fas fa-edit'></i></a>";
                       echo "<a href='eliminar_proveedor.php?id=" . $row['ID_PROVEEDOR'] . "' class='action-link-red'><i class='fas fa-trash-alt'></i></a></td>";
                       echo '</tr>';
                    }

                   // Verifica si no se encontraron resultados
                   if ($result->num_rows === 0) {
                     echo '<tr><td colspan="6">No se encontraron resultados.</td></tr>';
                    }
                ?>
           </tbody>
      </table>

    

      <script>
            $(document).ready(function() {
              var table = $('#proveedoresTable').DataTable({
                 "dom": 'lBfrtip',
                  "buttons": ['copy', 'excel', 'pdf', 'print'],
                  "ordering": false, // Deshabilitar la ordenación inicial
                 "paging": true, // Habilitar la paginación
                   "info": false, // Mostrar el mensaje de información
                  "lengthMenu": [20, 25, 50, 100], // Opciones de longitud
                  "language": {
                     "search": "Buscar", // Cambiar el texto del cuadro de búsqueda
                      "paginate": {
                         "next": "&#9654;", // Código de la flecha derecha para "Siguiente"
                         "previous": "&#9664;" // Código de la flecha izquierda para "Anterior"
                        },
                       "lengthMenu": "Mostrar _MENU_", // Texto para la longitud del menú         
               
                    },
                    "columnDefs": [
                       {
                          "targets": 'thead th', // Aplicar a todas las celdas del encabezado
                          "className": 'header-background' // Clase de estilo para el fondo
                        }
                    ]
               });

               $('#filterIcon').on('click', function() {
                  $('#filterContainer').toggle();
                });

                $('#filterFecha').on('keyup', function() {
                  var filterValue = $(this).val();
                   table.column(3).search(filterValue).draw();
                });
            });
        </script>


        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('div.dataTables_filter').css({
                        'text-align': 'left',
                        'margin-top': '10px',
                        'margin-right': '40px'
                    });

                    $('.search-bar .print-button').css({
                        'position': 'absolute',
                        'right': '1px',
                        'top': '65px'
                    });
                }, 100);
            });
        </script>

    </div>
</body>
</html>











