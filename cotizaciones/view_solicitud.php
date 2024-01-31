<?php
session_start();

include("../conexion/conexion.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuarioId"])) {
    // El usuario no ha iniciado sesión, redirige a la página de inicio de sesión
    header("Location: index.php");
    exit();
}

// Asegúrate de haber iniciado sesión correctamente
if (!isset($_SESSION["usuarioId"])) {
    // Redirige a la página de inicio de sesión si no hay una sesión activa
    header("Location: login.php");
    exit();
}

// Obtener información adicional del usuario (nombre y rol)
$sqlInfoUsuario = "SELECT nombre_usuario, rol FROM tbl_ms_usuario WHERE id_usuario = ?";
$stmtInfoUsuario = $conn->prepare($sqlInfoUsuario);
$stmtInfoUsuario->bind_param("i", $_SESSION["usuarioId"]);
$stmtInfoUsuario->execute();
$resultInfoUsuario = $stmtInfoUsuario->get_result();

if ($resultInfoUsuario->num_rows > 0) {
    $rowInfoUsuario = $resultInfoUsuario->fetch_assoc();

    // Establecer las variables de sesión con información adicional
    $_SESSION["nombreUsuario"] = $rowInfoUsuario["nombre_usuario"];
    $_SESSION["rol"] = $rowInfoUsuario["rol"];
} else {
    // Manejar el caso en que no se encuentre la información del usuario
    $_SESSION["nombreUsuario"] = null; // Puedes establecer un valor por defecto o manejarlo según tu lógica
    $_SESSION["rol"] = null; // Puedes establecer un valor por defecto o manejarlo según tu lógica
}



$usuariosId = $_SESSION["usuarioId"];

// Obtén el nombre del rol directamente en la consulta
$sqlRolUsuario = "SELECT r.NOMBRE_ROL
                  FROM tbl_ms_usuario u
                  JOIN tbl_ms_roles r ON u.rol = r.ID_ROL
                  WHERE u.id_usuario = ?";
$stmtRolUsuario = $conn->prepare($sqlRolUsuario);
$stmtRolUsuario->bind_param("i", $usuariosId);
$stmtRolUsuario->execute();
$resultRolUsuario = $stmtRolUsuario->get_result();

if ($resultRolUsuario->num_rows > 0) {
    $rowRolUsuario = $resultRolUsuario->fetch_assoc();
    // Actualizar la variable de sesión con el nombre del rol
    $_SESSION["rol"] = $rowRolUsuario["NOMBRE_ROL"];
} else {
    // Manejar el caso en que no se encuentra el nombre del rol del usuario
    $_SESSION["rol"] = null; // Puedes establecer un valor por defecto o manejarlo según tu lógica
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Ver Solicitud</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10);
            background-image: url('../imagen/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        .container {
            display: inline-block;
            text-align: center;
            border: 1px solid #ccc;
            padding: 40px;
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

        table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: cornsilk; /* Color de fondo  para las tablas */
}

th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
    max-width: 200px; /* Ajusta el ancho máximo de la celda para evitar el desbordamiento */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; /* Agrega puntos suspensivos (...) si el contenido es demasiado largo */
}


th {
    background-color: bisque;
}

        /* Estilos para todas las tablas con la clase "solicitud-info" */

        .solicitud-info {
            border: 0px solid #ccc;
            padding: 10px;
            width: 100%;
            background-color: cornsilk; /* Color de fondo  para las tablas */
        }

      /* Estilos para la tabla con clase "solicitud-info" */
table.solicitud-info {
    width: 80%; /* Ancho personalizado para esta tabla */
    border: 2px solid #ccc;
    margin: 0 auto; /* Centra la tabla horizontalmente */
}

table.solicitud-info tr td {
    padding: 2px;
    border-bottom: 1px solid #ccc;
    word-wrap: break-word;
    text-align: left;
}

