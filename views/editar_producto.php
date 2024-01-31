<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
    $id = $_POST["id"];
    $codigo = $_POST["codigo"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $descuento = $_POST["descuento"];
    $estado = $_POST["estado"];

    // Calcular el subtotal (precio - descuento)
    $subtotal = $precio - $descuento;

    // Calcular el impuesto (15% del subtotal)
    $impuesto = 0.15 * $subtotal;

    // Calcular el total (subtotal + impuesto)
    $total = $subtotal + $impuesto;

    // Conectarse a la base de datos
    $conn = new mysqli("localhost", "root", "", "gestion_compras2");

    // Comprobar la conexión
    if ($conn->connect_error) {
        die("Error al conectar con la base de datos: " . $conn->connect_error);
    }

    $sql = "UPDATE tbl_productos SET codigo='$codigo', descripcion='$descripcion', precio='$precio', descuento='$descuento', subtotal='$subtotal', impuesto='$impuesto', total='$total', estado='$estado'  WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        // Redirigir de nuevo a la página productos.php después de la actualización
        header("Location: productos.php");
        exit;
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }

    $conn->close();
} else {
    // Obtener el ID del producto de la URL
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        // Conectarse a la base de datos
        $conn = new mysqli("localhost", "root", "", "gestion_compras2");

        // Comprobar la conexión
        if ($conn->connect_error) {
            die("Error al conectar con la base de datos: " . $conn->connect_error);
        }

        // Consultar la base de datos para obtener los datos del producto seleccionado
        $sql = "SELECT * FROM tbl_productos WHERE id='$id'";
        $result = $conn->query($sql);

        // Verificar si se encontró el producto
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $codigo = $row["codigo"];
            $descripcion = $row["descripcion"];
            $precio = $row["precio"];
            $descuento = $row["descuento"];
            $estado = $row["estado"];
        } else {
            echo "Producto no encontrado.";
            exit;
        }
    } else {
        echo "ID de producto no proporcionado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../estilos.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    


    <title>Editar Producto</title>

    <style>
        body {
            
            background-image: url(../Gestionmain/imagen/imagen1.jpg);
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            
            

        }
        #container {
            width: 500px;
            padding: 20px;
            border: 2px solid black;
            text-align: center;
        }

       
    </style>
    <title>Botón de Regresar</title>
    

</head>
<body>

    <div id="container" style="background-color: beige;">
    <h1>Editar Producto</h1>
    <br><br>
    <form action="editar_producto.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" value="<?php echo $codigo; ?>" required><br><br>
        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" value="<?php echo $descripcion; ?>" required><br><br>
        <label for="estado">Estado:</label><br>
        <input type="radio" name="estado" value="activo" <?php if($estado === "activo") echo "checked"; ?> required> Activo<br>
        <input type="radio" name="estado" value="inactivo" <?php if($estado === "inactivo") echo "checked"; ?> required> Inactivo<br><br>
        <input type="submit" name="actualizar" value="Guardar">
    </form>
    <br>
    <a href="javascript:history.back()">Regresar</a>
    </div>
    </div>
</body>
</html>

