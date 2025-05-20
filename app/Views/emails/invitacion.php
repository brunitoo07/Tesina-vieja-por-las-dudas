<!DOCTYPE html>
<html>
<head>
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
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Has sido invitado a EcoVolt!</h1>
    </div>
    
    <div class="content">
        <p>Hola,</p>
        
        <p>Has sido invitado a unirte a EcoVolt como <?= $id_rol == 1 ? 'Administrador' : 'Usuario' ?>.</p>
        
        <p>Para completar tu registro, haz clic en el siguiente enlace:</p>
        
        <div style="text-align: center;">
            <a href="<?= $link ?>" class="button">Completar Registro</a> 
        </div>
        
        <p>Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
        <p style="word-break: break-all;"><?= $link ?></p> 
        
        <p>Este enlace expirará en 24 horas por razones de seguridad.</p>
    </div>
    
    <div class="footer">
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p>&copy; <?= date('Y') ?> EcoVolt. Todos los derechos reservados.</p>
    </div>
</body>
</html>