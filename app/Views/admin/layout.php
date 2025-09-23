<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - EcoVolt</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --gold: #d4af37;
            --gold-600: #c39a2f;
            --gray-900: #111111;
            --gray-800: #1a1a1a;
            --gray-700: #2a2a2a;
            --gray-600: #343a40;
            --gray-500: #6c757d;
            --white: #ffffff;
            --black: #000000;
            --surface: #f5f5f5;
        }

        /* Fondo y tipografía base */
        body {
            background-color: var(--surface);
            color: var(--gray-900);
        }

        a { color: var(--gold); }
        a:hover { color: var(--gold-600); }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: var(--gray-900);
        }
        .sidebar .nav-link {
            color: var(--white);
            padding: 0.6rem 1rem;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        .sidebar .nav-link:hover {
            background-color: var(--gray-800);
            border-left-color: var(--gray-600);
            color: var(--white);
        }
        .sidebar .nav-link.active {
            background-color: var(--gray-800);
            border-left-color: var(--gold);
            color: var(--gold);
        }

        /* Área de contenido */
        .content {
            min-height: 100vh;
            background-color: var(--surface);
        }

        /* Cards */
        .card {
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
            border-radius: 0.5rem;
        }
        .card .card-header {
            background: var(--white);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            font-weight: 600;
        }

        /* Sobrescribir paleta contextual de Bootstrap a dorado/grises/negro */
        .bg-primary { background-color: var(--gold) !important; }
        .border-primary { border-color: var(--gold) !important; }
        .text-primary { color: var(--gold) !important; }

        .bg-success { background-color: var(--gray-600) !important; }
        .bg-warning { background-color: var(--gray-500) !important; }
        .bg-danger { background-color: var(--black) !important; }

        .badge.bg-success,
        .badge.bg-warning,
        .badge.bg-danger,
        .badge.bg-primary {
            color: var(--white) !important;
        }

        /* Botones principales en dorado y grises */
        .btn-primary {
            background-color: var(--gold);
            border-color: var(--gold);
            color: var(--black);
        }
        .btn-primary:hover { background-color: var(--gold-600); border-color: var(--gold-600); }

        .btn-secondary { background-color: var(--gray-600); border-color: var(--gray-600); }
        .btn-secondary:hover { background-color: var(--gray-700); border-color: var(--gray-700); }

        .btn-dark { background-color: var(--black); border-color: var(--black); }
        .btn-dark:hover { background-color: var(--gray-900); border-color: var(--gray-900); }

        .btn-outline-primary { color: var(--gold); border-color: var(--gold); }
        .btn-outline-primary:hover { background-color: var(--gold); color: var(--black); }

        /* Tablas */
        .table thead th {
            background-color: var(--black);
            color: var(--white);
            border-color: var(--black);
        }
        .table tbody tr:hover { background-color: rgba(0,0,0,0.03); }

        /* Utilidades */
        .text-gold { color: var(--gold) !important; }
        .bg-gold { background-color: var(--gold) !important; }
        .border-gold { border-color: var(--gold) !important; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">EcoVolt</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= current_url() == base_url('admin') ? 'active' : '' ?>" href="<?= base_url('admin') ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(current_url(), 'admin/dispositivos') !== false ? 'active' : '' ?>" href="<?= base_url('admin/dispositivos') ?>">
                                <i class="fas fa-microchip"></i> Dispositivos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(current_url(), 'admin/gestionarUsuarios') !== false ? 'active' : '' ?>" href="<?= base_url('admin/gestionarUsuarios') ?>">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(current_url(), 'admin/invitar') !== false ? 'active' : '' ?>" href="<?= base_url('admin/invitar') ?>">
                                <i class="fas fa-user-plus"></i> Invitar Usuario
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(current_url(), 'admin/dispositivos') !== false ? 'active' : '' ?>" href="<?= base_url('admin/dispositivos') ?>">
                                <i class="fas fa-bolt"></i> Energía
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(current_url(), 'usuario/perfil') !== false ? 'active' : '' ?>" href="<?= base_url('usuario/perfil') ?>">
                                <i class="fas fa-user-circle"></i> Mi Perfil
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link text-danger" href="<?= base_url('cerrarSesion') ?>">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 