<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT d.id_departamento, e.nombre_empresa, d.nombre_departamento, d.estado_departamento, d.creado, DATE_FORMAT(d.fecha_creacion, '%d/%m/%Y') AS fecha_creacion, DATE_FORMAT(d.fecha_modificacion, '%d/%m/%Y') AS fecha_modificacion, d.modificado_por 
            FROM tbl_departamentos d 
            JOIN tbl_empresa e ON d.id_empresa = e.id_empresa 
            WHERE d.id_departamento = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $departamento = $stmt->fetch(PDO::FETCH_ASSOC);


    // Obtener el nombre de la empresa correspondiente al ID de la empresa
    $stmtEmpresa = $conn->prepare("SELECT nombre_empresa FROM tbl_empresa WHERE id_empresa = :id_empresa");
    $stmtEmpresa->bindParam(':id_empresa', $departamento['id_empresa']);
    $stmtEmpresa->execute();
    $nombreEmpresa = $stmtEmpresa->fetchColumn(); // Obtenemos el nombre de la empresa

} else {
    echo 'ID de departamento no proporcionado.';
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="../estilos.js"></script>
    <title>Editar Categoría</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../imagen/carpeta.jpeg'); /* Reemplaza con la ruta de tu imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        
        }

        /* Estilos para centrar el título */
        h2 {
         text-align: center; /* Centrar el texto horizontalmente */
        }

        /* Estilos para el contenedor del formulario (el cuadro) */
       .form-container {
         width: 80%; /* Ancho del contenedor */
         margin: 0 auto; /* Centrar horizontalmente en la página */
          padding: 20px; /* Espacio interno alrededor del formulario */
          border: 1px solid #ccc; /* Borde del cuadro */
         border-radius: 5px; /* Bordes redondeados */
         background-color: #f9f9f9; /* Color de fondo del cuadro */
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
         opacity: 0.8; /* Valor de opacidad (menos transparente) */
        }

      /* Estilos para la columna del formulario */
       .form-column {
         float: left; /* Flotar las columnas a la izquierda */
          width: 50%; /* Ancho del 50% para ambas columnas */
          box-sizing: border-box; /* Incluir el relleno y el borde en el ancho */
          padding: 10px; /* Espacio interno entre elementos dentro de la columna */
        }

        /* Estilos para los campos de entrada (input) en la columna */
        .form-column input {
          width: 100%; /* Ancho del 100% para llenar la columna completa */
           padding: 10px; /* Espaciado dentro de los campos de entrada */
          margin-bottom: 10px; /* Espacio inferior entre campos de entrada */
        }

        /* Estilos para las etiquetas (label) en la columna */
       .form-column label {
         display: block; /* Mostrar las etiquetas en una nueva línea */
          margin-bottom: 5px; /* Espacio inferior entre etiquetas */
        }

        select{
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        /* Estilos para los botones */
        button, .cancel-button {
           border: none; /* Sin borde */
           cursor: pointer;
           text-decoration: none; /* Quitar el subrayado */
        }

        /* Estilos para el botón "Guardar" */
       .custom-button {
          padding: 12px 20px; /* Ajustar el espaciado dentro de los botones */
           background-color: #007bff; /* Color de fondo del botón "Guardar" */
          color: #fff; /* Color de texto para el botón "Guardar" */
        }

        /* Estilos para el botón "Cancelar" */
        .cancel-button {
          padding: 15px 20px; /* Mantener el espaciado en el botón "Cancelar" */
          background-color: gray; /* Color de fondo para el botón "Cancelar" */
          color: #fff; /* Color de texto para el botón "Cancelar" */
        }
 
    </style>
</head>
<body>
    <br><br>
    <div class="form-container">

      <h2> Departamento</h2>

       <form method="POST" action="actualizar_departamento.php">
         <input type="hidden" name="id" value="<?php echo $departamento['id_departamento']; ?>">

          <div class="form-column">
              <label for="nombre_empresa">Empresa:</label>
              <input type="text" name="nombre_empresa" id="nombre_empresa" value="<?php echo $departamento['nombre_empresa']; ?>" required>

              <label for="nombre_departamento">Departamento:</label>
              <input type="text" name="nombre_departamento" id="nombre_departamento" value="<?php echo $departamento['nombre_departamento']; ?>" required>
        
              <label for="estado_departamento">Estado:</label>
              <select name="estado_departamento" id="estado_departamento">
                  <option value="" <?php if (empty($departamento['estado_departamento'])) echo 'selected'; ?>>--Seleccione--</option>
                  <option value="A" <?php if ($departamento['estado_departamento'] === 'A') echo 'selected'; ?>>Activo</option>
                  <option value="I" <?php if ($departamento['estado_departamento'] === 'I') echo 'selected'; ?>>Inactivo</option>
                  <option value="B" <?php if ($departamento['estado_departamento'] === 'B') echo 'selected'; ?>>Bloqueado</option>
               </select><br>

            </div>

           <div class="form-column">
             <label for="creado">Creado por:</label>
              <input type="text" name="creado" id="creado" value="<?php echo $departamento['creado']; ?>" readonly>

              <label for="fecha_creacion">Fecha de Creación:</label>
              <input type="text" name="fecha_creacion" id="fecha_creacion" value="<?php echo date('d/m/Y', strtotime($departamento['fecha_creacion'])); ?>" readonly>

              <label for="modificado_por">Modificado por:</label>
               <input type="text" name="modificado_por" id="modificado_por" value="<?php echo $departamento['modificado_por']; ?>" required>

              <label for="fecha_modificacion">Fecha de Modificación:</label>
              <input type="text" name="fecha_modificacion" id="fecha_modificacion" value="<?php echo date('d/m/Y', strtotime($departamento['fecha_modificacion'])); ?>" readonly>
           </div>

           <div class="button-section">
                <button type="submit" class="custom-button save-button">Guardar</button>
                <a href="listar_departamentos.php" class="custom-button cancel-button">Cancelar</a>
            </div>
       </form>
    </div>
</body>
</html>