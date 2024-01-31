<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_compras2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $idOrdenCompraProducto = $_GET['id'];

    // Consulta para obtener datos de la orden de compra y detalles del proveedor, cuentas y cuenta proveedor asociadas
    $sql = "SELECT ocp.ID, ocp.ID_ORDEN, ocp.CANTIDAD, ocp.DESCRIPCION, ocp.PRECIO, ocp.TOTAL, ocp.SUBTOTAL, ocp.ISV, ocp.MONTO,
               oc.ID_ORDEN_COMPRA, p.NOMBRE, cp.NUMERO_CUENTA, cp.BANCO, cp.DESCRIPCION_CUENTA
        FROM tbl_orden_compra_productos ocp
        LEFT JOIN tbl_orden_compra oc ON ocp.ID_ORDEN = oc.ID_ORDEN_COMPRA
        LEFT JOIN tbl_proveedores p ON oc.ID_PROVEEDOR = p.ID_PROVEEDOR
        LEFT JOIN tbl_cuenta_proveedor cp ON p.ID_PROVEEDOR = cp.ID_PROVEEDOR
        WHERE ocp.ID = $idOrdenCompraProducto";

    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Obtener el nombre del proveedor
        $proveedorNombre = $row["NOMBRE"];

        // Obtener información de la cuenta del proveedor
        $infoCuentaProveedor = [
            'NUMERO_CUENTA' => $row["NUMERO_CUENTA"],
            'BANCO' => $row["BANCO"],
            'DESCRIPCION_CUENTA' => $row["DESCRIPCION_CUENTA"]
        ];

        
        // Crear una instancia de Dompdf
        require_once '../dompdf_2-0-3/dompdf/autoload.inc.php';
        $dompdf = new Dompdf\Dompdf();

        // Formatear la fecha en español
        $fechaFormateada = new DateTime(null, new DateTimeZone('America/Tegucigalpa'));
        $fechaFormateada = $fechaFormateada->format('d \d\e F \d\e Y');

        $logoPath = 'C:/xampp/htdocs/Gestionmain/compras/Imagen1.png';

        // Contenido HTML para el PDF con cambio en el tamaño del texto
        
        $html = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px; /* Tamaño del texto para párrafos */
                }
                h2 {
                    font-size: 16px; /* Tamaño del texto para h2 */
                    text-align: center; /* Centrar el texto */
                }
                p {
                    font-size: 12px; /* Tamaño del texto para párrafos */
                }
                /* Agrega el estilo necesario para tu PDF aquí */
                .logo {
                    position: absolute;
                    top: 20px;
                    rigth: 200px;
                    width: 100px; /* Ajusta el ancho según tu necesidad */
                    height: auto;
                }
            </style>
        </head>
        <body>
                <img src="' . $logoPath . '" alt="Logo"> <!-- Asegúrate de que esta línea esté presente y correcta -->

            <h2>INSTITUTO HONDUREÑO DE CULTURA INTERAMERICANA</h2>
            <h2>SOLICITUD DE TRANSFERENCIA L.</h2>

            <div class="form-section">
                <p><strong>Lugar y Fecha:</strong> Tegucigalpa, M.D.C. ' . $fechaFormateada . '</p>
                <p><strong>A Favor:</strong> ' . $proveedorNombre . '</p>
                <p><strong>Cantidad a transferir: L</strong> ' . $row["MONTO"] . '</p>
                <p><strong>Número de cuenta :</strong> ' . $infoCuentaProveedor['NUMERO_CUENTA'] . ' | Tipo de cuenta: ' . $infoCuentaProveedor['DESCRIPCION_CUENTA'] . '</p>
                <p><strong>Banco :</strong> ' . $infoCuentaProveedor['BANCO'] . '</p>
                <p><strong>Concepto de la solicitud :</strong> Compra de ' . $row["DESCRIPCION"] . '</p>
            

                <p><strong>Nombre del solicitante: </strong> ___________________________________________</p>

                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-line"></div>
                </div>
                <div class="signature">
                    <div class="signer" style="margin-left: 400px;">Firma supervisor de solicitante</div>
                    <div class="signer" style="margin-right: 400px;">Firma departamento solicitante</div>
                </div>

                <p><strong>Observaciones:</strong> ___________________________________________________________________</p>
                <p><strong> </strong> ________________________________________________________________________________</p>

                <div class="signature" style="justify-content: center;">
                    <div class="signature-line"></div>
                </div>
                <div class="signature" style="justify-content: center;">
                    <div class="signer">Contador General</div>
                </div>

                <p><strong>Fecha de remisión a la administración:</strong> ___________________________________________</p>

                <p class="exclusive-text">EXCLUSIVO PARA CONTABILIDAD</p>

                <p class="documentation-text">Documentación soporte de acuerdo a política:</p>
                <p>
                    <span class="options" style="margin-left: 20px;">
                        <span style="margin-right: 20px;">Si</span>
                        <input type="text" placeholder="" style="margin-right: 10px;">
                        <span style="margin-right: 10px;">No</span>
                        <input type="text" placeholder="">
                    </span>
                </p>

                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-line"></div>
                </div>
                <div class="signature">
                    <div class="signer">Revisión y Aprobación de Cheque / Transferencia                   Sub Dirección Ejecutiva / Dirección Ejecutiva</div>
                    <div class="signer">Autorización de Cheque / Transferencia                             Sub Dirección Ejecutiva / Dirección Ejecutiva</div>
                </div>
            </div>
        </body>
        </html>';

        // Cargar el contenido HTML al Dompdf
        $dompdf->loadHtml($html);

        // Establecer el tamaño del papel y la orientación
        $dompdf->setPaper('letter', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Mostrar el PDF en el navegador antes de descargarlo
        $dompdf->stream('solicitud_transferencia.pdf', array('Attachment' => 0));
    } else {
        echo "No se encontraron datos de la orden de compra.";
    }
} else {
    echo "ID de orden de compra no proporcionado.";
}

$conn->close();
?>
