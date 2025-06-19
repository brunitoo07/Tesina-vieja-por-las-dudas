<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 500px;">
        <h2 class="card-title text-center mb-4"><?= lang('App.complete_registration') ?></h2>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('password_error')) : ?>
            <div class="alert alert-warning"><?= session()->getFlashdata('password_error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('registro/procesarInvitado') ?>" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="email" value="<?= esc($email) ?>">
            <input type="hidden" name="id_rol_invitado" value="<?= esc($id_rol_invitado) ?>">
            <input type="hidden" name="token_invitacion" value="<?= esc($token_invitacion) ?>">

            <div class="mb-3">
                <label for="email" class="form-label"><?= lang('App.email') ?></label>
                <input type="email" class="form-control" id="email" value="<?= esc($email) ?>" readonly disabled>
                <small class="form-text text-muted"><?= lang('App.invitation_email_hint') ?></small>
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label"><?= lang('App.name') ?></label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= old('nombre') ?>" required>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label"><?= lang('App.lastname') ?></label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="<?= old('apellido') ?>" required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label"><?= lang('App.password') ?></label>
                <div class="input-group">
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i> </button>
                </div>
                <small class="form-text text-muted"><?= lang('App.password_hint') ?></small>
            </div>

            <div class="mb-3">
                <label for="confirmar_contrasena" class="form-label"><?= lang('App.confirm_password') ?></label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="bi bi-eye"></i> </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100"><?= lang('App.register_btn') ?></button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función genérica para el "ojito"
        function setupPasswordToggle(toggleButtonId, passwordFieldId) {
            const toggleButton = document.getElementById(toggleButtonId);
            const passwordField = document.getElementById(passwordFieldId);

            if (toggleButton && passwordField) {
                toggleButton.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    // Cambiar el icono (asume Bootstrap Icons 'bi-eye' y 'bi-eye-slash')
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('bi-eye');
                        icon.classList.toggle('bi-eye-slash');
                    }
                });
            }
        }

        // Aplicar a ambos campos de contraseña
        setupPasswordToggle('togglePassword', 'contrasena');
        setupPasswordToggle('toggleConfirmPassword', 'confirmar_contrasena');
    });
</script>

<?= $this->endSection() ?>