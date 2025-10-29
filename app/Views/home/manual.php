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
                        <?php 
                        // Determinar la URL de inicio según el rol del usuario
                        if (session()->get('logged_in')) {
                            $rol = session()->get('rol');
                            if ($rol === 'admin') {
                                $inicioUrl = base_url('admin');
                            } elseif ($rol === 'supervisor') {
                                $inicioUrl = base_url('supervisor');
                            } else {
                                $inicioUrl = base_url('usuario');
                            }
                        } else {
                            $inicioUrl = base_url('/');
                        }
                        ?>
                        <a class="nav-link" href="<?= $inicioUrl ?>">Inicio</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Manual Content -->
    <div class="manual-section mt-5">
        <div class="container">
            <h1 class="text-center mb-5">Manual del Sistema EcoVolt</h1>
            
            <!-- Información general del sistema -->
            <div class="alert alert-info mb-4">
                <h5><i class="fas fa-info-circle me-2"></i>Acerca del Sistema EcoVolt</h5>
                <p class="mb-0">EcoVolt es un sistema de monitoreo inteligente de energía que permite gestionar dispositivos ESP32, supervisar consumo en tiempo real y recibir alertas automáticas. El sistema está diseñado para administradores, supervisores y usuarios finales.</p>
            </div>
            
            <!-- Registro de Cuenta -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">1</div>
                    <h3>Registro y Activación de Cuenta</h3>
                    <p>Para comenzar a usar el sistema EcoVolt, sigue estos pasos:</p>
                    

                    <h5><i class="fas fa-shopping-cart me-2"></i>Registro por Compra (EcoVolt Pro)</h5>
                    <ol>
                        <li><strong>Compra del producto:</strong> Completa el proceso de compra del dispositivo EcoVolt Pro</li>
                        <li><strong>Registro automático:</strong> El sistema crea tu cuenta automáticamente durante la compra</li>
                        <li><strong>Activación inmediata:</strong> Tu cuenta queda activa al confirmar el pago</li>
                        <li><strong>Email de confirmación:</strong> Recibirás un email con los detalles de tu compra y soporte premium</li>
                    </ol>

                    <h5><i class="fas fa-envelope me-2"></i>Registro por Invitación</h5>
                    <ol>
                        <li><strong>Recibir invitación:</strong> Un administrador te enviará un email de invitación</li>
                        <li><strong>Completar registro:</strong> Haz clic en el enlace del email y completa tus datos</li>
                        <li><strong>Activación inmediata:</strong> Tu cuenta se activa al completar el registro con el token</li>
                    </ol>

                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <strong>Importante:</strong> Todas las cuentas se activan automáticamente. No necesitas verificar por email para acceder al sistema.
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Tip:</strong> Puedes usar el ícono del ojo 👁️ junto al campo de contraseña para ver/ocultar lo que escribes.
                    </div>
                </div>
            </div>

            <!-- Configuración del Dispositivo -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">2</div>
                    <h3>Gestión de Dispositivos ESP32</h3>
                    <p>El sistema permite gestionar dispositivos ESP32 para monitoreo de energía según tu rol:</p>
                    
                    <h5><i class="fas fa-crown me-2"></i>Para Administradores</h5>
                    <ol>
                        <li><strong>Agregar dispositivo:</strong> Ve a "Dispositivos" → "Agregar Nuevo Dispositivo"</li>
                        <li><strong>Datos requeridos:</strong>
                            <ul>
                                <li>Nombre del dispositivo (ej: "Medidor Principal")</li>
                                <li>Dirección MAC del ESP32 (formato: AA:BB:CC:DD:EE:FF)</li>
                                <li>Ubicación del dispositivo</li>
                            </ul>
                        </li>
                        <li><strong>Gestión de usuarios:</strong> Puedes invitar usuarios y asignarles dispositivos</li>
                        <li><strong>Configuración global:</strong> Establece límites y alertas para todo el sistema</li>
                    </ol>

                    <h5><i class="fas fa-user me-2"></i>Para Usuarios</h5>
                    <ol>
                        <li><strong>Dashboard personal:</strong> Ve a tu panel principal para ver tus dispositivos</li>
                        <li><strong>Dispositivos disponibles:</strong>
                            <ul>

                                <li><strong>Dispositivos Compartidos:</strong> Los que tu administrador ha compartido contigo</li>
                            </ul>
                        </li>
                        <li><strong>Monitoreo:</strong> Haz clic en "Ver Consumo" para ver el historial de cada dispositivo</li>
                        <li><strong>Configuración personal:</strong> Establece tus propios límites de consumo</li>
                    </ol>

                    <h5><i class="fas fa-user-tie me-2"></i>Para Supervisores</h5>
                    <ol>
                        <li><strong>Monitoreo general:</strong> Supervisa el sistema y los usuarios bajo tu responsabilidad</li>
                        <li><strong>Gestión de alertas:</strong> Recibe notificaciones cuando se superen los límites</li>
                        <li><strong>Reportes:</strong> Genera reportes de consumo y estadísticas</li>
                    </ol>

                    <div class="alert alert-success">
                        <i class="fas fa-share-alt"></i> Los dispositivos compartidos aparecen con un icono de compartir y muestran el nombre del propietario.
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-microchip"></i> <strong>Importante:</strong> La dirección MAC del ESP32 debe estar en formato válido (ej: AA:BB:CC:DD:EE:FF) y el dispositivo debe estar conectado a la red WiFi.
                    </div>
                </div>
            </div>

            <!-- Gestión de Usuarios y Roles -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">3</div>
                    <h3>Gestión de Usuarios y Roles</h3>
                    <p>El sistema EcoVolt maneja tres tipos de roles con diferentes permisos:</p>
                    
                    <h5><i class="fas fa-crown me-2"></i>Administrador</h5>
                    <ul>
                        <li><strong>Acceso completo:</strong> Gestiona usuarios, dispositivos y configuraciones globales</li>
                        <li><strong>Invitar usuarios:</strong> Envía invitaciones por email para registrar nuevos usuarios</li>
                        <li><strong>Asignar roles:</strong> Puede crear administradores, supervisores y usuarios</li>
                        <li><strong>Gestión de dispositivos:</strong> Agrega, modifica y elimina dispositivos del sistema</li>
                        <li><strong>Configuración global:</strong> Establece límites y alertas para todo el sistema</li>
                    </ul>

                    <h5><i class="fas fa-user-tie me-2"></i>Supervisor</h5>
                    <ul>
                        <li><strong>Monitoreo:</strong> Supervisa el sistema y usuarios bajo su responsabilidad</li>
                        <li><strong>Gestión de alertas:</strong> Recibe y gestiona notificaciones del sistema</li>
                        <li><strong>Reportes:</strong> Genera reportes de consumo y estadísticas</li>
                        <li><strong>Dispositivos:</strong> Puede ver y gestionar dispositivos asignados</li>
                    </ul>

                    <h5><i class="fas fa-user me-2"></i>Usuario</h5>
                    <ul>
                        <li><strong>Dashboard personal:</strong> Accede a su panel personal con sus dispositivos</li>
                        <li><strong>Monitoreo:</strong> Ve el consumo de sus dispositivos en tiempo real</li>
                        <li><strong>Configuración personal:</strong> Establece límites de consumo para sus dispositivos</li>
                        <li><strong>Dispositivos compartidos:</strong> Accede a dispositivos compartidos por su administrador</li>
                    </ul>

                    <h5><i class="fas fa-envelope me-2"></i>Proceso de Invitación</h5>
                    <ol>
                        <li><strong>Enviar invitación:</strong> El administrador va a "Usuarios" → "Invitar Usuario"</li>
                        <li><strong>Datos requeridos:</strong> Email del nuevo usuario y rol asignado</li>
                        <li><strong>Email automático:</strong> El sistema envía un email con enlace de registro</li>
                        <li><strong>Registro del usuario:</strong> El usuario hace clic en el enlace y completa sus datos</li>
                        <li><strong>Activación inmediata:</strong> La cuenta se activa automáticamente al completar el registro</li>
                    </ol>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Solo los administradores pueden asignar roles y enviar invitaciones.
                    </div>
                </div>
            </div>

            <!-- Monitoreo de Consumo -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">4</div>
                    <h3>Monitoreo de Consumo en Tiempo Real</h3>
                    <p>El sistema EcoVolt monitorea automáticamente el consumo de energía a través de dispositivos ESP32:</p>
                    <ol>
                        <li><strong>Lecturas automáticas:</strong> Los ESP32 envían datos cada 5 segundos con:
                            <ul>
                                <li>Voltaje (V)</li>
                                <li>Corriente (A)</li>
                                <li>Potencia (W)</li>
                                <li>Consumo acumulado (kWh)</li>
                            </ul>
                        </li>
                        <li><strong>Visualización:</strong> Accede a "Energía" para ver:
                            <ul>
                                <li>Gráficos en tiempo real</li>
                                <li>Historial de consumo</li>
                                <li>Estadísticas diarias/mensuales</li>
                            </ul>
                        </li>
                        <li><strong>Límites de consumo:</strong> Configura alertas cuando se supere un umbral</li>
                        <li><strong>Exportación:</strong> Genera reportes PDF con los datos históricos</li>
                    </ol>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Notificaciones:</strong> El sistema envía alertas por email y Telegram cuando se supera el límite de consumo configurado.
                    </div>
                </div>
            </div>

            <!-- Configuración de Límites y Alertas -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">5</div>
                    <h3>Configuración de Límites y Alertas</h3>
                    <p>El sistema permite configurar límites de consumo y recibir notificaciones automáticas:</p>
                    <ol>
                        <li><strong>Configurar límite:</strong> Ve a "Energía" → "Configurar Límite"</li>
                        <li><strong>Establecer umbral:</strong> Define el consumo máximo en kWh (ej: 10 kWh)</li>
                        <li><strong>Notificaciones automáticas:</strong> El sistema envía alertas cuando se supera el límite:
                            <ul>
                                <li>Email al usuario propietario del dispositivo</li>
                                <li>Mensaje por Telegram (si está configurado)</li>
                                <li>Indicador visual en el dashboard</li>
                            </ul>
                        </li>
                        <li><strong>Control de frecuencia:</strong> Las notificaciones se envían máximo una vez por hora para evitar spam</li>
                    </ol>
                   
                </div>
            </div>

            <!-- Asistente Virtual -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">6</div>
                    <h3>Asistente Virtual Inteligente</h3>
                    <p>EcoVolt incluye un asistente virtual disponible 24/7 para ayudarte:</p>
                    <ol>
                        <li><strong>Acceso:</strong> Haz clic en el botón flotante 💬 en la esquina inferior derecha</li>
                        <li><strong>Funcionalidades disponibles:</strong>
                            <ul>
                                <li>Ayuda técnica y soporte</li>
                                <li>Información del proyecto EcoVolt</li>
                            </ul>
                        </li>
                        <li><strong>Botones de acción rápida:</strong> Usa los botones predefinidos para consultas comunes</li>
                        <li><strong>Chat inteligente:</strong> El asistente responde en tiempo real con información actualizada</li>
                    </ol>
                   
                </div>
            </div>

            <!-- Configuración de Telegram -->
            <div class="card step-card">
                <div class="card-body">
                    <div class="step-number">6</div>
                    <h3>Configuración de Notificaciones por Telegram</h3>
                    <p>EcoVolt permite recibir notificaciones personalizadas directamente en tu Telegram:</p>
                    
                    <h5><i class="fab fa-telegram me-2"></i>Configuración Paso a Paso</h5>
                    <ol>
                        <li><strong>Buscar el Bot:</strong> En Telegram, busca <strong>@EcoVoltBot</strong> o usa el enlace directo</li>
                        <li><strong>Iniciar Bot:</strong> Envía <code>/start</code> al bot para comenzar el proceso</li>
                        <li><strong>Registrar Cuenta:</strong> Envía <code>/registrar</code> para obtener tu código único</li>
                        <li><strong>Vincular en EcoVolt:</strong> Ve a "Configuración de Telegram" en tu panel y pega el código</li>
                        <li><strong>¡Listo!</strong> Recibirás notificaciones personalizadas de tus dispositivos</li>
                    </ol>

                    <h5><i class="fas fa-bell me-2"></i>Tipos de Notificaciones</h5>
                    <ul>
                        <li><strong>Alertas de Consumo:</strong> Cuando se supere el límite configurado</li>
                        <li><strong>Prealertas:</strong> Al llegar al 90% del límite establecido</li>
                        <li><strong>Cortes de Línea:</strong> Notificaciones de problemas del sistema</li>
                        <li><strong>Configuraciones:</strong> Confirmación de cambios en límites y dispositivos</li>
                    </ul>

                    <h5><i class="fas fa-cog me-2"></i>Gestión de Notificaciones</h5>
                    <ul>
                        <li><strong>Activar/Desactivar:</strong> Controla las notificaciones desde tu panel</li>
                        <li><strong>Probar Notificación:</strong> Envía una notificación de prueba para verificar la configuración</li>
                        <li><strong>Desvincular:</strong> Puedes desvincular tu cuenta de Telegram en cualquier momento</li>
                    </ul>

                    <div class="alert alert-info">
                        <i class="fab fa-telegram"></i> <strong>Personalizado:</strong> Cada usuario recibe solo las notificaciones de sus propios dispositivos y configuraciones.
                    </div>
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
                                    ¿Cómo funciona el registro y activación de cuentas?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <strong>Registro por Compra:</strong> Al comprar EcoVolt Pro, tu cuenta se crea y activa automáticamente.<br>
                                    <strong>Registro por Invitación:</strong> Recibes un email, haces clic en el enlace, te registras y tu cuenta se activa inmediatamente.<br>
                                 </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    ¿Cómo cambio mi contraseña?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <strong>Opción 1:</strong> Ve a "Perfil" > "Seguridad" y sigue las instrucciones.<br>
                                    <strong>Opción 2:</strong> En la página de login, haz clic en "¿Olvidaste tu contraseña?" y sigue el proceso de recuperación por email.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    ¿Qué hacer si el ESP32 no se conecta?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <ol>
                                        <li>Verifica que el ESP32 esté conectado a la misma red WiFi</li>
                                        <li>Confirma que la dirección MAC esté registrada correctamente en el sistema</li>
                                        <li>Contacta al asistente virtual para soporte técnico</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                       
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    ¿Puedo exportar mis datos de consumo?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sí, el sistema permite generar reportes PDF con tus datos de consumo. Ve a la sección "Energía" y busca la opción "Generar Reporte" para exportar los datos en formato PDF.
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