<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<style>
    /* --- Paleta de Colores Premium (Sin Grises) --- */
    :root {
        --gold: #D4AF37;
        --gold-light: #EAD58B;
        --black-bg: #121212;
        --glass-bg: rgba(20, 20, 22, 0.95); /* Fondo casi opaco para máximo contraste */
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-primary: #F0F0F0;
        --text-secondary: rgba(240, 240, 240, 0.7);
        --glow-color: rgba(212, 175, 55, 0.3);

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

    /* --- Contenedor Principal con Efecto Glassmorphism --- */
    .premium-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        animation: fadeIn 0.8s ease-out forwards;
    }
    .premium-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--glass-border);
        font-size: 1.1rem;
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
    }
    .premium-alert.alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: #dc3545;
    }

    /* --- Lista de Información de Perfil --- */
    .profile-info-list .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid var(--glass-border);
    }
    .profile-info-list .info-item:last-child {
        border-bottom: none;
    }
    .profile-info-list .info-label {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    .profile-info-list .info-value {
        color: var(--text-primary);
        font-size: 1.1rem;
    }

    /* --- Botón Premium --- */
    .btn-premium-gold {
        display: inline-block;
        text-decoration: none;
        background: var(--gold);
        color: var(--black-bg);
        border: none;
        box-shadow: 0 4px 20px var(--glow-color);
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .btn-premium-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5);
        color: var(--black-bg);
    }

</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 page-header"><i class="fas fa-user-circle"></i> Mi Perfil</h1>
                <a href="<?= base_url('usuario') ?>" class="btn-premium-gold">
                    <i class="fas fa-arrow-left me-2"></i> Volver al Dashboard
                </a>
            </div>

            <div class="premium-card">
                <div class="premium-card-header">
                    <i class="fas fa-id-card"></i> Información Personal
                </div>
                <div class="premium-card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="premium-alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="premium-alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Vista de perfil rediseñada -->
                    <div class="profile-info-list mt-3">
                        <div class="row info-item align-items-center">
                            <div class="col-sm-4 info-label">Nombre</div>
                            <div class="col-sm-8 info-value"><?= esc($usuario['nombre']) ?></div>
                        </div>

                        <div class="row info-item align-items-center">
                            <div class="col-sm-4 info-label">Apellido</div>
                            <div class="col-sm-8 info-value"><?= esc($usuario['apellido']) ?></div>
                        </div>

                        <div class="row info-item align-items-center">
                            <div class="col-sm-4 info-label">Email</div>
                            <div class="col-sm-8 info-value"><?= esc($usuario['email']) ?></div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                         <a href="<?= base_url('usuario/cambiarContrasena') ?>" class="btn-premium-gold">
                            <i class="fas fa-key me-2"></i> Cambiar Contraseña
                         </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>