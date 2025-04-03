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
            background: linear-gradient(rgba(0, 102, 204, 0.8), rgba(0, 51, 153, 0.8)), url('/assets/img/energy-bg.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 147, 233, 0.89);
        }
        .section {
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 115, 230, 0.7);
            background-color: #e0f2ff;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.92);
        }
        .pricing-section {
            background-color: #e0f2ff;
        }
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #0066cc;
        }
        .navbar-nav .nav-link {
            font-size: 1.1rem;
            transition: color 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: #0066cc;
        }
        .btn-outline-light {
            border-color: #0066cc;
            color: #0066cc;
            transition: background-color 0.3s, color 0.3s;
        }
        .btn-outline-light:hover {
            background-color: #0066cc;
            color: white;
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
                        <a class="nav-link" href="#proyecto">Nuestro Proyecto </a>
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
                        <a class="btn btn-outline-light" href="<?php echo base_url('autenticacion/login'); ?>">Iniciar Sesión</a>
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
                    <img src="https://media.istockphoto.com/id/2190044172/es/vector/perfil-de-la-cuenta-l%C3%ADnea-fina-e-icono-de-glifo-s%C3%B3lido-trazo-editable-y-p%C3%ADxel-perfecto.jpg?s=612x612&w=0&k=20&c=_GBUr_rXvNRGdqhvQt5luPXnCpXXUVHFUKtTYvf51I8=" alt="Nuestro Equipo" class="img-fluid rounded">
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
                    <p>El cambio climático y el consumo excesivo de energía son desafíos globales que impactan nuestras ciudades hoy. Creemos que el cambio empieza localmente. Por eso, desarrollamos una solución que empodera a las personas para controlar su consumo energético y contribuir activamente a la sostenibilidad urbana y del planeta.</p>
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
                    <img src="https://resoluciondeproblema.files.wordpress.com/2013/03/autodisciplina-es-clave-lograr-el-exito-l-5ows2y.jpeg?w=300&h=225" alt="Manual Preview" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Comprar -->
    <section id="comprar" class="section pricing-section">
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
                                <a href="<?= base_url('compra') ?>" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card"></i> Comprar Ahora
                                </a>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 