<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegir Tipo de Compra - EcoVolt Pro Premium</title>
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

        /* === HEADER PREMIUM === */
        .premium-header {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 3rem 0;
            text-align: center;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-premium);
        }

        .premium-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .premium-header p {
            font-size: 1.3rem;
            margin: 1rem 0 0 0;
            font-weight: 600;
        }

        /* === TARJETAS DE OPCIÓN === */
        .option-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-premium);
            backdrop-filter: blur(10px);
            transition: var(--transition);
            text-align: center;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .option-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-gold);
        }

        .option-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(212, 175, 55, 0.4);
        }

        .option-card.nuevo {
            border-color: var(--blue-primary);
            box-shadow: var(--shadow-blue);
        }

        .option-card.nuevo::before {
            background: var(--gradient-blue);
        }

        .option-card.nuevo:hover {
            box-shadow: 0 20px 50px rgba(0, 123, 255, 0.4);
        }

        .option-card.existente {
            border-color: var(--green-success);
            box-shadow: var(--shadow-success);
        }

        .option-card.existente::before {
            background: var(--gradient-green);
        }

        .option-card.existente:hover {
            box-shadow: 0 20px 50px rgba(40, 167, 69, 0.4);
        }

        .option-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            display: block;
        }

        .option-card .option-icon {
            color: var(--gold-primary);
        }

        .option-card.nuevo .option-icon {
            color: var(--blue-primary);
        }

        .option-card.existente .option-icon {
            color: var(--green-success);
        }

        .option-title {
            color: var(--gold-primary);
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .option-card.nuevo .option-title {
            color: var(--blue-primary);
        }

        .option-card.existente .option-title {
            color: var(--green-success);
        }

        .option-description {
            color: var(--silver-primary);
            font-size: 1.2rem;
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .option-features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
            text-align: left;
        }

        .option-features li {
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }

        .option-features li:last-child {
            border-bottom: none;
        }

        .option-features i {
            color: var(--gold-primary);
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .option-card.nuevo .option-features i {
            color: var(--blue-primary);
        }

        .option-card.existente .option-features i {
            color: var(--green-success);
        }

        /* === BOTONES PREMIUM === */
        .btn-premium {
            background: var(--gradient-gold);
            color: var(--black-primary);
            border: none;
            border-radius: var(--border-radius);
            padding: 1.2rem 2.5rem;
            font-size: 1.3rem;
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

        .btn-premium.nuevo {
            background: var(--gradient-blue);
            color: var(--white-primary);
            box-shadow: var(--shadow-blue);
        }

        .btn-premium.nuevo:hover {
            box-shadow: 0 15px 40px rgba(0, 123, 255, 0.4);
            color: var(--white-primary);
        }

        .btn-premium.existente {
            background: var(--gradient-green);
            color: var(--white-primary);
            box-shadow: var(--shadow-success);
        }

        .btn-premium.existente:hover {
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.4);
            color: var(--white-primary);
        }

        /* === PRECIO === */
        .price-tag {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            margin: 2rem 0;
            font-size: 2.5rem;
            font-weight: 800;
            box-shadow: var(--shadow-premium);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .option-card.nuevo .price-tag {
            background: var(--gradient-blue);
            color: var(--white-primary);
            box-shadow: var(--shadow-blue);
        }

        .option-card.existente .price-tag {
            background: var(--gradient-green);
            color: var(--white-primary);
            box-shadow: var(--shadow-success);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .premium-header h1 {
                font-size: 2.5rem;
            }
            
            .option-card {
                padding: 2rem;
            }
            
            .option-icon {
                font-size: 4rem;
            }
            
            .option-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Premium -->
    <div class="premium-header">
        <div class="container">
            <h1><i class="fas fa-shopping-cart me-3"></i>EcoVolt Pro Premium</h1>
            <p>Elige el tipo de compra que mejor se adapte a tu situación</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Opción 1: Nuevo Usuario -->
            <div class="col-md-6">
                <div class="option-card nuevo">
                    <i class="fas fa-user-plus option-icon"></i>
                    <h3 class="option-title">Primera Compra</h3>
                    <p class="option-description">
                        Es la primera vez que compras un EcoVolt Pro. Te registraremos y activaremos tu cuenta premium.
                    </p>
                    
                    <ul class="option-features">
                        <li><i class="fas fa-check-circle"></i> Registro automático en el sistema</li>
                        <li><i class="fas fa-check-circle"></i> Cuenta premium activada</li>
                        <li><i class="fas fa-check-circle"></i> Soporte premium 24/7 incluido</li>
                        <li><i class="fas fa-check-circle"></i> Configuración inicial asistida</li>
                        <li><i class="fas fa-check-circle"></i> Garantía extendida de 2 años</li>
                    </ul>
                    
                    <div class="price-tag">$150 USD</div>
                    
                    <a href="<?= base_url('registro-compra') ?>" class="btn-premium nuevo">
                        <i class="fas fa-user-plus me-2"></i>COMPRAR COMO NUEVO USUARIO
                    </a>
                </div>
            </div>

            <!-- Opción 2: Usuario Existente -->
            <div class="col-md-6">
                <div class="option-card existente">
                    <i class="fas fa-plus-circle option-icon"></i>
                    <h3 class="option-title">Dispositivo Adicional</h3>
                    <p class="option-description">
                        Ya tienes una cuenta premium y quieres agregar un dispositivo adicional a tu sistema.
                    </p>
                    
                    <ul class="option-features">
                        <li><i class="fas fa-check-circle"></i> Se agrega a tu cuenta existente</li>
                        <li><i class="fas fa-check-circle"></i> Mismo soporte premium 24/7</li>
                        <li><i class="fas fa-check-circle"></i> Configuración automática</li>
                        <li><i class="fas fa-check-circle"></i> Compatible con tu sistema actual</li>
                        <li><i class="fas fa-check-circle"></i> Garantía extendida de 2 años</li>
                    </ul>
                    
                    <div class="price-tag">$150 USD</div>
                    
                    <a href="<?= base_url('compra-existente') ?>" class="btn-premium existente">
                        <i class="fas fa-plus-circle me-2"></i>COMPRAR DISPOSITIVO ADICIONAL
                    </a>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="option-card">
                    <h4 class="option-title">
                        <i class="fas fa-info-circle me-2"></i>Información Importante
                    </h4>
                    <div class="row">
                        <div class="col-md-4">
                            <h6 style="color: var(--gold-primary); font-weight: 700;">
                                <i class="fas fa-shipping-fast me-2"></i>Envío Gratis
                            </h6>
                            <p style="color: var(--silver-primary);">Envío gratuito a todo el país</p>
                        </div>
                        <div class="col-md-4">
                            <h6 style="color: var(--gold-primary); font-weight: 700;">
                                <i class="fas fa-headset me-2"></i>Soporte 24/7
                            </h6>
                            <p style="color: var(--silver-primary);">Asistencia técnica premium incluida</p>
                        </div>
                        <div class="col-md-4">
                            <h6 style="color: var(--gold-primary); font-weight: 700;">
                                <i class="fas fa-shield-alt me-2"></i>Garantía Premium
                            </h6>
                            <p style="color: var(--silver-primary);">2 años de garantía extendida</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
