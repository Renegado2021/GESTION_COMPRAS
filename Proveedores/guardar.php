<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_proveedor = $_POST['ID_PROVEEDOR'];
    $departamento = $_POST['DEPARTAMENTO'];
    $municipio = $_POST['MUNICIPIO'];
    $colonia = $_POST['COLONIA'];
    $estado = $_POST['ESTADO'];
    $creado_por = $_POST['CREADO_POR'];

    // Utiliza declaraciones preparadas para prevenir SQL injection
    $query = "INSERT INTO tbl_direcciones_proveedores (ID_PROVEEDOR, DEPARTAMENTO, MUNICIPIO, COLONIA, ESTADO, CREADO_POR) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("isssis", $id_proveedor, $departamento, $municipio, $colonia, $estado, $creado_por);

    if ($stmt->execute()) {
        // Redirigir a listar.php después de la inserción
        header('Location: listar.php');
        exit;
    } else {
        echo "Error al guardar el registro: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guardar Nueva Dirección de Proveedor</title>
</head>
<body>
    <h2>Guardar Nueva Dirección de Proveedor</h2>
    <form method="POST" action="guardar.php">
        <label>ID Proveedor:</label>
        <input type="text" name="ID_PROVEEDOR"><br>
        <label>Departamento:</label>
        <input type="text" name="DEPARTAMENTO"><br>
        <label>Municipio:</label>
        <input type="text" name="MUNICIPIO"><br>
        <label>Colonia:</label>
        <input type="text" name="COLONIA"><br>
        <label>Estado:</label>
        <input type="text" name="ESTADO"><br>
        <label>Creado por:</label>
        <input type="text" name="CREADO_POR"><br>
        <input type="submit" value="Guardar">
    </form>
</body>
</html>
