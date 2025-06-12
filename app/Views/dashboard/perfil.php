<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h4 class="card-title"><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></h4>
                    <p class="text-muted"><?= esc($usuario['email']) ?></p>
                    <p class="text-muted">
                        <span class="badge bg-primary"><?= ucfirst($usuario['rol']) ?></span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="datos-tab" data-bs-toggle="tab" href="#datos" role="tab">
                                <i class="fas fa-user"></i> Datos Personales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">
                                <i class="fas fa-key"></i> Cambiar Contraseña
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-4" id="profileTabsContent">
                        <!-- Mensajes de alerta -->
                        <?php if (session()->has('success')): ?>
                            <div class="alert alert-success">
                                <?= session('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger">
                                <?= session('error') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Datos Personales -->
                        <div class="tab-pane fade show active" id="datos" role="tabpanel">
                            <form action="<?= base_url('usuario/actualizar-perfil') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= esc($usuario['nombre']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= esc($usuario['apellido']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= esc($usuario['email']) ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </form>
                        </div>

                        <!-- Cambiar Contraseña -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form action="<?= base_url('usuario/cambiarContrasena') ?>" method="post" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">
                                        La contraseña debe tener al menos 6 caracteres, una mayúscula y un símbolo (!@#$%)
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key"></i> Cambiar Contraseña
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función para mostrar/ocultar contraseña
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});

// Validación del formulario de contraseña
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const currentPassword = document.getElementById('current_password').value;
    
    // Validar que la contraseña actual no esté vacía
    if (!currentPassword) {
        alert('Por favor ingrese su contraseña actual');
        return;
    }
    
    // Validar que la nueva contraseña cumpla con los requisitos
    if (newPassword.length < 6 || !/[A-Z]/.test(newPassword) || !/[!@#$%]/.test(newPassword)) {
        alert('La contraseña debe tener al menos 6 caracteres, una mayúscula y un símbolo (!@#$%)');
        return;
    }
    
    // Validar que las contraseñas coincidan
    if (newPassword !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        return;
    }
    
    // Si todo está bien, enviar el formulario
    this.submit();
});
</script>
<?= $this->endSection() ?> 