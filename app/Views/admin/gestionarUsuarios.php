<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Gestión de Usuarios</h2>
        </div>
        <div class="col text-end">
            <a href="<?= base_url('admin/invitarUsuario') ?>" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Invitar Usuario
            </a>
        </div>
    </div>

    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id'] ?></td>
                            <td><?= $usuario['nombre'] ?></td>
                            <td><?= $usuario['apellido'] ?></td>
                            <td><?= $usuario['email'] ?></td>
                            <td>
                                <form action="<?= base_url('admin/cambiarRol') ?>" method="post" class="d-inline">
                                    <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
                                    <select name="rol" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                        <option value="usuario" <?= $usuario['rol'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form action="<?= base_url('admin/eliminarUsuario') ?>" method="post" class="d-inline" 
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                    <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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