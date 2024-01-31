<?php
include 'db_connection.php';

$sql = "SELECT * FROM tbl_solicitudes";
$result = $conn->query($sql);
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

    <title>Listado de Solicitudes</title>
    <style>
        .menu li {
            margin-bottom: -1px;
        }

        .menu li a {
            padding: 0px 0px;
        }
    </style>
    
    <title>Solicitudes</title>
    
    <style>
        .solicitud-table {
            border-collapse: collapse;
            width: 100%;
        }
        .solicitud-table th, .solicitud-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        /* Estilos para el botón de inicio */
        .boton-inicio {
            background-color: #007bff; /* Color de fondo azul */
            border: none;
            color: white; /* Color del texto en blanco */
            padding: 10px 20px; /* Espaciado interno */
            border-radius: 5px; /* Borde redondeado */
            cursor: pointer;
        }

        .boton-inicio:hover {
            background-color: #0056b3; /* Color de fondo azul más oscuro al pasar el mouse */
        }

        .button-container {
          display: flex;  /* Mostrar en línea horizontal */
          align-items: center; /* Centrar verticalmente los botones si tienen diferentes alturas */
          gap: 10px; /* Espacio entre los botones */
        }


        /* Estilos para alinear a la derecha */
        .pagination-container {
            text-align: right; /* Alinea el contenido a la derecha */
        }

        /* Estilos para la lista de páginas */
        .pagination-container ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .pagination-container ul li {
            display: inline-block;
            margin-right: 10px; /* Espacio entre las páginas */
        }

        /* Estilos para el enlace de página activa */
        .pagination-container ul li.active {
            font-weight: bold; /* Opcional: resaltar la página activa */
        }
    </style>
</head>
<body>
    <h2>Solicitudes</h2>
    <table class="solicitud-table">
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Categoría</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["codigo"] . "</td>";
                echo "<td>" . $row["descripcion"] . "</td>";
                echo "<td>" . $row["cantidad"] . "</td>";
                echo "<td>" . $row["categoria_productos"] . "</td>";
                echo "<td>" . $row["estado"] . "</td>";
                echo "<td>
                <a href='view_solicitud.php?id=" . $row["id"] . "'><i class='fas fa-eye'></i></a>
                <a href='add_cotizacion.php?id=" . $row["id"] . "'><i class='fas fa-shopping-cart'></i></a>
                <a href='detalle_solicitud.php?id=" . $row["id"] . "'><i class='fas fa-file-alt'></i></a>
                
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "No se encontraron solicitudes.";
        }
        ?>
    </table>
</body>
</html>
