<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Monitoreo de Energía</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/assets/img/energy-bg.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            color: white;
        }
        .section {
            padding: 80px 0;
        }
        .feature-card {
            transition: transform 0.3s;
        } 
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .paypal-section {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">EcoVolt</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#quienes-somos">Quiénes Somos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#proyecto">Nuestro Proyecto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#por-que">Por qué lo hicimos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#manual">Manual</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#comprar">Comprar</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-light" href="autenticacion/login">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4">Monitoreo Inteligente de Energía</h1>
            <p class="lead">Controla y optimiza el consumo de energía en tu hogar</p>
            <a href="#comprar" class="btn btn-primary btn-lg">Comprar Ahora</a>
        </div>
    </section>

    <!-- Quiénes Somos -->
    <section id="quienes-somos" class="section">
        <div class="container">
            <h2 class="text-center mb-5">Quiénes Somos</h2>
            <div class="row">
                <div class="col-md-6">
                    <p>Somos un equipo apasionado por la tecnología y la sostenibilidad, comprometidos con el desarrollo de soluciones innovadoras para el monitoreo y control del consumo de energía.</p>
                    <p>Nuestra misión es proporcionar herramientas accesibles y eficientes para que cada hogar pueda contribuir a un futuro más sostenible.</p>
                </div>
                <div class="col-md-6">
                    <img src="/assets/img/team.jpg" alt="Nuestro Equipo" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Nuestro Proyecto -->
    <section id="proyecto" class="section bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Nuestro Proyecto</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card feature-card mb-4">
                        <div class="card-body text-center">
                            <i class="fas fa-bolt fa-3x mb-3 text-primary"></i>
                            <h3>Monitoreo en Tiempo Real</h3>
                            <p>Visualiza el consumo de energía de tu hogar en tiempo real a través de nuestra plataforma intuitiva.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card mb-4">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-3x mb-3 text-primary"></i>
                            <h3>Análisis de Datos</h3>
                            <p>Obtén insights detallados sobre tus patrones de consumo y oportunidades de ahorro.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card mb-4">
                        <div class="card-body text-center">
                            <i class="fas fa-mobile-alt fa-3x mb-3 text-primary"></i>
                            <h3>Control Remoto</h3>
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
            <h2 class="text-center mb-5">Por qué lo hicimos</h2>
            <div class="row">
                <div class="col-md-6">
                    <h3>Nuestra Motivación</h3>
                    <p>El cambio climático y el consumo excesivo de energía son desafíos globales que requieren acción inmediata. Nos propusimos crear una solución que permita a las personas tomar el control de su consumo de energía y contribuir activamente a la sostenibilidad del planeta.</p>
                </div>
                <div class="col-md-6">
                    <h3>Nuestro Impacto</h3>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Reducción del consumo de energía</li>
                        <li><i class="fas fa-check text-success me-2"></i>Conciencia ambiental</li>
                        <li><i class="fas fa-check text-success me-2"></i>Ahorro económico</li>
                        <li><i class="fas fa-check text-success me-2"></i>Comodidad y control</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Manual -->
    <section id="manual" class="section bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Manual de Usuario</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3>Primeros Pasos</h3>
                            <ol>
                                <li>Registro de cuenta</li>
                                <li>Configuración del dispositivo</li>
                                <li>Asignación de roles</li>
                            </ol>
                            <a href="home/manual" class="btn btn-primary">Ver Manual Completo</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="/assets/img/manual-preview.jpg" alt="Manual Preview" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Comprar -->
    <section id="comprar" class="section paypal-section">
        <div class="container">
            <h2 class="text-center mb-5">Adquiere Nuestro Proyecto</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3>Plan Premium</h3>
                            <p class="lead">$99.99</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Monitoreo ilimitado</li>
                                <li><i class="fas fa-check text-success me-2"></i>Soporte prioritario</li>
                                <li><i class="fas fa-check text-success me-2"></i>Actualizaciones gratuitas</li>
                                <li><i class="fas fa-check text-success me-2"></i>Acceso como administrador</li>
                            </ul>
                            <div class="mt-4">
                                <a href="<?= base_url('compra') ?>" class="btn btn-primary btn-lg">Comprar Ahora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 EcoVolt. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID&currency=MXN"></script>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '99.99'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Redirigir al registro después de la compra exitosa
                    window.location.href = '<?= base_url('autenticacion/register') ?>?purchase=true';
                });
            }
        }).render('#paypal-button-container');
        <script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '99.99'
                        <!-- Sección Comprar -->
<section id="comprar" class="section paypal-section">
    <div class="container">
        <h2 class="text-center mb-5">Adquiere Nuestro Proyecto</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h3>Plan Premium</h3>
                        <p class="lead">$99.99</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i> Monitoreo ilimitado</li>
                            <li><i class="fas fa-check text-success me-2"></i> Soporte prioritario</li>
                            <li><i class="fas fa-check text-success me-2"></i> Actualizaciones gratuitas</li>
                            <li><i class="fas fa-check text-success me-2"></i> Acceso como administrador</li>
                        </ul>
                        
                        <!-- Botón de pago de PayPal -->
                        <div id="paypal-button-container"></div>

                        <!-- Botón adicional para ver la compra -->
                        <a href="<?= base_url('compra/completada') ?>" class="btn btn-success mt-3">
                            Ver detalles de la compra
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Redirigir a la vista de compra completada
                window.location.href = '<?= base_url('compra/completada') ?>';
            });
        }
    }).render('#paypal-button-container');
</script>

    </script>
</body>
</html> 