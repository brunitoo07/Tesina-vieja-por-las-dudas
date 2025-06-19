<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('App.login_title') ?> - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .password-input {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        [data-theme="dark"] body {
            background: #181a1b !important;
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] .login-container {
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
        [data-theme="dark"] .alert-danger {
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
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">
                <i class="fas fa-bolt me-2"></i>EcoVolt
            </h2>

            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->get('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('iniciarSesion') ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label"><?= lang('App.email') ?></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3 password-input">
                    <label for="contrasena" class="form-label"><?= lang('App.password') ?></label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('contrasena')"></i>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i><?= lang('App.login') ?>
                    </button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="<?= base_url('autenticacion/correo') ?>" class="text-muted">
                    <i class="fas fa-key me-1"></i><?= lang('App.forgot_password') ?>
                </a>
            </div>
            <div class="mt-3 text-center">
                <a href="<?= base_url('/') ?>" class="btn btn-secondary">
                    <i class="fas fa-home me-2"></i><?= lang('App.back_home') ?>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar/ocultar contraseña
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
