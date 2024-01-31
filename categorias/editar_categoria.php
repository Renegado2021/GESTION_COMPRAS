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

       

        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px; /* Agrega margen inferior al formulario */
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .custom-button {
          display: inline-block;
          padding: 10px 20px; /* Ajusta el tamaño del botón según tus preferencias */
          border: none;
          border-radius: 3px;
          font-weight: bold;
         cursor: pointer;
        }


        .custom-button.cancel-button {
          background-color: gray; /* Cambia el color de fondo para el botón "Cancelar" */
          color: #fff; /* Cambia el color del texto para el botón "Cancelar" */
          text-decoration: none; /* Elimina el subrayado del enlace */
        }

       .custom-button {
         background-color: blue; /* Cambia el color de fondo para el botón "Guardar" */
         color: #fff; /* Cambia el color del texto para el botón "Guardar" */
        }
    </style>
</head>
<body>

  <div class="container">
       <?php
         session_start(); 
         include 'db_connect.php';

         if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
              $id = $_GET['id'];

              $stmt = $conn->prepare("SELECT * FROM tbl_categorias WHERE id = :id");
              $stmt->bindParam(':id', $id);
              $stmt->execute();
              $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
              echo 'ID de categoría no proporcionado.';
            }
        ?>
      <br><br>
      <form method="POST" action="actualizar_categoria.php">
          <h2 style="text-align: center;">Categoría</h2><br>

           <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>"> <!-- Corregir aquí -->

          <label for="categoria">Categoría:</label>
          <input type="text" name="categoria" id="categoria" value="<?php echo $categoria['categoria']; ?>" required>

          <label for="creado">Creado por:</label>
          <input type="text" name="creado" id="creado" value="<?php echo $categoria['creado']; ?>" readonly>

          <label for="fecha_creacion">Fecha de Creación:</label>
          <input type="text" name="fecha_creacion" id="fecha_creacion" value="<?php echo date("d/m/Y", strtotime($categoria['fecha_creacion'])); ?>" readonly>

          <label for="modificado">Modificado Por:</label>
          <input type="text" name="modificado" id="modificado" value="<?php echo $_SESSION['nombre_usuario']; ?>" required>

           <!-- Puedes agregar más campos de edición aquí, si es necesario. -->
          <br><br>
          <button type="submit" class="custom-button">Guardar</button>
            <a href="listar_categorias.php" class="custom-button cancel-button">Cancelar</a>
       </form>

    </div>
</body>
</html>