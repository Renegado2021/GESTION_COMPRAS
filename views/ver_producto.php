<!DOCTYPE html>
<html lang="en">
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
    

    
</head>
    <title>Gestión de Roles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
    <style>

          /* Estilo para el contenido principal */
.content {
    margin-left: 10%; /* Ajusta el margen izquierdo al 20% (o el valor que prefieras) */
    transition: margin-left 0.5s;
    padding: 0px;
    width: 80%; /* Ajusta el ancho del contenido principal al 80% (o el valor que prefieras) */
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
   
}
        /* Estilos adicionales */
        .floating-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .floating-form .close-button {
            position: relative;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #dc3545;
        }

        .floating-form .close-button:hover {
            color: #721c24;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            justify-content: right;
        }

        body {
        background-image: url("../Gestionmain/imagen/imagen4.jpg");
        /* Aquí puedes ajustar algunas propiedades adicionales */
        background-repeat: no-repeat; /* Evita que la imagen se repita */
        background-size: cover; /* Ajusta la imagen al tamaño de la pantalla */
        /* Puedes agregar más propiedades según tus necesidades */
       }
    </style>
    <title>Botón de Regresar</title>
</head>
<body>

    <?php
        // Conectarse a la base de datos
        $conn = new mysqli("localhost", "root", "", "gestion_compras2");

        // Comprobar la conexión
        if ($conn->connect_error) {
            die("Error al conectar con la base de datos: " . $conn->connect_error);
        }

        // Obtener la lista de productos de la base de datos
        $sql = "SELECT * FROM tbl_productos";
        $result = $conn->query($sql);

        // Paginación
$por_pagina = 5;

if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}

$empieza = ($pagina - 1) * $por_pagina;

// Búsqueda
if (isset($_GET['buscar'])) {
    $buscar = $_GET['buscar'];
} else {
    $buscar = '';
}

// Consulta a la base de datos
$sql = "SELECT * FROM tbl_productos WHERE id LIKE '%$buscar%' OR descripcion LIKE '%$buscar%'";
$result = $conn->query($sql);

$total_productos = mysqli_num_rows($result);

$total_paginas = ceil($total_productos / $por_pagina);
$sql_paginacion = "SELECT * FROM tbl_productos WHERE id LIKE '%$buscar%' OR descripcion LIKE '%$buscar%' LIMIT $empieza, $por_pagina";
$result_paginacion = $conn->query($sql_paginacion);


    ?>
       <div class="content">  
         <h2 class="text-center">LISTA DE PRODUCTOS</h2>  
   
    <table>
    
        <tr>
            <th>Id</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>Estado</th>
            
            
        </tr>
     
        <?php
            // Mostrar los productos en la tabla
            if ($result_paginacion->num_rows > 0) {
                while($row = $result_paginacion->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["codigo"] . "</td>";
                    echo "<td>" . $row["descripcion"] . "</td>";
                    echo "<td>" . $row["estado"] . "</td>";
            
                    echo "</tr>";
                     
                }
            } else {
                echo "<tr><td colspan='6'>No se encontraron productos</td></tr>";
            }
            
            // Cerrar la conexión
            $conn->close();
        ?>
      
    </table>
    
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=" . $i . "&buscar=" . $buscar . "'";

            if ($pagina == $i) {
                echo " style='font-weight:bold'";
            }

            echo ">" . $i . "</a> ";
        }
        ?>
        <br>
    
        <div style="text-align: right;">
            <a href="javascript:history.back()">Regresar</a>
        </div>
    </div>
</body>
</html>