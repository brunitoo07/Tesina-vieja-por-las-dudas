<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url('imagenes/rayito.png'); ?>">
    <title>Página de Bienvenida</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        /* Estilos personalizados */
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                        url('<?= base_url('imagenes/bombilla.jpg'); ?>') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            color: #f8f9fa;
        }
        
        .navbar {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            border-bottom: 1px solid #ddd;
        }

        .navbar-brand {
            font-weight: bold;
            color: #007bff !important;
        }

        .navbar-nav .nav-item .nav-link {
            color: #343a40 !important;
            font-weight: 500;
        }

        .navbar-toggler {
            border-color: #007bff;
        }

        .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.9);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            color: #343a40;
        }

        h1 {
            color: #007bff;
            font-weight: bold;
        }

        .btn-primary, .btn-danger {
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger:hover {
            background-color: #d9534f;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación con menú desplegable -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Medidor de Energía</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('energia') ?>">Ver Consumo</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Opciones
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Inicio</a>
                        <a class="dropdown-item" href="<?= base_url('perfil/perfil') ?>">Perfil</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="<?= base_url('cerrarSesion') ?>">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container text-center mt-5">
        <h1>Bienvenido, <?= session()->get('userData')['nombre']; ?>!</h1>
        <p class="lead">Has iniciado sesión correctamente.</p>
        <p>Este es el sistema de gestión de consumo energético, donde puedes administrar usuarios, viviendas, medidores y lecturas de consumo.</p>
        <a href="<?= base_url('cerrarSesion') ?>" class="btn btn-danger mt-4">Cerrar Sesión</a>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
