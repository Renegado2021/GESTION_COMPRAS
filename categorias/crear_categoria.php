<?php
 session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Categoría</title>
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
            max-width: 500px;
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
            width: 95%;
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
    <br><br>
    <form action="guardar_categoria.php" method="POST">
      <h2 style="text-align: center;">Categoría</h2>
        <label for="categoria">Categoría:</label>
        <input type="text" name="categoria" required>

        <label for="creado">Creado por:</label>
        <input type="text" name="creado" value="<?php echo $_SESSION['nombre_usuario']; ?>">
        
        <label for="fecha_creacion">Fecha de Creación:</label>
        <input type="text" name="fecha_creacion" value="<?php echo date("Y-m-d"); ?>" readonly>


        <br><br>
        <button type="submit" class="custom-button">Guardar</button>
        <a href="listar_categorias.php" class="custom-button cancel-button">Cancelar</a>
    </form>
   
</div>
</body>
</html>
