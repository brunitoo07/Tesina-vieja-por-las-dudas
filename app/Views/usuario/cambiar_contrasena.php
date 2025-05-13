<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Cambiar Contraseña</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cambiar Contraseña</h6>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('usuario/cambiarContrasena') ?>" method="post">
                        <div class="form-group">
                            <label for="contrasena_actual">Contraseña Actual</label>
                            <input type="password" class="form-control <?= (session('validation') && session('validation')->hasError('contrasena_actual')) ? 'is-invalid' : '' ?>" 
                                   id="contrasena_actual" name="contrasena_actual" required>
                            <?php if (session('validation') && session('validation')->hasError('contrasena_actual')): ?>
                                <div class="invalid-feedback">
                                    <?= session('validation')->getError('contrasena_actual') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="nueva_contrasena">Nueva Contraseña</label>
                            <input type="password" class="form-control <?= (session('validation') && session('validation')->hasError('nueva_contrasena')) ? 'is-invalid' : '' ?>" 
                                   id="nueva_contrasena" name="nueva_contrasena" required>
                            <?php if (session('validation') && session('validation')->hasError('nueva_contrasena')): ?>
                                <div class="invalid-feedback">
                                    <?= session('validation')->getError('nueva_contrasena') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="confirmar_contrasena">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control <?= (session('validation') && session('validation')->hasError('confirmar_contrasena')) ? 'is-invalid' : '' ?>" 
                                   id="confirmar_contrasena" name="confirmar_contrasena" required>
                            <?php if (session('validation') && session('validation')->hasError('confirmar_contrasena')): ?>
                                <div class="invalid-feedback">
                                    <?= session('validation')->getError('confirmar_contrasena') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                        <a href="<?= base_url('perfil/perfil') ?>" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 