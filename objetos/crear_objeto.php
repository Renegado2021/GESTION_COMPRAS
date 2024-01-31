<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreObjeto = $_POST['nombre_objeto'];
    $descripcion = $_POST['descripcion'];

    // Insertar datos en la base de datos
    $stmt = $conn->prepare("INSERT INTO tbl_objetos (NOMBRE_OBJETO, DESCRIPCION) VALUES (:nombreObjeto, :descripcion)");
    $stmt->bindParam(':nombreObjeto', $nombreObjeto);
    $stmt->bindParam(':descripcion', $descripcion);

    if ($stmt->execute()) {
        header('Location: listar_objetos.php'); // Redirige a la lista de objetos después de la creación
        exit;
    } else {
        echo 'Error al crear el objeto.';
    }
}
?>

<!-- Formulario HTML para crear un objeto -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">

    <title>Crear Objeto</title>
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

        /* Estilo para el campo de entrada de texto */
  textarea {
    width: 100%;
    height: 150px;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px;
    font-size: 14px;
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
    padding: 15px 10px; /* Ajusta el tamaño del botón según tus preferencias */
    border: none;
    border-radius: 3px;
    font-weight: bold;
    cursor: pointer;
}


.custom-button.cancel-button {
    background-color: #ff0000; /* Cambia el color de fondo para el botón "Cancelar" */
    color: #fff; /* Cambia el color del texto para el botón "Cancelar" */
}

.custom-button {
    background-color: blue; /* Cambia el color de fondo para el botón "Guardar" */
    color: #fff; /* Cambia el color del texto para el botón "Guardar" */
}

.custom-button:hover {
    background-color: #555; /* Cambio de color de fondo al pasar el cursor sobre ambos botones */
}


      
    </style>
</head>
<body>
    <div class="container">
    <br><br>
    <form action="crear_objeto.php" method="POST">
        <h2 style="text-align: center;">Crear Objetos</h2>
        <label for="nombre_objeto">Nombre del Objeto:</label>
        <input type="text" name="nombre_objeto" required>
        <br>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion"></textarea>
        <br>
        
        <button type="submit" class="custom-button">Guardar</button>
         <!-- Botón de Cancelar que redirige a listar_objetos.php -->
         <a href="listar_objetos.php" class="custom-button cancel-button">Cancelar</a>
    </form>
    </div>
</body>
</html>
