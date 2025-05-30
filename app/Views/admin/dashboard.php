<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Panel de Administración</h1>
    
    <div class="row mt-4">
        <!-- Tarjeta de Dispositivos -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Dispositivos</h4>
                            <p class="mb-0">Gestiona tus dispositivos ESP32</p>
                        </div>
                        <i class="fas fa-microchip fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('admin/dispositivos') ?>">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Usuarios -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Usuarios</h4>
                            <p class="mb-0">Administra los usuarios del sistema</p>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('admin/gestionarUsuarios') ?>">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Invitaciones -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Invitaciones</h4>
                            <p class="mb-0">Invita nuevos usuarios</p>
                        </div>
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('admin/invitar') ?>">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Energía -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Energía</h4>
                            <p class="mb-0">Monitorea el consumo</p>
                        </div>
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('energia') ?>">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-bolt me-1"></i>
                    Acciones Rápidas
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Buscar Dispositivos
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('admin/dispositivos/registrar') ?>" class="btn btn-success w-100">
                                <i class="fas fa-plus me-1"></i> Registrar Dispositivo
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('admin/invitar') ?>" class="btn btn-warning w-100">
                                <i class="fas fa-user-plus me-1"></i> Invitar Usuario
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('energia') ?>" class="btn btn-danger w-100">
                                <i class="fas fa-chart-line me-1"></i> Ver Consumo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos Dispositivos -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-microchip me-1"></i>
                    Últimos Dispositivos Registrados
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($ultimosDispositivos) && !empty($ultimosDispositivos)): ?>
                                    <?php foreach ($ultimosDispositivos as $dispositivo): ?>
                                        <tr>
                                            <td><?= esc($dispositivo['nombre']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $dispositivo['estado'] === 'activo' ? 'success' : ($dispositivo['estado'] === 'pendiente' ? 'warning' : 'danger') ?>">
                                                    <?= ucfirst($dispositivo['estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No hay dispositivos registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>