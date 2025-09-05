<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura - <?= esc($dispositivo['nombre']) ?></title>
    <style>
        body { font-family: Arial; font-size: 12px; }
        h2 { text-align:center; margin-bottom: 20px; }
        table { width:100%; border-collapse: collapse; margin-top:15px; }
        th, td { border:1px solid black; padding:6px; text-align:center; }
        .resumen { margin-top:20px; font-size:14px; }
    </style>
</head>
<body>
    <h2>Factura de Consumo Energético</h2>
    <p><b>Dispositivo:</b> <?= esc($dispositivo['nombre']) ?></p>
    <p><b>Fecha de Emisión:</b> <?= date('d/m/Y H:i') ?></p>

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
            <?php foreach ($lecturas as $l): ?>
                <tr>
                    <td><?= date('d/m/Y H:i:s', strtotime($l['fecha'])) ?></td>
                    <td><?= number_format($l['voltaje'], 2) ?></td>
                    <td><?= number_format($l['corriente'], 2) ?></td>
                    <td><?= number_format($l['potencia'], 2) ?></td>
                    <td><?= number_format($l['kwh'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="resumen">
        <p><b>Total Energía Consumida:</b> <?= number_format($totalKwh, 2) ?> kWh</p>
        <p><i>El costo final depende del valor de kWh ingresado por el usuario.</i></p>
    </div>
</body>
</html>
