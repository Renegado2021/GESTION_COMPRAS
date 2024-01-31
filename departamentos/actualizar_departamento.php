<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombreEmpresa = $_POST['nombre_empresa'];
    $nombreDepartamento = $_POST['nombre_departamento'];
    $estadoDepartamento = $_POST['estado_departamento'];
    $modificadoPor = $_POST['modificado_por'];

    // Obtiene la fecha y hora actual en el formato SQL
    $fechaModificacion = date('Y-m-d H:i:s');

    // Realiza la actualización del departamento
    $stmt = $conn->prepare("UPDATE tbl_departamentos 
                           SET nombre_departamento = :nombre_departamento,
                               estado_departamento = :estado_departamento,
                               modificado_por = :modificado_por,
                               fecha_modificacion = :fecha_modificacion
                           WHERE id_departamento = :id");
    $stmt->bindParam(':nombre_departamento', $nombreDepartamento);
    $stmt->bindParam(':estado_departamento', $estadoDepartamento);
    $stmt->bindParam(':modificado_por', $modificadoPor);
    $stmt->bindParam(':fecha_modificacion', $fechaModificacion);
    $stmt->bindParam(':id', $id);
    $result = $stmt->execute();

    if ($result) {
        // Actualización exitosa, redirige a la lista de departamentos
        header("Location: listar_departamentos.php");
        exit;
    } else {
        // Error en la actualización
        echo "Error en la actualización del departamento.";
    }
} else {
    echo "Acceso no autorizado.";
}
?>

