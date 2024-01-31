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
    


    <title>Agregar Producto</title>

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

<body >
  

    <div id="container" style="background-color: beige;">
    

        <?php
          // Conexión a la base de datos
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "gestion_compras2";

           $conn = new mysqli($servername, $username, $password, $dbname);
           if ($conn->connect_error) {
              die("Conexión fallida: " . $conn->connect_error);
            }

            // Obtener el proveedor desde la base de datos
            $sql = "SELECT ID_PROVEEDOR, NOMBRE FROM tbl_proveedores";
            $result = $conn->query($sql);

            // Si se enviaron los datos del formulario
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Obtener los valores del formulario
                $codigo = $_POST["codigo"];
                $descripcion = $_POST["descripcion"];
                $tipoproducto = $_POST["tipo_producto"];
                $proveedor = $_POST["proveedor"];
               
    
                // Insertar el producto en la base de datos
                $sql = "INSERT INTO tbl_productos (codigo, descripcion, tipo_producto, proveedor,  estado)
                VALUES ('$codigo', '$descripcion', '$tipoproducto', '$proveedor', 'activo')";

                if ($conn->query($sql) === TRUE) {
                    echo "Producto agregado correctamente.";
                } else {
                    echo "Error al agregar el producto: " . $conn->error;
                }
            }

          $conn->close();
       ?>

        <h2>Agregar Producto</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Código: <input type="text" name="codigo" required><br><br>
            Descripción: <input type="text" name="descripcion" required><br><br>
            Tipo de producto: <input type="text" name="tipo_producto" required><br><br>
            Proveedor:
            <select name="proveedor">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["ID_PROVEEDOR"] . "'>" . $row["NOMBRE"] . "</option>";
                    }
                }
                ?>
            </select><br><br>
           
            <input type="submit" value="Guardar">
            <br><br>
        </form>
        <a href="javascript:history.back()">Regresar</a>
    </div> 
</body>
</html>