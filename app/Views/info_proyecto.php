<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Completa del Proyecto - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #FFD700;
            --dark-color: #000000;
            --light-color: #ffffff;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .section {
            padding: 80px 0;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all var(--transition-speed) ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 20px;
        }

        .feature-icon {
            font-size: 3rem;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .tech-item {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -22px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 3px var(--primary-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all var(--transition-speed) ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
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
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
            }
        }

        .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-duration: 25s;
        }

        .shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 40%;
            right: 10%;
            animation-duration: 30s;
        }

        .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 60%;
            left: 20%;
            animation-duration: 20s;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <div class="container hero-content">
            <h1 class="display-4 fw-bold mb-4">🚀 EcoVolt - Sistema de Monitoreo de Energía</h1>
            <p class="lead mb-5">Información completa sobre nuestro proyecto innovador de monitoreo inteligente de consumo eléctrico</p>
            <a href="<?= base_url() ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-home me-2"></i> Volver al Inicio
            </a>
        </div>
    </section>

    <!-- Información General -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-info-circle me-2"></i> ¿Qué es EcoVolt?</h2>
                        </div>
                        <div class="card-body">
                            <p class="lead">EcoVolt es un sistema inteligente de monitoreo de consumo eléctrico en tiempo real que permite a los usuarios controlar y optimizar su uso de energía de manera eficiente.</p>
                            
                            <h4 class="mt-4">🎯 Objetivos Principales:</h4>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Reducir el consumo de energía eléctrica</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Crear conciencia sobre el uso eficiente de energía</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Proporcionar datos en tiempo real para la toma de decisiones</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Contribuir a la sostenibilidad ambiental</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Características Técnicas -->
    <section class="section bg-light">
        <div class="container">
            <h2 class="text-center mb-5">⚡ Características Técnicas</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <h4>Hardware</h4>
                            <p>Dispositivos ESP32 con sensores de energía integrados para medición precisa de voltaje, corriente y potencia.</p>
                            <div class="tech-stack">
                                <span class="tech-item">ESP32</span>
                                <span class="tech-item">Sensores de Energía</span>
                                <span class="tech-item">WiFi</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-server"></i>
                            </div>
                            <h4>Backend</h4>
                            <p>Servidor web robusto construido con PHP y CodeIgniter 4 para manejar datos y proporcionar APIs.</p>
                            <div class="tech-stack">
                                <span class="tech-item">PHP 8+</span>
                                <span class="tech-item">CodeIgniter 4</span>
                                <span class="tech-item">MySQL</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <h4>Frontend</h4>
                            <p>Interfaz web moderna y responsiva con gráficos en tiempo real y dashboard interactivo.</p>
                            <div class="tech-stack">
                                <span class="tech-item">HTML5</span>
                                <span class="tech-item">CSS3</span>
                                <span class="tech-item">JavaScript</span>
                                <span class="tech-item">Chart.js</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h4>Inteligencia Artificial</h4>
                            <p>Asistente virtual integrado con Botpress para consultas inteligentes y soporte automatizado.</p>
                            <div class="tech-stack">
                                <span class="tech-item">Botpress</span>
                                <span class="tech-item">NLP</span>
                                <span class="tech-item">Telegram API</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Funcionalidades -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">🔧 Funcionalidades Principales</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-chart-line text-primary me-2"></i>Monitoreo en Tiempo Real</h5>
                            <p class="card-text">Visualización instantánea del consumo de energía con gráficos interactivos y actualizaciones automáticas cada 5 segundos.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-bell text-warning me-2"></i>Sistema de Alertas</h5>
                            <p class="card-text">Notificaciones automáticas por Telegram cuando se superan los límites de consumo configurados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-users text-info me-2"></i>Gestión de Usuarios</h5>
                            <p class="card-text">Sistema de roles y permisos para administradores, supervisores y usuarios finales.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-database text-success me-2"></i>Base de Datos</h5>
                            <p class="card-text">Almacenamiento seguro de lecturas de energía, información de dispositivos y configuraciones de usuarios.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-mobile-alt text-primary me-2"></i>Responsive Design</h5>
                            <p class="card-text">Interfaz adaptada para dispositivos móviles, tablets y computadoras de escritorio.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-shield-alt text-danger me-2"></i>Seguridad</h5>
                            <p class="card-text">Autenticación segura, validación de datos y protección contra ataques comunes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Arquitectura del Sistema -->
    <section class="section bg-light">
        <div class="container">
            <h2 class="text-center mb-5">🏗️ Arquitectura del Sistema</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-sitemap me-2"></i> Flujo de Datos</h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <h5>1. Captura de Datos</h5>
                                    <p>Los dispositivos ESP32 miden continuamente voltaje, corriente y potencia eléctrica.</p>
                                </div>
                                <div class="timeline-item">
                                    <h5>2. Transmisión WiFi</h5>
                                    <p>Los datos se envían vía WiFi al servidor web en intervalos regulares.</p>
                                </div>
                                <div class="timeline-item">
                                    <h5>3. Procesamiento</h5>
                                    <p>El servidor procesa los datos, calcula kWh y verifica límites de consumo.</p>
                                </div>
                                <div class="timeline-item">
                                    <h5>4. Almacenamiento</h5>
                                    <p>Los datos se guardan en la base de datos MySQL para análisis histórico.</p>
                                </div>
                                <div class="timeline-item">
                                    <h5>5. Visualización</h5>
                                    <p>La interfaz web muestra los datos en tiempo real con gráficos interactivos.</p>
                                </div>
                                <div class="timeline-item">
                                    <h5>6. Notificaciones</h5>
                                    <p>Se envían alertas por Telegram cuando se superan los límites configurados.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas del Proyecto -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">📊 Estadísticas del Proyecto</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">5+</div>
                    <div class="stat-label">Dispositivos ESP32</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Lecturas de Energía</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Monitoreo Continuo</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Precisión de Medición</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Beneficios -->
    <section class="section bg-light">
        <div class="container">
            <h2 class="text-center mb-5">🎯 Beneficios del Sistema</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success"><i class="fas fa-leaf me-2"></i>Beneficios Ambientales</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Reducción del consumo de energía</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Conciencia ambiental</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Contribución a la sostenibilidad</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Reducción de la huella de carbono</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><i class="fas fa-dollar-sign me-2"></i>Beneficios Económicos</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>Ahorro en facturas de electricidad</li>
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>Optimización de recursos</li>
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>Detección de consumos anómalos</li>
                                <li class="mb-2"><i class="fas fa-check text-primary me-2"></i>ROI rápido del sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tecnologías Utilizadas -->
    <section class="section">
        <div class="container">
            <h2 class="text-center mb-5">🛠️ Stack Tecnológico</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fab fa-php fa-3x text-primary mb-3"></i>
                            <h5>PHP 8+</h5>
                            <p>Backend robusto y escalable</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-database fa-3x text-success mb-3"></i>
                            <h5>MySQL</h5>
                            <p>Base de datos relacional</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fab fa-js-square fa-3x text-warning mb-3"></i>
                            <h5>JavaScript</h5>
                            <p>Interactividad del frontend</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-microchip fa-3x text-info mb-3"></i>
                            <h5>ESP32</h5>
                            <p>Hardware de monitoreo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section bg-primary text-white">
        <div class="container text-center">
            <h2 class="mb-4">🚀 ¿Listo para comenzar?</h2>
            <p class="lead mb-4">Únete a la revolución del monitoreo inteligente de energía</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?= base_url('autenticacion/register') ?>" class="btn btn-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i> Registrarse
                </a>
                <a href="<?= base_url('autenticacion/login') ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 EcoVolt. Todos los derechos reservados.</p>
            <p class="mb-0">Sistema de Monitoreo de Energía Inteligente</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
