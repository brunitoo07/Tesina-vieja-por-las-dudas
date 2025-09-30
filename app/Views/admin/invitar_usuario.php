<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<style>
    /* --- Paleta de Colores Premium (Sin Grises) --- */
    :root {
        --gold: #D4AF37;
        --gold-light: #EAD58B;
        --black-bg: #121212;
        --card-bg: #1B1B1E; /* Fondo sólido y oscuro para la tarjeta */
        --card-border: rgba(255, 255, 255, 0.1);
        --text-primary: #FFFFFF; /* Blanco puro para máximo contraste */
        --text-secondary: #E0E0E0; /* Blanco secundario MÁS LEGIBLE */
        --glow-color: rgba(212, 175, 55, 0.3);
        --danger: #dc3545;

        --border-radius: 20px;
    }

    /* --- Importación de Fuente y Estilos Base --- */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    
    body {
        background-color: var(--black-bg);
        color: var(--text-primary);
        font-family: 'Poppins', sans-serif;
        background-image: radial-gradient(circle at top, rgba(50, 50, 50, 0.2), transparent 40%);
    }

    /* --- Animación de Entrada --- */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- Contenedor Principal con Fondo Sólido --- */
    .premium-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--border-radius);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        animation: fadeIn 0.8s ease-out forwards;
    }
    .premium-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--card-border);
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--gold-light);
    }
    .premium-card-header i { margin-right: 0.75rem; }
    .premium-card-body { padding: 2rem; }
    
    /* --- Encabezado de Página --- */
    .page-header { color: var(--text-primary); }
    .page-header i { color: var(--gold); }

    /* --- Alertas Premium --- */
    .premium-alert {
        border-radius: 12px;
        border-width: 1px;
        border-style: solid;
        padding: 1rem 1.5rem;
    }
    .premium-alert.alert-success { 
        background-color: rgba(40, 167, 69, 0.1); 
        border-color: #28a745; 
        color: #e8f5e9;
    }
    .premium-alert.alert-danger { 
        background-color: rgba(220, 53, 69, 0.1); 
        border-color: var(--danger); 
        color: #f8d7da;
    }
    .premium-alert.alert-info { 
        background-color: rgba(23, 162, 184, 0.1); 
        border-color: #17a2b8; 
        color: #d1ecf1;
    }
    .premium-alert.alert-warning { 
        background-color: rgba(255, 193, 7, 0.1); 
        border-color: #ffc107; 
        color: #fff3cd;
    }
    .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

    /* --- Formularios Premium --- */
    .form-control, .form-select {
        background-color: rgba(0,0,0,0.2);
        border: 1px solid var(--card-border);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        background-color: rgba(0,0,0,0.3);
        border-color: var(--gold);
        box-shadow: 0 0 10px var(--glow-color);
        color: var(--text-primary);
    }
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    .form-label {
        color: var(--text-secondary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .form-text {
        color: var(--text-secondary);
        opacity: 0.7;
    }
    
    /* --- Botones Premium --- */
    .btn-gold {
        background: var(--gold); 
        color: var(--black-bg); 
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 20px var(--glow-color); 
        transition: all 0.3s ease;
    }
    .btn-gold:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5); 
        background-color: var(--gold-light);
        color: var(--black-bg);
    }
    
    /* --- Botón con borde --- */
    .btn-outline-premium {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .btn-outline-premium:hover {
        color: var(--gold-light);
        border-color: var(--gold);
        background-color: rgba(212, 175, 55, 0.1);
    }
    
    /* --- Input Group --- */
    .input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .input-group .btn-outline-premium {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: none;
    }
</style>

<div class="container my-5">
    <?php if (isset($isAdmin) && $isAdmin): ?>
        <h1 class="h3 mb-4 page-header"><i class="fas fa-user-plus"></i> Invitar Usuario</h1>
        
        <div class="premium-card">
            <div class="premium-card-header">
                <i class="fas fa-envelope"></i> Formulario de Invitación
            </div>
            <div class="premium-card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="premium-alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="premium-alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('admin/enviarInvitacion') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="id_rol" class="form-label">Rol</label>
                        <select class="form-select" id="id_rol" name="id_rol" required>
                            <option value="">Seleccione un rol</option>
                            <option value="2" <?= (old('id_rol') == '2') ? 'selected' : '' ?>>Usuario</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gold">Enviar invitación</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <h1 class="h3 mb-4 page-header"><i class="fas fa-user-check"></i> Completar Registro</h1>
        
        <div class="premium-card">
            <div class="premium-card-header">
                <i class="fas fa-user-edit"></i> Información de Usuario
            </div>
            <div class="premium-card-body">
                <?php if (session()->getFlashdata('error')): ?> 
                    <div class="premium-alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('password_error')) : ?> 
                    <div class="premium-alert alert-warning alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('password_error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/guardarUsuario') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <input type="hidden" name="email" value="<?= esc($email) ?>">
                    <input type="hidden" name="id_rol" value="<?= esc($id_rol) ?>">
                    <input type="hidden" name="token" value="<?= esc($token) ?>">

                    <div class="mb-4">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= old('nombre') ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="<?= old('apellido') ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                            <button class="btn btn-outline-premium" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="form-text">La contraseña debe tener al menos 8 caracteres</small>
                    </div>

                    <div class="mb-4">
                        <label for="confirmar_contrasena" class="form-label">Confirmar contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                            <button class="btn btn-outline-premium" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gold">Completar registro</button>
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
                                icon.classList.toggle('fa-eye');
                                icon.classList.toggle('fa-eye-slash');
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