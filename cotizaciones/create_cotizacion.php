<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProveedor = $_POST['id_proveedor'];
    $numeroCotizacion = $_POST['numero_cotizacion'];
    $departamento = $_POST['departamento'];
    $fechaCotizacion = $_POST['fecha_cotizacion'];
    $nombreProveedor = $_POST['nombre_proveedor'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    
    $sql = "INSERT INTO tbl_cotizacion (ID_PROVEEDOR, NUMERO_COTIZACION, DEPARTAMENTO, FECHA_COTIZACION, NOMBRE_PROVEEDOR, DESCRIPCION, CANTIDAD, PRECIO) 
            VALUES ($idProveedor, '$numeroCotizacion', '$departamento', '$fechaCotizacion', '$nombreProveedor', '$descripcion', $cantidad, $precio)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registro creado con éxito.";
    } else {
        echo "Error al crear el registro: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crear Cotización</title>
</head>
<body>
    <h2>Crear Cotización</h2>
    <form method="post" action="">
        <label>ID de Proveedor:</label>
        <input type="text" name="id_proveedor" required><br><br>
        
        <label>Número de Cotización:</label>
        <input type="text" name="numero_cotizacion"><br><br>
        
        <label>Departamento:</label>
        <input type="text" name="departamento" required><br><br>
        
        <label>Fecha de Cotización:</label>
        <input type="date" name="fecha_cotizacion"><br><br>
        
        <label>Nombre de Proveedor:</label>
        <input type="text" name="nombre_proveedor" required><br><br>
        
        <label>Descripción:</label>
        <input type="text" name="descripcion" required><br><br>
        
        <label>Cantidad:</label>
        <input type="text" name="cantidad" required><br><br>
        
        <label>Precio:</label>
        <input type="text" name="precio" required><br><br>
        
        <input type="submit" value="Guardar">
    </form>
</body>
</html>
