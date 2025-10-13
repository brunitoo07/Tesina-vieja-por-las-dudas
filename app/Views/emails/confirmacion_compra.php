<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra - EcoVolt Premium</title>
    <style>
        /* === PALETA DE COLORES PREMIUM === */
        :root {
            --gold-primary: #D4AF37;
            --gold-secondary: #B8860B;
            --gold-light: #F7E98E;
            --gold-dark: #8B7355;
            --silver-primary: #C0C0C0;
            --silver-secondary: #A8A8A8;
            --silver-light: #E8E8E8;
            --black-primary: #1a1a1a;
            --black-secondary: #2d2d2d;
            --black-light: #404040;
            --white-primary: #ffffff;
            --white-secondary: #f8f9fa;
            --white-dark: #e9ecef;
            --green-success: #28a745;
            --green-light: #d4edda;
            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            --gradient-green: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            --shadow-premium: 0 10px 30px rgba(212, 175, 55, 0.3);
            --shadow-success: 0 10px 30px rgba(40, 167, 69, 0.3);
            --border-radius: 15px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* === ESTILOS GLOBALES === */
        body {
            margin: 0;
            padding: 0;
            background-color: var(--white-secondary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--black-primary);
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: var(--white-primary);
            box-shadow: var(--shadow-premium);
        }

        /* === HEADER PREMIUM === */
        .email-header {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 2rem;
            text-align: center;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .email-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 800;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .email-header p {
            margin: 0.5rem 0 0 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* === CONTENIDO PRINCIPAL === */
        .email-content {
            padding: 2rem;
        }

        .greeting {
            font-size: 1.3rem;
            color: var(--black-primary);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .success-message {
            background: var(--gradient-green);
            color: var(--white-primary);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            margin: 2rem 0;
            box-shadow: var(--shadow-success);
        }

        .success-message h2 {
            margin: 0 0 1rem 0;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .success-message p {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* === SECCIONES DE INFORMACIÓN === */
        .info-section {
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .info-section h3 {
            color: var(--gold-primary);
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
        }

        .info-section h3 i {
            margin-right: 0.5rem;
        }

        .info-section p {
            margin: 0.5rem 0;
            color: var(--black-primary);
        }

        .info-section strong {
            color: var(--gold-primary);
            font-weight: 700;
        }

        /* === LISTA DE CARACTERÍSTICAS === */
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            align-items: center;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list i {
            color: var(--gold-primary);
            margin-right: 0.8rem;
            font-size: 1.1rem;
        }

        /* === SOPORTE PREMIUM === */
        .support-section {
            background: var(--gradient-gold);
            color: var(--black-primary);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin: 2rem 0;
            text-align: center;
        }

        .support-section h3 {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0 0 1rem 0;
        }

        .support-section p {
            font-weight: 600;
            margin: 0.5rem 0;
        }

        /* === FOOTER === */
        .email-footer {
            background: var(--gradient-dark);
            color: var(--white-primary);
            padding: 2rem;
            text-align: center;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        .email-footer h4 {
            color: var(--gold-primary);
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 1rem 0;
        }

        .email-footer p {
            margin: 0.5rem 0;
            color: var(--silver-primary);
        }

        .email-footer a {
            color: var(--gold-primary);
            text-decoration: none;
            font-weight: 600;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        /* === RESPONSIVE === */
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .email-header h1 {
                font-size: 2rem;
            }
            
            .email-content {
                padding: 1.5rem;
            }
            
            .info-section {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Premium -->
        <div class="email-header">
            <h1><i class="fas fa-crown"></i> ¡Compra Exitosa!</h1>
            <p>EcoVolt Pro Premium - Confirmación de Compra</p>
            <p style="font-size: 1rem; margin-top: 0.5rem;">Plan Premium Único - Dispositivo + Soporte Mensual</p>
        </div>

        <!-- Contenido Principal -->
        <div class="email-content">
            <div class="greeting">
                ¡Hola <?= esc($nombre) ?>!
            </div>

            <p>¡Felicitaciones! Tu compra del <strong>EcoVolt Pro Premium</strong> ha sido procesada exitosamente. Tu cuenta ha sido creada y tu soporte premium ya está activo.</p>

            <!-- Mensaje de Éxito -->
            <div class="success-message">
                <h2><i class="fas fa-check-circle"></i> ¡Pago Confirmado!</h2>
                <p>Tu pedido premium está siendo procesado</p>
            </div>

            <!-- Detalles del Producto -->
            <div class="info-section">
                <h3><i class="fas fa-box"></i>Detalles del Producto</h3>
                <p><strong>Producto:</strong> EcoVolt Premium</p>
                <p><strong>Precio:</strong> $150 USD</p>
                <p><strong>Soporte Premium:</strong> Mensual Incluido</p>
                <p><strong>Garantía:</strong> 2 años premium</p>
                <p><strong>Estado:</strong> <span style="color: var(--green-success); font-weight: 700;">Activo</span></p>
            </div>

            <!-- Información de Pago -->
            <div class="info-section">
                <h3><i class="fas fa-credit-card"></i>Información de Pago</h3>
                <p><strong>ID de Transacción:</strong> <?= esc($payment_id) ?></p>
                <p><strong>Número de Pedido:</strong> <?= esc($numero_pedido) ?></p>
                <p><strong>Fecha de Compra:</strong> <?= esc($fecha) ?></p>
                <p><strong>Método de Pago:</strong> PayPal</p>
            </div>

            <!-- Dirección de Envío -->
            <div class="info-section">
                <h3><i class="fas fa-map-marker-alt"></i>Dirección de Envío</h3>
                <p><?= esc($direccion) ?></p>
            </div>

            <!-- Soporte Premium Incluido -->
            <div class="support-section">
                <h3><i class="fas fa-crown"></i>Soporte Premium Incluido</h3>
                <p>¡Tu soporte premium ya está activo!</p>
                <p>Disfruta de todos estos beneficios:</p>
                
                <ul class="feature-list" style="text-align: left; margin: 1rem 0;">
                    <li><i class="fas fa-headset"></i> Soporte técnico 24/7 todos los días</li>
                    <li><i class="fas fa-user-cog"></i> Asistencia personalizada para configuración</li>
                    <li><i class="fas fa-sync-alt"></i> Actualizaciones premium gratuitas</li>
                    <li><i class="fas fa-shield-alt"></i> Garantía extendida de 2 años</li>
                </ul>
                
                <p style="margin-top: 1rem; font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i>
                    El soporte premium se renueva mensualmente
                </p>
            </div>

            <!-- Próximos Pasos -->
            <div class="info-section">
                <h3><i class="fas fa-arrow-right"></i>Próximos Pasos</h3>
                <ul class="feature-list">
                    <li><i class="fas fa-truck"></i> Tu dispositivo será enviado a la dirección proporcionada</li>
                    <li><i class="fas fa-sign-in-alt"></i> Puedes iniciar sesión con tu cuenta recién creada</li>
                    <li><i class="fas fa-headset"></i> Tu soporte premium ya está disponible</li>
                    <li><i class="fas fa-envelope"></i> Recibirás actualizaciones por email</li>
                </ul>
            </div>

            <p style="margin-top: 2rem; text-align: center;">
                <strong>¡Gracias por elegir EcoVolt Pro Premium!</strong><br>
                Estamos emocionados de tenerte como parte de nuestra comunidad premium.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <h4><i class="fas fa-envelope"></i> ¿Necesitas Ayuda?</h4>
            <p>Si tienes alguna pregunta sobre tu compra o necesitas asistencia técnica,</p>
            <p>nuestro equipo de soporte premium está disponible 24/7.</p>
            <p>
                <a href="mailto:soporte@ecomonitor.com">soporte@ecomonitor.com</a> | 
                <a href="https://ecomonitor.com">ecomonitor.com</a>
            </p>
            <p style="margin-top: 1rem; font-size: 0.9rem; color: var(--silver-secondary);">
                © 2024 EcoVolt Pro Premium. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>