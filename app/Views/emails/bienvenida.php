<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a EcoVolt</title>
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
        .password {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 18px;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido a EcoVolt!</h1>
        </div>
        <div class="content">
            <p>Hola <?= $nombre ?>,</p>
            <p>Tu cuenta ha sido creada exitosamente. Aquí están tus credenciales temporales:</p>
            
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Contraseña temporal:</strong></p>
            <div class="password"><?= $contrasenaTemporal ?></div>
            
            <p>Por razones de seguridad, te recomendamos:</p>
            <ol>
                <li>Iniciar sesión con estas credenciales</li>
                <li>Cambiar tu contraseña inmediatamente</li>
            </ol>
            
            <p>Puedes iniciar sesión en: <a href="<?= base_url('autenticacion/login') ?>"><?= base_url('autenticacion/login') ?></a></p>
            
            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
            
            <p>Saludos,<br>El equipo de EcoVolt</p>
        </div>
        <div class="footer">
            <p>Este es un email automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html> 