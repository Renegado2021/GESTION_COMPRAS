<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC IHCI</title>

    <!-- Carga de Bootstrap 5 y Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/860e3c70ee.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.11/dist/interact.min.js"></script>

    
    
    <style>
        /* Estilos para el contenedor de los cuadros */
        .content {
            display: flex;
            flex-wrap: wrap;
        }

        /* Estilos para el cuadro arrastrable */
        .draggable-box {
            width: 150px;
            height: 150px;
            margin: 10px;
            color: #fff;
            border: 1px solid #2980b9;
            border-radius: 5px;
            text-align: center;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Estilos para el cuadro azul */
        .blue {
            background-color: #3498db;
        }

        /* Estilos para el cuadro verde */
        .green {
            background-color: #4CAF50;
        }

        /* Estilos para el cuadro amarillo */
        .yellow {
            background-color: #FFD700;
            color: #000;
        }

          /* Estilos para el cuadro azul */
          .violet {
            background-color: violet;
            color: #000;
        }

         /* Estilos para el cuadro rosado */
         .pink {
            background-color: pink;
            color: #000;
        }

        /* Estilos para el candado */
        .lock-icon {
            font-size: 18px;
            cursor: pointer;
            position: absolute;
            top: 5px;
            right: 5px;
        }

        /* En tu hoja de estilo CSS externa o en una etiqueta <style> en el head */
       .black-link {
          color: black;
        }

    </style>
</head>
<body>
    <div class="content">
        <div class="draggable-box blue" id="box1">
          
            <i class="fas fa-user-shield"></i>
            <i class="fas fa-lock lock-icon" id="lock1" onclick="toggleLock('box1')"></i>
            <a href="../roles/roles.php" class="black-link">Roles y Permisos</a>
           
        </div>

        <div class="draggable-box green" id="box2">
            <i class="fas fa-user"></i>
            <i class="fas fa-lock lock-icon" id="lock2" onclick="toggleLock('box2')"></i>
            <a href="../usuario/usuario.php" class="black-link">Usuarios</a>
        </div>
        <div class="draggable-box yellow" id="box3">
            <i class="fas fa-cube"></i>
            <i class="fas fa-lock lock-icon" id="lock3" onclick="toggleLock('box3')"></i>
            <a href="../objetos/listar_objetos.php" class="black-link">Objetos</a>
        </div>
        <div class="draggable-box violet" id="box4">
            <i class="fas fa-question"></i>
            <i class="fas fa-lock lock-icon" id="lock4" onclick="toggleLock('box4')"></i>
            <a href="../preguntas/preguntas.php" class="black-link">Preguntas</a>
        </div>
        <div class="draggable-box pink" id="box5">
            <i class="fas fa-sliders-h"></i>
            <i class="fas fa-lock lock-icon" id="lock5" onclick="toggleLock('box5')"></i>
            <a href="../parametros/parametros.php" class="black-link">Paràmetros</a>
        </div>
    </div>

    <script>
// Función para alternar el estado del candado
function toggleLock(boxId) {
    var box = document.getElementById(boxId);
    var lockIcon = box.querySelector('.lock-icon');

    if (box.classList.contains('locked')) {
        // Deshabilita el movimiento del cuadro
        box.classList.remove('locked');
        lockIcon.style.color = '#000'; // Cambia el color del candado para indicar desbloqueado
    } else {
        // Habilita el movimiento del cuadro
        box.classList.add('locked');
        lockIcon.style.color = '#f00'; // Cambia el color del candado para indicar bloqueado
    }
}

interact('.draggable-box')
    .draggable({
        onmove: function (event) {
            var target = event.target;
            if (!target.classList.contains('locked')) {
                var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
                target.setAttribute('data-x', x);
                target.setAttribute('data-y', y);
            }
        }
    });


</script>


</body>
</html>






