<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Panel de Supervisor</h1>

    <!-- Tarjetas de resumen -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($usuarios) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Total Administradores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($admins) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
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
                                Total Supervisores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($supervisores) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Dispositivos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalDispositivos ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-microchip fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos usuarios registrados -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Últimos Usuarios Registrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosUsuarios as $usuario): ?>
                        <tr>
                            <td><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></td>
                            <td><?= esc($usuario['email']) ?></td>
                            <td>
                                <span class="badge <?= $usuario['id_rol'] == 1 ? 'bg-success' : ($usuario['id_rol'] == 2 ? 'bg-info' : 'bg-warning') ?>">
                                    <?= $usuario['nombre_rol'] ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('supervisor/dispositivosUsuarios/' . $usuario['id_usuario']) ?>" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-microchip"></i> Ver Dispositivos
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 