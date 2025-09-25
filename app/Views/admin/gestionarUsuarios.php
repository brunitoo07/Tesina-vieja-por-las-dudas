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
    .premium-alert.alert-success { background-color: rgba(40, 167, 69, 0.1); border-color: #28a745; }
    .premium-alert.alert-danger { background-color: rgba(220, 53, 69, 0.1); border-color: var(--danger); }
    .premium-alert.alert-info { background-color: rgba(23, 162, 184, 0.1); border-color: #17a2b8; }
    .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

    /* --- Tabla Premium --- */
    .premium-table { color: var(--text-primary); }
    .premium-table thead th {
        border: none;
        color: var(--gold-light);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .premium-table tbody tr {
        transition: background-color 0.2s;
        border-bottom: 1px solid var(--card-border);
    }
    .premium-table tbody tr:last-child { border-bottom: none; }
    .premium-table tbody td { 
        padding: 1rem; 
        vertical-align: middle; 
        border: none; 
        font-size: 0.95rem;
    }
    .premium-table tbody tr:hover { background-color: rgba(255,255,255,0.05); }

    /* --- Formularios Premium --- */
    .form-select {
        background-color: rgba(0,0,0,0.2);
        border: 1px solid var(--card-border);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 0.5rem 1rem;
        width: 150px;
        appearance: none; 
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23D4AF37' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); 
        background-repeat: no-repeat; 
        background-position: right .75rem center; 
        background-size: 16px 12px;
        transition: all 0.2s ease;
    }
    .form-select:focus {
        background-color: rgba(0,0,0,0.3);
        border-color: var(--gold);
        box-shadow: 0 0 10px var(--glow-color);
    }
    
    /* --- Botón Eliminar --- */
    .btn-delete {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--card-border);
        transition: all 0.3s ease;
    }
    .btn-delete:hover {
        color: var(--danger);
        border-color: var(--danger);
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    /* --- Modal Oscuro --- */
    .modal-content {
        background-color: #2a2a2e;
        border: 1px solid var(--card-border);
        border-radius: var(--border-radius);
    }
    .modal-header { border-bottom: 1px solid var(--card-border); color: var(--gold); }
    .btn-secondary { background-color: var(--card-border); border: none; }
    .btn-gold {
        background: var(--gold); color: var(--black-bg); border: none;
        box-shadow: 0 4px 20px var(--glow-color); transition: all 0.3s ease;
    }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5); }
</style>

<div class="container my-5">
    <h1 class="h3 mb-4 page-header"><i class="fas fa-users-cog"></i> Usuarios Invitados</h1>

    <div class="premium-card">
        <div class="premium-card-header">
            <i class="fas fa-list-ul"></i> Lista de Usuarios
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

            <?php if (empty($usuarios)) : ?>
                <div class="premium-alert alert-info">No hay usuarios invitados actualmente.</div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table premium-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol actual</th>
                                <th>Cambiar rol</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= esc($usuario['id_usuario']) ?></td>
                                    <td><?= esc($usuario['nombre']) ?></td>
                                    <td><?= esc($usuario['email']) ?></td>
                                    <td><?= esc($usuario['rol']) ?></td>
                                    <td>
                                        <form action="<?= base_url('admin/cambiarRol') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="usuario_id" value="<?= $usuario['id_usuario'] ?>">
                                            <select name="id_rol" class="form-select" onchange="this.form.submit()">
                                                <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
                                                <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-delete btn-sm" onclick="confirmarEliminacion(<?= $usuario['id_usuario'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="form-eliminar-<?= $usuario['id_usuario'] ?>" action="<?= base_url('admin/eliminarUsuario') ?>" method="post" class="d-none">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="usuario_id" value="<?= $usuario['id_usuario'] ?>">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalTitle"><i class="fas fa-exclamation-triangle me-2"></i> Confirmar Acción</h5>
            </div>
            <div class="modal-body">
                <p id="confirmationModalMessage">¿Estás seguro de que quieres eliminar este usuario? Esta acción es irreversible.</p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--card-border);">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gold" id="confirmActionBtn">Sí, Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    let confirmationModal = null;
    let confirmActionCallback = null;

    document.addEventListener("DOMContentLoaded", function() {
        if (document.getElementById('confirmationModal')) {
            confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            
            document.getElementById('confirmActionBtn').addEventListener('click', function() {
                if (typeof confirmActionCallback === 'function') {
                    confirmActionCallback();
                }
                confirmationModal.hide();
            });
        }
    });

    function confirmarEliminacion(usuarioId) {
        confirmActionCallback = function() {
            const form = document.getElementById('form-eliminar-' + usuarioId);
            if (form) {
                form.submit();
            }
        };
        if (confirmationModal) {
            confirmationModal.show();
        }
    }
</script>

<?= $this->endSection() ?>