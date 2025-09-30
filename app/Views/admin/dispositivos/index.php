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
    .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

    /* --- Tabla Premium --- */
    .premium-table { 
        color: var(--text-primary); 
        border-collapse: separate;
        border-spacing: 0;
    }
    .premium-table thead th {
        border: none;
        color: var(--gold-light);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 1rem;
        background-color: rgba(0,0,0,0.2);
    }
    .premium-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--card-border);
    }
    .premium-table tbody tr:last-child { border-bottom: none; }
    .premium-table tbody td { 
        padding: 1rem; 
        vertical-align: middle; 
        border: none; 
        font-size: 0.95rem;
    }
    .premium-table tbody tr:hover { 
        background-color: rgba(255,255,255,0.08);
        transform: translateY(-1px);
    }

    /* --- Botones de Acción Siempre Visibles --- */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        justify-content: center;
        align-items: center;
        min-height: 44px;
    }

    /* --- Botones Premium con Animaciones --- */
    .btn-action {
        background: transparent;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

    /* Efecto de pulso suave en estado normal */
    .btn-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: currentColor;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 6px;
    }

    .btn-action:hover::before {
        opacity: 0.1;
    }

    /* Animación de brillo al hover */
    .btn-action:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    /* Colores específicos para cada botón */
    .btn-action.btn-view {
        color: var(--text-secondary);
        border-color: var(--card-border);
    }
    .btn-action.btn-view:hover {
        color: var(--gold-light);
        border-color: var(--gold);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .btn-action.btn-edit {
        color: var(--text-secondary);
        border-color: var(--card-border);
    }
    .btn-action.btn-edit:hover {
        color: #17a2b8;
        border-color: #17a2b8;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .btn-action.btn-control {
        color: #28a745;
        border-color: #28a745;
    }
    .btn-action.btn-control:hover {
        color: #28a745;
        border-color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-action.btn-activate {
        color: #28a745;
        border-color: #28a745;
    }
    .btn-action.btn-activate:hover {
        color: #28a745;
        border-color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        animation: pulse-green 0.6s ease-in-out;
    }

    .btn-action.btn-deactivate {
        color: #ffc107;
        border-color: #ffc107;
    }
    .btn-action.btn-deactivate:hover {
        color: #ffc107;
        border-color: #ffc107;
        background-color: rgba(255, 193, 7, 0.1);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        animation: pulse-yellow 0.6s ease-in-out;
    }

    .btn-action.btn-delete {
        color: var(--danger);
        border-color: var(--danger);
    }
    .btn-action.btn-delete:hover {
        color: var(--danger);
        border-color: var(--danger);
        background-color: rgba(220, 53, 69, 0.1);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        animation: pulse-red 0.6s ease-in-out;
    }

    .btn-action.btn-charts {
        color: #6f42c1;
        border-color: #6f42c1;
    }
    .btn-action.btn-charts:hover {
        color: #6f42c1;
        border-color: #6f42c1;
        background-color: rgba(111, 66, 193, 0.1);
        box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
    }

    /* Animaciones de pulso */
    @keyframes pulse-green {
        0%, 100% { transform: translateY(-2px) scale(1.05); }
        50% { transform: translateY(-2px) scale(1.1); }
    }

    @keyframes pulse-yellow {
        0%, 100% { transform: translateY(-2px) scale(1.05); }
        50% { transform: translateY(-2px) scale(1.1); }
    }

    @keyframes pulse-red {
        0%, 100% { transform: translateY(-2px) scale(1.05); }
        50% { transform: translateY(-2px) scale(1.1); }
    }

    /* Efecto de carga en botones de estado */
    .btn-action.btn-activate:active::after,
    .btn-action.btn-deactivate:active::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        animation: ripple 0.6s ease-out;
    }

    @keyframes ripple {
        to {
            width: 100%;
            height: 100%;
            opacity: 0;
        }
    }

    /* --- Badges Premium --- */
    .badge-success-premium {
        background-color: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border: 1px solid #28a745;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .badge-warning-premium {
        background-color: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid #ffc107;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .badge-danger-premium {
        background-color: rgba(220, 53, 69, 0.2);
        color: var(--danger);
        border: 1px solid var(--danger);
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    /* Efecto en badges al hover de la fila */
    .premium-table tbody tr:hover .badge-success-premium {
        background-color: rgba(40, 167, 69, 0.3);
        transform: scale(1.05);
    }

    .premium-table tbody tr:hover .badge-warning-premium {
        background-color: rgba(255, 193, 7, 0.3);
        transform: scale(1.05);
    }

    .premium-table tbody tr:hover .badge-danger-premium {
        background-color: rgba(220, 53, 69, 0.3);
        transform: scale(1.05);
    }

    /* --- Modal Oscuro --- */
    .modal-content {
        background-color: #2a2a2e;
        border: 1px solid var(--card-border);
        border-radius: var(--border-radius);
        color: var(--text-primary);
    }
    .modal-header { 
        border-bottom: 1px solid var(--card-border); 
        color: var(--gold); 
    }
    .modal-footer { 
        border-top: 1px solid var(--card-border); 
    }

    /* --- Botón Principal --- */
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

    /* --- Tooltips Mejorados --- */
    .btn-action {
        position: relative;
    }

    .btn-action::after {
        content: attr(title);
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
        z-index: 1000;
        border: 1px solid var(--card-border);
    }

    .btn-action:hover::after {
        opacity: 1;
    }

    /* --- Responsive para móviles --- */
    @media (max-width: 768px) {
        .premium-table thead th:nth-child(4),
        .premium-table tbody td:nth-child(4) {
            display: none;
        }
        
        .action-buttons {
            justify-content: flex-start;
        }
        
        .btn-action {
            width: 36px;
            height: 36px;
            font-size: 0.8rem;
        }
        
        .btn-action:hover {
            transform: translateY(-1px) scale(1.03);
        }
    }
</style>

<div class="container-fluid px-4 my-5">
    <h1 class="h3 mb-4 page-header"><i class="fas fa-microchip"></i> Gestión de Dispositivos</h1>
    
    <div class="premium-card">
        <div class="premium-card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-microchip me-1"></i>
                Dispositivos Registrados
            </div>
            <div>
                <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-gold">
                    <i class="fas fa-search me-1"></i> Buscar Dispositivos
                </a>
            </div>
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
                            <th>Descripción</th>
                            <th>MAC Address</th>
                            <th>Admin Dueño</th>
                            <th>Estado</th>
                            <th>Última Actualización</th>
                            <th style="width: 280px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dispositivos)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay dispositivos registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dispositivos as $dispositivo): ?>
                                <tr>
                                    <td><?= esc($dispositivo['nombre']) ?></td>
                                    <td><?= esc($dispositivo['descripcion'] ?? '') ?></td>
                                    <td><?= esc($dispositivo['mac_address']) ?></td>
                                    <td>
                                        <?= esc($dispositivo['nombre_admin'] ?? '-') ?><br>
                                        <small class="text-muted"><?= esc($dispositivo['email_admin'] ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $estadoClass = '';
                                        switch ($dispositivo['estado']) {
                                            case 'activo':
                                                $estadoClass = 'badge-success-premium';
                                                break;
                                            case 'pendiente':
                                                $estadoClass = 'badge-warning-premium';
                                                break;
                                            case 'inactivo':
                                                $estadoClass = 'badge-danger-premium';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $estadoClass ?>">
                                            <?= ucfirst($dispositivo['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= isset($dispositivo['fecha_actualizacion']) ? 
                                            date('d/m/Y H:i', strtotime($dispositivo['fecha_actualizacion'])) : 
                                            'Nunca' ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn-action btn-view" 
                                                    onclick="verDetalles(<?= $dispositivo['id_dispositivo'] ?>)"
                                                    title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-edit" 
                                                    onclick="editarDispositivo(<?= $dispositivo['id_dispositivo'] ?>, '<?= esc($dispositivo['nombre'], 'js') ?>', '<?= esc($dispositivo['descripcion'] ?? '', 'js') ?>')" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= base_url('dispositivo/control/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn-action btn-control" 
                                               title="Controlar Foco">
                                                <i class="fas fa-lightbulb"></i>
                                            </a>
                                            <?php if (in_array($dispositivo['estado'], ['pendiente', 'inactivo'])): ?>
                                                <a href="<?= base_url('admin/dispositivos/activar/' . $dispositivo['id_dispositivo']) ?>" 
                                                   class="btn-action btn-activate" 
                                                   onclick="return confirm('¿Estás seguro de activar este dispositivo?')"
                                                   title="Activar">
                                                    <i class="fas fa-power-off"></i>
                                                </a>
                                            <?php elseif ($dispositivo['estado'] === 'activo'): ?>
                                                <a href="<?= base_url('admin/dispositivos/desactivar/' . $dispositivo['id_dispositivo']) ?>" 
                                                   class="btn-action btn-deactivate" 
                                                   onclick="return confirm('¿Estás seguro de desactivar este dispositivo?')"
                                                   title="Desactivar">
                                                    <i class="fas fa-power-off"></i>
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn-action btn-delete" 
                                                    onclick="eliminarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <a href="<?= base_url('energia/dispositivo/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn-action btn-charts"
                                               title="Ver Lecturas">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
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

<!-- Modal para ver detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalles del Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detallesContenido"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar dispositivo -->
<div class="modal fade" id="editarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar dispositivo</h5>
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
                <div id="msgEditarAdmin"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary-premium" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gold" onclick="guardarEdicion()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
function verDetalles(id) {
    fetch(`<?= base_url('admin/dispositivos/detalles/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const dispositivo = data.dispositivo;
                document.getElementById('detallesContenido').innerHTML = `
                    <p><strong>ID:</strong> ${dispositivo.id_dispositivo}</p>
                    <p><strong>Nombre:</strong> ${dispositivo.nombre}</p>
                    <p><strong>MAC Address:</strong> ${dispositivo.mac_address}</p>
                    <p><strong>Estado:</strong> ${dispositivo.estado}</p>
                    <p><strong>Última Actualización:</strong> ${dispositivo.ultima_conexion}</p>
                `;
                new bootstrap.Modal(document.getElementById('detallesModal')).show();
            } else {
                alert('Error al cargar los detalles: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los detalles del dispositivo');
        });
}

function eliminarDispositivo(id) {
    if (confirm('¿Estás seguro de eliminar este dispositivo?')) {
        fetch(`<?= base_url('admin/dispositivos/eliminar/') ?>${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Error al eliminar el dispositivo: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el dispositivo');
        });
    }
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
    const formData = new FormData(form);
    fetch(form.action, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                msg.innerHTML = '<div class="premium-alert alert-success">Dispositivo actualizado</div>';
                setTimeout(() => { location.reload(); }, 800);
            } else {
                msg.innerHTML = '<div class="premium-alert alert-danger">' + (res.error || 'Error') + '</div>';
            }
        })
        .catch(err => {
            msg.innerHTML = '<div class="premium-alert alert-danger">Error: ' + err + '</div>';
        });
}
</script>

<?= $this->endSection() ?>