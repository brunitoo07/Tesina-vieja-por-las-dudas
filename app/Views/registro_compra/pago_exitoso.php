<!-- PAGO EXITOSO DE LA PRIMER COMPRA -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pago Exitoso! - EcoVolt Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        /* === ESTILOS GLOBALES PREMIUM === */
        body {
            background: var(--gradient-dark);
            color: var(--white-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* === HEADER DE ÉXITO === */
        .success-header {
            background: var(--gradient-green);
            color: var(--white-primary);
            padding: 3rem 0;
            text-align: center;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-success);
        }

        .success-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .success-header p {
            font-size: 1.3rem;
            margin: 1rem 0 0 0;
            font-weight: 600;
        }

        /* === ICONO DE ÉXITO ANIMADO === */
        .success-icon {
            font-size: 6rem;
            color: var(--green-success);
            margin-bottom: 2rem;
            animation: successPulse 2s ease-in-out infinite;
        }

        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* === TARJETAS PREMIUM === */
        .premium-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-premium);
            backdrop-filter: blur(10px);
            transition: var(--transition);
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.4);
        }

        .premium-title {
            color: var(--gold-primary);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* === LISTA DE CARACTERÍSTICAS === */
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
        }

        .feature-list li {
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list i {
            color: var(--gold-primary);
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        /* === BOTÓN PREMIUM === */
        .btn-premium {
            background: var(--gradient-gold);
            color: var(--black-primary);
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 2rem;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
            box-shadow: var(--shadow-premium);
        }

        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.4);
            color: var(--black-primary);
        }

        /* === INFORMACIÓN DEL PEDIDO === */
        .order-info {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid var(--green-success);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .order-info h5 {
            color: var(--green-success);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .order-info p {
            margin: 0.5rem 0;
            color: var(--white-primary);
        }

        .order-info strong {
            color: var(--gold-primary);
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

        .support-section h4 {
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .support-section p {
            font-weight: 600;
            margin: 0.5rem 0;
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .success-header h1 {
                font-size: 2.5rem;
            }
            
            .premium-card {
                padding: 1.5rem;
            }
            
            .success-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header de Éxito -->
    <div class="success-header">
        <div class="container">
            <h1><i class="fas fa-check-circle me-3"></i>¡Pago Exitoso!</h1>
            <p>Plan Premium Único - Dispositivo + Soporte Mensual</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <!-- Mensaje de Felicitaciones -->
                <div class="premium-card text-center">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h2 class="premium-title">¡Felicitaciones, <?= esc($nombre) ?>!</h2>
                    <p class="lead" style="font-size: 1.3rem; color: var(--silver-primary);">
                        Tu pedido premium ha sido procesado correctamente y tu cuenta ha sido creada.
                    </p>
                </div>

                <!-- Detalles del Pedido -->
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-shopping-bag me-2"></i>Detalles del Pedido
                    </h3>
                    
                    <div class="order-info">
                        <h5><i class="fas fa-box me-2"></i>Producto</h5>
                        <p><strong>EcoVolt Pro Premium</strong></p>
                        <p><strong>Precio:</strong> $150 USD</p>
                        <p><strong>Soporte Premium:</strong> Mensual Incluido</p>
                        <p><strong>Garantía:</strong> 2 años premium</p>
                    </div>

                    <div class="order-info">
                        <h5><i class="fas fa-map-marker-alt me-2"></i>Dirección de Envío</h5>
                        <p><?= esc($direccion) ?></p>
                    </div>

                    <div class="order-info">
                        <h5><i class="fas fa-calendar me-2"></i>Fecha de Compra</h5>
                        <p><?= esc($fecha) ?></p>
                    </div>
                </div>

                <!-- Soporte Premium Incluido -->
                <div class="support-section">
                    <h4><i class="fas fa-crown me-2"></i>Soporte Premium Incluido</h4>
                    <p>¡Tu soporte premium ya está activo!</p>
                    <p>Disfruta de todos estos beneficios:</p>
                    
                    <ul class="feature-list" style="text-align: left; margin: 1rem 0;">
                        <li><i class="fas fa-headset"></i> Soporte técnico 24/7 todos los días</li>
                        <li><i class="fas fa-user-cog"></i> Asistencia personalizada para configuración</li>
                        <li><i class="fas fa-sync-alt"></i> Actualizaciones premium gratuitas</li>
                        <li><i class="fas fa-shield-alt"></i> Garantía extendida de 2 años</li>
                    </ul>
                    
                    <p style="margin-top: 1rem; font-size: 0.9rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        El soporte premium se renueva mensualmente
                    </p>
                </div>

                <!-- Próximos Pasos -->
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-arrow-right me-2"></i>Próximos Pasos
                    </h3>
                    
                    <ul class="feature-list">
                        <li><i class="fas fa-envelope"></i> Recibirás un email de confirmación con todos los detalles</li>
                        <li><i class="fas fa-truck"></i> Tu dispositivo será enviado a la dirección proporcionada</li>
                        <li><i class="fas fa-sign-in-alt"></i> Puedes iniciar sesión con tu cuenta recién creada</li>
                        <li><i class="fas fa-headset"></i> Tu soporte premium ya está disponible</li>
                    </ul>
                </div>

                <!-- Botón de Acción -->
                <div class="text-center">
                    <a href="<?= base_url('autenticacion/login') ?>" class="btn-premium">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>