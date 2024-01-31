<?php
include('db.php');

$query = "SELECT cp.ID_CUENTA_PROVEEDOR, cp.NUMERO_CUENTA, cp.BANCO, cp.DESCRIPCION_CUENTA, cp.SALDO, cp.FECHA_CREACION, cp.FECHA_MODIFICACION, p.NOMBRE as NOMBRE_PROVEEDOR
          FROM tbl_cuenta_proveedor cp
          INNER JOIN tbl_proveedores p ON cp.ID_PROVEEDOR = p.ID_PROVEEDOR";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT cp.ID_CUENTA_PROVEEDOR, cp.NUMERO_CUENTA, cp.BANCO, cp.DESCRIPCION_CUENTA, cp.SALDO, cp.FECHA_CREACION, cp.FECHA_MODIFICACION, p.NOMBRE as NOMBRE_PROVEEDOR
              FROM tbl_cuenta_proveedor cp
              INNER JOIN tbl_proveedores p ON cp.ID_PROVEEDOR = p.ID_PROVEEDOR
              WHERE cp.NUMERO_CUENTA LIKE '%$search%' OR cp.BANCO LIKE '%$search%' OR p.NOMBRE LIKE '%$search%'";
} else {
    $query = "SELECT cp.ID_CUENTA_PROVEEDOR, cp.NUMERO_CUENTA, cp.BANCO, cp.DESCRIPCION_CUENTA, cp.SALDO, cp.FECHA_CREACION, cp.FECHA_MODIFICACION, p.NOMBRE as NOMBRE_PROVEEDOR
              FROM tbl_cuenta_proveedor cp
              INNER JOIN tbl_proveedores p ON cp.ID_PROVEEDOR = p.ID_PROVEEDOR";
}

// Número de cuentas por página (puede venir de un formulario o ser un valor predeterminado)
$resultsPerPage = isset($_GET['por_pagina']) ? intval($_GET['por_pagina']) : 10;

// Página actual (por defecto es 1)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calcular el desplazamiento
$offset = ($page - 1) * $resultsPerPage;

// Realizar la consulta SQL con el límite y el desplazamiento
$query = "SELECT cp.ID_CUENTA_PROVEEDOR, cp.NUMERO_CUENTA, cp.BANCO, cp.DESCRIPCION_CUENTA, cp.SALDO, cp.FECHA_CREACION, cp.FECHA_MODIFICACION, p.NOMBRE as NOMBRE_PROVEEDOR
          FROM tbl_cuenta_proveedor cp
          INNER JOIN tbl_proveedores p ON cp.ID_PROVEEDOR = p.ID_PROVEEDOR";
$query .= " LIMIT $resultsPerPage OFFSET $offset";



// Calcular el número total de cuentas en la base de datos
$queryCount = "SELECT COUNT(*) AS total FROM tbl_cuenta_proveedor";
$countResult = $conexion->query($queryCount);
$totalAccounts = $countResult->fetch_assoc()['total'];

// Calcular el número total de páginas
$totalPages = ceil($totalAccounts / $resultsPerPage);






$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Listado de Cuentas de Proveedores</title>
    <meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="../estilos.js"></script>
<title>Listado de Proveedores</title>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: left;
            color: black;
            padding: 10px;
            margin-left: 110px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #0078d4;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #0078d4;
        }

        a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #0078d4;
            color: #fff;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            display: inline-block;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button-container {
            display: flex;
            justify-content: flex-end; /* Cambia space-between a flex-end */
            align-items: center;
            margin: -20px 0; /* Ajusta el margen superior para alinear más cerca de la tabla */
            padding: 10px;
            margin-right: 110px;
           
            
        }

        .button-container label {
            margin-right: 190px;
        }

        .button-container input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: -0px;
        }

        .button-container form {
            display: flex;
            align-items: center;
            margin-right: 1px;
        }

        .button-container button {
    margin-left: -0px; /* Ajusta el margen izquierdo */
    border-radius: 20px; /* Ajusta el valor del border-radius para dar forma ovalada */
    
}

/* Estilo específico para el botón "Crear Nuevo Proveedor" */
.btn-crear {
    background-color: #4CAF50; /* Cambia el color del botón a verde */
    color: #fff; /* Cambia el color del texto a blanco */
    padding: 10px 20px; /* Ajusta el relleno para darle espacio al ícono y el texto */
    border: none; /* Quita el borde para que se vea como un botón simple */
    border-radius: 20px; /* Da forma ovalada al botón */
}




        .action-link-blue,
    .action-link-green,
    .action-link-red {
        text-decoration: none;
    }

    .action-link-blue i,
    .action-link-green i,
    .action-link-red i {
        margin-right: 5px; /* Añade un espacio entre el icono y el texto */
    }

    .action-link-blue {
        color: #0078d4; /* Azul para "Ver" */
    }

    .action-link-green {
        color: #4CAF50; /* Verde para "Editar" */
    }

    .action-link-red {
        color: #FF0000; /* Rojo para "Eliminar" */
    }

    .action-link-blue:hover,
    .action-link-green:hover,
    .action-link-red:hover {
        text-decoration: underline;
    }

    /* Estilo para el selector de cantidad de proveedores por página */
