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
        <h1>¡Bienvenido a EcoVolt!</h1>
    </div>
    
    <div class="content">
        <p>Hola <?= esc($nombre) ?>,</p>
        
        <p>¡Gracias por elegir EcoVolt para tu sistema de monitoreo de energía! Estamos emocionados de tenerte como parte de nuestra comunidad.</p>
        
        <p>Para activar tu cuenta y comenzar a disfrutar de todos los beneficios de EcoVolt, por favor haz clic en el siguiente botón:</p>
        
        <div style="text-align: center;">
            <a href="<?= esc($enlace_activacion) ?>" class="button">Activar mi cuenta</a>
        </div>
        
        <p>Si el botón no funciona, puedes copiar y pegar el siguiente enlace en tu navegador:</p>
        <p style="word-break: break-all;"><?= esc($enlace_activacion) ?></p>
        
        <p>Una vez que actives tu cuenta, podrás:</p>
        <ul>
            <li>Acceder a tu panel de control personalizado</li>
            <li>Monitorear tu consumo de energía en tiempo real</li>
            <li>Recibir alertas y recomendaciones personalizadas</li>
            <li>Gestionar tus dispositivos de manera eficiente</li>
        </ul>
        
        <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.</p>
        
        <p>¡Bienvenido a la revolución de la energía inteligente!</p>
        
        <p>Saludos cordiales,<br>El equipo de EcoVolt</p>
    </div>
    
    <div class="footer">
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p>&copy; <?= date('Y') ?> EcoVolt. Todos los derechos reservados.</p>
    </div>
</body>
</html> 