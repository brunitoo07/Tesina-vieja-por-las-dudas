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
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Gracias por tu compra!</h1>
        </div>
        
        <div class="content">
            <p>Hola <?= esc($nombre) ?>,</p>
            
            <p>Tu compra ha sido procesada exitosamente. Aquí están los detalles de tu pedido:</p>
            
            <h3>Detalles del Producto:</h3>
            <ul>
                <li>Producto: <?= esc($dispositivo['nombre']) ?></li>
                <li>Precio: $<?= $precio ?></li>
                <li>Fecha de compra: <?= $fecha ?></li>
            </ul>

            <h3>Dirección de Envío:</h3>
            <p><?= esc($direccion) ?></p>

            <p>Tu dispositivo será enviado en los próximos días hábiles. Recibirás un email con el número de seguimiento cuando sea enviado.</p>

            <p>Si tienes alguna pregunta sobre tu compra, no dudes en contactarnos respondiendo a este email.</p>

            <p>¡Gracias por confiar en EcoVolt!</p>
        </div>

        <div class="footer">
            <p>Este es un email automático, por favor no respondas a este mensaje.</p>
            <p>&copy; <?= date('Y') ?> EcoVolt. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html> 