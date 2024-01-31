<?php
include 'db_connect.php'; // Asegúrate de que este archivo incluye la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empresa = $_POST['id_empresa'];
    $nombre_departamento = $_POST['nombre_departamento'];
    $fecha_creacion = date('Y-m-d'); // Genera la fecha de creación en el formato "año-mes-día"
    $creado = $_POST['creado']; // Nombre de la persona que creó el departamento

    $estado = strtoupper($_POST['estado_departamento']);


    
    // Inserta el nuevo departamento en la base de datos
    $stmt = $conn->prepare("INSERT INTO tbl_departamentos (id_empresa, nombre_departamento, fecha_creacion, creado, estado_departamento) VALUES (:id_empresa, :nombre_departamento, :fecha_creacion, :creado, :estado_departamento)");
    $stmt->bindParam(':id_empresa', $id_empresa);
    $stmt->bindParam(':nombre_departamento', $nombre_departamento);
    $stmt->bindParam(':fecha_creacion', $fecha_creacion);
    $stmt->bindParam(':creado', $creado);
    $stmt->bindParam(':estado_departamento', $estado);

    if ($stmt->execute()) {
        header('Location: listar_departamentos.php');
    } else {
        echo 'Error al guardar el departamento.';
        echo 'Error: ' . print_r($stmt->errorInfo(), true); // Muestra información detallada del error
    }
}

// Obtener la lista de empresas para mostrar en el formulario
$stmt_empresas = $conn->query("SELECT id_empresa, nombre_empresa FROM tbl_empresa");
$empresas = $stmt_empresas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Crear Departamento</title>
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

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Fondo semi-transparente */
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px; /* Agrega margen inferior al formulario */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input {
            width: 95%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        select{
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

       
       .button-container {
         text-align: center;
        }

        .custom-button {
          display: inline-block;
          width: 150px; /* Ajusta el ancho de los botones según tus preferencias */
          padding: 10px 0; /* Ajusta el espaciado vertical si es necesario */
          margin: 0 10px; /* Ajusta el espaciado horizontal entre los botones si es necesario */
          border: none;
          border-radius: 3px;
          font-weight: bold;
          cursor: pointer;
          text-align: center;
        }

       .custom-button.cancel-button {
          background-color: gray; /* Cambia el color de fondo para el botón "Cancelar" */
          color: #fff; /* Cambia el color del texto para el botón "Cancelar" */
          text-decoration: none; /* Quitar el subrayado */
        }

        .custom-button {
          background-color: blue; /* Cambia el color de fondo para el botón "Guardar" */
         color: #fff; /* Cambia el color del texto para el botón "Guardar" */
        }

    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="crear_departamento.php">
           <h2 style="text-align: center;">Departamento</h2>
           <label for="id_empresa">Empresa:</label>
            <select name="id_empresa" id="id_empresa" required>
               <option value="">--Seleccione--</option>
               <?php foreach ($empresas as $empresa) { ?>
                  <option value="<?php echo $empresa['id_empresa']; ?>"><?php echo $empresa['nombre_empresa']; ?></option>
                <?php } ?>
            </select>

            <label for="nombre_departamento">Departamento:</label>
            <input type="text" name="nombre_departamento" id="nombre_departamento" required>

            <label for="estado_departamento">Estado:</label>
            <select name="estado_departamento" id="estado_departamento" required>
             <option value="">--Seleccione--</option>
             <option value="A">Activo</option>
             <option value="I">Inactivo</option>
             <option value="B">Bloqueado</option>
            </select>

            <label for="fecha_creacion">Fecha de Creación:</label>
            <input type="text" name="fecha_creacion" id="fecha_creacion" value="<?php echo date('Y-m-d'); ?>" readonly>

            <label for="creado">Creado por:</label>
            <input type="text" name="creado" id="creado" required>

            <br><br>
            <div class="button-container">
              <button type="submit" class="custom-button">Guardar</button>
              <a href="listar_departamentos.php" class="custom-button cancel-button">Cancelar</a>
            </div>
       </form>
    </div>
</body>
</html>




