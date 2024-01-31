<?php
// Incluye tu archivo de conexión a la base de datos
include('../conexion/conexion.php');

// Obtén el ID_ROL de la URL
$id_rol_seleccionado = $_GET['id_rol'] ?? null;

// Asegúrate de que el ID_ROL no esté vacío y sea un número válido
if (!is_numeric($id_rol_seleccionado)) {
    die("ID de rol no válido.");
}

// Obtén el nombre del rol seleccionado
$consultaNombreRol = mysqli_query($conn, "SELECT NOMBRE_ROL FROM tbl_ms_roles WHERE ID_ROL = $id_rol_seleccionado");
$nombre_rol_seleccionado = mysqli_fetch_assoc($consultaNombreRol)['NOMBRE_ROL'];

// Obtén la lista de objetos y permisos desde tus tablas
$consultaObjetos = mysqli_query($conn, "SELECT ID_OBJETO, NOMBRE_OBJETO FROM tbl_objetos");
$consultaPermisos = mysqli_query($conn, "SELECT ID_PERMISO, NOMBRE_PERMISO FROM tbl_permisos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopila los datos del formulario
    $permisos_seleccionados = $_POST['permisos'] ?? [];

    // Elimina los permisos existentes para este rol
    mysqli_query($conn, "DELETE FROM tbl_roles_permisos WHERE id_rol = $id_rol_seleccionado");

    // Guarda los nuevos permisos en la tabla tbl_roles_permisos
    foreach ($permisos_seleccionados as $id_objeto => $permisos) {
        foreach ($permisos as $id_permiso) {
            $sql = "INSERT INTO tbl_roles_permisos (id_rol, id_objeto, id_permiso) VALUES ($id_rol_seleccionado, $id_objeto, $id_permiso)";
            mysqli_query($conn, $sql);
        }
    }

    // Después de guardar los permisos, redirige a roles.php
    header("Location: ../roles/roles.php");
    exit; // Asegúrate de salir para evitar la ejecución adicional del código
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }

    h1 {
        font-size: 24px;
        color: #333;
    }

    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 10px;
    }

    select, input[type="checkbox"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px; /* Ajusta el margen inferior para separar la tabla de los botones */
    }

    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ccc;
    }

    th {
        background-color: #f2f2f2;
    }

    button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 3px;
        cursor: pointer;
    }

    a.styled-button {
        display: inline-block;
        text-decoration: none;
        background-color: #ccc;
        color: #333;
        padding: 10px 20px;
        border-radius: 3px;
        margin-right: 10px;
    }

    a.styled-button.cancel-button {
        background-color: #ff3333;
        color: #fff;
        padding: 10px 10px; /* Ajusta el ancho y alto de los botones según tus preferencias */
    }

     
</style>

</head>
<body>
    <h1>Asignar Permisos para el Rol: <?php echo $nombre_rol_seleccionado; ?></h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?id_rol=" . $id_rol_seleccionado; ?>">
        <table>
            <thead>
                <tr>
                    <th>ID Objeto</th>
                    <th>Nombre Objeto</th>
                    <?php
                    while ($permiso = mysqli_fetch_assoc($consultaPermisos)) {
                        echo "<th>" . $permiso['NOMBRE_PERMISO'] . "</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($objeto = mysqli_fetch_assoc($consultaObjetos)) {
                    echo "<tr>";
                    echo "<td>" . $objeto['ID_OBJETO'] . "</td>";
                    echo "<td>" . $objeto['NOMBRE_OBJETO'] . "</td>";

                    // Obtén los permisos asignados para este objeto y rol
                    $consultaPermisosAsignados = mysqli_query($conn, "SELECT id_permiso FROM tbl_roles_permisos WHERE id_rol = $id_rol_seleccionado AND id_objeto = {$objeto['ID_OBJETO']}");
                    $permisos_asignados = [];
                    while ($permisoAsignado = mysqli_fetch_assoc($consultaPermisosAsignados)) {
                        $permisos_asignados[] = $permisoAsignado['id_permiso'];
                    }

                    // Itera sobre los permisos disponibles
                    mysqli_data_seek($consultaPermisos, 0);
                    while ($permiso = mysqli_fetch_assoc($consultaPermisos)) {
                        echo "<td>";
                        echo "<input type='checkbox' name='permisos[{$objeto['ID_OBJETO']}][]' value='{$permiso['ID_PERMISO']}'";

                        // Verifica si este permiso está asignado para este objeto y rol
                        if (in_array($permiso['ID_PERMISO'], $permisos_asignados)) {
                            echo " checked";
                        }

                        echo ">";
                        echo "</td>";
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <button type="submit">Guardar</button>
        
        <a href="../roles/roles.php" class="styled-button cancel-button"><i class='fas fa-times'></i> Cancelar</a>

    </form>
</body>
</html>



