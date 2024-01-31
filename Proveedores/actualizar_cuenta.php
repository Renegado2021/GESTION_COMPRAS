<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['ID_CUENTA_PROVEEDOR'];
    $numero_cuenta = $_POST['NUMERO_CUENTA'];
    $banco = $_POST['BANCO'];
    $descripcion_cuenta = $_POST['DESCRIPCION_CUENTA'];
    $saldo = $_POST['SALDO'];

    // Generar la fecha de modificación automáticamente
    $fechaModificacion = date("Y-m-d"); // Formato "año-mes-día"

    // No necesitas actualizar el ID_PROVEEDOR, ya que deseas mantenerlo igual.
    // El ID_PROVEEDOR seguirá siendo el mismo que el original.

    $query = "UPDATE tbl_cuenta_proveedor SET 
              NUMERO_CUENTA = ?, 
              BANCO = ?, 
              DESCRIPCION_CUENTA = ?, 
              SALDO = ?, 
              FECHA_MODIFICACION = ?
              WHERE ID_CUENTA_PROVEEDOR = ?";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssdsis", $numero_cuenta, $banco, $descripcion_cuenta, $saldo, $fechaModificacion, $id);

    if ($stmt->execute()) {
        header('Location: listar_cuentas.php');
        exit;
    } else {
        echo "Error al actualizar la cuenta: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT cp.*, p.NOMBRE AS NOMBRE_PROVEEDOR FROM tbl_cuenta_proveedor cp
              INNER JOIN tbl_proveedores p ON cp.ID_PROVEEDOR = p.ID_PROVEEDOR
              WHERE cp.ID_CUENTA_PROVEEDOR = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Definir $fechaCreacion y formatear la fecha
        $fechaCreacion = date("d/m/Y", strtotime($row['FECHA_CREACION']));

        // Definir la variable $fechaModificacion fuera del bloque if
        $fechaModificacion = date("d/m/Y", strtotime($row['FECHA_MODIFICACION']));
    } else {
        echo "Cuenta no encontrada.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID no proporcionado en la URL o no es válido.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cuentas Bancarias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10); /* Cambia el valor de "0.7" para ajustar la transparencia */
            background-image: url('../imagen/background.jpg'); /* Reemplaza con la ruta de tu imagen de fondo */
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
    background-color: powderblue; /* Color de fondo del cuadro */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra ligera */
    opacity: 0.9; /* Valor de opacidad (menos transparente) */
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
    width: 90%; /* Ancho del 100% para llenar la columna completa */
    padding: 10px; /* Espaciado dentro de los campos de entrada */
    margin-bottom: 10px; /* Espacio inferior entre campos de entrada */
}

/* Estilos para las etiquetas (label) en la columna */
.form-column label {
    display: block; /* Mostrar las etiquetas en una nueva línea */
    margin-bottom: 5px; /* Espacio inferior entre etiquetas */
}

select{
            width: 95%;
            padding: 10px;
            margin-top: 1px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

/* Estilos para la sección de botones */
.button-section {
    clear: both; /* Limpiar el flotado para que los elementos debajo no floten */
    text-align: right; /* Alinear los botones a la izquierda */
    margin-top: 20px; /* Espacio superior para separar de las columnas */
}

/* Estilos para los botones */
button, .custom-button {
    padding: 10px 20px; /* Espaciado dentro de los botones */
    background-color: #007bff; /* Color de fondo del botón principal */
    color: #fff; /* Color de texto para el botón principal */
    border: none; /* Sin borde */
    cursor: pointer;
}

.cancel-button {
    background-color: #dc3545; /* Color de fondo para el botón de cancelar */
}

/* Agregar estilos de botón cuando se pasa el mouse sobre ellos */
button:hover, .custom-button:hover {
    background-color: #0056b3;
}

/* estilos.css */
textarea[name="DESCRIPCION_CUENTA"] {
    width: 90%;
    height: 150px;
    padding: 10px;
    border: 1px solid #ccc;
    /* Otros estilos personalizados aquí */
}


    </style>
</head>
<body>
    <div class="form-container">
      <h2 style="text-align: center;">Actualizar Cuenta </h2>

    <form method="POST" action="actualizar_cuenta.php">
        <input type="hidden" name="ID_CUENTA_PROVEEDOR" value="<?php echo $row['ID_CUENTA_PROVEEDOR']; ?>">
        <div class="form-column">
          <label>Número de Cuenta:</label>
          <input type="text" name="NUMERO_CUENTA" value="<?php echo $row['NUMERO_CUENTA']; ?>"><br>
          <label>Banco:</label>
          <input type="text" name="BANCO" value="<?php echo $row['BANCO']; ?>"><br>
          <label>Descripción de Cuenta:</label>
          <textarea name="DESCRIPCION_CUENTA"><?php echo $row['DESCRIPCION_CUENTA']; ?></textarea><br>
        </div>
       
        <div class="form-column">
          <label>Saldo:</label>
          <input type="text" name="SALDO" value="<?php echo $row['SALDO']; ?>"><br>
          <label>Proveedor:</label>
          <input type="text" name="NOMBRE_PROVEEDOR" value="<?php echo $row['NOMBRE_PROVEEDOR']; ?>" readonly>
          <label>Fecha de Creación:</label>
          <input type="text" value="<?php echo $fechaCreacion; ?>" readonly>
          <label>Fecha de Modificación:</label>
          <input type="text" value="<?php echo $fechaModificacion; ?>" readonly>
        </div>
        
        <div class="button-section">
            <button type="submit">Guardar</button>
            <a href="listar_cuentas.php" class="custom-button cancel-button">Cancelar</a>
        </div>
        
    </form>
    </div>
</body>
</html>

