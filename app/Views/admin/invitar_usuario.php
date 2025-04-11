<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container mt-4">
    <?php if (isset($isAdmin) && $isAdmin): ?>
        <div class="card">
            <div class="card-header">
                <h2>Invitar Usuario</h2>
            </div>
            <div class="card-body">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('exito')): ?>
                    <div class="alert alert-success">
                        <?= session('exito') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/enviarInvitacion') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email del usuario a invitar</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_rol" class="form-label">Rol</label>
                        <select class="form-select" id="id_rol" name="id_rol" required>
                            <option value="">Seleccione un rol</option>
                            <option value="1">Administrador</option>
                            <option value="2">Usuario</option>
                            <option value="3">Invitado</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Invitación</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h2>Completar Registro</h2>
            </div>
            <div class="card-body">
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger">
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/guardarUsuario') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <input type="hidden" name="email" value="<?= esc($email) ?>">
                    <input type="hidden" name="id_rol" value="<?= esc($id_rol) ?>">
                    <input type="hidden" name="token" value="<?= esc($token) ?>">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>

                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                        <small class="text-muted">La contraseña debe tener al menos 8 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Completar Registro</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
