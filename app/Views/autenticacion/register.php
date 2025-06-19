<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title><?= lang('App.register_title') ?> - EcoVolt</title>
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

        .container-register {
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

        .container-register:hover {
            transform: translateY(-5px);
        }

        .container-register h2 {
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

        .login-link {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #3498db;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .admin-badge {
            background-color: #e74c3c;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        [data-theme="dark"] body {
            background: #181a1b !important;
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] .container-register {
            background: #23272b !important;
            color: #f1f1f1 !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.5) !important;
        }
        [data-theme="dark"] .form-control {
            background: #23272b !important;
            color: #f1f1f1 !important;
            border-color: #4a90e2 !important;
        }
        [data-theme="dark"] .form-control:focus {
            background: #23272b !important;
            color: #fff !important;
            border-color: #50e3c2 !important;
            box-shadow: 0 0 0 2px #4a90e2 !important;
        }
        [data-theme="dark"] .btn-primary {
            background: #222e3c !important;
            color: #fff !important;
            border: 1px solid #4a90e2 !important;
        }
        [data-theme="dark"] .btn-primary:hover {
            background: #4a90e2 !important;
            color: #fff !important;
        }
        [data-theme="dark"] .alert-danger, [data-theme="dark"] .alert-success {
            background: #23272b !important;
            color: #ffd700 !important;
            border-color: #4a90e2 !important;
        }
        [data-theme="dark"] .theme-switch {
            background: #23272b !important;
            color: #ffd700 !important;
        }
    </style>
    <script>
        // Script para modo claro/oscuro
        document.addEventListener('DOMContentLoaded', function() {
            const themeSwitch = document.getElementById('themeSwitch');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let theme = localStorage.getItem('theme');
            if (!theme) {
                theme = prefersDark ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
            }
            document.documentElement.setAttribute('data-theme', theme);
            if(themeSwitch) themeSwitch.innerHTML = theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            if(themeSwitch) themeSwitch.onclick = function() {
                theme = (theme === 'dark') ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                themeSwitch.innerHTML = theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            };
        });
    </script>
</head>
<body>
    <!-- Theme Switch -->
    <div class="theme-switch" id="themeSwitch" title="Modo claro/oscuro">
        <i class="fas fa-moon"></i>
    </div>
    <!-- Selector de idioma solo si no está autenticado -->
    <?php if (!session()->get('logged_in')): ?>
    <div style="position: absolute; top: 10px; right: 60px;">
        <?php $idioma = service('request')->getLocale(); ?>
        <a href="<?= base_url('cambiar-idioma/es') ?>" title="Español">
            <img src="<?= base_url('imagenes/' . ($idioma === 'es' ? 'es' : 'es') . '.png') ?>" alt="Español" style="width:24px;<?= $idioma === 'es' ? 'border:2px solid #333;border-radius:50%;' : '' ?>">
        </a>
        <a href="<?= base_url('cambiar-idioma/en') ?>" title="English">
            <img src="<?= base_url('imagenes/' . ($idioma === 'en' ? 'en' : 'en') . '.png') ?>" alt="English" style="width:24px;<?= $idioma === 'en' ? 'border:2px solid #333;border-radius:50%;' : '' ?>">
        </a>
    </div>
    <?php endif; ?>
    <main>
        <div class="container-register">
            <h2><?= lang('App.register') ?></h2>
            
            <?php if (isset($purchase) && $purchase)): ?>
                <div class="admin-badge">
                    <i class="fas fa-crown me-1"></i><?= lang('App.admin_register') ?>
                </div>
                <input type="hidden" name="purchase" value="true">
            <?php endif; ?>

            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->get('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->get('password_error')): ?>
                <div class="alert alert-danger">
                    <?= session()->get('password_error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->get('success')): ?>
                <div class="alert alert-success">
                    <?= session()->get('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('autenticacion/registrarse') ?>" method="post">
                <?php if (isset($purchase) && $purchase)): ?>
                    <input type="hidden" name="purchase" value="true">
                <?php endif; ?>

                <div class="form-group">
                    <label for="nombre"><?= lang('App.name') ?></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required 
                           pattern="[a-zA-Z\s]+" title="Solo letras y espacios">
                </div>

                <div class="form-group">
                    <label for="apellido"><?= lang('App.lastname') ?></label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required
                           pattern="[a-zA-Z\s]+" title="Solo letras y espacios">
                </div>

                <div class="form-group">
                    <label for="email"><?= lang('App.email') ?></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="contrasena"><?= lang('App.password') ?></label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    <small class="text-muted"><?= lang('App.password_hint') ?></small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i><?= lang('App.register_btn') ?>
                </button>
            </form>

            <div class="login-link">
                <?= lang('App.already_account') ?> <a href="<?= base_url('autenticacion/login') ?>"><?= lang('App.login_here') ?></a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>