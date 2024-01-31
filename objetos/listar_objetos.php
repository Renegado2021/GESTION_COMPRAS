<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Objetos</title>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.25/sorting/datetime-moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <style>
       body {
         font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
        }

        .content {
          margin-left: 10%;
          transition: margin-left 0.5s;
          padding: 20px;
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

        #objetosTable {
          border-collapse: collapse;
          width: 100%;
        }

        #objetosTable th, #objetosTable td {
         border: 2px solid #f2f2f2;
         padding: 8px;
         text-align: left;
         background-color: white; /* Fondo blanco en todas las celdas */
        }

        #objetosTable th {
          background-color: #f2f2f2;
        }

        #objetosTable tr:nth-child(even) {
          background-color: #f2f2f2;
        }

       .search-bar {
          display: flex;
          align-items: center;
          justify-content: flex-end;
          margin-top: 20px;
        }

       .print-button {
         background-color: #007bff;
         color: #fff;
         padding: 8px 12px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         text-decoration: none;
         margin-right: 10px;
        }

        .back-link {
          background-color: #007bff;
          color: #fff;
           font-size: 14px; /* Tamaño de fuente más pequeño */
          padding: 8px 16px; /* Ajusta el relleno según sea necesario */
          text-decoration: none;
          border-radius: 5px;
        }

        .filter-icon {
         margin-left: 10px;
          cursor: pointer;
        }

        #fecha_filtro {
          margin-left: 10px;
          padding: 6px;
        }

        /* Estilo específico para el botón de regresar */
      .back-link {
         background-color: #007bff;
         color: #fff;
        }

        /* Estilo específico para el botón de agregar */
        .print-button {
         background-color: #007bff;
         color: #fff;
           padding: 8px 10px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          text-decoration: none;
          margin-right: 20px;
          margin-top: 18px;
          margin-left: 0; /* Ajusta el valor de margin-left según sea necesario */
        }

        .actions-cell {
         white-space: nowrap;
        }

        .actions-cell a {
         margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="content">
        <?php
            include '../objetos/db_connect.php';

            if (isset($_GET['busqueda'])) {
                $busqueda = $_GET['busqueda'];

                if (in_array($busqueda, ['Activo', 'Inactivo', 'Bloqueado', 'Nuevo'])) {
                    $stmt = $conn->prepare("SELECT * FROM tbl_objetos WHERE estado_objeto = :estado_busqueda");
                    $stmt->bindValue(':estado_busqueda', $busqueda);
                } else {
                    $stmt = $conn->prepare("SELECT * FROM tbl_objetos WHERE id_objeto = :busqueda OR nombre_objeto LIKE :busqueda");
                    $stmt->bindValue(':busqueda', $busqueda);
                }

                $stmt->execute();
                $objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $conn->query("SELECT * FROM tbl_objetos");
                $objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        ?>

        <h1><i class="fas fa-cube"></i> Objetos</h1>

        <table id="objetosTable" class="display">
            <thead>
                <tr>
                    <th>Nombre del Objeto</th>
                    <th>Descripción</th>
                    <th>
                    <div class="search-bar">
                    Fecha de Creación
                        <i id="filterIcon" class="fas fa-filter filter-icon" onclick="toggleFechaFiltro()"></i>
                        <div id="filterContainer" style="display: none;">
                          <input type="date" id="fecha_filtro" onchange="filtrarPorFecha()">
                       </div>
                    </div>
                    </th>
                    <th>Fecha de Modificación</th>
                    <th>Creado por</th>
                    <th>Modificado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objetos as $objeto) { ?>
                    <tr>
                        <td><?php echo $objeto['NOMBRE_OBJETO']; ?></td>
                        <td><?php echo $objeto['DESCRIPCION']; ?></td>
                        <td><?php echo $objeto['FECHA_CREACION']; ?></td>
                        <td><?php echo $objeto['FECHA_MODIFICACION']; ?></td>
                        <td><?php echo $objeto['CREADO_POR']; ?></td>
                        <td><?php echo $objeto['MODIFICADO_POR']; ?></td>
                        <td class="actions-cell">
                        
                          <a href='estadisticas_objetos.php?id=<?php echo $objeto['ID_OBJETO']; ?>' class='view-link btn btn-info'><i class='fas fa-eye'></i></a>
                          <a href='editar_objeto.php?id=<?php echo $objeto['ID_OBJETO']; ?>' class='edit-link btn btn-success'><i class='fas fa-pencil-alt'></i></a>
                           <a href='eliminar_objeto.php?id=<?php echo $objeto['ID_OBJETO']; ?>' class='delete-link btn btn-danger'><i class='fas fa-trash'></i></a>
                       </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="search-bar">
            <a href="crear_objeto.php" class="print-button"><i class="fas fa-plus"></i></a>
        </div>

        <div class="actions-cell">
            <a href="../admin/administrar.php" class="back-link"><i class="fas fa-arrow-left"></i> Regresar</a>
        </div>

        <script>
            
            $(document).ready(function() {
                var table = $('#objetosTable').DataTable({
                    "dom": 'lBfrtip',
                    "buttons": ['copy', 'excel', 'pdf', 'print'],
                    "ordering": false,
                    "paging": false,
                    "info": false,
                    "language": {
                        "search": "Buscar"
                    },
                    "columnDefs": [
                        {
                            "targets": 'thead th',
                            "className": 'header-background'
                        }
                    ]
                });

              
            
                // Configuración del filtro de fecha
                $('#fecha_filtro').on('change', function () {
                    var filterValue = $(this).val();
                    table.column(2).search(filterValue).draw();
                });

                // Ajustes de estilo
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

          function toggleFechaFiltro() {
             $('#filterContainer').toggle();
            }
        </script>
    </div>
</body>
</html>

