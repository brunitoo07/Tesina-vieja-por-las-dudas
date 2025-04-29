<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Mi Perfil</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información Personal</h6>
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

                    <!-- Vista solo lectura del perfil -->
                    <dl class="row">
                        <dt class="col-sm-3">Nombre</dt>
                        <dd class="col-sm-9"><?= esc($usuario['nombre']) ?></dd>

                        <dt class="col-sm-3">Apellido</dt>
                        <dd class="col-sm-9"><?= esc($usuario['apellido']) ?></dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9"><?= esc($usuario['email']) ?></dd>
                    </dl>

                    <a href="<?= base_url('usuario/cambiarContrasena') ?>" class="btn btn-warning">Cambiar Contraseña</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para mostrar los datos enviados -->
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('Datos del formulario:', {
            nombre: document.getElementById('nombre').value,
            apellido: document.getElementById('apellido').value,
            email: document.getElementById('email').value
        });
    });
</script>
<?= $this->endSection() ?> 