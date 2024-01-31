<!DOCTYPE html>
<html>
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
    

    
</head>
    <title>Gestión de Roles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
</head>
<body>
<div class="sidebar">
        <div class="sidebar-header">
            <element class="logo">
                <img src="../imagen/ihci.jfif">
            </element>
            <button class="menu-toggle" onclick="toggleSidebar()">&#9776;
                <span class="menu-icon"></span>
            </button>
        </div>
    <ul class="menu">
    <li><a href="../pantallas/admin.php"><i class="fas fa-home"></i><span> Inicio </span></a></li>
            <li><a href="../cotizaciones/solicitudes.php"><i class="fas fa-envelope"></i><span> Solicitudes</span></a></li>
            <li><a href="./views/crear_solicitud.php"><i class="fas fa-plus"></i><span> Crear Solicitud</span></a></li>
            <li><a href="../cotizaciones/cotizaciones.php"><i class="fas fa-file-alt"></i><span> Cotizaciones</span></a></li>
            <li><a href="../proveedores/proveedores.php"><i class="fas fa-users"></i><span> Proveedores</span></a></li>
            <li><a href="../views/productos.php"><i class="fas fa-cubes"></i><span> Productos</span></a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i><span> Reportes</span></a></li>
            <li><a href="../setting/ajustes.php"><i class="fas fa-cog"></i><span> Settings</span></a></li>
            <li><a href="#"><i class="fas fa-bell"></i><span> Mis Notificaciones</span></a></li>
            <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i><span> Salir</span></a></li>
            <li><div><a href="#"><i class="fas fa-question-circle"></i><span> Help</span></a></div></li>
</ul>
    </div >
         <div class="content">
            <div class="content">
                <h3 class="text-center">Nueva solicitud de compra</h3>
                <form action="procesar_solicitud.php" method="POST">

                    <div class="form-group">
                        <label for="nombre">Nombre del Solicitante</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="departamento">Departamento o Área</label>
                        <input type="text" id="departamento" name="departamento" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción de la Solicitud</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha de la Solicitud</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Solicitud</label>
                        <select id="tipo" name="tipo" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <option value="1">Solicitud 1</option>
                            <option value="2">Solicitud 2</option>
                            <option value="3">Solicitud 3</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Crear Solicitud</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Incluir los scripts de Bootstrap>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script-->
</body>
</html>
