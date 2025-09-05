<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Consumo</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; color: #333; margin: 0; padding: 0; }
        .container { max-width: 640px; margin: 0 auto; padding: 24px; }
        .card { background: #ffffff; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); overflow: hidden; }
        .header { background: linear-gradient(135deg, #22c55e, #16a34a); color: #ffffff; padding: 20px 24px; display:flex; align-items:center; gap:12px; }
        .header h1 { margin: 0; font-size: 20px; }
        .logo { width: 32px; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; }
        .content { padding: 24px; }
        .pill { display: inline-block; padding: 6px 12px; border-radius: 999px; background: #fef3c7; color: #92400e; font-size: 12px; font-weight: 600; }
        .metrics { margin: 18px 0; padding: 16px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; }
        .metric { margin: 6px 0; font-size: 14px; }
        .metric strong { color: #111827; }
        .cta { margin-top: 22px; }
        .btn { display: inline-block; padding: 12px 18px; background: #16a34a; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; }
        .footer { text-align: center; color: #6b7280; font-size: 12px; padding: 16px 0 8px; }
        .small { font-size: 12px; color: #6b7280; }
    </style>
    <!--[if mso]>
    <style type="text/css">
      .btn { padding: 12px 18px !important; }
    </style>
    <![endif]-->
    <!--[if !mso]><!-->
    <!-- Fuente segura por si el cliente no soporta webfonts -->
    <!--<![endif]-->
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <span class="logo">
                    <img src="<?= esc(base_url('imagenes/rayito.png')) ?>" alt="EcoVolt" style="width:22px;height:22px;display:block;" />
                </span>
                <h1>Alerta de consumo superado</h1>
            </div>
            <div class="content">
                <span class="pill">Umbral alcanzado</span>
                <p style="margin-top:14px;">Hola <?= esc($nombre ?? 'usuario') ?>, detectamos que el consumo configurado fue alcanzado/superado.</p>

                <div class="metrics">
                    <div class="metric"><strong>Consumo actual:</strong> <?= esc(number_format((float)($consumoActual ?? 0), 4)) ?> kWh</div>
                    <div class="metric"><strong>Límite configurado:</strong> <?= esc(number_format((float)($limiteConfigurado ?? 0), 4)) ?> kWh</div>
                    <?php if (!empty($dispositivoNombre)): ?>
                        <div class="metric"><strong>Dispositivo:</strong> <?= esc($dispositivoNombre) ?></div>
                    <?php elseif (!empty($idDispositivo)): ?>
                        <div class="metric"><strong>ID Dispositivo:</strong> <?= esc($idDispositivo) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($momento)): ?>
                        <div class="metric small"><strong>Momento:</strong> <?= esc($momento) ?></div>
                    <?php endif; ?>
                </div>

                <p>Te recomendamos revisar el panel para identificar posibles consumos inusuales y tomar medidas para optimizar tu energía.</p>

                <?php if (!empty($urlPanel)): ?>
                <div class="cta">
                    <a class="btn" href="<?= esc($urlPanel) ?>" target="_blank" rel="noopener">Abrir panel</a>
                </div>
                <?php endif; ?>

                <p class="small" style="margin-top:18px;">Si no configuraste este límite o crees que se trata de un error, por favor actualiza tus preferencias desde el panel.</p>
            </div>
            <div class="footer">
                <p>Este es un correo automático. © <?= date('Y') ?> EcoVolt.</p>
            </div>
        </div>
    </div>
</body>
</html>


