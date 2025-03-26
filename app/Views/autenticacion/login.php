<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Iniciar Sesión - EcoMonitor</title>
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/assets/img/energy-bg.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5%;
            min-height: 100vh;
        }

        .container-login {
            padding: 2.6rem;
            border-radius: 1.2rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 22rem;
            text-align: center;
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .container-login:hover {
            transform: translateY(-5px);
        }

        .container-login h2 {
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.2rem;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 0.8rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
        }

        .alert {
            margin-bottom: 1rem;
            border-radius: 0.5rem;
        }

        .register-link {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #3498db;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main>
        <div class="container-login">
            <h2>Iniciar Sesión</h2>
            
            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->get('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->get('exito')): ?>
                <div class="alert alert-success">
                    <?= session()->get('exito') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('iniciarSesion') ?>" method="post">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                </button>
            </form>

            <div class="register-link">
                ¿No tienes una cuenta? <a href="<?= base_url('autenticacion/register') ?>">Regístrate aquí</a>
            </div>

            <div class="mt-3">
                <a href="<?= base_url('autenticacion/correo') ?>" class="text-muted">¿Olvidaste tu contraseña?</a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
