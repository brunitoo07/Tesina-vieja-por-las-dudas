<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - EcoVolt Premium</title>
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
            --blue-primary: #007bff;
            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            --gradient-green: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            --gradient-blue: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            --shadow-premium: 0 10px 30px rgba(212, 175, 55, 0.3);
            --shadow-success: 0 10px 30px rgba(40, 167, 69, 0.3);
            --shadow-blue: 0 10px 30px rgba(0, 123, 255, 0.3);
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

        /* === HEADER DE BIENVENIDA === */
        .welcome-header {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 3rem 0;
            text-align: center;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-premium);
        }

        .welcome-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .welcome-header p {
            font-size: 1.3rem;
            margin: 1rem 0 0 0;
            font-weight: 600;
        }

        /* === TARJETAS DE OPCIÓN === */
        .option-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-premium);
            backdrop-filter: blur(10px);
            transition: var(--transition);
            text-align: center;
            height: 100%;
        }

        .option-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(212, 175, 55, 0.4);
        }

        .option-card.dashboard {
            border-color: var(--blue-primary);
            box-shadow: var(--shadow-blue);
        }

        .option-card.dashboard:hover {
            box-shadow: 0 20px 50px rgba(0, 123, 255, 0.4);
        }

        .option-card.purchase {
            border-color: var(--green-success);
            box-shadow: var(--shadow-success);
        }

        .option-card.purchase:hover {
            box-shadow: 0 20px 50px rgba(40, 167, 69, 0.4);
        }

        .option-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: block;
        }

        .option-card .option-icon {
            color: var(--gold-primary);
        }

        .option-card.dashboard .option-icon {
            color: var(--blue-primary);
        }

        .option-card.purchase .option-icon {
            color: var(--green-success);
        }

        .option-title {
            color: var(--gold-primary);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .option-card.dashboard .option-title {
            color: var(--blue-primary);
        }

        .option-card.purchase .option-title {
            color: var(--green-success);
        }

        .option-description {
            color: var(--silver-primary);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* === BOTONES PREMIUM === */
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

        .btn-premium.dashboard {
            background: var(--gradient-blue);
            color: var(--white-primary);
            box-shadow: var(--shadow-blue);
        }

        .btn-premium.dashboard:hover {
            box-shadow: 0 15px 40px rgba(0, 123, 255, 0.4);
            color: var(--white-primary);
        }

        .btn-premium.purchase {
            background: var(--gradient-green);
            color: var(--white-primary);
            box-shadow: var(--shadow-success);
        }

        .btn-premium.purchase:hover {
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.4);
            color: var(--white-primary);
        }

        /* === INFORMACIÓN DEL USUARIO === */
        .user-info {
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: center;
        }

        .user-info h5 {
            color: var(--gold-primary);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .user-info p {
            margin: 0.5rem 0;
            color: var(--white-primary);
        }

        .user-info strong {
            color: var(--gold-primary);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .welcome-header h1 {
                font-size: 2.5rem;
            }
            
            .option-card {
                padding: 1.5rem;
            }
            
            .option-icon {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header de Bienvenida -->
    <div class="welcome-header">
        <div class="container">
            <h1><i class="fas fa-crown me-3"></i>¡Bienvenido!</h1>
            <p>EcoVolt Pro Premium - ¿Qué te gustaría hacer?</p>
        </div>
    </div>

    <div class="container">
        <!-- Información del Usuario -->
        <div class="user-info">
            <h5><i class="fas fa-user-circle me-2"></i>Tu Cuenta Premium</h5>
            <p><strong>Nombre:</strong> <?= esc(session()->get('nombre')) ?></p>
            <p><strong>Email:</strong> <?= esc(session()->get('email')) ?></p>
            <p><strong>Estado:</strong> <span style="color: var(--gold-primary); font-weight: 700;">Premium Activo</span></p>
        </div>

        <div class="row">
            <!-- Opción 1: Dashboard -->
            <div class="col-md-6">
                <div class="option-card dashboard">
                    <i class="fas fa-tachometer-alt option-icon"></i>
                    <h3 class="option-title">Panel de Control</h3>
                    <p class="option-description">
                        Accede a tu dashboard principal para monitorear tu consumo de energía, 
                        gestionar tus dispositivos y ver estadísticas detalladas.
                    </p>
                    <a href="<?= base_url('dashboard') ?>" class="btn-premium dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>Ir al Dashboard
                    </a>
                </div>
            </div>

            <!-- Opción 2: Comprar Dispositivo Adicional -->
            <div class="col-md-6">
                <div class="option-card purchase">
                    <i class="fas fa-plus-circle option-icon"></i>
                    <h3 class="option-title">Comprar Dispositivo</h3>
                    <p class="option-description">
                        Agrega un dispositivo adicional a tu cuenta premium. 
                        Disfruta del mismo soporte 24/7 y todas las funcionalidades premium.
                    </p>
                    <a href="<?= base_url('compra-existente') ?>" class="btn-premium purchase">
                        <i class="fas fa-shopping-cart me-2"></i>Comprar Adicional - $150 USD
                    </a>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="option-card">
                    <h4 class="option-title">
                        <i class="fas fa-info-circle me-2"></i>Información Importante
                    </h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="fas fa-check-circle me-2" style="color: var(--green-success);"></i>
                                <strong>Dashboard:</strong> Monitorea tu consumo en tiempo real</p>
                            <p><i class="fas fa-check-circle me-2" style="color: var(--green-success);"></i>
                                <strong>Dispositivos:</strong> Gestiona todos tus medidores</p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="fas fa-check-circle me-2" style="color: var(--green-success);"></i>
                                <strong>Soporte Premium:</strong> Asistencia 24/7 incluida</p>
                            <p><i class="fas fa-check-circle me-2" style="color: var(--green-success);"></i>
                                <strong>Dispositivos Adicionales:</strong> Mismo soporte premium</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
