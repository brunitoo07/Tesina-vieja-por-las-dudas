<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= lang('App.admin_panel') ?></h1>
    
    <div class="row mt-4">
        <!-- Tarjeta de Dispositivos -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><?= lang('App.devices') ?></h4>
                            <p class="mb-0"><?= lang('App.manage_esp32') ?></p>
                        </div>
                        <i class="fas fa-microchip fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('admin/dispositivos') ?>"><?= lang('App.view_details') ?></a>
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
                            <h4 class="mb-0"><?= lang('App.users') ?></h4>
                            <p class="mb-0"><?= lang('App.manage_users') ?></p>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('admin/gestionarUsuarios') ?>"><?= lang('App.view_details') ?></a>
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
                            <h4 class="mb-0"><?= lang('App.invitations') ?></h4>
                            <p class="mb-0"><?= lang('App.invite_new_users') ?></p>
                        </div>
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('admin/invitar') ?>"><?= lang('App.view_details') ?></a>
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
                            <p class="mb-0">Monitorea el consumo de tus dispositivos.</p>
                        </div>
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('admin/dispositivos') ?>">Ver Dispositivos</a>
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
                    <?= lang('App.quick_actions') ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> <?= lang('App.search_devices') ?>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('admin/dispositivos/registrar') ?>" class="btn btn-success w-100">
                                <i class="fas fa-plus me-1"></i> <?= lang('App.register_device') ?>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('admin/invitar') ?>" class="btn btn-warning w-100">
                                <i class="fas fa-user-plus me-1"></i> <?= lang('App.invite_user') ?>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-danger w-100">
                                <i class="fas fa-chart-line me-1"></i> Ver Dispositivos
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
                    <?= lang('App.latest_devices') ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?= lang('App.name') ?></th>
                                    <th><?= lang('App.status') ?></th>
                                    <th><?= lang('App.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($ultimosDispositivos)): ?>
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
                                        <td colspan="3" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <?= lang('App.no_devices') ?>
                                                <a href="<?= base_url('admin/dispositivos/registrar') ?>" class="alert-link">
                                                    <?= lang('App.register_first_device') ?>
                                                </a>
                                            </div>
                                        </td>
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