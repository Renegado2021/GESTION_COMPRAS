<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Compras</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 50vh;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        #formEstadisticas {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        label {
            margin-right: 10px;
        }

        select, button {
            padding: 10px;
            font-size: 16px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Generar estadísticas de Compras</h1>

<div id="formEstadisticas">
    <form action="mostrar_estadisticas.php" method="post">
        <label for="mes">Seleccionar mes:</label>
        <select name="mes" id="mes">
            <?php
            $meses = [
                '01' => 'Enero',
                '02' => 'Febrero',
                '03' => 'Marzo',
                '04' => 'Abril',
                '05' => 'Mayo',
                '06' => 'Junio',
                '07' => 'Julio',
                '08' => 'Agosto',
                '09' => 'Septiembre',
                '10' => 'Octubre',
                '11' => 'Noviembre',
                '12' => 'Diciembre',
            ];

            foreach ($meses as $numeroMes => $nombreMes) {
                echo "<option value=\"$numeroMes\">$nombreMes</option>";
            }
            ?>
        </select>

        <label for="anio">Seleccionar año:</label>
        <select name="anio" id="anio">
            <?php
            $anioActual = date('Y');
            for ($i = $anioActual; $i >= $anioActual - 10; $i--) {
                echo "<option value=\"$i\">$i</option>";
            }
            ?>
        </select>

        <button type="submit">Aceptar</button>
    </form>
</div>

</body>
</html>
