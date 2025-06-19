<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container mt-4">
    <?php if (isset($isAdmin) && $isAdmin): ?>
        <div class="card">
            <div class="card-header">
                <h2><?= lang('App.invite_user_title') ?></h2>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url('admin/enviarInvitacion') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label"><?= lang('App.invite_user_email') ?></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_rol" class="form-label"><?= lang('App.role') ?></label>
                        <select class="form-select" id="id_rol" name="id_rol" required>
                            <option value=""><?= lang('App.select_role') ?></option>
                            <option value="2" <?= (old('id_rol') == '2') ? 'selected' : '' ?>><?= lang('App.user') ?></option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= lang('App.send_invitation') ?></button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h2><?= lang('App.complete_registration') ?></h2>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?> <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('password_error')) : ?> <div class="alert alert-warning"><?= session()->getFlashdata('password_error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('admin/guardarUsuario') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <input type="hidden" name="email" value="<?= esc($email) ?>">
                    <input type="hidden" name="id_rol" value="<?= esc($id_rol) ?>">
                    <input type="hidden" name="token" value="<?= esc($token) ?>">

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
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted"><?= lang('App.password_hint') ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_contrasena" class="form-label"><?= lang('App.confirm_password') ?></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= lang('App.complete_registration_btn') ?></button>
                </form>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function setupPasswordToggle(toggleButtonId, passwordFieldId) {
                    const toggleButton = document.getElementById(toggleButtonId);
                    const passwordField = document.getElementById(passwordFieldId);

                    if (toggleButton && passwordField) {
                        toggleButton.addEventListener('click', function() {
                            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                            passwordField.setAttribute('type', type);

                            const icon = this.querySelector('i');
                            if (icon) {
                                icon.classList.toggle('bi-eye');
                                icon.classList.toggle('bi-eye-slash');
                            }
                        });
                    }
                }
                setupPasswordToggle('togglePassword', 'contrasena');
                setupPasswordToggle('toggleConfirmPassword', 'confirmar_contrasena');
            });
        </script>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>