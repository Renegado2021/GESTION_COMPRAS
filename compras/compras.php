
<!DOCTYPE html>
<html>
<head>
    <title>Orden de Compra</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        /* Estilos para la cabecera de la orden de compra */
        .order-header {
            margin-right: 100px;
            float: left;
            margin-top: 20px;
        }

        /* Estilos para la tabla de la orden de compra */
        .order-table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 20px;
        }

       .order-table th, .order-table td {
         border: 1px solid black;
         padding: 8px;
         text-align: center;
         white-space: nowrap; /* Evitar que el texto se ajuste automáticamente a la siguiente línea */
         overflow: hidden;
         text-overflow: ellipsis; /* Mostrar puntos suspensivos (...) cuando el texto es demasiado largo */
        }

        .order-table th:first-child,
        .order-table td:first-child {
          width: 5%; /* Ajusta el ancho de la primera columna según tus necesidades */
        }

        .order-table th:nth-child(2),
        .order-table td:nth-child(2) {
         width: 40%; /* Ajusta el ancho de la segunda columna según tus necesidades */
        }

       .order-table th:nth-child(3),
       .order-table td:nth-child(3),
       .order-table th:nth-child(4),
       .order-table td:nth-child(4) {
          width: 15%; /* Ajusta el ancho de las columnas 3 y 4 según tus necesidades */
        }

       .order-table th:last-child,
       .order-table td:last-child {
         width: 20%; /* Ajusta el ancho de la última columna según tus necesidades */
        }

        /* Estilos para la firma del solicitante */
        .order-signature {
            margin-top: 20px;
        } 

    </style>

    <style>
    
      /* Estilos para la tabla de orden total */
   /* Contenedor de la tabla de orden total */
.order-total-container {
    position: relative;
}

/* Estilos para la tabla de orden total */
.order-total {
    position: absolute;
    top: 0;
    left: 370px; /* Ajusta el margen derecho según tus necesidades */
    text-align: right;
}

.order-total table {
    width: 39%;
    border-collapse: collapse;
    margin-top: 0px;
    margin-left: auto; /* Centra la tabla horizontalmente */
    margin-right: auto; /* Centra la tabla horizontalmente */
}

