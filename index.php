<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>

      .contenedor-a-la-derecha {
         text-align: right;
    
        }

       .contenedor-a-la-izquierda {
         text-align: center;
         margin-left: 25%;
        }

        #footer {
          position: fixed;
          bottom: 0;
          width: 100%;
          background-color: rgba(248, 249, 250, 0.8);
          padding: 10px;
          box-sizing: border-box;
          z-index: 0; /* Cambia el z-index a 0 para que esté detrás de otros elementos */
        }

        .col-5 {
          position: relative;
          z-index: 1; /* Asegúrate de que el contenido en la columna izquierda esté por encima del pie de página */
        }
    </style>
</head>

<body style="overflow-x: hidden; overflow-y: hidden; margin: 0;">
    <div class="row">
        <div class="col-5" style="border-radius: 0 50% 50% 0; height: 100vh; background-color: #04346D; padding-top: 16%; padding-left: 10%; position: relative;">
          <img src="ihci.png" alt="" style="max-width: 400px; max-height: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
          <p style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; font-size: 200px; color: white; font-weight: bold;">IHCI</p>
        </div>

        <div class="col-7" style="padding-top: 1%;">

            <h1 style="  width: 100%; text-align: center; color:darkslategray">Gestión de Compras IHCI</h1>
               
            <form action="../GESTION_COMPRAS/login/login.php" method="post">
                <div id="login" >
                    <div class="container">
                        <div id="login-row" class="row justify-content-center align-items-center">
                            <div id="login-column" class="col-md-6">
                                <div id="login-box" class="col-md-12">
                                  <br>    
                                    <h4 class="text-center" style="color:  dimgray;">Iniciar Sesión</h4>
                                    
                                    <?php
                                        session_start();

                                        if (isset($_SESSION["loginError"])) {
                                            echo '<p style="color: red;">' . $_SESSION["loginError"] . '</p>';
                                            unset($_SESSION["loginError"]);
                                        }
                                    ?>
       
                                    
                                    <div class="form-group mb-3" style="width: 120%; padding-left: 10%;">
                                       <label for="nombre_usuario">Usuario:</label><br>
                                        <input type="text" name="nombre_usuario" id="nombre_usuario" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3" style="width: 120%; padding-left: 10%;">
                                        <label for="contraseña">Contraseña:</label><br>
                                        <input type="password" name="contraseña" id="contraseña" class="form-control" required>
                                        <input type="hidden" name="accion" value="acceso_user">
                                    </div>
                                            
                                    <div class="contenedor-a-la-derecha">
                                      <a href="./OlvideContraseña/olvide.php" style="color: gray;">He olvidado mi contraseña</a>
                                    </div>

                                    <div class="form-group">
                                        
                                        <center>
                                            <input style="background-color: #555CB3; width: 50%; margin-left: 25%; margin-top: 5%;" type="submit"class="btn btn-success" value="Ingresar">   
                                        </center>
                                                
                                    </div>

                                    <div class="contenedor-a-la-izquierda" >
                                     <a href="./Registro/registro_usuario.php" style="color: gray;">Registrese</a>
                                    </div>
                                             
                                        
                                    
                                </div>
                            </div>
                        </div>
                    </div> 
                   
                </div>
            </form>   
        </div>
        
    </div>
    <footer id="footer" class="text-center mt-5">
        <p>&copy; <?php echo date("Y"); ?> UNAH. Todos los derechos reservados.</p>
    </footer> 
</body>
</html>




