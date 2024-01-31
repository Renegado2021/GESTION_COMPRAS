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
    <title>Listado de Departamentos</title>

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
        
        .actions-cell {
    display: flex;
    align-items: center;
}

/* Estilo para los enlaces de editar y eliminar */
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

        #departamentosTable {
          border-collapse: collapse;
          width: 100%;
        }

        #departamentosTable th, #departamentosTable td {
         border: 1px solid #f2f2f2;
         padding: 8px;
         text-align: left;
         background-color: white; /* Fondo blanco en todas las celdas */
        }

        #departamentosTable th {
          background-color: #f2f2f2;
        }

        #departamentosTable tr:nth-child(even) {
          background-color: #f2f2f2;
        }

      /* Estilo para el enlace de regresar fuera de la tabla */
      .back-link {
         display: block;
         text-align: center;
         margin-top: 220px; /* Ajusta el espacio entre la tabla y el enlace según sea necesario */
         background-color: #007bff; /* Estilo para Regresar */
         color: #fff;
         padding: 10px 20px;
          text-decoration: none;
          border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="content">
       <?php
          include 'db_connect.php';

           // Procesar la búsqueda
           if (isset($_GET['busqueda'])) {
             $busqueda = $_GET['busqueda'];

              // Verificar si la búsqueda es un estado específico
              if (in_array($busqueda, ['Activo', 'Inactivo', 'Bloqueado', 'Nuevo'])) {
                 $stmt = $conn->prepare("SELECT d.id_departamento, e.nombre_empresa, d.nombre_departamento, d.estado_departamento, d.creado, d.fecha_creacion, d.fecha_modificacion, d.modificado_por 
                               FROM tbl_departamentos d 
                               JOIN tbl_empresa e ON d.id_empresa = e.id_empresa 
                               WHERE d.estado_departamento = :estado_busqueda");
                    $stmt->bindValue(':estado_busqueda', $busqueda);
                } else {
                  // Si no es un estado específico, buscar por id o nombre
                   $stmt = $conn->prepare("SELECT d.id_departamento, e.nombre_empresa, d.nombre_departamento, d.estado_departamento, d.creado, d.fecha_creacion, d.fecha_modificacion, d.modificado_por 
                               FROM tbl_departamentos d 
                               JOIN tbl_empresa e ON d.id_empresa = e.id_empresa 
                               WHERE d.id_departamento = :busqueda OR d.nombre_departamento LIKE :busqueda");
                    $stmt->bindValue(':busqueda', $busqueda);
               }

              $stmt->execute();
              $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
               // Si no se ha realizado una búsqueda, muestra todos los departamentos
               $stmt = $conn->query("SELECT d.id_departamento, e.nombre_empresa, d.nombre_departamento, d.estado_departamento, d.creado, d.fecha_creacion, d.fecha_modificacion, d.modificado_por 
                        FROM tbl_departamentos d 
                        JOIN tbl_empresa e ON d.id_empresa = e.id_empresa");
                $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

        ?>

        <h1><span class="fas fa-folder"></span>Departamentos</h1>

        <!-- Tu tabla HTML -->
        <table id="departamentosTable" class="display">
            <thead>
                <tr>
                    
                    <th>Empresa</th>
                    <th>Departamento</th>
                    <th>Estado</th>
                    <th>Creado por</th>
                    <th>
                        Fecha de Creación 
                        <span class="filter-icon" id="filterIcon"><i class="fas fa-filter"></i></span>
                        <div id="filterContainer" style="display: none;">
                            <input type="text" id="filterFecha" placeholder="YYYY-MM" />
                        </div>
                    </th>
                    <th>Fecha de Modificación</th>
                    <th>Modificado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departamentos as $departamento) { ?>
                    <tr>
                        <td><?php echo $departamento['nombre_empresa']; ?></td>
                        <td><?php echo $departamento['nombre_departamento']; ?></td>
                        <td>
                         <?php
                              $estadoCodigo = $departamento['estado_departamento'];
                              $estadoCompleto = '';
                              switch ($estadoCodigo) {
                                  case 'A':
                                     $estadoCompleto = 'Activos';
                                      break;
                                  case 'I':
                                      $estadoCompleto = 'Inactivo';
                                      break;
                                    case 'B':
                                       $estadoCompleto = 'Bloqueado';
                                       break;
                                    case 'N':
                                      $estadoCompleto = 'Nuevo';
                                        break;
                                    default:
                                     $estadoCompleto = 'Desconocido';
                                }
           
                                echo $estadoCompleto;
                           ?>
                       </td>
                        <td><?php echo $departamento['creado']; ?></td>

                        <td class="fecha-creacion" data-fecha="<?php echo date("Y-m-d", strtotime($departamento['fecha_creacion'])); ?>">
                            <?php echo date("Y-m-d", strtotime($departamento['fecha_creacion'])); ?>
                        </td>
                        <td><?php echo date("Y-m-d", strtotime($departamento['fecha_modificacion'])); ?></td>
                        <td><?php echo $departamento['modificado_por']; ?></td>
                        <td>
                        <div class="actions-cell">
                          <a href="editar_departamento.php?id=<?php echo $departamento['id_departamento']; ?>" class="edit-link"><i class="fas fa-edit"></i></a>
                          <a href="eliminar_departamento.php?id=<?php echo $departamento['id_departamento']; ?>" class="delete-link"><i class="fas fa-trash-alt"></i></a>
                      </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Botón "plus" pegado a la tabla -->
        <div class="search-bar" style="text-align: right; margin-top: -207px;">
            <a href="crear_departamento.php" class="print-button" onclick="toggleFloatingForm()"><i class="fas fa-plus"></i></a>
        </div>

        
        <div class="actions-cell">
          <a href="../admin/administrar.php" class="back-link"><i class="fas fa-arrow-left"></i> Regresar</a>
        </div>
        
        <!-- Script para inicializar DataTables y agregar la funcionalidad de filtrado -->
        <script>
          $(document).ready(function() {
               var table = $('#departamentosTable').DataTable({
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
                  table.column(5).search(filterValue).draw();
                });
            });
        </script>

        <script>
           // Espera a que el documento esté completamente cargado
          $(document).ready(function() {
             // Espera un breve momento para asegurarte de que DataTables haya terminado de inicializarse
               setTimeout(function() {
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
