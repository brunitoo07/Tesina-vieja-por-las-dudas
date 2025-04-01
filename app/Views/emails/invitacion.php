<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invitación a Medidor Inteligente</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 2rem auto;
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 2rem;
            background-color: #ffffff;
        }
        .button-container {
            text-align: center;
            margin: 2rem 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 14px;
        }
        .note {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            padding: 1.5rem;
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #dee2e6;
        }
        .url-fallback {
            word-break: break-all;
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
            font-family: monospace;
            font-size: 12px;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invitación a Medidor Inteligente</h1>
        </div>
        
        <div class="content">
            <p>¡Hola!</p>
            
            <p>Has sido invitado a unirte a <strong>Medidor Inteligente</strong> como <strong><?= $rol ?></strong>.</p>
            
            <p>Para completar tu registro, por favor haz clic en el siguiente botón:</p>
            
            <div class="button-container">
                <a href="<?= $registroUrl ?>" class="button">
                    Completar Registro
                </a>
            </div>
            
            <div class="note">
                <strong>Nota importante:</strong><br>
                Este enlace expirará en 24 horas por razones de seguridad.
            </div>
            
            <p>Si no puedes hacer clic en el botón, copia y pega el siguiente enlace en tu navegador:</p>
            <div class="url-fallback">
                <?= $registroUrl ?>
            </div>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; <?= date('Y') ?> Medidor Inteligente. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html> 