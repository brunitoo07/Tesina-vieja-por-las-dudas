<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - EcoMonitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #3498db;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-leaf me-2"></i>EcoMonitor
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('admin') ?>">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/gestionarUsuarios') ?>">
                            <i class="fas fa-users me-1"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/invitar') ?>">
                            <i class="fas fa-user-plus me-1"></i> Invitar Usuario
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('cerrarSesion') ?>">
                            <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tachometer-alt me-2"></i>Panel de Administración</h2>
            <div class="text-muted">
                <?= date('d/m/Y H:i') ?>
            </div>
        </div>
        
        <?php if (session()->get('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->get('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->get('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Tarjeta de Usuarios -->
            <div class="col-md-4">
                <div class="card admin-card border-primary">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">Usuarios Registrados</h5>
                        <p class="stat-number">
                            <?= isset($usuarios) ? count($usuarios) : 0 ?>
                        </p>
                        <p class="text-muted">Total de usuarios en el sistema</p>
                        <a href="<?= base_url('admin/gestionarUsuarios') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-cog me-1"></i> Gestionar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Administradores -->
            <div class="col-md-4">
                <div class="card admin-card border-success">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h5 class="card-title">Administradores</h5>
                        <p class="stat-number">
                            <?= isset($admins) ? count($admins) : 0 ?>
                        </p>
                        <p class="text-muted">Usuarios con privilegios</p>
                        <a href="<?= base_url('admin/gestionarUsuarios/admin') ?>" class="btn btn-outline-success">
                            <i class="fas fa-list me-1"></i> Ver Lista
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Invitaciones -->
            <div class="col-md-4">
                <div class="card admin-card border-warning">
                    <div class="card-body text-center">
                        <div class="card-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h5 class="card-title">Invitar Usuario</h5>
                        <p class="text-muted">Agrega nuevos usuarios al sistema</p>
                        <a href="<?= base_url('admin/invitar') ?>" class="btn btn-warning">
                            <i class="fas fa-envelope me-1"></i> Enviar Invitación
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de últimos usuarios -->
        <?php if (!empty($ultimosUsuarios)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Últimos Usuarios Registrados</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Fecha Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ultimosUsuarios as $usuario): ?>
                                    <tr>
                                    <td><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></td>

                                        <td><?= esc($usuario['email']) ?></td>
                                        <td>
                                            <span class="badge <?= $usuario['id_rol'] == 1 ? 'bg-success' : 'bg-info' ?>">
                                                <?= $usuario['id_rol'] == 1 ? 'Administrador' : 'Usuario' ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>