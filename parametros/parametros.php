<?php
  // Incluye el archivo de conexión a la base de datos
  include('../conexion/conexion.php');

  // Consulta SQL para obtener todos los parámetros
  $sql = "SELECT * FROM tbl_ms_parametros";
  $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .create-button {
            margin-top: 10px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right; /* Alinea a la derecha */
        }

        .edit-link {
          background-color: #28a745; /* Estilo para Editar */
          color: #fff;
          padding: 5px 10px;
          text-decoration: none;
          border-radius: 5px;
           margin-right: 5px; /* Agregamos margen derecho para separar los enlaces */
        }

       .delete-link {
          background-color: #dc3545; /* Estilo para Eliminar */
          color: #fff;
          padding: 5px 10px;
           text-decoration: none;
          border-radius: 5px;
          margin-right: 5px;
          border: none; /* Elimina el borde */
        }

        .delete-link:focus {
          outline: none; /* Elimina el contorno al hacer clic en el botón */
        }

    </style>
</head>
<body>


    <h2><i class="fas fa-sliders-h"></i>Parámetros</h2>
    <a href="crear.php" class="create-button"><i class='fas fa-plus'></i></a>
    <?php
         if ($result->num_rows > 0) {
              echo '<table border="1">';
               echo '<tr><th>PARAMETRO</th><th>VALOR</th><th>FECHA_CREACION</th><th>FECHA_MODIFICACION</th><th>ACCIONES</th></tr>';
        
               while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['PARAMETRO'] . '</td>';
                echo '<td>' . $row['VALOR'] . '</td>';
                echo '<td>' . date('Y-m-d', strtotime($row['FECHA_CREACION'])) . '</td>';
                echo '<td>' . date('Y-m-d', strtotime($row['FECHA_MODIFICACION'])) . '</td>';
               // Agregar la columna de acciones con los botones de editar y eliminar
               echo '<td>';
               echo '<a href="editar.php?id=' . $row['ID_PARAMETRO'] . '" class="edit-link"><i class="fas fa-edit"></i></a>';


            
              // Agregar el botón de eliminar con el evento onclick
             echo '<button class="delete-link " onclick="eliminarParametro(' . $row['ID_PARAMETRO'] . ')"><i class="fas fa-trash"></i></button>';
             echo '</td>';
             echo '</tr>';
            }

            echo '</table>';
        } else {
          echo 'No hay parámetros registrados.';
        }

      // Cierra la conexión
      $conn->close();
    ?>
    <button class="styled-button" onclick="window.location.href='../setting/ajustes.php'" style="background-color: #007bff; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;">Regresar</button>

    <script>
        // Función para confirmar la eliminación y llamar a la función eliminarParametroAjax
        function eliminarParametro(idParametro) {
            if (confirm('¿Estás seguro de que deseas eliminar este parámetro?')) {
                eliminarParametroAjax(idParametro);
            }
        }

        // Función para realizar la eliminación mediante Ajax
        function eliminarParametroAjax(idParametro) {
            // Crear una instancia de XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Configurar la solicitud
            xhr.open("POST", "eliminar.php", true);

            // Configurar la función de retorno de llamada
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // La solicitud fue exitosa, puedes realizar acciones adicionales si es necesario
                    console.log('El parámetro fue eliminado exitosamente');
                    // Actualizar la página para reflejar los cambios
                    location.reload();
                }
            }; 

            // Configurar las cabeceras de la solicitud
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            // Enviar la solicitud con el ID del parámetro a eliminar
            xhr.send("idParametro=" + idParametro);
        }
    </script>
</body>
</html>