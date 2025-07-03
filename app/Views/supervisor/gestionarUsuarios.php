<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestión de Usuarios</h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Tabla de usuarios -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Usuarios</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Invitado por</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= esc($usuario['id_usuario']) ?></td>
                            <td><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></td>
                            <td><?= esc($usuario['email']) ?></td>
                            <td>
                                <form action="<?= base_url('supervisor/cambiarRol') ?>" method="post" class="d-inline">
                                    <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                                    <select name="nuevo_rol" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
                                        <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <?php if (!empty($usuario['nombre_admin'])): ?>
                                    <?= esc($usuario['nombre_admin'] . ' ' . $usuario['apellido_admin']) ?><br>
                                    <small><?= esc($usuario['email_admin']) ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('supervisor/dispositivosUsuarios/' . $usuario['id_usuario']) ?>" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-microchip"></i> Ver Dispositivos
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="eliminarUsuario(<?= $usuario['id_usuario'] ?>)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarUsuario(idUsuario) {
    if (confirm('¿Está seguro que desea eliminar este usuario?')) {
        fetch('<?= base_url('supervisor/eliminarUsuario') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_usuario=' + idUsuario
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar el usuario: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el usuario');
        });
    }
}
</script>

<?= $this->endSection() ?> 