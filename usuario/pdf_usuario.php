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

        // Mapeo de valores de estado a sus equivalentes completos
        $estadosCompletos = array(
            'A' => 'Activo',
            'I' => 'Inactivo',
            'B' => 'Bloqueado'
        );

        // Consulta a la base de datos para obtener la lista de usuarios con nombres de roles
        $sql = "SELECT u.id_usuario, u.nombre_usuario, r.NOMBRE_ROL as rol, u.correo_electronico, u.fecha_creacion, u.estado
                FROM tbl_ms_usuario u
                INNER JOIN tbl_ms_roles r ON u.rol = r.ID_ROL";
        $result = $conn->query($sql);
        ?>

        <h2>USUARIOS</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre Usuario</th>
                <th>Rol</th>
                <th>Correo Electrónico</th>
                <th>Fecha Creación</th>
                <th>Estado</th>
                
            </tr>

            <?php
            // Mostrar los usuarios en la tabla
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["id_usuario"]."</td>";
                    echo "<td>".$row["nombre_usuario"]."</td>";
                    echo "<td>".$row["rol"]."</td>";
                    echo "<td>".$row["correo_electronico"]."</td>";
                     // Formatear la fecha para mostrar solo la fecha (sin la hora)
                     $fechaCreacion = date('Y-m-d', strtotime($row["fecha_creacion"]));
                     echo "<td>".$fechaCreacion."</td>";
                    // Mostrar el estado completo utilizando el array de mapeo
                    echo "<td>".$estadosCompletos[$row["estado"]]."</td>";
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
$dompdf->stream("lista_usuarios.pdf", array("Attachment" => false));
?>
