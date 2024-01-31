<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el nombre del proveedor y el ID están en la sesión
    if (isset($_SESSION['NOMBRE']) && isset($_SESSION['ID_PROVEEDOR'])) {
        $nombre = $_SESSION['NOMBRE'];
        $id_proveedor = $_SESSION['ID_PROVEEDOR'];
    } else {
        $nombre = "";
        $id_proveedor = 0; // Cambia esto según corresponda
    }

    $nombre = strtoupper($_POST['NOMBRE']); // Convertir a mayúsculas
    $cargo = $_POST['CARGO'];
    $estado = $_POST['ESTADO'];
     // Establecer el valor "ADMIN" para el campo que debe llenarse automáticamente
     $creado_por = "ADMIN";
    
    // Utiliza declaraciones preparadas para prevenir SQL injection
    $query = "INSERT INTO tbl_contactos_proveedores (NOMBRE, CARGO, ESTADO,  ID_PROVEEDOR, CREADO_POR) 
              VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssis", $nombre, $cargo, $estado,  $id_proveedor, $creado_por);

    if ($stmt->execute()) {
        header('Location: listar_proveedores.php');
        exit;
    } else {
        echo "Error al guardar la cuenta: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Agregar Contacto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.10);
            background-image: url('../imagen/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
        }

        .form-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: powderblue;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .form-column {
            float: left;
            width: 50%;
            box-sizing: border-box;
            padding: 10px;
        }

        .form-column input {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
        }

        .form-column label {
            display: block;
            margin-bottom: 5px;
        }

        select {
            width: 95%;
            padding: 10px;
            margin-top: 1px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .button-section {
            clear: both;
            text-align: right;
            margin-top: 20px;
        }

        button, .custom-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .cancel-button {
            background-color: gray; /* Color de fondo para el botón de cancelar */
            text-decoration: none; /* Quita el subrayado */
        }

        button:hover, .custom-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <br><br>
    <div class="form-container">
        <h2 style="text-align: center;"> Contacto de Proveedores</h2>
        <form method="POST" action="crear_contacto.php">
            <div class="form-column">
                <label>Nombre:</label>
                <input type="text" name="NOMBRE"><br>
                <label>Cargo:</label>
                <input type="text" name="CARGO"><br>
            </div>
            <div class="form-column">
                <label>Proveedor:</label>
                <input type="text" name="NOMBRE" value="<?php echo isset($_SESSION['NOMBRE']) ? $_SESSION['NOMBRE'] : ''; ?>" readonly>
                <label>Estado:</label>
                <select name="ESTADO" required>
                    <option value="">--Seleccione--</option>
                    <option value="A">Activo</option>
                    <option value="I">Inactivo</option>
                    <option value="B">Bloqueado</option>
                </select>
            </div>
            <div class="button-section">
                <button type="submit">Guardar</button>
                <a href="crear_cuenta.php" class="custom-button cancel-button">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>


