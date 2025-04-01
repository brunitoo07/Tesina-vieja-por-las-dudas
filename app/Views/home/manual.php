<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario - Sistema de Monitoreo de Energía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .manual-section {
            padding: 80px 0;
        }
        .step-card {
            border-left: 4px solid #007bff;
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        .step-card:hover {
            transform: translateX(10px);
        }
        .step-number {
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">EcoMonitor</a>
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
            <p>&copy; 2024 EcoMonitor. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 