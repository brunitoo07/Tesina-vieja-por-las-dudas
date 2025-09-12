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
    // Obtenemos el resumen mensual por dispositivo
    $resumen = $energiaModel->obtenerResumenMensualPorDispositivo($idDispositivo);

    // Calculamos precio aproximado
    $precio_factura = isset($usuario['precio_kwh']) ? $resumen['kwh'] * $usuario['precio_kwh'] : 0;

    // Limite de lecturas para mostrar (últimos 50 registros)
    $lecturasLimitadas = $energiaModel->obtenerUltimosDatos($idDispositivo, 50);
    ?>

    <div class="informe-texto">
        <?php if(!empty($lecturasLimitadas)): ?>
            Durante el período analizado, el dispositivo <strong><?= $usuario['nombre_dispositivo'] ?></strong> registró un consumo energético con las siguientes características:
            <ul>
                <li>Voltaje promedio: <?= number_format($resumen['voltaje'], 2) ?> V</li>
                <li>Corriente promedio: <?= number_format($resumen['corriente'], 2) ?> A</li>
                <li>Potencia promedio: <?= number_format($resumen['potencia'], 2) ?> W</li>
                <li>Energía total consumida: <?= number_format($resumen['kwh'], 2) ?> kWh</li>
            </ul>
            Estos datos representan un resumen profesional del comportamiento del dispositivo.
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