.select-por-pagina {
    padding: -10px;
    border: 1px solid #ccc;
    border-radius: 1px;
    margin-left: -5px;
}

/* Estilo para el label "Proveedores por página" */
label[for="por_pagina"] {
    display: inline-block; /* Alinea el label en línea con el select */
    font-weight: bold; /* Hace que el texto sea más destacado */
    margin-left: 120px; /* Añade espacio del label */
    color: #0078d4; /* Cambia el color del texto a azul */
}

/* Estilo para centrar la paginación */
.pagination-center {
    text-align: center; /* Centra el contenido horizontalmente */
    margin-right: -900px; /* Agrega margen superior para alinear más cerca de la tabla */
}

#por_pagina {
    width: 50px; /* Ancho deseado */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

.pagination-button {
    background-color: #0078d4; /* Color de fondo deseado */
    color: #fff; /* Color del texto dentro del botón */
    width: 50px; /* Ajusta el ancho del botón */
    height: 40px; /* Ajusta la altura del botón */
    padding: 10px 20px; /* Ajusta el relleno para dar espacio al ícono o texto */
    border: none; /* Quita el borde para que se vea como un botón simple */
    border-radius: 50%; /* Configura el radio del borde para hacer que el botón sea ovalado */
    font-size: 14px; /* Tamaño de fuente deseado */
}

.pagination-button:hover {
    background-color: #0056b3; /* Cambia el color de fondo al pasar el mouse sobre el botón */
}

    </style>
</head>
<body>
    <h2><i class="fas fa-wallet"></i> Cuentas Bancarias</h2>

    <div class="button-container">

<div class="search-container">

    <form method="GET">
        <label for="search"></label>
        <input type="text" name="search" id="search">
        <button type="submit" class="fas fa-search"></button>
    </form>

 </div>  
  <a href="crear_cuenta1.php"><button class="btn-crear"><i class="fas fa-plus"></i> Crear</button></a>
</div>
 
<form method="GET">
    <label for="por_pagina">Proveedores por página:</label>
    <input type="text" name="por_pagina" id="por_pagina" value="<?php echo $resultsPerPage; ?>">
    <button type="submit" id="search-button" class="pagination-button"><i class="fas fa-search"></i></button>
</form>

    <table>
        <tr>
            <th>ID</th>
            <th>Número de Cuenta</th>
            <th>Banco</th>
            <th>Descripción</th>
            <th>Saldo</th>
            <th>Fecha de Creación</th>
            <th>Fecha de Modificación</th>
            <th>Proveedor</th>
            <th>Acciones</th>
        </tr>

        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['ID_CUENTA_PROVEEDOR'] . "</td>";
            echo "<td>" . $row['NUMERO_CUENTA'] . "</td>";
            echo "<td>" . $row['BANCO'] . "</td>";
            echo "<td>" . $row['DESCRIPCION_CUENTA'] . "</td>";
            echo "<td>" . $row['SALDO'] . "</td>";
            // Cambia el formato de las fechas para mostrar solo día, mes y año
           echo "<td>" . date("d/m/Y", strtotime($row['FECHA_CREACION'])) . "</td>";
           echo "<td>" . date("d/m/Y", strtotime($row['FECHA_MODIFICACION'])) . "</td>";
            echo "<td>" . $row['NOMBRE_PROVEEDOR'] . "</td>";
          
            echo "<td><a href='actualizar_cuenta.php?id=" . $row['ID_CUENTA_PROVEEDOR'] . "' class='action-link-green'><i class='fas fa-edit'></i></a>";
            echo "<a href='eliminar_cuenta.php?id=" . $row['ID_CUENTA_PROVEEDOR'] . "'class='action-link-red'><i class='fas fa-trash-alt'></i></a></td>";

            echo "</tr>";
        }
        ?>
    </table>

    <div class="pagination-center">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $page) ? 'active' : '';
            echo "<a href='listar_cuentas.php?page=$i&por_pagina=$resultsPerPage' class='$activeClass'>$i</a> ";
        }
        ?>
    </div>

   



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        $(document).ready(function () {
            // Manejo de clics en los enlaces de paginación
            $(".pagination-link").on("click", function (e) {
                e.preventDefault();
                var page = $(this).data("page");

                // Realizar una solicitud AJAX para cargar la página deseada
                $.ajax({
                    type: "GET",
                    url: "listar_cuentas", // Reemplaza "tu_pagina.php" por el nombre de tu archivo PHP
                    data: { page: page, por_pagina: <?php echo $resultsPerPage; ?> },
                    success: function (data) {
                        // Actualizar el contenido de la página sin recargar
                        $("#resultados").html(data);
                    },
                    error: function () {
                        alert("Error al cargar los resultados.");
                    }
                });
            });
        });
    </script>

</body>
</html>