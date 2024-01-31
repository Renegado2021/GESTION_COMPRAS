<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="../estilos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

        .filter-icon {
            cursor: pointer;
        }

        #fecha_filtro {
            display: none;
        }
    </style>    

</head>
<body>

<?php
include('../conexion/conexion.php'); // Incluye el archivo de conexión

// Almacena el contenido del encabezado
$headerContent = '
    <h2><i class="fas fa-book"></i>Cotizaciones</h2>
    <table class="solicitud-table">
        <tr>
            <th>Nº Cotización</th>
            <th>Solicitud</th>
            <th>Proveedor</th>
            <th>
                Fecha
                <i class="fas fa-filter filter-icon" onclick="toggleFechaFiltro()"></i>
                <input type="date" id="fecha_filtro" onchange="filtrarPorFecha()">
            </th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
';

// Verificar si se proporcionó una fecha para filtrar
if (isset($_GET['fecha_filtro'])) {
    $fechaFiltro = date('Y-m-d', strtotime($_GET['fecha_filtro']));
    $sql = "SELECT DISTINCT c.NUMERO_COTIZACION, c.ID_COTIZACION, c.ID, c.ID_PROVEEDOR, p.NOMBRE as PROVEEDOR, c.FECHA_COTIZACION, c.ESTADO
            FROM tbl_cotizacion c
            INNER JOIN tbl_proveedores p ON c.ID_PROVEEDOR = p.ID_PROVEEDOR
            WHERE c.FECHA_COTIZACION = '$fechaFiltro'
            ORDER BY c.NUMERO_COTIZACION";
} else {
    // Si no se proporcionó una fecha, obtén todas las cotizaciones
    $sql = "SELECT DISTINCT c.NUMERO_COTIZACION, c.ID_COTIZACION, c.ID, c.ID_PROVEEDOR, p.NOMBRE as PROVEEDOR, c.FECHA_COTIZACION, c.ESTADO
            FROM tbl_cotizacion c
            INNER JOIN tbl_proveedores p ON c.ID_PROVEEDOR = p.ID_PROVEEDOR
            ORDER BY c.NUMERO_COTIZACION";
}

$result = $conn->query($sql);

$rowsContent = '';

if ($result->num_rows > 0) {
    // Mostrar datos en una tabla HTML
    echo $headerContent; // Imprime el encabezado

    while ($row = $result->fetch_assoc()) {
        $rowsContent .= '<tr>';
        $rowsContent .= '<td>' . $row["NUMERO_COTIZACION"] . '</td>';
        $rowsContent .= '<td>' . $row["ID"] . '</td>';
        $rowsContent .= '<td>' . $row["PROVEEDOR"] . '</td>';
        $rowsContent .= '<td>' . date("d/m/Y", strtotime($row["FECHA_COTIZACION"])) . '</td>';
        $rowsContent .= '<td>' . $row["ESTADO"] . '</td>';
        $rowsContent .= '<td>
                           <a href="verCotizacion.php?id='. $row["ID_COTIZACION"] . '" style="background-color: blue; color: white; padding: 4px; border-radius: 5px; margin-right: 5px;">
                           <i class="fas fa-eye"></i>
                           </a> 
      
                            <a href="editar_cotizacion.php?id=' . $row["ID_COTIZACION"] . '" style="background-color: green; color: white; padding: 5px; border-radius: 5px;"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger" onclick="eliminarCotizacion(' . $row["ID_COTIZACION"] . ')" style="font-size: 14px;">
                              <i class="fas fa-trash"></i>
                            </button>

                            <a href="../cotizaciones/add_cotizaciones.php?id=' . $row["ID"] . '" style="background-color: orange; color: white; padding: 5px; border-radius: 5px;"><i class="fas fa-shopping-cart"></i></a>
                        </td>';
        $rowsContent .= '</tr>';
    }

    echo $rowsContent; // Imprime el contenido de las filas

    echo '</table>'; // Cierra la etiqueta de la tabla
} else {
    // Imprime el encabezado cuando no hay cotizaciones
    echo $headerContent;
    
    // Imprime la fila de mensaje dentro del cuerpo de la tabla
    echo '<tr><td colspan="6">No se encontraron cotizaciones.</td></tr>';
    
    echo '</table>'; // Cierra la etiqueta de la tabla
}

// Cerrar conexión a la base de datos
$conn->close();
?>


<!-- Este crip sirve para editar la solicitud -->

   <script>
        function editarSolicitud(id) {
            // Redirige a la página editar_solicitud.php con el ID de la solicitud como parámetro
            window.location.href = `../solicitudes/editar_solicitud.php?id=${id}`;
        }

        <!-- Agrega este script JavaScript para manejar la eliminación con AJAX -->

    function eliminarCotizacion(idCotizacion) {
        // Mostrar un cuadro de confirmación
        var confirmacion = confirm("¿Seguro que quieres eliminar esta cotización?");

        if (confirmacion) {
            // Realizar una solicitud AJAX para eliminar la cotización
            fetch('eliminar_cotizacion.php?id=' + idCotizacion + '&confirmar=true', {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                // Recargar la página después de la eliminación
                location.reload();
            })
            .catch(error => {
                console.error('Error en la solicitud AJAX:', error);
            });
        }
    }

    function toggleFechaFiltro() {
        $('#fecha_filtro').toggle();
        toggleHeaderVisibility(); // Añade esto para manejar la visibilidad del encabezado
    }

    function filtrarPorFecha() {
        var fechaFiltro = $('#fecha_filtro').val();

        $.ajax({
            url: 'cotizaciones.php',
            type: 'GET',
            data: { fecha_filtro: fechaFiltro },
            success: function(data) {
                var tbody = $('.solicitud-table tbody');

                // Limpiar el contenido actual del cuerpo de la tabla
                tbody.html('');

                if (data.trim() !== '') {
                    // Si hay datos, agregar el contenido al cuerpo de la tabla
                    tbody.append(data);
                } else {
                    // Si no hay datos, agregar la fila informativa
                    tbody.html('<tr><td colspan="5">No hay datos en esa fecha.</td></tr>');
                }

                console.log('Tabla actualizada correctamente.');
                toggleHeaderVisibility(); // Añade esto para manejar la visibilidad del encabezado
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al realizar la solicitud AJAX:', textStatus, errorThrown);
            }
        });
    }

    // Añadir esta función para controlar la visibilidad del encabezado
    function toggleHeaderVisibility() {
        var fechaFiltro = $('#fecha_filtro').val();
        var header = $('h2:contains("Cotizaciones")');

        if (fechaFiltro && fechaFiltro.trim() !== '' && header.is(':visible')) {
            header.hide();
        } else if ((!fechaFiltro || fechaFiltro.trim() === '') && !header.is(':visible')) {
            header.show();
        }
    }

    // Llamar a la función toggleHeaderVisibility() en el cambio de la fecha
    $('#fecha_filtro').on('change', toggleHeaderVisibility);

</script>
</body>
</html>