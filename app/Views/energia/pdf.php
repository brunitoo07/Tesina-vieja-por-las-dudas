<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe de Energía</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        line-height: 1.6;
        color: #333;
    }

    h1, h2 {
        text-align: center;
        margin-bottom: 15px;
        color: #222;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
        padding: 20px;
        background: #FFD700;
        border-radius: 10px;
        color: #111;
    }

    .header h1 {
        margin-bottom: 15px;
        font-size: 24px;
    }

    .user-info p {
        margin: 5px 0;
        font-size: 14px;
        font-weight: bold;
        color: #222;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: #fff;
        border: 1px solid #ddd;
    }

    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background: #333;
        color: #FFD700;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .resumen td {
        font-weight: bold;
        background: #fff7d1;
    }

    .informe-texto {
        margin-bottom: 20px;
        font-size: 14px;
        padding: 15px;
        background: #ffffff;
        border-left: 4px solid #FFD700;
        border-radius: 5px;
    }

    .precio {
        font-weight: bold;
        font-size: 18px;
        text-align: center;
        margin-top: 20px;
        color: #111;
        background: #FFD700;
        padding: 10px;
        border-radius: 5px;
        display: inline-block;
    }

    .tarifa {
        text-align: center;
        font-size: 12px;
        color: #555;
        margin-top: 5px;
    }

    .footer {
        text-align: center;
        font-size: 12px;
        margin-top: 20px;
        color: #777;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
</style>


</head>
<body>
<div class="header">
    <h1>⚡ EcoVolt - Informe de Consumo de Energía</h1>
    <div class="user-info">
        <p><strong>Usuario:</strong> <?= $usuario['nombre'] ?></p>
        <p><strong>Dispositivo:</strong> <?= $usuario['nombre_dispositivo'] ?></p>
    </div>
</div>


    <?php
    // La vista recibe: $usuario, $lecturas, $promedios, $total_kwh, $precioTotal, $informeTexto
    $resumen = [
        'voltaje' => isset($promedios['voltaje']) ? (float)$promedios['voltaje'] : 0,
        'corriente' => isset($promedios['corriente']) ? (float)$promedios['corriente'] : 0,
        'potencia' => isset($promedios['potencia']) ? (float)$promedios['potencia'] : 0,
        'kwh' => isset($total_kwh) ? (float)$total_kwh : 0,
    ];

    // Precio aproximado de factura (calculado por el controlador como $precioTotal)
    $precio_factura = isset($precioTotal) ? (float)$precioTotal : 0;

    // Limitar lecturas (el controlador ya envía $lecturas del mes; mostramos hasta 50 por prolijidad)
    $lecturasLimitadas = isset($lecturas) ? array_slice($lecturas, 0, 50) : [];
    ?>

    <div class="informe-texto">
        <?php if(!empty($lecturasLimitadas)): ?>
            <?= $informeTexto ?>
        <?php else: ?>
            Durante el período analizado, no se registraron lecturas para el dispositivo <strong><?= $usuario['nombre_dispositivo'] ?></strong>.
        <?php endif; ?>
    </div>

    <h2>Resumen Mensual</h2>
    <table class="resumen">
        <tr>
            <td>Voltaje promedio (V)</td>
            <td><?= number_format($resumen['voltaje'], 2) ?></td>
        </tr>
        <tr>
            <td>Corriente promedio (A)</td>
            <td><?= number_format($resumen['corriente'], 2) ?></td>
        </tr>
        <tr>
            <td>Potencia promedio (W)</td>
            <td><?= number_format($resumen['potencia'], 2) ?></td>
        </tr>
        <tr>
            <td>Energía total (kWh)</td>
            <td><?= number_format($resumen['kwh'], 2) ?></td>
        </tr>
    </table>

    <div class="precio">
        Precio aproximado de la factura: $<?= number_format($precio_factura, 2) ?>
    </div>
    <div class="tarifa">
        Tarifa aplicada: $<?= isset($precioKwh) ? number_format($precioKwh, 4) : '0.0000' ?> por kWh
    </div>


    <?php if(!empty($lecturasLimitadas)): ?>
        <h2>Historial de Lecturas (Últimos 50 registros)</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Voltaje (V)</th>
                    <th>Corriente (A)</th>
                    <th>Potencia (W)</th>
                    <th>Energía (kWh)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($lecturasLimitadas as $lectura): ?>
                <tr>
                    <td><?= date('d/m/Y H:i:s', strtotime($lectura['fecha'])) ?></td>
                    <td><?= number_format($lectura['voltaje'], 2) ?></td>
                    <td><?= number_format($lectura['corriente'], 2) ?></td>
                    <td><?= number_format($lectura['potencia'], 2) ?></td>
                    <td><?= number_format($lectura['kwh'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        Informe generado el <?= date('d/m/Y H:i') ?>
    </div>
</body>
</html>
