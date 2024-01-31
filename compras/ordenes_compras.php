<!DOCTYPE html>
<html>
<head>
    <title>Orden de Compra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        .solicitud-table {
            border-collapse: collapse;
            width: 100%;
        }

        .solicitud-table th, .solicitud-table td {
            border: 1px solid #ddd;
            padding: 9px;
            text-align: left;
        }

        .solicitud-table th {
            background-color: #f2f2f2;
        }

        .btn-eliminar {
         color: red;
         border: none; /* Esta propiedad quita los bordes de los botones */
         background-color: white;
        }

       /* Estilo para el botón "Agregar" */
       .print-button {
         float: right;
          margin-left: 10px;
          text-decoration: none;
          padding: 10px 10px;
          background-color: #007bff;
          color: #fff;
          border: none;
          border-radius: 5px;
          cursor: pointer;
        }

        .filter-icon {
            cursor: pointer;
        }

        #fecha_filtro {
            display: none;
        }
    </style>
</head>
<body>
    <?php
      include('../conexion/conexion.php');

        // Almacena el contenido del encabezado
        $headerContent = '
         <h2>Orden de Compra</h2>
         <table class="solicitud-table">
               <tr>
                 <th>Número de Orden</th>
                  <th>
                     Fecha
                      <i class="fas fa-filter filter-icon" onclick="toggleFechaFiltro()"></i>
                     <input type="date" id="fecha_filtro" onchange="filtrarPorFecha()">
                   </th>
                  <th>Proveedor</th>
                  <th>Contacto</th>
                  <th>Acciones</th>
               </tr>
        ';

        // Verificar si se proporcionó una fecha para filtrar
        if (isset($_GET['fecha_filtro'])) {
         $fechaFiltro = date('Y-m-d', strtotime($_GET['fecha_filtro']));
          $sql = "SELECT * FROM tbl_orden_compra WHERE FECHA_ORDEN = '$fechaFiltro'";
        } else {
          $sql = "SELECT * FROM tbl_orden_compra";
        }

        $result = $conn->query($sql);

        $rowsContent = '';

       if ($result->num_rows > 0) {
           while ($row = $result->fetch_assoc()) {
                $rowsContent .= '<tr>';
                $rowsContent .= '<td>' . $row["NUMERO_ORDEN"] . '</td>';
                $rowsContent .= '<td>' . date("d/m/Y", strtotime($row["FECHA_ORDEN"])) . '</td>';
               $rowsContent .= '<td>' . obtenerProveedor($conn, $row["ID_PROVEEDOR"]) . '</td>';
                $rowsContent .= '<td>' . obtenerContacto($conn, $row["ID_CONTACTO"]) . '</td>';
                $rowsContent .= '<td>';
                $rowsContent .= '<a href="ver_orden.php?numero_orden=' . $row["NUMERO_ORDEN"] . '" class="btn btn-primary"><i class="fas fa-eye"></i></a>';
                $rowsContent .= '<button class="btn btn-eliminar" onclick="eliminarSolicitud(' . $row["NUMERO_ORDEN"] . ')"><i class="fas fa-trash"></i></button>';
                $rowsContent .= '</td>';
                $rowsContent .= '</tr>';
            }
        } else {
          $rowsContent .= '<tr><td colspan="5">No hay datos en esa fecha.</td></tr>';
        }

       // Devolver el contenido del encabezado y las filas de la tabla
       echo $headerContent . $rowsContent;

       $conn->close();

       function obtenerProveedor($conn, $idProveedor) {
          $sqlProveedor = "SELECT NOMBRE FROM tbl_proveedores WHERE ID_PROVEEDOR = $idProveedor";
          $resultProveedor = $conn->query($sqlProveedor);
          return ($resultProveedor->num_rows > 0) ? $resultProveedor->fetch_assoc()["NOMBRE"] : '';
        }

        function obtenerContacto($conn, $idContacto) {
            if ($idContacto !== null) {
             $sqlContacto = "SELECT NOMBRE FROM tbl_contactos_proveedores WHERE ID_CONTACTO_PROVEEDOR = $idContacto";
              $resultContacto = $conn->query($sqlContacto);
              return ($resultContacto->num_rows > 0) ? $resultContacto->fetch_assoc()["NOMBRE"] : '';
            }
            return '';
        }
    ?>
    </table>

    <script>
        
        function eliminarSolicitud(id) {
            var confirmarEliminar = confirm("¿Estás seguro de que deseas eliminar la orden con ID " + id + "?");

            if (confirmarEliminar) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../compras/eliminar.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var respuesta = xhr.responseText;
                        if (respuesta === "Orden eliminada exitosamente") {
                            location.reload();
                        } else {
                            alert(respuesta);
                        }
                    } else if (xhr.readyState === 4 && xhr.status !== 200) {
                        alert("Error al eliminar la orden");
                    }
                };

                xhr.send("id=" + id);
            }
        }

        function toggleFechaFiltro() {
          $('#fecha_filtro').toggle();
        }

        function filtrarPorFecha() {
          var fechaFiltro = $('#fecha_filtro').val();

           $.ajax({
              url: 'ordenes_compras.php',
              type: 'GET',
              data: { fecha_filtro: fechaFiltro },
               success: function(data) {
                   var tbody = $('.solicitud-table tbody');

                  // Limpiar el contenido actual del cuerpo de la tabla
                  tbody.html('');

                   if (data.trim() !== '') {
                      // Si hay datos, agregar el contenido al cuerpo de la tabla
                      tbody.append(data);
                    } else {
                      // Si no hay datos, agregar la fila informativa
                      tbody.html('<tr><td colspan="5">No hay datos en esa fecha.</td></tr>');
                    }

                   console.log('Tabla actualizada correctamente.');
                },
                   error: function(jqXHR, textStatus, errorThrown) {
                   console.error('Error al realizar la solicitud AJAX:', textStatus, errorThrown);
                }
            });
        }

        // Añadir esta función para controlar la visibilidad del encabezado
       function toggleHeaderVisibility() {
          var fechaFiltro = $('#fecha_filtro').val();
          var header = $('h2:contains("Orden de Compra")');

          if (fechaFiltro && fechaFiltro.trim() !== '' && header.is(':visible')) {
              header.hide();
            } else if ((!fechaFiltro || fechaFiltro.trim() === '') && !header.is(':visible')) {
              header.show();
            }
        }

        // Llamar a la función toggleHeaderVisibility() en el cambio de la fecha
       $('#fecha_filtro').on('change', toggleHeaderVisibility);

    </script>
</body>
</html>