.order-total td {
    /* Estilos para las celdas de la tabla de orden total */
    border: 1px solid black;
    padding: 8px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


.order-total td:first-child {
    width: 5%;
}

.order-total td:nth-child(2) {
    width: 39%;
}

.order-total td:nth-child(3),
.order-total td:nth-child(4) {
    width: 39%;
}

.order-total td:last-child {
    width: 39%;
}

       .centered-content {
         display: flex;
         flex-direction: column;
         justify-content: center;
         align-items: center;
         height: 10vh;     
        }

       .botones-container {
         display: flex;
        }

      .boton-azul,
      .boton-verde {
         background-color: blue;
         color: white;
         padding: 10px 15px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         text-decoration: none; /* Para quitar el subrayado en el enlace */
         margin-right: 10px; /* Espaciado entre los botones */
        }

       .boton-verde {
         background-color: green;
        }

    </style>
</head>
<body>
    <div style="text-align: center; position: relative;">
         
      <img src="../imagen/IHCIS.jpg" alt="Logo 1" style="width: 60px; position: absolute; top: 0; left: 0; margin-left: 30px;">

      <div class="centered-content" style="display: inline-block; text-align: center; margin: 0; padding: 0;">
         <p style="margin: 0;"><strong>INSTITUTO HONDUREÑO DE CULTURA INTERAMERICANA</strong></p>
         <p style="margin: 0;"><strong>RTN 08019995223469</strong></p>
          <p style="margin: 0; color: blue;"><strong>SOLICITUD COMPRAS</strong></p>
       </div>


       <img src="../imagen/IHCI1.jpg" alt="Logo 2" style="width: 70px; position: absolute; top: 0; right: 0; margin-right: 140px;">

    </div>

    <div class="order-header">
        <?php
         // Conecta a la base de datos
         $servername = "localhost";
         $username = "root";
         $password = "";
         $dbname = "gestion_compras2";

          $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Error de conexión: " . $conn->connect_error);
            }
            
           
          // Consulta para obtener el número de orden más alto
          $sql = "SELECT MAX(NUMERO_ORDEN) as max_numero_orden FROM tbl_orden_compra";
           $result = $conn->query($sql);
  
            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $max_numero_orden = $row["max_numero_orden"];
              $nuevo_numero_orden = str_pad((intval($max_numero_orden) + 1), 4, '0', STR_PAD_LEFT);
            } else {
              // Si no hay órdenes en la base de datos, comienza con 0001
               $nuevo_numero_orden = "0001";
            }
        ?>
        
        <form action="guardar_orden.php" method="POST">
       
       
      <!-- Elemento para mostrar el ID de la cotización -->
       <p id="cotizacionIdDisplay">ID de Cotización: </p>

        <p style="display: flex; justify-content: space-between;">
              <span>Nº de Pedido: <?php echo $nuevo_numero_orden; ?></span>
            </p>
           <input type="hidden" name="numero_orden" value="<?php echo $nuevo_numero_orden; ?>">

           <p style="display: flex; align-items: center;"> <!-- Utilizamos flexbox para alinear verticalmente los elementos -->
             <span style="width: 71px;">Fecha:</span> <!-- Establecemos un ancho fijo para el label de la fecha -->
             <input type="text" name="fecha_orden" style="margin-left: 10px; width: 188px;" value="<?php echo date("Y-m-d"); ?>">
            </p>

            <p style="display: flex; align-items: center;">
              <span style="width: 80px;">Proveedor:</span>
                <select name="proveedor" id="proveedorSelect" onchange="seleccionarProveedor()">
                 <option value="">Selecciona un proveedor</option>
                   <?php
                     $servername = "localhost";
                     $username = "root";
                     $password = "";
                      $dbname = "gestion_compras2";

                     // Obtener el ID de la cotización desde la URL
                      $cotizacionId = isset($_GET['cotizacion_id']) ? $_GET['cotizacion_id'] : null;

                        // Validar el ID de la cotización
                       if ($cotizacionId === null || !is_numeric($cotizacionId)) {
                          // Manejar el error, redirigir o mostrar un mensaje de error
                          echo "ID de cotización no válido";
                           exit;
                        }

                      // Establecer la conexión con la base de datos
                      $conn = new mysqli($servername, $username, $password, $dbname);
                      if ($conn->connect_error) {
                          die("Error de conexión: " . $conn->connect_error);
                        }

                      $sql = "SELECT ID_PROVEEDOR, NOMBRE FROM tbl_proveedores WHERE ESTADO_PROVEEDOR = 'A'";
                       $result = $conn->query($sql);

                       if ($result === false) {
                            die("Error en la consulta: " . $conn->error);
                        }

                       if ($result->num_rows > 0) {
                          while ($proveedor = $result->fetch_assoc()) {
                               echo '<option value="' . $proveedor["ID_PROVEEDOR"] . '" data-cotizacion-id="' . $cotizacionId . '">' . $proveedor["NOMBRE"] . '</option>';
                            }
                        } else {
                          echo "No se encontraron proveedores activos en la base de datos.";
                        }

                      // Cierra la conexión con la base de datos
                       $conn->close();
                    ?>
                </select>
            </p>


            <p style="display: flex; align-items: center;"> <!-- Utilizamos flexbox para alinear verticalmente los elementos -->
              <span style="width: 80px;">Contacto:</span> <!-- Establecemos un ancho fijo para el label de contacto -->
              <select name="contacto" id="contactoSelect" style="width: 195px;">
                 <option value="">Selecciona un contacto</option>
               </select>
           </p>

    </div>
    <!-- Tabla para mostrar detalles de cotización -->
   
    <table class="order-table">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Descripción del Artículo</th>
                <th>Precio Unitario</th>
                <th>Valor Total</th>
                <th>Excento</th>
                
            </tr>

        </thead>
        <tbody id=cotizacionesTable>
        </tbody>
       
    </table>

  <!-- Calcular Sub-Total, 15% ISV y Total Factura debajo de la tabla -->
  <div class="order-total-container">
  <div class="order-total">
        <table>
            <tbody>
                <tr>
                    <td style=" border: none;  text-align: right; font-weight: bold;"><label>Sub-Total:</label></td>
                    <td><span class="subtotalValue" style="font-weight: bold;">0.00</span></td>
                    <input type="hidden" name="subtotal" id="subtotalInput" value="0,00">
                </tr>
                <tr>
                    <td style=" border: none;  text-align: right; font-weight: bold;"><label>15% ISV:</label></td>
                    <td><span id="isvValue" style="font-weight: bold;">0.00</span></td>
                    <input type="hidden" name="isv" id="isvInput" value="">
                </tr>
                <tr>
                    <td style=" border: none;  text-align: right; font-weight: bold;"><label>Total Factura:</label></td>
                    <td><span id="totalValue" style="font-weight: bold;">0.00</span></td>
                    <input type="hidden" id="total_factura_input" name="total_factura" value="0.00">
                </tr>
            </tbody>
        </table>
    </div>
    </div>

   
    <br><br>
    <div class="botones-container">
      
     <input type="button" id="btnGuardarOrden" class="boton-verde" value="Guardar Orden">
      <a href="../solicitudes/solicitudes.php" class="boton-azul">Regresar</a>
    </div>
    </form>

    <script>
        function seleccionarProveedor() {
         var selectElement = document.getElementById('proveedorSelect');
         var cotizacionIdInput = document.getElementById('cotizacionIdInput');

         var cotizacionId = selectElement.options[selectElement.selectedIndex].getAttribute('data-cotizacion-id');
         cotizacionIdInput.value = cotizacionId;

         cargarCotizaciones();
        }
    </script>

    <script>
      $(document).ready(function() {
            $("#proveedorSelect").on("change", function() {
             var proveedorId = $("#proveedorSelect").val();
              cargarContactos(proveedorId); // Llama a la función cargarContactos con el proveedorId seleccionado
            });
        });

       function cargarContactos(proveedorId) {
            if (!proveedorId) {
              return;
            }

            $.ajax({
              type: "POST",
              url: "obtener_contactos.php",
              data: { proveedorId: proveedorId },
              success: function(response) {
                 console.log(response); // Agrega esta línea para verificar la respuesta
                  $("#contactoSelect").html(response);
                }
            });
        }
   </script>



   <script>
      $(document).ready(function() {
    
      var envioActivo = true;
    
       $("#proveedorSelect").on("change", function() {
          if (envioActivo) {
              envioActivo = false;

              var proveedorId = $(this).val();
       
              cargarCotizaciones(proveedorId);
            }
        });

       function cargarCotizaciones() {
          var selectElement = document.getElementById('proveedorSelect');
          var cotizacionIdDisplay = document.getElementById('cotizacionIdDisplay');

          // Obtener el valor seleccionado del proveedor
          var proveedorId = selectElement.value;

          // Obtener el atributo data-cotizacion-id de la opción seleccionada
          var cotizacionId = selectElement.options[selectElement.selectedIndex].getAttribute('data-cotizacion-id');

          // Mostrar el ID de la cotización en pantalla
          cotizacionIdDisplay.textContent = 'ID de Cotización: ' + cotizacionId;

          // Llamar a la función para cargar los detalles de la cotización
           if (cotizacionId !== null && proveedorId !== null) {
              cargarDetallesCotizacion(proveedorId, cotizacionId);
            }

        }
    
  
        $("#proveedorSelect").on("change", function () {
         
         var proveedorId = $(this).val();
     
         cargarContactos(proveedorId);
          cargarDetallesCotizacion(proveedorId);

        });

        // Obtener proveedor seleccionado al cargar la página
        var proveedorIdInicial = $("#proveedorSelect").val();
        cargarContactos(proveedorIdInicial);
        cargarDetallesCotizacion(proveedorIdInicial);

       function cargarDetallesCotizacion(proveedorId) {
         // Limpiar ambas tablas antes de agregar nuevas filas
         $("#cotizacionesTable").empty();
          $("#order-total tbody").empty();
 
           if (proveedorId) {
             var cotizacionId = $("#proveedorSelect option:selected").data('cotizacion-id');

                $.ajax({
                 type: "POST",
                 url: "obtener_cotizaciones.php",
                 data: { proveedorId: proveedorId, cotizacionId: cotizacionId },
                 dataType: 'json',
                   success: function (response) {
                      // Limpiar la tabla antes de agregar nuevas filas
                      $("#cotizacionesTable").empty();

                        if (response.length > 0) {
                          // Agregar filas a la tabla solo si hay datos
                          response.forEach(function (cotizacion) {
                              var filaCotizaciones = '<tr>' +
                               '<td>' + cotizacion.CANTIDAD + '</td>' +
                               '<td>' + cotizacion.DESCRIPCION + '</td>' +
                               '<td><input type="text" name="precios[]" class="precioInput" placeholder="Ingrese el precio"></td>' +
                               '<td class="valorTotal"></td>' +
                               '<td class="excentoValue">' + (cotizacion.EXCENTO ? 'Sí' : 'No') + '</td>' +
                               '</tr>';

                               $("#cotizacionesTable").append(filaCotizaciones);
 
                               // Calcular totales al ingresar el precio
                               $(".precioInput").on("input", function () {
                                  calcularTotales();
                                });
                            });
                        } else {
                          // Si no hay datos disponibles, mostrar el mensaje
                            $("#cotizacionesTable").append('<tr><td colspan="5">No hay datos disponibles para el proveedor seleccionado.</td></tr>');

                          // Limpiar totales si no hay datos
                          $(".subtotalValue").text('0.00');
                           $("#subtotalInput").val('0.00');
                          $("#isvValue").text('0.00');
                          $("#isvInput").val('0.00');
                           $("#totalValue").text('0.00');
                          $("#total_factura_input").val('0.00');
                        }
                    },
                      error: function (xhr, status, error) {
                       console.error("Error en la solicitud AJAX:", status, error);
                    }
                });
           } else {
             // Si no hay proveedor seleccionado, no mostrar el mensaje
            }
        }


       // Función para enviar los datos al servidor
        $("#btnGuardarOrden").on("click", function() {
       
          console.log("Botón Guardar clickeado");
          // Obtener los datos del formulario
          var formData = $("form").serialize();

          // Obtener las cotizaciones y precios
          var cotizaciones = [];
          var precios = [];

          $("#cotizacionesTable tr").each(function(index, row) {
              var cantidad = $(row).find("td:eq(0)").text();
              var descripcion = $(row).find("td:eq(1)").text();
              var precio = parseFloat($(row).find(".precioInput").val()) || 0;

              cotizaciones.push({ cantidad: cantidad, descripcion: descripcion });
              precios.push(precio);
           });

          // Agregar las cotizaciones y precios a los datos del formulario
           formData += "&cotizaciones=" + JSON.stringify(cotizaciones);
           formData += "&precios=" + JSON.stringify(precios);

           // Enviar datos al servidor a través de AJAX
           $.ajax({
              type: "POST",
              url: "guardar_orden.php",
              data: formData,
              dataType: 'json',
              success: function(response) {
                 console.log(response);
                 // Manejar la respuesta del servidor si es necesario

                 // Redirigir a ordenes_compras.php después de guardar
                  window.location.href = "ordenes_compras.php";
                },
                    error: function(xhr, status, error) {
                       console.error("Error en la solicitud AJAX:", status, error);
                    }
               });
            });
        });

      // Función para limpiar y calcular totales
       function calcularTotales() {
            var subtotal = 0;

          // Calcular y mostrar el valor total en la fila
           $("#cotizacionesTable tr").each(function(index, row) {
              var cantidad = $(row).find("td:eq(0)").text();
              var precio = parseFloat($(row).find(".precioInput").val()) || 0;
               var valorTotal = cantidad * precio;
              $(row).find(".valorTotal").text(valorTotal.toFixed(2));

              subtotal += valorTotal;
           });

            var isv = subtotal * 0.15; // 15% de ISV
            var total = subtotal + isv;

           // Actualizar los campos ocultos con los totales calculados
           $(".subtotalValue").text(subtotal.toFixed(2));
           $("#isvValue").text(isv.toFixed(2));
            $("#totalValue").text(total.toFixed(2));

           // Actualizar los campos ocultos con los totales calculados (estos se envían al servidor)
           $("#subtotalInput").val(subtotal.toFixed(2));
           $("#isvInput").val(isv.toFixed(2));
           $("#total_factura_input").val(total.toFixed(2));
        }

   
    </script>


</body>
</html>