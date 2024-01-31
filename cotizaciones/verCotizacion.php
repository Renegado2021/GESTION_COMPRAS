<?php
  include '../conexion/conexion.php';

  // Obtener datos de la cotización
  $id_cotizacion = isset($_GET['id']) ? $_GET['id'] : null;
  $cotizacion = null;

   if ($id_cotizacion !== null) {
      // Utilizar consulta preparada
       $query = "SELECT c.*, p.NOMBRE AS NOMBRE_PROVEEDOR 
              FROM tbl_cotizacion c
              INNER JOIN tbl_proveedores p ON c.ID_PROVEEDOR = p.ID_PROVEEDOR
              WHERE c.ID_COTIZACION = ?";
    
       // Preparar la consulta
       $stmt = mysqli_prepare($conn, $query);

       // Vincular parámetros
       mysqli_stmt_bind_param($stmt, "i", $id_cotizacion);

       // Ejecutar la consulta
       mysqli_stmt_execute($stmt);

       // Obtener resultado
       $result = mysqli_stmt_get_result($stmt);

       if ($result) {
         $cotizacion = mysqli_fetch_assoc($result);
        } else {
          echo 'Error en la consulta: ' . mysqli_error($conn);
        }

      // Cerrar la consulta preparada
       mysqli_stmt_close($stmt);

       // Obtener detalles de la cotización
       $query_detalle = "SELECT * FROM tbl_cotizacion_detalle WHERE ID_COTIZACION = $id_cotizacion";
       $result_detalle = mysqli_query($conn, $query_detalle);

       // Resto del código para mostrar detalles...
    } else {
      echo 'Error: Falta el parámetro "id" en la URL.';
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Cotización</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }

        /* Estilo para dividir la tabla en dos columnas */
        .split-table {
            width: 30%;
            float: left;
        }
    </style>
</head>
<body>

   <h2>Detalle de Cotización</h2>

    <?php
      // Verifica si $cotizacion está definida antes de utilizarla
      if ($cotizacion !== null) {
          ?>
               <div class="split-table">
                  <table>
                     <tr>
                         <th>Número de Cotización</th>
                          <td><?php echo isset($cotizacion['NUMERO_COTIZACION']) ? $cotizacion['NUMERO_COTIZACION'] : ''; ?></td>
                      </tr>
                       <tr>
                          <th>Solicitud</th>
                          <td><?php echo isset($cotizacion['ID']) ? $cotizacion['ID'] : ''; ?></td>
                       </tr>
                       <tr>
                          <th>Fecha</th>
                          <td><?php echo isset($cotizacion['FECHA_COTIZACION']) ? $cotizacion['FECHA_COTIZACION'] : ''; ?></td>
                       </tr>
                   </table>
               </div>
    
               <div class="split-table">
                  <table>
                     <tr>
                         <th>Proveedor</th>
                         <td><?php echo isset($cotizacion['NOMBRE_PROVEEDOR']) ? $cotizacion['NOMBRE_PROVEEDOR'] : ''; ?></td>
                       </tr>
                       <tr>
                          <th>Departamento</th>
                          <td><?php echo isset($cotizacion['DEPARTAMENTO']) ? $cotizacion['DEPARTAMENTO'] : ''; ?></td>
                       </tr>
                       <tr>
                          <th>Estado</th>
                          <td><?php echo isset($cotizacion['ESTADO']) ? $cotizacion['ESTADO'] : ''; ?></td>
                        </tr>
                   </table>
                    <br><br> 
                </div>

    
               <table>
                  <tr>
                     <th>Cantidad</th>
                      <th>Descripción</th>
                       <th>Categoría</th>
                  </tr>
                  <?php
                     // Obtener detalles de la cotización
                      $query_detalle = "SELECT * FROM tbl_cotizacion_detalle WHERE ID_COTIZACION = $id_cotizacion";
                      $result_detalle = mysqli_query($conn, $query_detalle);

                      while ($detalle = mysqli_fetch_assoc($result_detalle)) {
                          echo '<tr>';
                          echo '<td>' . $detalle['CANTIDAD'] . '</td>';
                          echo '<td>' . $detalle['DESCRIPCION'] . '</td>';

                          // Obtener nombre de la categoría
                          $id_categoria = $detalle['ID_CATEGORIA'];
                          $query_categoria = "SELECT categoria FROM tbl_categorias WHERE id = $id_categoria";
                           $result_categoria = mysqli_query($conn, $query_categoria);

                          // Verificar si la consulta de la categoría fue exitosa
                          if ($result_categoria) {
                              $categoria = mysqli_fetch_assoc($result_categoria);
                              echo '<td>' . $categoria['categoria'] . '</td>';
                            } else {
                                echo '<td>Error al obtener la categoría</td>';
                            }

                         echo '</tr>';
                        }
                    ?>
               </table>
           <?php
        } else {
          echo 'No se encontraron detalles de la cotización.';
        }

        // Cierra la conexión a la base de datos al final del script
       mysqli_close($conn);
    ?>

</body>
</html>


