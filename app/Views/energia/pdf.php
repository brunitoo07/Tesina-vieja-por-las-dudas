<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura / Informe de Energía</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        line-height: 1.6;
        color: #333;
    }

    h1 { margin: 0; }
    h2 { margin: 0 0 10px 0; color: #1a1a1a; }

    .page {
        padding: 28px 32px;
    }

    .brandbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #D4AF37;
        padding-bottom: 12px;
        margin-bottom: 18px;
    }

    .brandbar .brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brandbar .brand .logo {
        width: 64px; height: 64px; border-radius: 8px; background: #F7E98E; display: inline-block;
    }

    .brandbar .brand .name {
        font-size: 20px;
        font-weight: bold;
        color: #1a1a1a;
    }

    .brandbar .meta {
        text-align: right;
        font-size: 12px;
        color: #2d2d2d;
    }

    .grid-two {
        display: table;
        width: 100%;
        margin-bottom: 16px;
    }
    .grid-two .col { display: table-cell; width: 50%; vertical-align: top; }
    .card {
        border: 1px solid #D4AF37;
        border-radius: 8px;
        padding: 12px;
        background: #ffffff;
    }
    .card h3 {
        font-size: 12px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #B8860B;
        margin: 0 0 8px 0;
    }
    .dl { margin: 0; font-size: 13px; color: #1a1a1a; }
    .dl .row { display: table; width: 100%; }
    .dl .row .dt { display: table-cell; width: 45%; color: #8B7355; padding: 4px 0; }
    .dl .row .dd { display: table-cell; width: 55%; font-weight: 600; padding: 4px 0; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 14px 0 6px 0;
        background: #fff;
        border: 1px solid #ddd;
    }

    th, td {
        padding: 8px 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background: #D4AF37;
        color: #1a1a1a;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .totals {
        display: table; width: 100%; margin-top: 10px;
    }
    .totals .spacer { display: table-cell; width: 60%; }
    .totals .box { display: table-cell; width: 40%; }
    .totals .box .line { display: table; width: 100%; font-size: 13px; }
    .totals .box .line .label { display: table-cell; text-align: left; padding: 6px 8px; color: #334155; }
    .totals .box .line .value { display: table-cell; text-align: right; padding: 6px 8px; font-weight: 600; }
    .totals .grand { background: #fff8e1; border: 1px solid #D4AF37; border-radius: 6px; }

    .informe-texto {
        margin-bottom: 20px;
        font-size: 13px;
        padding: 12px;
        background: #fffdf5;
        border-left: 3px solid #D4AF37;
        border-radius: 6px;
    }

    .note { font-size: 11px; color: #64748b; margin-top: 6px; }

    .footer {
        text-align: center;
        font-size: 12px;
        margin-top: 16px;
        color: #777;
        border-top: 1px solid #e2e8f0;
        padding-top: 8px;
    }
</style>


</head>
<body>
<div class="page">
    <div class="brandbar">
        <div class="brand">
            <?php
            $logoPath = FCPATH . 'imagenes/logo.png';
            $hasLogo = file_exists($logoPath) && extension_loaded('gd');
            ?>
            <?php if ($hasLogo): ?>
                <img class="logo" src="<?= base_url('imagenes/logo.png') ?>" alt="Logo" />
            <?php else: ?>
                <span class="logo"></span>
            <?php endif; ?>
            <div class="name">EcoVolt</div>
        </div>
        <div class="meta">
            <div><strong>Fecha:</strong> <?= date('d/m/Y') ?></div>
            <div><strong>N° Documento:</strong> <?= date('YmdHis') ?></div>
        </div>
    </div>

    <div class="grid-two">
        <div class="col" style="padding-right:8px;">
            <div class="card">
                <h3>Datos del cliente</h3>
                <div class="dl">
                    <div class="row"><div class="dt">Nombre</div><div class="dd"><?= $usuario['nombre'] ?></div></div>
                    <div class="row"><div class="dt">Email</div><div class="dd"><?= $usuario['email'] ?? '—' ?></div></div>
                    <div class="row"><div class="dt">Dispositivo</div><div class="dd"><?= $usuario['nombre_dispositivo'] ?></div></div>
                    <?php 
                        $calle = $usuario['calle'] ?? $usuario['direccion'] ?? null;
                        $ciudad = $usuario['ciudad'] ?? null;
                        $provincia = $usuario['provincia'] ?? null;
                        $cp = $usuario['cp'] ?? $usuario['codigo_postal'] ?? null;
                        $direccionFull = trim(implode(', ', array_filter([$calle, $ciudad, $provincia])));
                        if ($cp) { $direccionFull .= ($direccionFull ? ' ' : '') . 'CP ' . $cp; }
                    ?>
                    <?php if(!empty($calle) || !empty($ciudad) || !empty($provincia) || !empty($cp)): ?>
                        <div class="row"><div class="dt">Dirección</div><div class="dd"><?= $direccionFull ?></div></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col" style="padding-left:8px;">
            <div class="card">
                <h3>Detalles del período</h3>
                <div class="dl">
                    <div class="row"><div class="dt">Periodo</div><div class="dd"><?= date('01/m/Y') ?> - <?= date('t/m/Y') ?></div></div>
                    <div class="row"><div class="dt">Tarifa (kWh)</div><div class="dd">$<?= isset($precioKwh) ? number_format($precioKwh, 4) : '0.0000' ?></div></div>
                    <div class="row"><div class="dt">Moneda</div><div class="dd">ARS</div></div>
                </div>
            </div>
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

    <h2>Resumen mensual</h2>
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

    <?php
        $subtotal = $precio_factura;
        $impuestos = $subtotal * 0.21; // IVA 21%
        $total = $subtotal + $impuestos;
    ?>
    <div class="totals">
        <div class="spacer"></div>
        <div class="box">
            <div class="line"><div class="label">Subtotal</div><div class="value">$<?= number_format($subtotal, 2) ?></div></div>
            <div class="line"><div class="label">IVA (21%)</div><div class="value">$<?= number_format($impuestos, 2) ?></div></div>
            <div class="line grand"><div class="label">Total a pagar</div><div class="value">$<?= number_format($total, 2) ?></div></div>
            <div class="note">Tarifa aplicada: $<?= isset($precioKwh) ? number_format($precioKwh, 4) : '0.0000' ?> por kWh</div>
    </div>
    </div>


    <?php if(!empty($consumoDiario)): ?>
        <h2>Consumo diario del período</h2>
        <?php
            // Preparar mini gráfico de barras SVG (sparkbar)
            $dias = array_keys($consumoDiario);
            $vals = array_values($consumoDiario);
            $max = max($vals);
            $w = 400; $h = 80; $pad = 6;
            $barW = max(2, floor(($w - 2*$pad) / max(1, count($vals))));
        ?>
        <div style="margin:8px 0;">
            <svg width="<?= $w ?>" height="<?= $h ?>" xmlns="http://www.w3.org/2000/svg">
                <rect x="0" y="0" width="<?= $w ?>" height="<?= $h ?>" fill="#ffffff" />
                <!-- Eje Y -->
                <line x1="<?= $pad ?>" y1="<?= $pad ?>" x2="<?= $pad ?>" y2="<?= $h-$pad ?>" stroke="#cccccc" stroke-width="1" />
                <!-- Eje X -->
                <line x1="<?= $pad ?>" y1="<?= $h-$pad ?>" x2="<?= $w-$pad ?>" y2="<?= $h-$pad ?>" stroke="#cccccc" stroke-width="1" />
                <?php $i=0; foreach ($vals as $v): 
                    $bh = $max > 0 ? intval(($v/$max)*($h - 2*$pad)) : 0;
                    $x = $pad + $i*$barW;
                    $y = $h - $pad - $bh;
                ?>
                <rect x="<?= $x ?>" y="<?= $y ?>" width="<?= max(1,$barW-2) ?>" height="<?= $bh ?>" fill="#D4AF37" />
                <?php $i++; endforeach; ?>
                <!-- Etiqueta máxima -->
                <text x="<?= $w-$pad ?>" y="<?= $pad+10 ?>" font-size="10" text-anchor="end" fill="#8B7355">Máx: <?= number_format($max, 2) ?> kWh</text>
            </svg>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Energía (kWh)</th>
                    <th>Costo ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($consumoDiario as $dia => $kwhDia): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($dia)) ?></td>
                    <td><?= number_format((float)$kwhDia, 3) ?></td>
                    <td>$<?= number_format(((float)$kwhDia) * (float)$precioKwh, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if(!empty($picoPotencia) && $picoPotencia['valor'] > 0): ?>
        <div class="grid-two" style="margin-top:12px;">
            <div class="col" style="padding-right:8px;">
                <div class="card">
                    <h3>Pico de potencia</h3>
                    <div class="dl">
                        <div class="row"><div class="dt">Potencia máxima</div><div class="dd"><?= number_format($picoPotencia['valor'], 2) ?> W</div></div>
                        <div class="row"><div class="dt">Fecha</div><div class="dd"><?= $picoPotencia['fecha'] ? date('d/m/Y H:i', strtotime($picoPotencia['fecha'])) : '—' ?></div></div>
                    </div>
                </div>
            </div>
            <div class="col" style="padding-left:8px;">
                <div class="card">
                    <h3>Consumo medio diario</h3>
                    <div class="dl">
                        <div class="row"><div class="dt">Promedio</div><div class="dd"><?= isset($promedioDiario) ? number_format((float)$promedioDiario, 3) : '0.000' ?> kWh/día</div></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($recomendaciones)): ?>
        <div class="card" style="margin-top:10px;">
            <h3>Recomendaciones</h3>
            <div class="dl">
                <?php foreach($recomendaciones as $rec): ?>
                    <div class="row"><div class="dt">•</div><div class="dd" style="font-weight:400;"><?= $rec ?></div></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($totalesMensuales)): ?>
        <h2 style="margin-top:12px;">Comparativo mensual</h2>
        <?php
            // Gráfico de líneas simple en SVG para totales mensuales
            $labels = array_map(fn($m) => $m['label'], $totalesMensuales);
            $series = array_map(fn($m) => (float)$m['kwh'], $totalesMensuales);
            $maxM = max($series);
            $w2 = 500; $h2 = 120; $pad2 = 20;
            $n = count($series);
            $stepX = $n > 1 ? ($w2 - 2*$pad2) / ($n - 1) : 0;
            // Generar puntos
            $points = [];
            for ($i=0; $i<$n; $i++) {
                $x = $pad2 + $i * $stepX;
                $y = $h2 - $pad2 - ($maxM > 0 ? ($series[$i]/$maxM) * ($h2 - 2*$pad2) : 0);
                $points[] = $x . ',' . $y;
            }
        ?>
        <div style="margin:8px 0;">
            <svg width="<?= $w2 ?>" height="<?= $h2 ?>" xmlns="http://www.w3.org/2000/svg">
                <rect x="0" y="0" width="<?= $w2 ?>" height="<?= $h2 ?>" fill="#ffffff" />
                <!-- Ejes -->
                <line x1="<?= $pad2 ?>" y1="<?= $pad2 ?>" x2="<?= $pad2 ?>" y2="<?= $h2-$pad2 ?>" stroke="#cccccc" stroke-width="1" />
                <line x1="<?= $pad2 ?>" y1="<?= $h2-$pad2 ?>" x2="<?= $w2-$pad2 ?>" y2="<?= $h2-$pad2 ?>" stroke="#cccccc" stroke-width="1" />
                <!-- Área suave -->
                <?php 
                    $areaPoints = $points;
                    $areaPoints[] = ($w2-$pad2).','.($h2-$pad2);
                    $areaPoints[] = $pad2.','.($h2-$pad2);
                ?>
                <polygon points="<?= implode(' ', $areaPoints) ?>" fill="rgba(212,175,55,0.15)" stroke="none" />
                <!-- Línea -->
                <polyline points="<?= implode(' ', $points) ?>" fill="none" stroke="#B8860B" stroke-width="2" />
                <?php for ($i=0; $i<$n; $i++): 
                    $x = $pad2 + $i * $stepX;
                    $y = $h2 - $pad2 - ($maxM > 0 ? ($series[$i]/$maxM) * ($h2 - 2*$pad2) : 0);
                ?>
                    <circle cx="<?= $x ?>" cy="<?= $y ?>" r="3" fill="#D4AF37" />
                <?php endfor; ?>
                <!-- Etiquetas extremos -->
                <text x="<?= $pad2 ?>" y="<?= $pad2-6 ?>" font-size="10" fill="#8B7355">kWh</text>
                <text x="<?= $w2-$pad2 ?>" y="<?= $h2-6 ?>" font-size="10" text-anchor="end" fill="#8B7355">Máx: <?= number_format($maxM,2) ?> kWh</text>
            </svg>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>kWh</th>
                    <th>Variación</th>
                    <th>Costo ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($totalesMensuales as $m): ?>
                <?php 
                    $costoMes = ((float)$m['kwh']) * ((float)$precioKwh);
                    $varTxt = is_null($m['variacion']) ? '—' : (number_format($m['variacion'], 1) . '%');
                ?>
                <tr>
                    <td><?= $m['label'] ?></td>
                    <td><?= number_format((float)$m['kwh'], 3) ?></td>
                    <td><?= $varTxt ?></td>
                    <td>$<?= number_format($costoMes, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        Este documento es informativo. Informe generado el <?= date('d/m/Y H:i') ?> · EcoVolt
    </div>
    </div>
</body>
</html>
