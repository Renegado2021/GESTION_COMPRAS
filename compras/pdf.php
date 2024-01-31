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

// Obtiene el número de orden desde el parámetro de la URL
$numeroOrden = $_GET['numero_orden'];

// Consulta para obtener los detalles de la orden
$sql = "SELECT * FROM tbl_orden_compra WHERE NUMERO_ORDEN = $numeroOrden";
$result = $conn->query($sql);

// Cierra la conexión con la base de datos
$conn->close();

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Orden</title>

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
          width: 27%; /* Ajusta el ancho de las columnas 3 y 4 según tus necesidades */
        }

        .order-table th:last-child,
        .order-table td:last-child {
         width: 20%; /* Ajusta el ancho de la última columna según tus necesidades */
        }

        /* Estilos para la firma del solicitante */
        .order-signature {
            margin-top: 20px;
        }

        .order-total {
   
          margin: 0 auto; /* Centra el contenedor */
          text-align: right;
           margin-left: 216px;
        }

        .order-total table {
            text-align: right;
            width: 56%; /* Ajusta el ancho según tus necesidades */
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
        }

        .order-total td {
            padding: 5px;
            border: 1px solid #000;
            text-align: center;
        }

        .order-total label {
            font-weight: bold;
            display: block; /* Muestra cada label en una línea separada */
            margin-bottom: 5px; /* Añade un espacio entre cada label */
            
        }


        .centered-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 10vh;
        }

        .order-total td:nth-child(1) {
          width: 70%; /* Ajusta el ancho según tus necesidades */
        }
    </style>
</head>
<body>


    <div style="text-align: center; position: relative;">
         
     <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents('../imagen/IHCIS.jpg')); ?>" alt="Logo 1" style="width: 80px; position: absolute; top: 0; left: 0; margin-left: 30px;">

        <div class="centered-content" style="display: inline-block; text-align: center; margin: 0; padding: 0;">
         <p style="margin: 0;"><strong>INSTITUTO HONDUREÑO DE CULTURA INTERAMERICANA</strong></p>
         <p style="margin: 0;"><strong>RTN 08019995223469</strong></p>
          <p style="margin: 0; color: blue;"><strong>SOLICITUD COMPRAS</strong></p>
       </div>
   
        <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents('../imagen/IHCI1.jpg')); ?>" alt="Logo 2" style="width: 80px; position: absolute; top: 0; right: 0; margin-right: 0px;">
          
    </div>

    <br><br><br>

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

       // Obtiene el número de orden desde el parámetro de la URL
       $numeroOrden = $_GET['numero_orden'];

       // Consulta para obtener los detalles de la orden
       $sql = "SELECT * FROM tbl_orden_compra WHERE NUMERO_ORDEN = $numeroOrden";
       $result = $conn->query($sql);

       if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          echo '<p style="width: 900px;"><span style="font-weight: bold;">Nº de Pedido:</span> <span style="padding-left: 15px;"> ' . $numeroOrden . '</span></p>';
  
          echo '<p><span style="font-weight: bold;">Fecha:</span> <span style="padding-left: 40px;">  ' . $row["FECHA_ORDEN"] . '</span></p>';

          // Recupera el proveedor
          $idProveedor = $row["ID_PROVEEDOR"];
          $sqlProveedor = "SELECT NOMBRE FROM tbl_proveedores WHERE ID_PROVEEDOR = $idProveedor";
          $resultProveedor = $conn->query($sqlProveedor);
          if ($resultProveedor->num_rows > 0) {
             $proveedor = $resultProveedor->fetch_assoc();
             echo '<p><span style="font-weight: bold;">Proveedor:</span> <span style="padding-left: 10px;">  ' . $proveedor["NOMBRE"] . '</span></p>';
            }

         // Recupera el nombre del contacto directamente de la tabla tbl_contactos_proveedores
          $idContacto = $row["ID_CONTACTO"];
           if ($idContacto !== null) {
             $sqlContacto = "SELECT NOMBRE FROM tbl_contactos_proveedores WHERE ID_CONTACTO_PROVEEDOR = $idContacto";
             $resultContacto = $conn->query($sqlContacto);
               if ($resultContacto->num_rows > 0) {
                 $contacto = $resultContacto->fetch_assoc();
                 echo '<p><span style="font-weight: bold;">Contacto:</span> <span style="padding-left: 16px;">  ' . $contacto["NOMBRE"] . '</span></p>';
                }
            }


           // Consulta para obtener las cotizaciones aprobadas del proveedor
