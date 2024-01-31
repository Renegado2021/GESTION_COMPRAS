<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Dirección de Proveedor</title>
</head>
<body>
    <h2>Actualizar Dirección de Proveedor</h2>
    <?php
    include('db.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "SELECT * FROM tbl_direcciones_proveedores WHERE ID_DIRECCION_PROVEEDOR = $id";
        $result = $conexion->query($query);
        $row = $result->fetch_assoc();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['ID_DIRECCION_PROVEEDOR'];
        $id_proveedor = $_POST['ID_PROVEEDOR'];
        $departamento = $_POST['DEPARTAMENTO'];
        $municipio = $_POST['MUNICIPIO'];
        $colonia = $_POST['COLONIA'];
        $estado = $_POST['ESTADO'];
        $creado_por = $_POST['CREADO_POR'];

        $query = "UPDATE tbl_direcciones_proveedores SET 
                  ID_PROVEEDOR = '$id_proveedor', 
                  DEPARTAMENTO = '$departamento', 
                  MUNICIPIO = '$municipio', 
                  COLONIA = '$colonia', 
                  ESTADO = '$estado', 
                  CREADO_POR = '$creado_por' 
                  WHERE ID_DIRECCION_PROVEEDOR = $id";

        if ($conexion->query($query) === TRUE) {
            echo "Registro actualizado correctamente.";
            // Después de actualizar, redirigir a listar.php
    header('Location: listar.php');
    exit;
        } else {
            echo "Error al actualizar el registro: " . $conexion->error;
        }
    }
    ?>

    <form method="POST" action="actualizar.php">
        <input type="hidden" name="ID_DIRECCION_PROVEEDOR" value="<?php echo $row['ID_DIRECCION_PROVEEDOR']; ?>">
        <label>ID Proveedor:</label>
        <input type="text" name="ID_PROVEEDOR" value="<?php echo $row['ID_PROVEEDOR']; ?>"><br>
        <label>Departamento:</label>
        <input type="text" name="DEPARTAMENTO" value="<?php echo $row['DEPARTAMENTO']; ?>"><br>
        <label>Municipio:</label>
        <input type="text" name="MUNICIPIO" value="<?php echo $row['MUNICIPIO']; ?>"><br>
        <label>Colonia:</label>
        <input type="text" name="COLONIA" value="<?php echo $row['COLONIA']; ?>"><br>
        <label>Estado:</label>
        <input type="text" name="ESTADO" value="<?php echo $row['ESTADO']; ?>"><br>
        <label>Creado por:</label>
        <input type="text" name="CREADO_POR" value="<?php echo $row['CREADO_POR']; ?>"><br>
        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