table.solicitud-info tr:last-child td {
    border-bottom: none;
}

        .button-row {
            text-align: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        /* Estilo del botón */
        .aprobar-button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }

        .aprobar-button:hover {
            background-color: #45a049;
        }

        .regresar-button {
            background-color: #007bff;
            color: white;
            padding: 12px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .regresar-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
<div class="container">
    
    <div class="table">
    
       <?php
          include 'db_connection.php';

           if (isset($_GET['id'])) {
                $idSolicitud = $_GET['id'];

               if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aprobar'])) {
                 // Manejo de aprobación de cotización
                   if (isset($_POST['cotizaciones_aprobadas']) && is_array($_POST['cotizaciones_aprobadas'])) {
                      // Obtener las cotizaciones aprobadas
                      $cotizacionesAprobadas = $_POST['cotizaciones_aprobadas'];

                      // Marcar todas las cotizaciones como "En Proceso"
                      $sqlMarcarEnProceso = "UPDATE tbl_cotizacion SET ESTADO = 'Proceso' WHERE ID = $idSolicitud";
                       if ($conn->query($sqlMarcarEnProceso) !== TRUE) {
                          echo "Error al marcar cotizaciones en proceso: " . $conn->error;
                        }

                      // Actualizar el estado de las cotizaciones aprobadas a "Aprobada"
                      foreach ($cotizacionesAprobadas as $cotizacionID) {
                          $sqlActualizarCotizacion = "UPDATE tbl_cotizacion SET ESTADO = 'Aprobada' WHERE ID_COTIZACION = $cotizacionID";
                            if ($conn->query($sqlActualizarCotizacion) !== TRUE) {
                             echo "Error al aprobar la cotización ID $cotizacionID: " . $conn->error;
                            }
                        }

                      echo "Cotización aprobada con éxito.";
                      header('Location: ../solicitudes/solicitudes.php'); // Redirige a la página de solicitudes

                      exit();
                    } else {
                       echo "No se han seleccionado cotizaciones para aprobar.";
                    }
                }

              // Recuperar y mostrar información de la solicitud
               $sqlSolicitud = "SELECT s.id,  s.idDepartamento, s.codigo,  u.nombre_usuario, d.nombre_departamento 
               FROM tbl_solicitudes s
                LEFT JOIN tbl_departamentos d ON s.idDepartamento = d.id_departamento
                LEFT JOIN tbl_ms_usuario u ON s.usuario_id = u.id_usuario
                WHERE s.id = $idSolicitud";
                $resultSolicitud = $conn->query($sqlSolicitud);

               if ($resultSolicitud->num_rows > 0) {
                    $rowSolicitud = $resultSolicitud->fetch_assoc();

                   echo '<div class="solicitud-info">';
                   echo '<h3>Información de la Solicitud</h3>';
                   echo '<table class="solicitud-info">';
                   echo '<tr><td>Número:</td><td>' . $rowSolicitud['id'] . '</td></tr>';
                   echo '<tr><td>Código:</td><td>' . $rowSolicitud['codigo'] . '</td></tr>';
                   echo '<tr><td>Usuario:</td><td>' . $rowSolicitud['nombre_usuario'] . '</td></tr>';
                   echo '<tr><td>Departamento:</td><td>' . $rowSolicitud['nombre_departamento'] . '</td></tr>';
                  
                  echo '</table>';
                  echo '</div>';
        
                  echo '<br>';        
        

                  // Lógica para verificar si la solicitud ya tiene una cotización aprobada
                    $sqlVerificarAprobacion = "SELECT COUNT(*) AS num_aprobadas FROM tbl_cotizacion WHERE ID = $idSolicitud AND ESTADO = 'Aprobada'";
                    $resultVerificarAprobacion = $conn->query($sqlVerificarAprobacion);
   
                   if ($resultVerificarAprobacion->num_rows > 0) {
                      $rowVerificarAprobacion = $resultVerificarAprobacion->fetch_assoc();
                      $numCotizacionesAprobadas = $rowVerificarAprobacion['num_aprobadas'];
                      if ($_SESSION["rol"] === "Administrador" || $_SESSION["rol"] === "Aprobador") {
                       if ($numCotizacionesAprobadas > 0) {
                           // Ya tiene cotizaciones aprobadas, preguntar si desea cambiarlas
                          
                           echo '<p>¿Desea cambiar la cotización?</p>';
                          echo '<form method="post" action="">';
                          echo '<div class="radio-button">';
                          echo '<input type="radio" name="cambiar" value="Si" onclick="mostrarTabla()"> Sí';
                          echo '<input type="radio" name="cambiar" value="No" onclick="mostrarTabla()"> No';
                          echo '</div>';
                          //echo '<button class="aprobar-button" type="submit" name="cambiar_cotizacion">Cambiar</button>';
                           echo '</form>';
                        } else {
                           // No tiene cotizaciones aprobadas, mostrar directamente las cotizaciones asociadas a la solicitud
                           $sqlCotizaciones = "SELECT c.*, p.NOMBRE AS NOMBRE_PROVEEDOR 
                            FROM tbl_cotizacion c
                            JOIN tbl_proveedores p ON c.ID_PROVEEDOR = p.ID_PROVEEDOR
                            WHERE c.id = $idSolicitud";
                            $resultCotizaciones = $conn->query($sqlCotizaciones);

                           if ($resultCotizaciones->num_rows > 0) {
                              echo '<h2>Cotizaciones Asociadas</h2>';
                              echo '<form method="post" action="">';
                              echo '<table border="1">';
                              echo '<tr><th>Seleccionar</th><th style="text-align:center;">Nº Cotización</th><th style="text-align:center;">Proveedor</th><th style="text-align:center;">URL</th></tr>';

                              while ($rowCotizacion = $resultCotizaciones->fetch_assoc()) {
                                  echo '<tr>';
                                  echo '<td><input type="radio" name="cotizaciones_aprobadas[]" value="' . $rowCotizacion['ID_COTIZACION'] . '"></td>';
                                  echo '<td>' . $rowCotizacion['NUMERO_COTIZACION'] . '</td>';
                                  echo '<td>' . $rowCotizacion['NOMBRE_PROVEEDOR'] . '</td>';
                                  echo '<td><a href="' . $rowCotizacion['URL'] . '" target="_blank">' . $rowCotizacion['URL'] . '</a></td>';
                                  
                                  echo '</tr>';
                                }

                               echo '</table>';
                               echo '<br>';
                               echo '<div class="button-container">';
                               echo '<button class="aprobar-button" type="submit" name="aprobar">Aprobar</button>';
                               echo '</div>';
                               echo '</form>';
                            } else {
                               echo "No hay cotizaciones asociadas.";
                            }
                        }
                    } else {
                        echo '<p>No tienes permisos para cambiar o seleccionar la cotización.</p>';
                    }
                    }
                }
            }
        ?>

       <div id="cotizaciones-section" style="display: none;">
           <?php
              // Consulta original para obtener las cotizaciones asociadas
               $sqlCotizaciones = "SELECT c.*, p.NOMBRE AS NOMBRE_PROVEEDOR 
               FROM tbl_cotizacion c
               JOIN tbl_proveedores p ON c.ID_PROVEEDOR = p.ID_PROVEEDOR
                WHERE c.id = $idSolicitud";

                $resultCotizaciones = $conn->query($sqlCotizaciones);

                if ($resultCotizaciones->num_rows > 0) {
                  echo '<h2>Cotizaciones Asociadas</h2>';
                  echo '<form method="post" action="">';
                  echo '<table border="1">';
                  echo '<tr><th>Seleccionar</th><th style="text-align:center;">Codigó</th><th style="text-align:center;">Proveedor</th><th style="text-align:center;">URL</th></tr>';

                  while ($rowCotizacion = $resultCotizaciones->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><input type="radio" name="cotizaciones_aprobadas[]" value="' . $rowCotizacion['ID_COTIZACION'] . '"></td>';
                        echo '<td>' . $rowCotizacion['NUMERO_COTIZACION'] . '</td>';
                        echo '<td>' . $rowCotizacion['NOMBRE_PROVEEDOR'] . '</td>';
                        echo '<td><a href="' . $rowCotizacion['URL'] . '" target="_blank">' . $rowCotizacion['URL'] . '</a></td>';
                       
                        echo '</tr>';
                    }

                   echo '</table>';
                   echo '<br>';
                   echo '<div class "button-container">';
                   echo '<button class="aprobar-button" type="submit" name="aprobar">Aprobar</button>';
                  echo '<a href="../solicitudes/solicitudes.php" class="regresar-button">Regresar</a>';
                   echo '</div>';
                   echo '</form>';
                } else {
                   echo "No hay cotizaciones asociadas.";
                }
            ?>
        </div>
    </div>

    <script>
      function mostrarTabla() {
         var cambiar = document.querySelector('input[name="cambiar"]:checked').value;
         var cotizacionesSection = document.getElementById('cotizaciones-section');

            if (cambiar === 'Si') {
             cotizacionesSection.style.display = 'block';
            } else if (cambiar === 'No') {
              // Realizar una redirección cuando se selecciona "No"
             window.location.href = '../solicitudes/solicitudes.php';
            }
       }
    </script>

</div>

</body>
</html>