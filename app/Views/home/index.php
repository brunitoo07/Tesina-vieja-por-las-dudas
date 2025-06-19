<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Monitoreo de Energía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <style>
        :root {
            --primary-color: #0066cc;
            --secondary-color: #00cc66;
            --accent-color: #ff6b6b;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --transition-speed: 0.3s;
        }

        [data-theme="dark"] {
            --primary-color: #4a90e2;
            --secondary-color: #50e3c2;
            --accent-color: #ff6b6b;
            --dark-color: #1a1a1a;
            --light-color: #23272b;
            background: #181a1b !important;
            color: #f1f1f1 !important;
        }

        [data-theme="dark"] body {
            background: #181a1b !important;
            color: #f1f1f1 !important;
        }

        [data-theme="dark"] .container,
        [data-theme="dark"] .section,
        [data-theme="dark"] .order-details,
        [data-theme="dark"] .card,
        [data-theme="dark"] .feature-card,
        [data-theme="dark"] .pricing-card {
            background: #23272b !important;
            color: #f1f1f1 !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.5) !important;
        }

        [data-theme="dark"] .navbar,
        [data-theme="dark"] .navbar.scrolled {
            background: #181a1b !important;
            color: #f1f1f1 !important;
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

        [data-theme="dark"] .card-body,
        [data-theme="dark"] .card-header {
            background: #23272b !important;
            color: #f1f1f1 !important;
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

        [data-theme="dark"] .success-header {
            background: linear-gradient(135deg, #23272b 0%, #181a1b 100%) !important;
            color: #ffd700 !important;
        }

        [data-theme="dark"] .theme-switch {
            background: #23272b !important;
            color: #ffd700 !important;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background-color var(--transition-speed);
        }

        .theme-switch {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 9999 !important;
            background: rgba(44,62,80,0.95);
            color: #fff;
            padding: 14px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .theme-switch:hover {
            background: #222;
            color: #ffd700;
            transform: scale(1.1);
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.9), rgba(0, 204, 102, 0.9)), url('/assets/img/energy-bg.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .section {
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .feature-card {
            transition: all var(--transition-speed) ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background: white;
            height: 100%;
            position: relative;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .feature-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: transform var(--transition-speed);
        }

        .feature-card:hover i {
            transform: scale(1.2);
        }

        .navbar {
            background: rgba(44, 62, 80, 0.95) !important;
            backdrop-filter: blur(10px);
            transition: all var(--transition-speed) ease;
        }

        .navbar.scrolled {
            background: var(--dark-color) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all var(--transition-speed);
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-speed);
            z-index: 1000;
        }

        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .scroll-to-top:hover {
            transform: translateY(-5px);
        }

        .section-title {
            position: relative;
            margin-bottom: 3rem;
            text-align: center;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
            }
        }

        .feature-card .card-body {
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .feature-card .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity var(--transition-speed);
            z-index: 0;
        }

        .feature-card:hover .card-overlay {
            opacity: 0.1;
        }

        .pricing-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all var(--transition-speed) ease;
            position: relative;
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .pricing-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .pricing-card .price {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .floating-shapes .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-duration: 20s;
        }

        .floating-shapes .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 40%;
            right: 10%;
            animation-duration: 25s;
        }

        .floating-shapes .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 60%;
            left: 20%;
            animation-duration: 15s;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
    <script>
        // Script para modo claro/oscuro
        document.addEventListener('DOMContentLoaded', function() {
            let themeSwitch = document.getElementById('themeSwitch');
            if (!themeSwitch) {
                themeSwitch = document.createElement('div');
                themeSwitch.className = 'theme-switch';
                themeSwitch.id = 'themeSwitch';
                themeSwitch.title = 'Modo claro/oscuro';
                themeSwitch.innerHTML = '<i class="fas fa-moon"></i>';
                document.body.appendChild(themeSwitch);
            }
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let theme = localStorage.getItem('theme');
            if (!theme) {
                theme = prefersDark ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
            }
            document.documentElement.setAttribute('data-theme', theme);
            themeSwitch.innerHTML = theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            themeSwitch.onclick = function() {
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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">EcoVolt</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#quienes-somos"><?= lang('App.quienes_somos') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#proyecto"><?= lang('App.nuestro_proyecto') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#por-que"><?= lang('App.por_que') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#manual"><?= lang('App.manual') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#comprar"><?= lang('App.comprar') ?></a>
                    </li>
                    <?php $idioma = service('request')->getLocale(); ?>
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="idiomaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= base_url('imagenes/' . ($idioma === 'en' ? 'en' : 'es') . '.png') ?>" alt="Idioma" style="width:24px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="idiomaDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('cambiar-idioma/es') ?>">
                                    <img src="<?= base_url('imagenes/es.png') ?>" alt="Español" style="width:24px;" class="me-2"> Español
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?= base_url('cambiar-idioma/en') ?>">
                                    <img src="<?= base_url('imagenes/en.png') ?>" alt="English" style="width:24px;" class="me-2"> English
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-light" href="<?php echo base_url('autenticacion/login'); ?>"><?= lang('App.iniciar_sesion') ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="particles" id="particles-js"></div>
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <div class="container text-center hero-content" data-aos="fade-up">
            <h1 class="display-4 fw-bold mb-4"><?= lang('App.monitoreo_inteligente') ?></h1>
            <p class="lead mb-5"><?= lang('App.controla_optimiza') ?></p>
            <a href="#comprar" class="btn btn-primary btn-lg"><?= lang('App.comprar_ahora') ?></a>
        </div>
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down fa-2x"></i>
        </div>
    </section>

    <!-- Quiénes Somos -->
    <section id="quienes-somos" class="section">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up"><?= lang('App.quienes_somos') ?></h2>
            <div class="row align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <p class="lead">Somos un equipo apasionado por la tecnología y la sostenibilidad, comprometidos con el desarrollo de soluciones innovadoras para el monitoreo y control del consumo de energía.</p>
                    <p>Nuestra misión es proporcionar herramientas accesibles y eficientes para que cada hogar pueda contribuir a un futuro más sostenible.</p>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <img src="https://media.istockphoto.com/id/2190044172/es/vector/perfil-de-la-cuenta-l%C3%ADnea-fina-e-icono-de-glifo-s%C3%B3lido-trazo-editable-y-p%C3%ADxel-perfecto.jpg?s=612x612&w=0&k=20&c=_GBUr_rXvNRGdqhvQt5luPXnCpXXUVHFUKtTYvf51I8=" alt="Nuestro Equipo" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Nuestro Proyecto -->
    <section id="proyecto" class="section bg-light">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up"><?= lang('App.nuestro_proyecto') ?></h2>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card feature-card mb-4">
                        <div class="card-overlay"></div>
                        <div class="card-body text-center">
                            <i class="fas fa-bolt"></i>
                            <h3 class="mt-3">Monitoreo en Tiempo Real</h3>
                            <p>Visualiza el consumo de energía de tu hogar en tiempo real a través de nuestra plataforma intuitiva.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card feature-card mb-4">
                        <div class="card-overlay"></div>
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line"></i>
                            <h3 class="mt-3">Análisis de Datos</h3>
                            <p>Obtén insights detallados sobre tus patrones de consumo y oportunidades de ahorro.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card feature-card mb-4">
                        <div class="card-overlay"></div>
                        <div class="card-body text-center">
                            <i class="fas fa-mobile-alt"></i>
                            <h3 class="mt-3">Control Remoto</h3>
                            <p>Gestiona tus dispositivos y monitorea tu consumo desde cualquier lugar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Por qué lo hicimos -->
    <section id="por-que" class="section">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up"><?= lang('App.por_que') ?></h2>
            <div class="row">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="card feature-card h-100">
                        <div class="card-overlay"></div>
                        <div class="card-body">
                            <h3 class="mb-4">Nuestra Motivación</h3>
                            <p>El cambio climático y el consumo excesivo de energía son desafíos globales que impactan nuestras ciudades hoy. Creemos que el cambio empieza localmente. Por eso, desarrollamos una solución que empodera a las personas para controlar su consumo energético y contribuir activamente a la sostenibilidad urbana y del planeta.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="card feature-card h-100">
                        <div class="card-overlay"></div>
                        <div class="card-body">
                            <h3 class="mb-4">Nuestro Impacto</h3>
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Reducción del consumo de energía</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Conciencia ambiental</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Ahorro económico</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Comodidad y control</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Manual -->
    <section id="manual" class="section bg-light">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up"><?= lang('App.manual_usuario') ?></h2>
            <div class="row align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="card feature-card h-100">
                        <div class="card-overlay"></div>
                        <div class="card-body">
                            <h3 class="mb-4"><?= lang('App.primeros_pasos') ?></h3>
                            <ol class="mb-4">
                                <li class="mb-2"><?= lang('App.registro_cuenta') ?></li>
                                <li class="mb-2"><?= lang('App.configuracion_dispositivo') ?></li>
                                <li class="mb-2"><?= lang('App.asignacion_roles') ?></li>
                            </ol>
                            <a href="home/manual" class="btn btn-primary"><?= lang('App.ver_manual') ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <img src="https://resoluciondeproblema.files.wordpress.com/2013/03/autodisciplina-es-clave-lograr-el-exito-l-5ows2y.jpeg?w=300&h=225" alt="Manual Preview" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Comprar -->
    <section id="comprar" class="section">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up"><?= lang('App.adquiere_proyecto') ?></h2>
            <div class="row justify-content-center">
                <div class="col-md-6" data-aos="zoom-in">
                    <div class="card pricing-card">
                        <div class="card-body text-center">
                            <h3 class="mb-4"><?= lang('App.plan_premium') ?></h3>
                            <p class="price mb-4">$99.99</p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><?= lang('App.monitoreo_ilimitado') ?></li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><?= lang('App.soporte_prioritario') ?></li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><?= lang('App.actualizaciones_gratis') ?></li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i><?= lang('App.acceso_admin') ?></li>
                            </ul>
                            <a href="<?= base_url('registro-compra') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i> <?= lang('App.comprar_ahora_btn') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 EcoVolt. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Theme Switch
        const themeSwitch = document.getElementById('themeSwitch');
        const body = document.body;
        const icon = themeSwitch.querySelector('i');

        themeSwitch.addEventListener('click', () => {
            body.setAttribute('data-theme', body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
            icon.classList.toggle('fa-moon');
            icon.classList.toggle('fa-sun');
        });

        // Navbar Scroll Effect
        $(window).scroll(function() {
            if ($(window).scrollTop() > 50) {
                $('.navbar').addClass('scrolled');
            } else {
                $('.navbar').removeClass('scrolled');
            }
        });

        // Scroll to Top Button
        const scrollToTop = document.querySelector('.scroll-to-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTop.classList.add('visible');
            } else {
                scrollToTop.classList.remove('visible');
            }
        });

        scrollToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Particles.js Configuration
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: '#ffffff'
                },
                shape: {
                    type: 'circle'
                },
                opacity: {
                    value: 0.5,
                    random: true
                },
                size: {
                    value: 3,
                    random: true
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#ffffff',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: true,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'grab'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                }
            },
            retina_detect: true
        });

        // Smooth Scroll for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html> 