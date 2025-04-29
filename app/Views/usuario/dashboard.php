<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid">
    <!-- Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="h3 text-gray-800">Bienvenido, <?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></h1>
                    <p class="text-muted">Email: <?= esc($usuario['email']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Consumo 24h</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($consumo24h ?? 0, 2) ?> kWh</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bolt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Consumo Promedio Diario</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($consumoPromedio ?? 0, 2) ?> kWh</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Dispositivos Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($dispositivos ?? []) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plug fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('dispositivo/agregar') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Agregar Dispositivo
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('perfil/perfil') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-user"></i> Ver Perfil
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('usuario/cambiarContrasena') ?>" class="btn btn-warning btn-block">
                                <i class="fas fa-key"></i> Cambiar Contraseña
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('home/manual') ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-book"></i> Manual de Usuario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de dispositivos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Dispositivos</h6>
                    <a href="<?= base_url('dispositivo/agregar') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Dispositivo
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($dispositivos)): ?>
                        <div class="alert alert-info">
                            No tienes dispositivos registrados. 
                            <a href="<?= base_url('dispositivo/agregar') ?>" class="alert-link">Agrega tu primer dispositivo</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                        <th>Consumo Actual</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dispositivos as $dispositivo): ?>
                                    <tr>
                                        <td><?= esc($dispositivo['nombre']) ?></td>
                                        <td>
                                            <span class="badge <?= $dispositivo['estado'] ? 'badge-success' : 'badge-danger' ?>">
                                                <?= $dispositivo['estado'] ? 'Activo' : 'Inactivo' ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($dispositivo['consumo_actual'] ?? 0, 2) ?> kWh</td>
                                        <td>
                                            <a href="<?= base_url('energia/verDatos/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-chart-bar"></i> Ver Consumo
                                            </a>
                                            <a href="<?= base_url('dispositivo/eliminar/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿Estás seguro de eliminar este dispositivo?')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para inicializar DataTables -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            }
        });
    });
</script>
<?= $this->endSection() ?> 