<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario - Sistema de Monitoreo de Energía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FFD700; /* gold */
            --secondary-color: #6c757d; /* gray */
            --dark-color:rgb(0, 0, 0); /* black */
            --light-color:rgb(255, 255, 255); /* white */
            --transition-speed: 0.3s;
        }

        /* Fondo base con gradientes (igual a otras vistas) */
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
        }

        /* Auroras animadas y destellos dorados */
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
            0% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-10px) scale(1.02); }
            100% { transform: translateY(0) scale(1); }
        }
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

        [data-theme="dark"] body {
            background: #0f0f0f !important;
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] .card {
            background: #1f1f1f !important;
            color: #f1f1f1 !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.5) !important;
        }
        [data-theme="dark"] .navbar {
            background: var(--dark-color) !important;
        }
        [data-theme="dark"] .accordion-button:not(.collapsed) {
            background-color: #121212;
            color: #f1f1f1;
        }

        .manual-section {
            padding: 80px 0;
        }
        .step-card {
            border-left: 4px solid var(--primary-color);
            margin-bottom: 30px;
            transition: transform 0.3s;
            background: rgba(255, 255, 255, 0.7);
            box-shadow: 0 12px 40px rgba(0,0,0,0.10), 0 0 0 1px rgba(255,215,0,0.10) inset, 0 0 24px rgba(255,215,0,0.12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .step-card:hover {
            transform: translateX(10px);
        }
        .step-number {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.95) !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
        }
        .navbar .navbar-brand,
        .navbar .nav-link:hover {
            color: var(--primary-color) !important;
        }
        .accordion-button:focus {
            box-shadow: 0 0 0 .2rem rgba(255,215,0,0.35);
            border-color: var(--primary-color);
        }
        .card {
            background: rgba(255, 255, 255, 0.7);
            box-shadow: 0 12px 40px rgba(0,0,0,0.10), 0 0 0 1px rgba(255,215,0,0.06) inset;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Modo oscuro para nuevas capas */
        [data-theme="dark"] .bg-aurora {
            background:
                radial-gradient(60% 80% at 10% 10%, rgba(255, 215, 0, 0.12), transparent 60%),
                radial-gradient(60% 80% at 90% 90%, rgba(108, 117, 125, 0.12), transparent 60%),
                radial-gradient(50% 60% at 70% 20%, rgba(255, 255, 255, 0.04), transparent 60%);
        }
        [data-theme="dark"] .bg-sparkles span {
            box-shadow: 0 0 16px rgba(255, 215, 0, 0.75), 0 0 30px rgba(255, 215, 0, 0.45);
        }
        [data-theme="dark"] .card,
        [data-theme="dark"] .step-card {
            background: rgba(15, 15, 15, 0.65) !important;
            color: #f1f1f1 !important;
            box-shadow: 0 12px 40px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,215,0,0.10) inset !important;
        }
    </style>
</head>
<body>
    <div class="bg-aurora"></div>
    <div class="bg-sparkles" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">EcoVolt S.R.L</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('home/index'); ?>">Inicio</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Manual Content -->
    <div class="manual-section mt-5">
        <div class="container">
            <h1 class="text-center mb-5">Manual de Usuario</h1>
            
            <!-- Registro de Cuenta -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">1</div>
                    <h3>Registro de Cuenta</h3>
                    <p>Para comenzar a usar el sistema, sigue estos pasos:</p>
                    <ol>
                        <li>Ingresa a la página principal y haz clic en "iniciar sesion",si no tienes cuenta 
                            registrate.
                        </li>
                        <li>Completa el formulario con tus datos personales</li>
                        <li>Verifica tu correo electrónico</li>
                        <li>Inicia sesión con tus credenciales</li>
                    </ol>
                </div>
            </div>

            <!-- Configuración del Dispositivo -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">2</div>
                    <h3>Configuración del Dispositivo</h3>
                    <p>Para agregar un nuevo dispositivo:</p>
                    <ol>
                        <li>Ve a la sección "Dispositivos" en el menú principal</li>
                        <li>Haz clic en "Agregar Nuevo Dispositivo"</li>
                        <li>Ingresa el ID único del dispositivo</li>
                        <li>Selecciona el tipo de medidor</li>
                        <li>Configura los parámetros de medición</li>
                        <li>Guarda la configuración</li>
                    </ol>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> El ID del dispositivo se encuentra en la etiqueta del medidor.
                    </div>
                </div>
            </div>

            <!-- Asignación de Roles -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">3</div>
                    <h3>Asignación de Roles</h3>
                    <p>Para gestionar usuarios y roles:</p>
                    <ol>
                        <li>Como administrador, ve a la sección "Usuarios"</li>
                        <li>Haz clic en "Invitar Usuario"</li>
                        <li>Ingresa el correo electrónico del nuevo usuario</li>
                        <li>Selecciona el rol (Admin o Usuario)</li>
                        <li>Envía la invitación</li>
                    </ol>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Solo los administradores pueden asignar roles.
                    </div>
                </div>
            </div>

            <!-- Monitoreo de Consumo -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">4</div>
                    <h3>Monitoreo de Consumo</h3>
                    <p>Para visualizar el consumo de energía:</p>
                    <ol>
                        <li>Accede a la sección "Consumo"</li>
                        <li>Selecciona el período de tiempo deseado</li>
                        <li>Visualiza los gráficos y estadísticas</li>
                        <li>Exporta los datos si es necesario</li>
                    </ol>
                </div>
            </div>

            <!-- Configuración de Alertas -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">5</div>
                    <h3>Configuración de Alertas</h3>
                    <p>Para configurar notificaciones:</p>
                    <ol>
                        <li>Ve a "Configuración" > "Alertas"</li>
                        <li>Establece los umbrales de consumo</li>
                        <li>Selecciona los métodos de notificación</li>
                        <li>Guarda la configuración</li>
                    </ol>
                </div>
            </div>

            <!-- Preguntas Frecuentes -->
            <div class="card mt-5">
                <div class="card-body">
                    <h3 class="text-center mb-4">Preguntas Frecuentes</h3>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    ¿Cómo cambio mi contraseña?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ve a "Perfil" > "Seguridad" y sigue las instrucciones para cambiar tu contraseña.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    ¿Qué hacer si el dispositivo no se conecta?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Verifica la conexión a internet, el ID del dispositivo y contacta a soporte si el problema persiste.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 EcoVolt S.R.L. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 