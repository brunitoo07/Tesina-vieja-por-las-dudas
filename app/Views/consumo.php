<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumo de Energía</title>
    <style>
        /* Fuentes e iconos */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: #2c3e50;
            font-weight: 700;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            padding: 25px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.15);
        }

        .realtime-data {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .realtime-card {
            flex: 1;
            min-width: 150px;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s ease;
        }

        .realtime-card:hover {
            transform: translateY(-5px);
        }

        .realtime-card p {
            font-size: 20px;
            margin: 10px 0;
            color: #2980b9;
        }

        #fecha {
            font-weight: bold;
            color: #e74c3c;
        }

        .sort-button {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            font-size: 16px;
            font-weight: 600;
        }

        .sort-button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #2980b9;
            color: white;
            padding: 15px;
            font-weight: 600;
        }

        td {
            background-color: #f9f9f9;
            padding: 12px;
        }

        tr:hover td {
            background-color: #e1e9f0;
        }
        .volver-btn {
           position: absolute;
            top: 20px;
            left: 20px;
            background: #007bff;
            color: white;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .volver-btn:hover {
            background: #0056b3;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #34495e;
            color: white;
            font-size: 14px;
            margin-top: 30px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Consumo de Energía en Tiempo Real</h1>
 <!-- hola enzito -->
    <!-- Mostrar los datos más recientes -->
    <div class="realtime-data">
        <div class="realtime-card">
            <p>Voltaje</p>
            <p id="voltaje"><?= $ultimoDato[0]['voltaje']; ?> V</p>
        </div>
        <div class="realtime-card">
            <p>Corriente</p>
            <p id="corriente"><?= $ultimoDato[0]['corriente']; ?> A</p>
        </div>
        <div class="realtime-card">
            <p>Potencia</p>
            <p id="potencia"><?= $ultimoDato[0]['potencia']; ?> W</p>
        </div>
        <div class="realtime-card">
            <p>Consumo de Energía</p>
            <p id="kwh"><?= $ultimoDato[0]['kwh']; ?> kWh</p>
        </div>
        <div class="realtime-card" id="fecha">
            <p>Fecha</p>
            <p><?= $fechaActual; ?></p>
        </div>
    </div>

    <!-- Mostrar advertencia si el consumo supera el límite -->
    <?php if (isset($advertencia)): ?>
        <div class="warning" style="color: red; font-weight: bold; margin-top: 20px;">
            <?= $advertencia ?>
        </div>
    <?php endif; ?>

    <!-- Botón de alternancia de orden -->
    <form method="get" action="">
        <button type="submit" class="sort-button" name="direction" value="<?= $direction === 'ASC' ? 'DESC' : 'ASC'; ?>">
            Ver <?= $direction === 'ASC' ? 'registros más recientes' : 'registros más antiguos'; ?>
        </button>
    </form>

    <!-- Historial de Consumo -->
    <h2>Historial de Consumo</h2>
    <table>
        <thead>
            <tr>
                <th>Voltaje (V)</th>
                <th>Corriente (A)</th>
                <th>Potencia (W)</th>
                <th>Consumo (kWh)</th>
                <th>Fecha y hora</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($energia)): ?>
                <?php foreach ($energia as $row): ?>
                    <tr>
                        <td><?= $row['voltaje']; ?></td>
                        <td><?= $row['corriente']; ?></td>
                        <td><?= $row['potencia']; ?></td>
                        <td><?= $row['kwh']; ?></td>
                        <td><?= $row['fecha']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay datos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

  <!-- Mostrar advertencia llamativa si se supera el límite -->
<?php if (isset($advertencia)): ?>
    <div style="background-color: #ff4d4d; color: white; padding: 20px; text-align: center; border-radius: 10px; font-size: 18px;">
        <?= $advertencia ?>
    </div>
<?php endif; ?> 

<!-- Formulario para actualizar límite de consumo -->
<h3>Establecer Límite de Consumo</h3>
<form method="POST" action="<?= base_url('energia/actualizarLimite'); ?>">
    <label for="nuevo_limite">Límite de Consumo (kWh):</label>
    <input type="number" name="nuevo_limite" step="0.1" value="<?= $limite_consumo ?>" required>
    <button type="submit" style="background-color: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Actualizar</button>
</form>

<a href="<?= base_url('home/bienvenida') ?>" class="volver-btn">Volver</a>
<footer>
    <p>© 2024 Sistema de Medición de Energía. Todos los derechos reservados.</p>
</footer>

<!-- Agregar código JavaScript para AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function getLatestData() {
        $.ajax({
            url: "<?= site_url('energia/getLatestData'); ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {
                if (data && data.voltaje) {
                    $('#voltaje').text(data.voltaje + ' V');
                    $('#corriente').text(data.corriente + ' A');
                    $('#potencia').text(data.potencia + ' W');
                    $('#kwh').text(data.kwh + ' kWh');
                    $('#fecha').text(data.fecha);
                }
            }
        });
    }
    setInterval(getLatestData, 5000);
</script>

</body>
</html>

