<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<style>
    /* --- Paleta de Colores y Estilos Premium --- */
    :root {
        --gold: #D4AF37;
        --gold-light: #EAD58B;
        --black-bg: #121212;
        --card-bg: #1B1B1E;
        --card-border: rgba(255, 255, 255, 0.1);
        --text-primary: #FFFFFF;
        --text-secondary: #E0E0E0;
        --glow-color: rgba(212, 175, 55, 0.3);
        --border-radius: 20px;
        /* Color de acento añadido */
        --accent-blue: #4A90E2;
        --accent-blue-glow: rgba(74, 144, 226, 0.4);
        --dark-blue-bg: #111A22;
    }

    /* --- Base y Animaciones --- */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    body {
        background-color: var(--black-bg);
        color: var(--text-primary);
        font-family: 'Poppins', sans-serif;
        background: radial-gradient(ellipse at bottom, var(--dark-blue-bg) 0%, var(--black-bg) 70%);
        min-height: 100vh;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- Tarjeta Principal --- */
    .premium-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--border-radius);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        animation: fadeIn 0.8s ease-out forwards;
    }
    .premium-card-body { padding: 2.5rem; }

    /* --- Tarjeta Resumen de Perfil (Izquierda) --- */
    .profile-summary-card {
        background: linear-gradient(145deg, #232326, var(--card-bg));
    }
    .profile-summary-card .profile-icon {
        font-size: 5rem;
        color: var(--gold);
        text-shadow: 0 0 15px var(--glow-color);
    }
    .profile-summary-card .card-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-top: 1rem;
    }
    .profile-summary-card .text-muted {
        color: var(--text-secondary) !important;
    }
    .role-badge {
        background-color: rgba(212, 175, 55, 0.1);
        border: 1px solid var(--gold);
        color: var(--gold-light);
        padding: 0.5em 1em;
        border-radius: 50px;
        font-weight: 600;
    }
    
    /* --- Pestañas de Navegación (Tabs) --- */
    .premium-tabs {
        border-bottom: 2px solid var(--card-border);
    }
    .premium-tabs .nav-link {
        border: none;
        color: var(--text-secondary);
        font-weight: 600;
        padding-bottom: 1rem;
        position: relative;
    }
    .premium-tabs .nav-link i {
        margin-right: 0.5rem;
        color: var(--accent-blue);
        transition: color 0.3s ease;
    }
    .premium-tabs .nav-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: var(--gold);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    .premium-tabs .nav-link.active, .premium-tabs .nav-link:hover {
        color: var(--text-primary);
    }
    .premium-tabs .nav-link.active i, .premium-tabs .nav-link:hover i {
        color: var(--gold);
    }
    .premium-tabs .nav-link.active::after {
        transform: scaleX(1);
    }

    /* --- Formularios y Alertas --- */
    .form-label { color: var(--text-secondary); font-weight: 600; font-size: 0.9rem; }
    .form-control {
        background-color: rgba(0,0,0,0.2);
        border: 1px solid var(--card-border);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 0.8rem 1rem;
    }
    .form-control:focus {
        background-color: rgba(0,0,0,0.3);
        border-color: var(--gold);
        box-shadow: 0 0 10px var(--glow-color), 0 0 4px var(--accent-blue-glow);
        color: var(--text-primary);
    }
    .input-group .form-control { border-right: none; }
    .input-group .btn { background-color: rgba(0,0,0,0.2); border: 1px solid var(--card-border); border-left: none; color: var(--text-secondary); }
    .input-group:focus-within .form-control, .input-group:focus-within .btn { border-color: var(--gold); box-shadow: 0 0 10px var(--glow-color); }
    .form-text { color: var(--text-secondary) !important; }

    .premium-alert { border-radius: 12px; border-width: 1px; border-style: solid; padding: 1rem 1.5rem; }
    .premium-alert.alert-success { background-color: rgba(40, 167, 69, 0.1); border-color: #28a745; }
    .premium-alert.alert-danger { background-color: rgba(220, 53, 69, 0.1); border-color: #dc3545; }

    /* --- Botón --- */
    .btn-premium-gold {
        background: var(--gold); color: var(--black-bg); border: none;
        box-shadow: 0 4px 20px var(--glow-color); padding: 0.8rem 1.5rem; font-weight: 600;
        border-radius: 12px; transition: all 0.3s ease;
    }
    .btn-premium-gold:hover { transform: translateY(-3px); box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5); color: var(--black-bg); }
</style>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <!-- Tarjeta de Resumen del Perfil -->
            <div class="premium-card profile-summary-card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle profile-icon"></i>
                    </div>
                    <h4 class="card-title"><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></h4>
                    <p class="text-muted"><?= esc($usuario['email']) ?></p>
                    <p class="mt-3">
                        <span class="role-badge"><?= ucfirst(esc($usuario['rol'])) ?></span>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Tarjeta con Formularios y Pestañas -->
            <div class="premium-card">
                <div class="premium-card-body">
                    <ul class="nav premium-tabs mb-4" id="profileTabs" role="tablist">
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

                    <div class="tab-content" id="profileTabsContent">
                        <!-- Mensajes de alerta -->
                        <?php if (session()->has('success')): ?>
                            <div class="premium-alert alert-success"><?= session('success') ?></div>
                        <?php endif; ?>
                        <?php if (session()->has('error')): ?>
                            <div class="premium-alert alert-danger"><?= session('error') ?></div>
                        <?php endif; ?>

                        <!-- Pestaña: Datos Personales -->
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
                                <button type="submit" class="btn btn-premium-gold mt-3">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </form>
                        </div>

                        <!-- Pestaña: Cambiar Contraseña -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form id="passwordForm" action="<?= base_url('usuario/cambiarContrasena') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        <button class="btn toggle-password" type="button" data-target="current_password"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <button class="btn toggle-password" type="button" data-target="new_password"><i class="fas fa-eye"></i></button>
                                    </div>
                                    <small class="form-text">Mínimo 6 caracteres, una mayúscula y un símbolo (!@#$%).</small>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <button class="btn toggle-password" type="button" data-target="confirm_password"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-premium-gold mt-3">
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
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>

<?= $this->endSection() ?>
