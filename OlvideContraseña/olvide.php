<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Contraseña</title>
    <style>
        /* Estilos para el contenedor principal */

        body {
    font-family: Arial, sans-serif;
    background-image: url('../imagen/imagen1.jpg'); /* Reemplaza 'ruta_de_la_imagen.jpg' con la ruta de tu imagen de fondo */
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

        #recoveryContainer {
            max-width: 500px; /* Ajusta el ancho máximo del contenedor */
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: center;
        }

        /* Estilos para el encabezado */
        #recoveryContainer h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Estilos para las etiquetas y radio buttons */
        label {
            display: inline;
            width: 150px;
            text-align: right;
            margin-right: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="radio"] {
            margin-bottom: 10px;
        }

        /* Estilos para el campo de usuario */
        #usuario {
            width: 90%; /* Ajusta el ancho del campo de usuario al 100% */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Estilos para los radio buttons */
        .radio-group {
            text-align: center; /* Alinea los radio buttons a la izquierda */
        }

        .radio-group label {
            width: auto; /* Ancho automático para las etiquetas de los radio buttons */
            margin-right: 20px; /* Espaciado entre los radio buttons */
        }

        /* Estilos para la etiqueta "Método de Recuperación" centrada */
        .method-label {
            display: inline;
            text-align: center;
            margin-right: 10px;
            font-weight: bold;
        }

        /* Estilos para el botón */
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Estilos para el botón de Cancelar */
input[type="button"] {
    background-color: #e74c3c; /* Cambia el color de fondo según tus preferencias */
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="button"]:hover {
    background-color: #c0392b; /* Cambia el color de fondo al pasar el mouse según tus preferencias */
}

    </style>
</head>
<body>
<div id="recoveryContainer">
    <h2>Recuperar Contraseña</h2>
    <form method="POST" action="email.php" >
    
    <label for="nombre_usuario">Nombre de Usuario:</label>
    <input type="text" name="nombre_usuario" id="nombre_usuario" required><br><br>
    
    <!-- Etiqueta "Método de Recuperación" centrada -->
    <label class="method-label">Método de Recuperación:</label><br><br>

    <div class="radio-group">
    <input type="radio" name="metodo" value="preguntas" id="metodo_preguntas" required>
    <label for="metodo_preguntas">Vìa Pregunta</label>

    <input type="radio" name="metodo" value="email" id="metodo_email" required>
    <label for="metodo_email">Correo Electrónico</label><br><br>
    </div>

    <input type="submit" name="iniciar" value="Iniciar">
    <input type="button" value="Cancelar" onclick="window.location.href='../index.php';">

    </form>
</div>

</body>
</html>
