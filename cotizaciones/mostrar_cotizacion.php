<!DOCTYPE html>
<html>
<head>
    <title>Ver Solicitud</title>
    <style>
          body {
            text-align: center;
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10); /* Cambia el valor de "0.7" para ajustar la transparencia */
            background-image: url('../imagen/background.jpg'); /* Reemplaza con la ruta de tu imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
           
        
        }

        .container {
            display: inline-block;
            text-align: left;
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
            background-color: powderblue; /* Color de fondo azul claro (cielo) */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
            opacity: 0.9; /* Valor de opacidad (menos transparente) */
        }

        .table-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .table {
            width: 100%; /* El 2% es para el espacio entre las tablas */
            box-sizing: border-box; /* Para evitar que los bordes agreguen más ancho */
            background-color: cornsilk; /* Color de fondo  para las tablas */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            
        }

        th {
            background-color: bisque;
        }

        .blue-button {
        background-color: blue;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .blue-button:hover {
        background-color: darkred;
    }



    </style>
</head>
<body>
<div class="container">
    
    <div class="table">
<?php
include 'db_connection.php';


if (isset($_GET['id'])) {
    $idSolicitud = $_GET['id']; // Obtener el ID de solicitud desde la URL
    // Realiza una consulta para obtener las cotizaciones de la solicitud
$sql = "SELECT C.ID_COTIZACION, P.NOMBRE AS NOMBRE_PROVEEDOR, C.DESCRIPCION, C.CANTIDAD, C.ESTADO, C.PRECIO 
FROM tbl_cotizacion AS C
INNER JOIN tbl_proveedores AS P ON C.ID_PROVEEDOR = P.ID_PROVEEDOR
WHERE C.ID = $idSolicitud";
$resultCotizaciones = $conn->query($sql);

if ($resultCotizaciones->num_rows > 0) {
// Muestra las cotizaciones en una tabla
echo "<h2 style='text-align: center;'>Cotizaciones</h2>";
echo "<table>";
echo "<tr><th>ID Cotización</th><th>Proveedor</th><th>Descripción</th><th>Cantidad</th><th>Estado</th><th>Precio</th></tr>";
while ($rowCotizacion = $resultCotizaciones->fetch_assoc()) {
echo "<tr>";
echo "<td>" . $rowCotizacion['ID_COTIZACION'] . "</td>";
echo "<td>" . $rowCotizacion['NOMBRE_PROVEEDOR'] . "</td>";
echo "<td>" . $rowCotizacion['DESCRIPCION'] . "</td>";
echo "<td>" . $rowCotizacion['CANTIDAD'] . "</td>";
echo "<td>" . $rowCotizacion['ESTADO'] . "</td>";
echo "<td>" . $rowCotizacion['PRECIO'] . "</td>";
echo "</tr>";

}

echo "</table>";
echo "<br>";


} else {
echo "No se encontraron cotizaciones para esta solicitud.";
}
} else {
    echo "El parámetro 'id' no está definido en la URL.";
}
echo "<a href='../solicitudes/solicitudes.php' class='blue-button'>Regresar</a>";
?>

</div>

<br>
</div>
</body>
</html>