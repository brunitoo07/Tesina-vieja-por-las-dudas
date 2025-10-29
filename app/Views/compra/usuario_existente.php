<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Dispositivo Adicional - EcoVolt</title>
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
            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            --gradient-silver: linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            --shadow-premium: 0 10px 30px rgba(212, 175, 55, 0.3);
            --shadow-dark: 0 10px 30px rgba(0, 0, 0, 0.3);
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

        /* === HEADER PREMIUM === */
        .premium-header {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-premium);
        }

        .premium-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .premium-header p {
            font-size: 1.2rem;
            margin: 0.5rem 0 0 0;
            font-weight: 600;
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

        /* === INFORMACIÓN DEL USUARIO === */
        .user-info {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 2rem 0;
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

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .premium-header h1 {
                font-size: 2rem;
            }
            
            .premium-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    
    <!-- Header Premium -->
    <div class="premium-header">
        
        <div class="container">
            <h1><i class="fas fa-plus-circle me-3"></i>Dispositivo Adicional</h1>
            <p>Agrega un segundo medidor a tu cuenta premium</p>
        </div>
    </div>
    <div class="container mb-4">
        <a href="<?= base_url('tipo-compra') ?>" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Volver a la vista principal
        </a>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Información del Usuario -->
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-user me-2"></i>Tu Cuenta Premium
                    </h3>
                    
                    <div class="user-info">
                        <h5><i class="fas fa-user-circle me-2"></i>Información de la Cuenta</h5>
                        <p><strong>Nombre:</strong> <?= esc($usuario['nombre']) ?> <?= esc($usuario['apellido']) ?></p>
                        <p><strong>Email:</strong> <?= esc($usuario['email']) ?></p>
                        <p><strong>Estado:</strong> <span style="color: var(--gold-primary); font-weight: 700;">Premium Activo</span></p>
                        <p><strong>Dispositivos actuales:</strong> <?= $dispositivos_count ?> dispositivo(s)</p>
                    </div>

                    <div class="text-center">
                        <p class="lead" style="color: var(--silver-primary); margin-bottom: 2rem;">
                            ¡Perfecto! Ya tienes una cuenta premium activa. Puedes agregar dispositivos adicionales usando la misma cuenta.
                        </p>
                        
                        <form action="<?= base_url('compra-existente/procesar') ?>" method="post" style="display: inline;">
                            <input type="hidden" name="id_dispositivo" value="1">
                            <button type="submit" class="btn-premium">
                                <i class="fas fa-shopping-cart me-2"></i>Comprar Dispositivo Adicional
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Información del Dispositivo -->
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-microchip me-2"></i>EcoVolt Pro Premium
                    </h3>
                    
                    <div class="text-center">
                        <i class="fas fa-microchip" style="font-size: 4rem; color: var(--gold-primary); margin-bottom: 1rem;"></i>
                        <h4 style="color: var(--gold-primary); margin-bottom: 1rem;">Dispositivo Adicional</h4>
                        <p style="color: var(--silver-primary); margin-bottom: 2rem;">
                            Agrega un segundo medidor de energía a tu cuenta premium existente.
                        </p>
                        
                        <div style="background: var(--gradient-gold); color: var(--black-primary); padding: 1.5rem; border-radius: var(--border-radius); margin: 2rem 0;">
                            <h4 style="margin: 0; font-weight: 800;">$150 USD</h4>
                            <p style="margin: 0.5rem 0 0 0; font-weight: 600;">Dispositivo + Soporte Premium</p>
                        </div>
                    </div>
                </div>

                <!-- Beneficios -->
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-star me-2"></i>Beneficios Incluidos
                    </h3>
                    
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Se agregará a tu cuenta existente
                        </li>
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Mismo soporte premium 24/7
                        </li>
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Configuración automática
                        </li>
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Garantía extendida de 2 años
                        </li>
                        <li style="padding: 0.8rem 0; display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Actualizaciones premium gratuitas
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>