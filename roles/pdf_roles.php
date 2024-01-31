<?php
ob_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <!-- Agrega aquí tus estilos CSS si los tienes -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Incluir el archivo de conexión a la base de datos
        include '../views/conexion.php';

        // Array asociativo para traducir el estado de letras a palabras
        $estados = array(
          'A' => 'Activo',
          'I' => 'Inactivo',
          'B' => 'Bloqueado'
        );

        // Consulta a la base de datos para obtener la lista de roles
        $sql = "SELECT * FROM tbl_ms_roles";
        $result = $conn->query($sql);
        ?>

        <h2>LISTA DE ROLES</h2>

        <table>
            <tr>
                <th>Còdigo</th>
                <th>Rol</th>
                <th>Fecha Creación</th>
                <th>Fecha Modificación</th>
                <th>Estado</th>
                
            </tr>

            <?php
            // Mostrar los productos en la tabla
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["ID_ROL"]."</td>";
                    echo "<td>".$row["NOMBRE_ROL"]."</td>";
                    // Formatear la fecha de creación (sin hora)
                    $fechaCreacion = date('Y-m-d', strtotime($row["FECHA_CREACION"]));
                    echo "<td>" . $fechaCreacion . "</td>";

                   // Formatear la fecha de modificación (sin hora)
                   $fechaModificacion = date('Y-m-d', strtotime($row["FECHA_MODIFICACION"]));
                    echo "<td>" . $fechaModificacion . "</td>";
                   // Verificar si el estado existe en el array antes de mostrarlo
                   echo "<td>" . (isset($estados[$row["ESTADO_ROL"]]) ? $estados[$row["ESTADO_ROL"]] : 'Desconocido') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No se encontraron usuarios</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

require_once '../dompdf_2-0-3/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new DOMPDF();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnable' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();
$dompdf->stream("lista_roles.pdf", array("Attachment" => false));
?>