$sql = "SELECT oc.*, op.* FROM tbl_orden_compra oc 
JOIN tbl_orden_compra_productos op ON oc.ID_ORDEN_COMPRA = op.ID_ORDEN
WHERE oc.NUMERO_ORDEN = $numeroOrden";

$resultCotizaciones = $conn->query($sql);

if ($resultCotizaciones) {
              echo '<table class="order-table">';
              echo '<tr><th>Cantidad</th><th>Descripción</th><th>Precio</th><th>Valor Total</th></tr>';
            
              $subtotal = 0; // Inicializa el subtotal

               while ($cotizacion = $resultCotizaciones->fetch_assoc()) {
                  echo '<tr>';
                  echo '<td>' . $cotizacion["CANTIDAD"] . '</td>';
                  echo '<td>' . $cotizacion["DESCRIPCION"] . '</td>';
                  echo '<td>' . $cotizacion["PRECIO"] . '</td>';
                  echo '<td>' . $cotizacion["TOTAL"] . '</td>';
                  echo '</tr>';

                  // Suma al subtotal
                  $subtotal += $cotizacion["TOTAL"];
                }

               echo '</table>';

               echo '<div class="order-total" style="width: 90%;  text-align: right;">';
                echo    '<table>';
               // Muestra el subtotal
               echo '<tr>
               <label style="display: block;">SubTotal:</label>
               <td style="font-weight: bold;">' . number_format($subtotal, 2) . '</td>
               </tr>'; 

               // Calcula el ISV (Impuesto sobre Ventas)
               $isv = $subtotal * 0.15;
               echo '<tr>
               <label style="display: block;">ISV (15%):</label>
               <td style="font-weight: bold;">' . number_format($isv, 2) . '</td>
               </tr>';

              // Muestra el total factura
              $totalFactura = $subtotal + $isv;
              echo '<tr>
              <label style="display: block;">Total Factura:</label>
              <td style="font-weight: bold;">' . number_format($totalFactura, 2) . '</td>
              </tr>';
       
              echo '</table>';
              echo '</div>';
            } else {
              echo '<p>No hay cotizaciones aprobadas para este proveedor.</p>';
            }
        } else {
          echo "No se encontró la orden de compra con el número: $numeroOrden";
        }

       // Cierra la conexión con la base de datos
       $conn->close();
    ?>

    <br>
    <p style="font-weight: bold;">Observaciones:</p>

    <br><br><br>
    <div class="order-signature">
        <p style="font-weight: bold;">Firma del solicitante: _________________________________</p>
    </div>
   

    <br><br><br><br><br><br>
    <br><br><br><br><br>
    <br><br><br>
    
    <!-- Pie de página -->
    <footer>
       <div style="text-align: center; margin-top: 20px;">
          <div style="display: inline-block; text-align: left; width: 30%;">
             <p style="margin: 0; font-size: 10px;">IHCI Comayagüela:</p>
             <p style="margin: 0; font-size: 10px;">2da. Avenida, Calle Real, entre 5ta y 6ta calle</p>
             <p style="margin: 0; font-size: 10px;">Tel: 2220-1393</p>
            </div>
            <div style="display: inline-block; text-align: center; width: 30%;">
              <p style="margin: 0; font-size: 10px;">IHCI Tegucigalpa:</p>
              <p style="margin: 0; font-size: 10px;">Col. Matamoros Ave. Santader</p>
               <p style="margin: 0; font-size: 10px;">Tel: 2221-1406</p>
           </div>
           <div style="display: inline-block; text-align: right; width: 30%;">
              <p style="margin: 0; font-size: 11px;">IHCI Comayagua:</p>
              <p style="margin: 0; font-size: 11px;">Calle hacia el Comayagua Golf Club</p>
              <p style="margin: 0; font-size: 11px;">Tel: 2772-7772</p>
           </div>
       
        </div>
    </footer>

    
</body>
</html>
<?php
$html = ob_get_clean();

require_once '../dompdf_2-0-3/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnable', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

// Establece la base de la URL para las imágenes
$dompdf->setBasePath('../imagen/');

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();

$dompdf->stream("orden_compra.pdf", array("Attachment" => false));
?>