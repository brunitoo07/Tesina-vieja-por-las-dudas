<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medidor Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    <style>
        [data-theme="dark"] body {
            background: #181a1b !important;
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] .container,
        [data-theme="dark"] .container-fluid,
        [data-theme="dark"] .card,
        [data-theme="dark"] .card-body,
        [data-theme="dark"] .card-header,
        [data-theme="dark"] .navbar,
        [data-theme="dark"] .navbar-dark,
        [data-theme="dark"] .navbar-light {
            background: #23272b !important;
            color: #f1f1f1 !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.5) !important;
        }
        [data-theme="dark"] .btn-primary,
        [data-theme="dark"] .btn-outline-light {
            background: #222e3c !important;
            color: #fff !important;
            border: 1px solid #4a90e2 !important;
        }
        [data-theme="dark"] .btn-primary:hover,
        [data-theme="dark"] .btn-outline-light:hover {
            background: #4a90e2 !important;
            color: #fff !important;
        }
        [data-theme="dark"] .form-control,
        [data-theme="dark"] input,
        [data-theme="dark"] textarea,
        [data-theme="dark"] select {
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
        [data-theme="dark"] .dropdown-menu {
            background: #23272b !important;
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] .dropdown-item {
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] .dropdown-item:hover {
            background: #4a90e2 !important;
            color: #fff !important;
        }
        [data-theme="dark"] .alert {
            background: #23272b !important;
            color: #ffd700 !important;
            border-color: #4a90e2 !important;
        }
        [data-theme="dark"] .theme-switch {
            background: #23272b !important;
            color: #ffd700 !important;
        }
    </style>
</head>
<body>
    <!-- Theme Switch -->
    <div class="theme-switch" id="themeSwitch" title="Modo claro/oscuro" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <i class="fas fa-moon"></i>
    </div>
    <!-- Selector de idioma solo si no está autenticado -->
    <?php if (!session()->get('logged_in')): ?>
    <div style="position: fixed; top: 20px; right: 60px; z-index: 1000;">
        <?php $idioma = service('request')->getLocale(); ?>
        <a href="<?= base_url('cambiar-idioma/es') ?>" title="Español">
            <img src="<?= base_url('imagenes/' . ($idioma === 'es' ? 'es' : 'es') . '.png') ?>" alt="Español" style="width:24px;<?= $idioma === 'es' ? 'border:2px solid #333;border-radius:50%;' : '' ?>">
        </a>
        <a href="<?= base_url('cambiar-idioma/en') ?>" title="English">
            <img src="<?= base_url('imagenes/' . ($idioma === 'en' ? 'en' : 'en') . '.png') ?>" alt="English" style="width:24px;<?= $idioma === 'en' ? 'border:2px solid #333;border-radius:50%;' : '' ?>">
        </a>
    </div>
    <?php endif; ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">Medidor Inteligente</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (session()->get('logged_in')): ?>
                        <?php if (session()->get('rol') === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin') ?>">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/gestionarUsuarios') ?>">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('admin/invitar') ?>">Invitar Usuario</a>
                            </li>
                        <?php elseif (session()->get('rol') === 'supervisor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('supervisor') ?>">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('supervisor/gestionarUsuarios') ?>">Mis Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('supervisor/dispositivosGlobal') ?>">Dispositivos</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('dispositivo') ?>">Mis Dispositivos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('energia') ?>">Energía</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (session()->get('logged_in')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('usuario/perfil') ?>">
                                <i class="fas fa-user-circle"></i> Mi Perfil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('autenticacion/cerrarSesion') ?>">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('autenticacion/login') ?>">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    <?= $this->renderSection('contenido') ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 