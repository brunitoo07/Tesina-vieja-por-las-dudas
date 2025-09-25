<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<style>
    /* --- Paleta de Colores y Variables de Diseño Premium --- */
    :root {
        --gold: #D4AF37;
        --gold-light: #EAD58B;
        --black-bg: #121212;
        --glass-bg: rgba(30, 30, 30, 0.65);
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-primary: #F0F0F0;
        --text-secondary: #A0A0A0;
        --glow-color: rgba(212, 175, 55, 0.3);
        
        /* Colores de estado */
        --success: #28a745;
        --warning: #ffc107;
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

    /* --- Contenedor Principal (Tarjeta de Cristal) --- */
    .premium-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
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

    /* --- Estilo del Encabezado de la Página --- */
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
        border-color: var(--success);
    }
    .premium-alert.alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: var(--danger);
    }
    .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

    /* --- Tabla Premium --- */
    .premium-table { color: var(--text-primary); }
    .premium-table thead th {
        border: none;
        color: var(--gold-light);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .premium-table tbody tr {
        transition: background-color 0.2s;
        border-bottom: 1px solid var(--glass-border);
    }
    .premium-table tbody tr:last-child { border-bottom: none; }
    .premium-table tbody td { padding: 1rem; vertical-align: middle; border: none; }
    .premium-table tbody tr:hover { background-color: rgba(255,255,255,0.05); }
    .premium-table code {
        background-color: var(--glass-border);
        color: var(--gold-light);
        padding: 0.2em 0.4em;
        border-radius: 4px;
    }
    .premium-table .admin-info small { color: var(--text-secondary); }

    /* --- Badges de Estado Modernos --- */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .status-badge::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    .status-badge.status-activo { background-color: rgba(40, 167, 69, 0.15); color: #90ee90; }
    .status-badge.status-activo::before { background-color: var(--success); }
    .status-badge.status-pendiente { background-color: rgba(255, 193, 7, 0.15); color: #ffd54f; }
    .status-badge.status-pendiente::before { background-color: var(--warning); }
    .status-badge.status-inactivo { background-color: rgba(220, 53, 69, 0.15); color: #ff8a8a; }
    .status-badge.status-inactivo::before { background-color: var(--danger); }

    /* --- Botones --- */
    .btn-gold {
        background: var(--gold);
        color: var(--black-bg);
        border: none;
        box-shadow: 0 4px 20px var(--glow-color);
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .btn-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5);
        color: var(--black-bg);
    }
    /* Para botones de acción en tabla */
    .btn-outline-action {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--glass-border);
        transition: all 0.3s ease;
    }
    .btn-outline-action:hover { color: var(--text-primary); border-color: var(--text-primary); }
    .btn-outline-action.danger:hover { color: var(--danger); border-color: var(--danger); }

    /* --- Modal Oscuro --- */
    .modal-content {
        background-color: #2a2a2e; /* Un poco más claro para que resalte */
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
    }
    .modal-header { border-bottom: 1px solid var(--glass-border); color: var(--gold); }
    .form-label { color: var(--text-secondary); }
    .form-control {
        background-color: rgba(0,0,0,0.2);
        border: 1px solid var(--glass-border);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 0.8rem 1rem;
    }
    .form-control:focus {
        background-color: rgba(0,0,0,0.3);
        border-color: var(--gold);
        box-shadow: 0 0 10px var(--glow-color);
        color: var(--text-primary);
    }
    .btn-secondary { background-color: var(--glass-border); border: none; }
</style>

<div class="container-fluid py-4">
    <h1 class="h3 mb-4 page-header"><i class="fas fa-microchip"></i> Gestión de Dispositivos</h1>
    
    <div class="premium-card">
        <div class="premium-card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-list-ul"></i> Dispositivos Registrados</div>
            <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-gold">
                <i class="fas fa-search me-1"></i> Buscar Dispositivos
            </a>
        </div>
        <div class="premium-card-body">
            <?php if (session()->has('success')): ?>
                <div class="premium-alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->has('error')): ?>
                <div class="premium-alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table premium-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>MAC Address</th>
                            <th>Admin Dueño</th>
                            <th>Estado</th>
                            <th>Última Actualización</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dispositivos)): ?>
                            <tr><td colspan="6" class="text-center py-4">No hay dispositivos registrados.</td></tr>
                        <?php else: ?>
                            <?php foreach ($dispositivos as $dispositivo): ?>
                                <tr>
                                    <td><strong><?= esc($dispositivo['nombre']) ?></strong><br><small class="text-secondary"><?= esc($dispositivo['descripcion'] ?? '') ?></small></td>
                                    <td><code><?= esc($dispositivo['mac_address']) ?></code></td>
                                    <td class="admin-info">
                                        <?= esc($dispositivo['nombre_admin'] ?? '-') ?><br>
                                        <small><?= esc($dispositivo['email_admin'] ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= esc($dispositivo['estado']) ?>">
                                            <?= ucfirst(esc($dispositivo['estado'])) ?>
                                        </span>
                                    </td>
                                    <td><?= isset($dispositivo['fecha_actualizacion']) ? date('d/m/Y H:i', strtotime($dispositivo['fecha_actualizacion'])) : 'Nunca' ?></td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('energia/dispositivo/' . $dispositivo['id_dispositivo']) ?>" class="btn btn-sm btn-outline-action" title="Ver Lecturas"><i class="fas fa-chart-line"></i></a>
                                            <a href="<?= base_url('dispositivo/control/' . $dispositivo['id_dispositivo']) ?>" class="btn btn-sm btn-outline-action" title="Controlar Foco"><i class="fas fa-lightbulb"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-action" onclick="editarDispositivo(<?= $dispositivo['id_dispositivo'] ?>, '<?= esc($dispositivo['nombre'], 'js') ?>', '<?= esc($dispositivo['descripcion'] ?? '', 'js') ?>')" title="Editar"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-outline-action danger" onclick="eliminarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)" title="Eliminar"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarAdmin" action="<?= base_url('admin/dispositivos/actualizar') ?>" method="post">
                    <input type="hidden" name="id_dispositivo" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="editNombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="editDescripcion" rows="3"></textarea>
                    </div>
                </form>
                <div id="msgEditarAdmin" class="text-center mt-3"></div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--glass-border);">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gold" onclick="guardarEdicion()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmationModalMessage"></p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--glass-border);">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gold" id="confirmActionBtn">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
