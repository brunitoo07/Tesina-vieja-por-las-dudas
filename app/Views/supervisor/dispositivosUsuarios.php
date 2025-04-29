<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dispositivos de Usuarios Invitados</h1>

    <?php foreach ($usuarios as $usuario): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Dispositivos de <?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
            </h6>
        </div>
        <div class="card-body">
            <?php if (isset($dispositivos[$usuario['id_usuario']]) && !empty($dispositivos[$usuario['id_usuario']])): ?>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Última Lectura</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dispositivos[$usuario['id_usuario']] as $dispositivo): ?>
                        <tr>
                            <td><?= esc($dispositivo['id_dispositivo']) ?></td>
                            <td><?= esc($dispositivo['nombre']) ?></td>
                            <td>
                                <span class="badge <?= $dispositivo['estado'] == 'activo' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ucfirst($dispositivo['estado']) ?>
                                </span>
                            </td>
                            <td><?= $dispositivo['ultima_lectura'] ? date('d/m/Y H:i', strtotime($dispositivo['ultima_lectura'])) : 'Sin lecturas' ?></td>
                            <td>
                                <a href="<?= base_url('consumo/ver/' . $dispositivo['id_dispositivo']) ?>" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-chart-line"></i> Ver Consumo
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                Este usuario no tiene dispositivos registrados.
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?> 