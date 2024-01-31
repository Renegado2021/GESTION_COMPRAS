<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idCotizacion = $_POST['id_cotizacion'];
    $idProveedor = $_POST['id_proveedor'];
    $numeroCotizacion = $_POST['numero_cotizacion'];
    $departamento = $_POST['departamento'];
    $fechaCotizacion = $_POST['fecha_cotizacion'];
    $nombreProveedor = $_POST['nombre_proveedor'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    
    $sql = "UPDATE tbl_cotizacion 
            SET ID_PROVEEDOR=$idProveedor, NUMERO_COTIZACION='$numeroCotizacion', DEPARTAMENTO='$departamento', 
            FECHA_COTIZACION='$fechaCotizacion', NOMBRE_PROVEEDOR='$nombreProveedor', DESCRIPCION='$descripcion', 
            CANTIDAD=$cantidad, PRECIO=$precio
            WHERE ID_COTIZACION=$idCotizacion";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: read_cotizacion.php");
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }
} else {
    $idCotizacion = $_GET['id'];
    $sql = "SELECT * FROM tbl_cotizacion WHERE ID_COTIZACION=$idCotizacion";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Cotización</title>
</head>
<body>
    <h2>Editar Cotización</h2>
    <form method="post" action="">
        <input type="hidden" name="id_cotizacion" value="<?php echo $row['ID_COTIZACION']; ?>">
        
        <label>ID de Proveedor:</label>
        <input type="text" name="id_proveedor" value="<?php echo $row['ID_PROVEEDOR']; ?>" required><br><br>
        
        <label>Número de Cotización:</label>
        <input type="text" name="numero_cotizacion" value="<?php echo $row['NUMERO_COTIZACION']; ?>"><br><br>
        
        <label>Departamento:</label>
        <input type="text" name="departamento" value="<?php echo $row['DEPARTAMENTO']; ?>" required><br><br>
        
        <label>Fecha de Cotización:</label>
        <input type="date" name="fecha_cotizacion" value="<?php echo $row['FECHA_COTIZACION']; ?>"><br><br>
        
        <label>Nombre de Proveedor:</label>
        <input type="text" name="nombre_proveedor" value="<?php echo $row['NOMBRE_PROVEEDOR']; ?>" required><br><br>
        
        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?php echo $row['DESCRIPCION']; ?>" required><br><br>
        
        <label>Cantidad:</label>
        <input type="text" name="cantidad" value="<?php echo $row['CANTIDAD']; ?>" required><br><br>
        
        <label>Precio:</label>
        <input type="text" name="precio" value="<?php echo $row['PRECIO']; ?>" required><br><br>
        
        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
