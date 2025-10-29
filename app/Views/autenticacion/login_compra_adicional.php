<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Compra Adicional - EcoVolt Pro Premium</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* === CONTENEDOR PRINCIPAL === */
        .login-container {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 3rem;
            box-shadow: var(--shadow-premium);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 500px;
            margin: 2rem;
        }

        /* === HEADER === */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header .icon {
            font-size: 4rem;
            color: var(--gold-primary);
            margin-bottom: 1rem;
            text-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
        }

        .login-header h1 {
            color: var(--gold-primary);
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login-header p {
            color: var(--silver-primary);
            font-size: 1.1rem;
            margin: 0.5rem 0 0 0;
            font-weight: 600;
        }

        /* === INFORMACIÓN DE COMPRA === */
        .purchase-info {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid var(--green-success);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .purchase-info h5 {
            color: var(--green-success);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .purchase-info p {
            color: var(--white-primary);
            margin: 0.5rem 0;
        }

        .purchase-info strong {
            color: var(--gold-primary);
        }

        /* === FORMULARIO === */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: var(--gold-primary);
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            color: var(--white-primary);
            padding: 1rem;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--gold-light);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
            color: var(--white-primary);
        }

        .form-control::placeholder {
            color: var(--silver-secondary);
        }

        /* === BOTÓN DE LOGIN === */
        .btn-login {
            background: var(--gradient-green);
            color: var(--white-primary);
            border: none;
            border-radius: var(--border-radius);
            padding: 1.2rem 2rem;
            font-size: 1.3rem;
            font-weight: 700;
            width: 100%;
            transition: var(--transition);
            box-shadow: var(--shadow-success);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.4);
            color: var(--white-primary);
        }

        /* === MENSAJES DE ERROR/ÉXITO === */
        .alert {
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 2px solid;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
            color: #dc3545;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-color: var(--green-success);
            color: var(--green-success);
        }

        /* === ENLACES === */
        .login-links {
            text-align: center;
            margin-top: 2rem;
        }

        .login-links a {
            color: var(--gold-primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .login-links a:hover {
            color: var(--gold-light);
            text-decoration: underline;
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .login-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .login-header h1 {
                font-size: 2rem;
            }
            
            .login-header .icon {
                font-size: 3rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <i class="fas fa-plus-circle icon"></i>
            <h1>Iniciar Sesión</h1>
            <p>Para comprar tu dispositivo adicional</p>
        </div>

        <!-- Información de Compra -->
        <div class="purchase-info">
            <h5><i class="fas fa-shopping-cart me-2"></i>Compra Adicional</h5>
            <p><strong>Producto:</strong> EcoVolt Pro Premium (Adicional)</p>
            <p><strong>Precio:</strong> $150 USD</p>
            <p><strong>Beneficio:</strong> Se agregará a tu cuenta premium existente</p>
        </div>

        <!-- Mensajes de Error/Éxito -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de Login -->
        <form action="<?= base_url('login-compra-adicional/autenticar') ?>" method="post">
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2"></i>Email
                </label>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       placeholder="tu@email.com" 
                       required>
            </div>

            <div class="form-group">
                <label for="contrasena" class="form-label">
                    <i class="fas fa-lock me-2"></i> Contraseña
                </label>
                <input type="password" 
                       class="form-control" 
                       id="contrasena" 
                       name="contrasena" 
                       placeholder="Tu contraseña" 
                       required>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión y Comprar
            </button>
        </form>

        <!-- Enlaces -->
        <div class="login-links">
            <p>
                <a href="<?= base_url('tipo-compra') ?>">
                    <i class="fas fa-arrow-left me-1"></i>Volver a opciones de compra
                </a>
            </p>
            <p>
                ¿No tienes cuenta? 
                <a href="<?= base_url('registro-compra') ?>">Regístrate aquí</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
