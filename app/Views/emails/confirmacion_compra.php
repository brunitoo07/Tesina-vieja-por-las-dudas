<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .order-details {
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #eee;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Gracias por tu compra!</h1>
    </div>
    
    <div class="content">
        <p>Hola <?= esc($nombre) ?>,</p>
        
        <p>¡Gracias por confiar en EcoVolt! Tu compra ha sido procesada exitosamente.</p>
        
        <div class="order-details">
            <h3>Detalles de tu compra:</h3>
            <p><strong>Producto:</strong> <?= esc($dispositivo['nombre']) ?></p>
            <p><strong>Descripción:</strong> <?= esc($dispositivo['descripcion']) ?></p>
            <p><strong>Precio:</strong> $<?= number_format($dispositivo['precio'], 2) ?></p>
        </div>
        
        <p>Tu dispositivo será enviado a la dirección proporcionada en el proceso de compra. Recibirás un correo con el número de seguimiento cuando sea enviado.</p>
        
        <p>Para comenzar a usar tu dispositivo cuando lo recibas, te recomendamos seguir nuestro manual de usuario:</p>
        
        <div style="text-align: center;">
            <a href="<?= esc($manual_url) ?>" class="button">Ver Manual de Usuario</a>
        </div>
        
        <p>En el manual encontrarás:</p>
        <ul>
            <li>Instrucciones de instalación paso a paso</li>
            <li>Guía de configuración inicial</li>
            <li>Consejos para un uso óptimo</li>
            <li>Soluciones a problemas comunes</li>
        </ul>
        
        <p>Si tienes alguna pregunta o necesitas ayuda, nuestro equipo de soporte está disponible para ayudarte:</p>
        <ul>
            <li>Email: soporte@ecovolt.com</li>
            <li>Teléfono: +1 (555) 123-4567</li>
            <li>Horario: Lunes a Viernes, 9:00 AM - 6:00 PM</li>
        </ul>
        
        <p>¡Gracias por ser parte de la revolución de la energía inteligente!</p>
        
        <p>Saludos cordiales,<br>El equipo de EcoVolt</p>
    </div>
    
    <div class="footer">
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p>&copy; <?= date('Y') ?> EcoVolt. Todos los derechos reservados.</p>
    </div>
</body>
</html> 