let confirmationModal = null;
let confirmActionCallback = null;

document.addEventListener("DOMContentLoaded", function() {
    confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    
    document.getElementById('confirmActionBtn').addEventListener('click', function() {
        if (typeof confirmActionCallback === 'function') {
            confirmActionCallback();
        }
        confirmationModal.hide();
    });
});

function showConfirmation(title, message, callback) {
    document.getElementById('confirmationModalTitle').textContent = title;
    document.getElementById('confirmationModalMessage').textContent = message;
    confirmActionCallback = callback;
    confirmationModal.show();
}

function eliminarDispositivo(id) {
    showConfirmation('Confirmar Eliminación', '¿Estás seguro de que quieres eliminar este dispositivo? Esta acción no se puede deshacer.', () => {
        fetch(`<?= base_url('admin/dispositivos/eliminar/') ?>${id}`, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                showConfirmation('Error', 'No se pudo eliminar el dispositivo: ' + data.message, () => {});
            }
        })
        .catch(error => console.error('Error:', error));
    });
}

function editarDispositivo(id, nombre, descripcion) {
    document.getElementById('editId').value = id;
    document.getElementById('editNombre').value = nombre || '';
    document.getElementById('editDescripcion').value = descripcion || '';
    new bootstrap.Modal(document.getElementById('editarModal')).show();
}

function guardarEdicion() {
    const form = document.getElementById('formEditarAdmin');
    const msg = document.getElementById('msgEditarAdmin');
    msg.innerHTML = '';
    fetch(form.action, { method: 'POST', body: new FormData(form) })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                msg.innerHTML = '<span style="color:var(--success)">Dispositivo actualizado.</span>';
                setTimeout(() => { location.reload(); }, 800);
            } else {
                msg.innerHTML = `<span style="color:var(--danger);">${res.error || 'Error'}</span>`;
            }
        })
        .catch(err => {
            msg.innerHTML = `<span style="color:var(--danger);">Error: ${err}</span>`;
        });
}
</script>

<?= $this->endSection() ?>