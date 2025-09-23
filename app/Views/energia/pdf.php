<?php
$logoPath = FCPATH . 'imagenes/logo.png';
$logoData = base64_encode(file_get_contents($logoPath));
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe de Energía</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px;
        line-height: 1.6;
        background: #f8f9fa;
        color: #333;
    }

    h1, h2 {
        text-align: center;
        margin-bottom: 15px;
        color: #222;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* ---------- HEADER ---------- */
    /* ---------- HEADER ---------- */
.header {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px;
    background: linear-gradient(135deg, #FFD700, #FFB700);
    border-radius: 14px;
    color: #111;
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.logo-container {
    margin-bottom: 15px;
}

.logo-container img {
    height: 100px; /* podés ajustar el tamaño del logo */
    filter: drop-shadow(0px 3px 4px rgba(0,0,0,0.4));
}

.header h1 {
    margin-bottom: 20px;
    font-size: 28px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.user-info p {
    margin: 5px 0;
    font-size: 16px;
    font-weight: 500;
    color: #222;
}


    /* ---------- TABLAS ---------- */
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    th, td {
        padding: 12px;
        text-align: center;
    }

    th {
        background: #222;
        color: #FFD700;
        font-weight: bold;
        text-transform: uppercase;
    }

    tr:nth-child(even) {
        background-color: #f4f4f4;
    }

    tr:hover {
        background-color: #fff7d1;
    }

    .resumen td {
        font-weight: bold;
        background: #fdf5cc;
    }

    /* ---------- BLOQUES DE TEXTO ---------- */
    .informe-texto {
        margin-bottom: 20px;
        font-size: 15px;
        padding: 18px;
        background: #ffffff;
        border-left: 6px solid #FFD700;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }

    /* ---------- PRECIO Y TARIFA ---------- */
    .precio {
        font-weight: bold;
        font-size: 20px;
        text-align: center;
        margin-top: 20px;
        color: #111;
        background: #FFD700;
        padding: 12px;
        border-radius: 10px;
        display: inline-block;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    }

    .tarifa {
        text-align: center;
        font-size: 14px;
        color: #555;
        margin-top: 8px;
    }

    /* ---------- FOOTER ---------- */
    .footer {
        text-align: center;
        font-size: 13px;
        margin-top: 20px;
        color: #777;
        border-top: 1px solid #ddd;
        padding-top: 15px;
    }
</style>


</head>
<body>
<div class="header">
    <div class="logo-container" style="text-align:center; margin-bottom:15px;">
        <img src="data:image/png;base64,<?= $logoData ?>" 
             alt="Logo" 
             style="height:200px; display:block; margin:0 auto;">
    </div>
    <h1>Informe de Consumo de Energía</h1>
    <div class="user-info" style="text-align:center; margin-top:10px;">
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
