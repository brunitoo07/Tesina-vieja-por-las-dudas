<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 24px 20px 16px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            padding: 24px 20px;
            background-color: #f9f9f9;
            border-radius: 0 0 10px 10px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 28px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 24px 0 12px 0;
            font-size: 1.1rem;
        }
        .pedido {
            background: #e8f5e9;
            border-radius: 8px;
            padding: 12px 18px;
            margin: 18px 0;
            font-size: 1.1rem;
        }
        .telefono {
            font-weight: bold;
            color: #2E7D32;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= lang('compra.bienvenida', [], 'es') ?: '¡Bienvenido/a a EcoVolt!' ?></h1>
        </div>
        <div class="content">
            <p><?= lang('compra.hola', ['nombre' => esc($nombre)], 'es') ?: 'Hola ' . esc($nombre) . ',' ?></p>
            <p><?= lang('compra.confirmacion', [], 'es') ?: '¡Tu compra ha sido procesada exitosamente y tu cuenta ya está activa!' ?></p>

            <div class="pedido">
                <strong><?= lang('compra.numero_pedido', [], 'es') ?: 'Número de pedido:' ?></strong> #<?= rand(100000,999999) ?>
            </div>

            <h3><?= lang('compra.detalles_producto', [], 'es') ?: 'Detalles del Producto:' ?></h3>
            <ul>
                <li><?= lang('compra.producto', [], 'es') ?: 'Producto:' ?> <?= esc($dispositivo['nombre']) ?></li>
                <li><?= lang('compra.precio', [], 'es') ?: 'Precio:' ?> $<?= $precio ?></li>
                <li><?= lang('compra.fecha', [], 'es') ?: 'Fecha de compra:' ?> <?= $fecha ?></li>
            </ul>

            <h3><?= lang('compra.direccion_envio', [], 'es') ?: 'Dirección de Envío:' ?></h3>
            <p><?= esc($direccion) ?></p>

            <p><?= lang('compra.proximos_pasos', [], 'es') ?: 'Cuando recibas tu producto, ingresa a tu cuenta y sigue el manual para asociar tu dispositivo.' ?></p>

            <a href="<?= base_url('autenticacion/login') ?>" class="button">
                <?= lang('compra.ir_login', [], 'es') ?: 'Ir a iniciar sesión' ?>
            </a>

            <p><?= lang('compra.consulta', [], 'es') ?: '¿Tienes alguna pregunta o necesitas ayuda? Contáctanos:' ?></p>
            <p class="telefono">+54 9 11 1234-5678</p>

            <p><?= lang('compra.agradecimiento', [], 'es') ?: '¡Gracias por confiar en EcoVolt!' ?></p>
        </div>
        <div class="footer">
            <p><?= lang('compra.automatico', [], 'es') ?: 'Este es un email automático, por favor no respondas a este mensaje.' ?></p>
            <p>&copy; <?= date('Y') ?> EcoVolt. <?= lang('compra.derechos', [], 'es') ?: 'Todos los derechos reservados.' ?></p>
        </div>
    </div>
</body>
</html> 