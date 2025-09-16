<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe de Energía</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.4; color: #333; }
        h1, h2 { text-align: center; margin-bottom: 15px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header img { height: 50px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f7f7f7; }
        .resumen td { padding: 6px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; margin-top: 40px; color: #777; }
        .informe-texto { margin-bottom: 20px; font-size: 14px; }
        .precio { font-weight: bold; font-size: 16px; text-align: center; margin-top: 10px; }
        .tarifa { text-align: center; font-size: 13px; color: #555; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?= base_url('logo.png') ?>" alt="Logo">
        <h1>Informe de Consumo de Energía</h1>
        <p><strong>Usuario:</strong> <?= $usuario['nombre'] ?></p>
        <p><strong>Dispositivo:</strong> <?= $usuario['nombre_dispositivo'] ?></p>
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

    <?php if (isset($resumenDiario) && !empty($resumenDiario)): ?>
        <h2>Resumen Diario (Mes actual)</h2>
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>kWh del día</th>
                    <th>Voltaje prom. (V)</th>
                    <th>Corriente prom. (A)</th>
                    <th>Potencia prom. (W)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumenDiario as $r): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($r['dia'])) ?></td>
                        <td><?= number_format($r['kwh_dia'], 3) ?></td>
                        <td><?= number_format($r['voltaje_prom'], 2) ?></td>
                        <td><?= number_format($r['corriente_prom'], 2) ?></td>
                        <td><?= number_format($r['potencia_prom'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

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
