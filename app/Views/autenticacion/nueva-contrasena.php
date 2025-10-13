<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('App.new_password_title') ?> - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FFD700;
            --secondary-color: #6c757d;
            --accent-color: #FFD700;
            --dark-color: #000000;
            --light-color: rgba(245, 196, 0, 0.48);
            --transition-speed: 0.3s;
        }
        /* Fondo base con gradientes y grilla sutil */
        body {
            min-height: 100vh;
            background:
                radial-gradient(800px 400px at -10% -10%, rgba(255, 215, 0, 0.15) 0%, rgba(255, 215, 0, 0) 60%),
                radial-gradient(700px 420px at 110% 110%, rgba(108, 117, 125, 0.18) 0%, rgba(108, 117, 125, 0) 60%),
                linear-gradient(180deg, #ffffff 0%, #f7f7f7 100%);
            position: relative;
        }
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(transparent calc(100% - 1px), rgba(0, 0, 0, 0.06) 1px),
                linear-gradient(90deg, transparent calc(100% - 1px), rgba(0, 0, 0, 0.06) 1px);
            background-size: 60px 60px;
            mix-blend-mode: multiply;
        }

        /* Auroras animadas */
        .bg-aurora {
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            background:
                radial-gradient(60% 80% at 10% 10%, rgba(255, 215, 0, 0.18), transparent 60%),
                radial-gradient(60% 80% at 90% 90%, rgba(108, 117, 125, 0.18), transparent 60%),
                radial-gradient(50% 60% at 70% 20%, rgba(0, 0, 0, 0.08), transparent 60%);
            filter: saturate(110%);
            animation: moveAurora 18s ease-in-out infinite alternate;
        }
        @keyframes moveAurora {
            0% { transform: translateY(0) scale(1); filter: hue-rotate(0deg); }
            50% { transform: translateY(-10px) scale(1.02); filter: hue-rotate(-8deg); }
            100% { transform: translateY(0) scale(1); filter: hue-rotate(0deg); }
        }

        /* Destellos dorados flotantes */
        .bg-sparkles {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }
        .bg-sparkles span {
            position: absolute;
            display: block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #fff 0%, #ffe88a 35%, #ffd700 70%, rgba(255,215,0,0.0) 71%);
            box-shadow: 0 0 12px rgba(255, 215, 0, 0.6), 0 0 24px rgba(255, 215, 0, 0.35);
            opacity: 0.85;
            animation: floatSparkle calc(9s + var(--i) * 0.7s) linear infinite;
        }
        @keyframes floatSparkle {
            0% { transform: translateY(110vh) translateX(0) scale(0.9); opacity: 0; }
            10% { opacity: 0.9; }
            50% { transform: translateY(50vh) translateX(20px) scale(1); }
            90% { opacity: 0.9; }
            100% { transform: translateY(-10vh) translateX(-20px) scale(0.9); opacity: 0; }
        }
        .bg-sparkles span:nth-child(1) { left: 5%;  --i: 1; }
        .bg-sparkles span:nth-child(2) { left: 15%; --i: 2; }
        .bg-sparkles span:nth-child(3) { left: 25%; --i: 3; }
        .bg-sparkles span:nth-child(4) { left: 35%; --i: 4; }
        .bg-sparkles span:nth-child(5) { left: 45%; --i: 5; }
        .bg-sparkles span:nth-child(6) { left: 55%; --i: 6; }
        .bg-sparkles span:nth-child(7) { left: 65%; --i: 7; }
        .bg-sparkles span:nth-child(8) { left: 75%; --i: 8; }
        .bg-sparkles span:nth-child(9) { left: 85%; --i: 9; }
        .bg-sparkles span:nth-child(10) { left: 30%; --i: 10; }
        .bg-sparkles span:nth-child(11) { left: 60%; --i: 11; }
        .bg-sparkles span:nth-child(12) { left: 90%; --i: 12; }

        /* Contenedor (tarjeta) con glass y brillo */
        .login-container {
            max-width: 420px;
            margin: 100px auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 14px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.10), 0 0 0 1px rgba(255, 215, 0, 0.10) inset, 0 0 24px rgba(255, 215, 0, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }
        .login-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #bfa100, var(--secondary-color));
        }
        .login-container::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(closest-side, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0) 70%);
            transform: rotate(15deg);
            animation: sheen 6s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes sheen {
            0% { transform: translateX(-20%) translateY(-10%) rotate(15deg); opacity: 0.25; }
            50% { transform: translateX(10%) translateY(0%) rotate(15deg); opacity: 0.4; }
            100% { transform: translateX(40%) translateY(10%) rotate(15deg); opacity: 0.25; }
        }

        /* Inputs de contraseña */
        .password-input { position: relative; }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--secondary-color);
        }
        .login-container .text-muted { color: var(--primary-color) !important; font-weight: 500; transition: color 0.3s ease; }
        .login-container .text-muted:hover { color: #bfa100 !important; }

        /* Botón principal con brillo */
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: #000;
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background: var(--primary-color);
            color: #000;
            box-shadow: 0 8px 22px rgba(255, 215, 0, 0.35), 0 0 0 1px rgba(255, 215, 0, 0.35) inset;
            transform: translateY(-1px);
        }
        .btn-primary::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(120deg, rgba(255,255,255,0), rgba(255,255,255,0.55), rgba(255,255,255,0));
            transform: skewX(-20deg);
            animation: btnShine 3.2s ease-in-out infinite;
        }
        @keyframes btnShine {
            0% { left: -60%; }
            60% { left: 120%; }
            100% { left: 120%; }
        }

        /* Modo oscuro */
        [data-theme="dark"] body {
            background:
                radial-gradient(800px 400px at -10% -10%, rgba(255, 215, 0, 0.12) 0%, rgba(255, 215, 0, 0) 60%),
                radial-gradient(700px 420px at 110% 110%, rgba(108, 117, 125, 0.15) 0%, rgba(108, 117, 125, 0) 60%),
                linear-gradient(180deg, #0f0f0f 0%, #0b0b0b 100%) !important;
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] body::after {
            background-image:
                linear-gradient(transparent calc(100% - 1px), rgba(255, 215, 0, 0.05) 1px),
                linear-gradient(90deg, transparent calc(100% - 1px), rgba(255, 215, 0, 0.05) 1px);
            mix-blend-mode: normal;
        }
        [data-theme="dark"] .bg-aurora {
            background:
                radial-gradient(60% 80% at 10% 10%, rgba(255, 215, 0, 0.12), transparent 60%),
                radial-gradient(60% 80% at 90% 90%, rgba(108, 117, 125, 0.12), transparent 60%),
                radial-gradient(50% 60% at 70% 20%, rgba(255, 255, 255, 0.04), transparent 60%);
            filter: saturate(120%);
        }
        [data-theme="dark"] .bg-sparkles span {
            box-shadow: 0 0 16px rgba(255, 215, 0, 0.75), 0 0 30px rgba(255, 215, 0, 0.45);
        }
        [data-theme="dark"] .login-container {
            background: rgba(15, 15, 15, 0.65) !important;
            color: #f1f1f1 !important;
            border: 1px solid rgba(255, 215, 0, 0.25) !important;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 215, 0, 0.12) inset !important;
            backdrop-filter: blur(10px);
        }
        [data-theme="dark"] .form-control {
            background: #121212 !important;
            color: #f1f1f1 !important;
            border-color: var(--primary-color) !important;
        }
        [data-theme="dark"] .form-control:focus {
            background: #121212 !important;
            color: #fff !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 2px var(--primary-color) !important;
        }
        [data-theme="dark"] .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)) !important;
            color: #000 !important;
            border: 1px solid var(--primary-color) !important;
        }
        [data-theme="dark"] .btn-primary:hover {
            background: var(--primary-color) !important;
            color: #000 !important;
        }
        [data-theme="dark"] .alert-danger {
            background: #121212 !important;
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        [data-theme="dark"] .theme-switch {
            background: #1a1a1a !important;
            color: var(--primary-color) !important;
        }

        /* Animación para mensaje de éxito */
        @keyframes successPulse {
            0% { transform: scale(1); box-shadow: 0 0 20px rgba(40, 167, 69, 0.3); }
            50% { transform: scale(1.02); box-shadow: 0 0 30px rgba(40, 167, 69, 0.5); }
            100% { transform: scale(1); box-shadow: 0 0 20px rgba(40, 167, 69, 0.3); }
        }

        /* Botón de tema */
        .theme-switch {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 9999 !important;
            background: rgba(0,0,0,0.9);
            color: #fff;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }
        .theme-switch:hover {
            background: #000;
            color: var(--primary-color);
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="bg-aurora"></div>
    <div class="bg-sparkles" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="theme-switch" id="themeSwitch" title="Modo claro/oscuro">
        <i class="fas fa-moon"></i>
    </div>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">
                <i class="fas fa-bolt me-2"></i>EcoVolt
            </h2>

            <?php if (session()->get('exito')): ?>
                <div class="alert alert-success alert-dismissible fade show" style="
                    background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(32, 201, 151, 0.2) 100%);
                    border: 2px solid #28a745;
                    color: #28a745;
                    font-weight: 600;
                    box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
                    animation: successPulse 2s ease-in-out;
                ">
                    <i class="fas fa-check-circle me-2"></i><?= session()->get('exito') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" style="
                    background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(253, 126, 20, 0.2) 100%);
                    border: 2px solid #dc3545;
                    color: #dc3545;
                    font-weight: 600;
                    box-shadow: 0 0 20px rgba(220, 53, 69, 0.3);
                ">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= session()->get('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('actualizar-contrasena') ?>" method="post" id="formCambioContrasena">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="codigo" class="form-label"><?= lang('App.verification_code') ?></label>
                    <input type="text" class="form-control" id="codigo" name="codigo" required 
                           value="<?= old('codigo') ?>">
                    <small class="text-muted"><?= lang('App.enter_code_email') ?></small>
                </div>

                <div class="mb-3 password-input">
                    <label for="nueva_contrasena" class="form-label"><?= lang('App.new_password') ?></label>
                    <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('nueva_contrasena')"></i>
                    <small class="text-muted"><?= lang('App.password_hint') ?></small>
                </div>

                <div class="mb-3 password-input">
                    <label for="confirmar_contrasena" class="form-label"><?= lang('App.confirm_password') ?></label>
                    <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('confirmar_contrasena')"></i>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i><?= lang('App.change_password_btn') ?>
                    </button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="<?= base_url('autenticacion/login') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i><?= lang('App.back_login') ?>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para modo claro/oscuro (consistente con login/correo)
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
