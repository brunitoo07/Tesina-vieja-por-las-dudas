<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<style>
    /* === PALETA DE COLORES PREMIUM MEJORADA === */
    :root {
        --gold: #D4AF37;
        --gold-light: #EAD58B;
        --gold-dark: #B8941F;
        --black-bg: #121212;
        --card-bg: #1B1B1E;
        --card-bg-light: #232326;
        --card-border: rgba(255, 255, 255, 0.08);
        --text-primary: #FFFFFF;
        --text-secondary: #E0E0E0;
        --text-muted: #A0A0A0;
        --glow-color: rgba(212, 175, 55, 0.3);
        --danger: #e74c3c;
        --success: #2ecc71;
        --warning: #f39c12;
        --info: #3498db;
        --border-radius: 16px;
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* === IMPORTACIÓN DE FUENTES MEJORADA === */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
        background-color: var(--black-bg);
        color: var(--text-primary);
        font-family: 'Inter', sans-serif;
        line-height: 1.6;
        background-image: 
            radial-gradient(circle at 15% 50%, rgba(41, 41, 41, 0.3) 0%, transparent 25%),
            radial-gradient(circle at 85% 30%, rgba(41, 41, 41, 0.2) 0%, transparent 25%);
    }

    /* === ANIMACIONES MEJORADAS === */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideIn {
        from { transform: translateX(-10px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* === CONTENEDOR PRINCIPAL MEJORADO === */
    .premium-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--border-radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        animation: fadeIn 0.6s ease-out forwards;
        overflow: hidden;
    }
    
    .premium-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--card-border);
        background: var(--card-bg-light);
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gold-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .premium-card-header i { 
        margin-right: 0.75rem;
        color: var(--gold);
    }
    
    .premium-card-body { 
        padding: 2rem;
    }

    /* === ENCABEZADO DE PÁGINA MEJORADO === */
    .page-header { 
        color: var(--text-primary);
        margin-bottom: 2rem;
        font-weight: 700;
        font-size: 1.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-header i { 
        color: var(--gold);
        font-size: 1.5rem;
    }

    /* === ALERTAS MEJORADAS === */
    .premium-alert {
        border-radius: 12px;
        border-width: 1px;
        border-style: solid;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        animation: slideIn 0.4s ease-out;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .premium-alert.alert-success { 
        background-color: rgba(46, 204, 113, 0.1);
        border-color: var(--success);
        color: #d4f5e0;
    }
    
    .premium-alert.alert-danger { 
        background-color: rgba(231, 76, 60, 0.1);
        border-color: var(--danger);
        color: #fadbd8;
    }
    
    .premium-alert.alert-info { 
        background-color: rgba(52, 152, 219, 0.1);
        border-color: var(--info);
        color: #d6eaf8;
    }
    
    .btn-close { 
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.7;
        margin-left: auto;
    }
    
    .btn-close:hover {
        opacity: 1;
    }

    /* === TABLA MEJORADA === */
    .premium-table { 
        color: var(--text-primary);
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    
    .premium-table thead th {
        border: none;
        color: var(--gold-light);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 1.25rem 1rem;
        background-color: rgba(0,0,0,0.25);
        border-bottom: 1px solid var(--card-border);
    }
    
    .premium-table tbody tr {
        transition: var(--transition);
        border-bottom: 1px solid var(--card-border);
    }
    
    .premium-table tbody tr:last-child { 
        border-bottom: none; 
    }
    
    .premium-table tbody td { 
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border: none;
        font-size: 0.9rem;
    }
    
    .premium-table tbody tr:hover { 
        background-color: rgba(255,255,255,0.05);
        transform: translateY(-1px);
    }

    /* === BOTONES DE ACCIÓN MEJORADOS === */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        justify-content: center;
        align-items: center;
        min-height: 44px;
    }

    .btn-action {
        background: transparent;
        border: 1px solid var(--card-border);
        border-radius: 10px;
        transition: var(--transition);
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

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
        border-radius: 8px;
    }

    .btn-action:hover::before {
        opacity: 0.1;
    }

    .btn-action:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
    }

    /* Colores específicos para botones */
    .btn-action.btn-view {
        color: var(--text-secondary);
        border-color: var(--card-border);
    }
    .btn-action.btn-view:hover {
        color: var(--gold-light);
        border-color: var(--gold);
        box-shadow: 0 6px 18px rgba(212, 175, 55, 0.3);
    }

    .btn-action.btn-edit {
        color: var(--text-secondary);
        border-color: var(--card-border);
    }
    .btn-action.btn-edit:hover {
        color: var(--info);
        border-color: var(--info);
        box-shadow: 0 6px 18px rgba(52, 152, 219, 0.3);
    }

    .btn-action.btn-control {
        color: var(--warning);
        border-color: var(--warning);
    }
    .btn-action.btn-control:hover {
        color: var(--warning);
        border-color: var(--warning);
        background-color: rgba(243, 156, 18, 0.1);
        box-shadow: 0 6px 18px rgba(243, 156, 18, 0.3);
    }

    .btn-action.btn-activate {
        color: var(--success);
        border-color: var(--success);
    }
    .btn-action.btn-activate:hover {
        color: var(--success);
        border-color: var(--success);
        background-color: rgba(46, 204, 113, 0.1);
        box-shadow: 0 6px 18px rgba(46, 204, 113, 0.3);
        animation: pulse-green 0.6s ease-in-out;
    }

    .btn-action.btn-deactivate {
        color: var(--warning);
        border-color: var(--warning);
    }
    .btn-action.btn-deactivate:hover {
        color: var(--warning);
        border-color: var(--warning);
        background-color: rgba(243, 156, 18, 0.1);
        box-shadow: 0 6px 18px rgba(243, 156, 18, 0.3);
        animation: pulse-yellow 0.6s ease-in-out;
    }

    .btn-action.btn-delete {
        color: var(--danger);
        border-color: var(--danger);
    }
    .btn-action.btn-delete:hover {
        color: var(--danger);
        border-color: var(--danger);
        background-color: rgba(231, 76, 60, 0.1);
        box-shadow: 0 6px 18px rgba(231, 76, 60, 0.3);
        animation: pulse-red 0.6s ease-in-out;
    }

    .btn-action.btn-charts {
        color: #9b59b6;
        border-color: #9b59b6;
    }
    .btn-action.btn-charts:hover {
        color: #9b59b6;
        border-color: #9b59b6;
        background-color: rgba(155, 89, 182, 0.1);
        box-shadow: 0 6px 18px rgba(155, 89, 182, 0.3);
    }

    /* Animaciones de pulso mejoradas */
    @keyframes pulse-green {
        0%, 100% { transform: translateY(-2px) scale(1.05); }
        50% { transform: translateY(-2px) scale(1.08); }
    }

    @keyframes pulse-yellow {
        0%, 100% { transform: translateY(-2px) scale(1.05); }
        50% { transform: translateY(-2px) scale(1.08); }
    }

    @keyframes pulse-red {
        0%, 100% { transform: translateY(-2px) scale(1.05); }
        50% { transform: translateY(-2px) scale(1.08); }
    }

    /* === BADGES MEJORADOS === */
    .badge-status {
        padding: 0.5rem 0.9rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .badge-status::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .badge-status.active {
        background-color: rgba(46, 204, 113, 0.15);
        color: var(--success);
        border: 1px solid rgba(46, 204, 113, 0.3);
    }
    
    .badge-status.active::before {
        background-color: var(--success);
    }

    .badge-status.pending {
        background-color: rgba(243, 156, 18, 0.15);
        color: var(--warning);
        border: 1px solid rgba(243, 156, 18, 0.3);
    }
    
    .badge-status.pending::before {
        background-color: var(--warning);
    }

    .badge-status.inactive {
        background-color: rgba(231, 76, 60, 0.15);
        color: var(--danger);
        border: 1px solid rgba(231, 76, 60, 0.3);
    }
    
    .badge-status.inactive::before {
        background-color: var(--danger);
    }

    .premium-table tbody tr:hover .badge-status {
        transform: scale(1.05);
    }

    /* === BOTÓN PRINCIPAL MEJORADO === */
    .btn-gold {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        color: var(--black-bg);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 20px var(--glow-color);
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-gold:hover { 
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5);
        background: linear-gradient(135deg, var(--gold-light) 0%, var(--gold) 100%);
        color: var(--black-bg);
    }

    /* === TOOLTIPS MEJORADOS === */
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

    /* === ESTADOS VACÍOS MEJORADOS === */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--card-border);
    }
    
    .empty-state h4 {
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        margin-bottom: 1.5rem;
    }

    /* === MODALES MEJORADOS === */
    .modal-content {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--border-radius);
        color: var(--text-primary);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }
    
    .modal-header { 
        border-bottom: 1px solid var(--card-border);
        color: var(--gold);
        padding: 1.5rem 2rem;
    }
    
    .modal-body {
        padding: 2rem;
    }
    
    .modal-footer { 
        border-top: 1px solid var(--card-border);
        padding: 1.5rem 2rem;
    }
    
    .form-control, .form-select {
        background-color: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--card-border);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }
    
    .form-control:focus, .form-select:focus {
        background-color: rgba(0, 0, 0, 0.3);
        border-color: var(--gold);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
    }
    
    .form-label {
        color: var(--text-secondary);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    /* === RESPONSIVE MEJORADO === */
    @media (max-width: 1200px) {
        .premium-table thead th:nth-child(4),
        .premium-table tbody td:nth-child(4) {
            display: none;
        }
    }
    
    @media (max-width: 992px) {
        .premium-table thead th:nth-child(6),
        .premium-table tbody td:nth-child(6) {
            display: none;
        }
        
        .action-buttons {
            justify-content: flex-start;
        }
    }
    
    @media (max-width: 768px) {
        .premium-card-body {
            padding: 1.5rem;
        }
        
        .premium-table thead th:nth-child(2),
        .premium-table tbody td:nth-child(2) {
            display: none;
        }
        
        .btn-action {
            width: 38px;
            height: 38px;
            font-size: 0.85rem;
        }
        
        .btn-action:hover {
            transform: translateY(-1px) scale(1.03);
        }
        
        .page-header {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .premium-card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .premium-table thead th:nth-child(3),
        .premium-table tbody td:nth-child(3) {
            display: none;
        }
        
        .action-buttons {
            gap: 0.3rem;
        }
        
        .btn-action {
            width: 36px;
            height: 36px;
        }
    }
</style>

<!-- === CONTENIDO PRINCIPAL MEJORADO === -->
<div class="container-fluid px-3 px-md-4 my-4 my-md-5">
    <!-- Título de la página con icono -->
    <h1 class="page-header">
        <i class="fas fa-microchip"></i> Gestionar Dispositivos
    </h1>
    
    <!-- Tarjeta principal que contiene la lista de dispositivos -->
    <div class="premium-card">
        <!-- Encabezado de la tarjeta con botón de búsqueda -->
        <div class="premium-card-header">
            <div>
                <i class="fas fa-list me-2"></i>
                Dispositivos Registrados
            </div>
            <div>
                <!-- Botón para buscar dispositivos -->
                <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-gold">
                    <i class="fas fa-search me-2"></i> Buscar Dispositivos
                </a>
            </div>
        </div>
        
        <!-- Cuerpo de la tarjeta -->
        <div class="premium-card-body">
            <!-- === MENSAJES DE ÉXITO === -->
            <?php if (session()->has('success')): ?>
                <div class="premium-alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- === MENSAJES DE ERROR === -->
            <?php if (session()->has('error')): ?>
                <div class="premium-alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- === TABLA DE DISPOSITIVOS === -->
            <div class="table-responsive">
                <table class="table premium-table">
                    <!-- Encabezados de la tabla -->
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
                    
                    <!-- Cuerpo de la tabla con datos de dispositivos -->
                    <tbody>
                        <?php if (empty($dispositivos)): ?>
                            <!-- Estado vacío mejorado -->
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-microchip-slash"></i>
                                        <h4>No hay dispositivos registrados</h4>
                                        <p>Comienza agregando tu primer dispositivo al sistema</p>
                                        <a href="<?= base_url('admin/dispositivos/agregar') ?>" class="btn btn-gold">
                                            <i class="fas fa-plus me-2"></i> Agregar Dispositivo
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dispositivos as $dispositivo): ?>
                                <tr>
                                    <!-- Nombre del dispositivo -->
                                    <td>
                                        <div class="fw-medium"><?= esc($dispositivo['nombre']) ?></div>
                                    </td>
                                    
                                    <!-- Descripción del dispositivo -->
                                    <td>
                                        <span class="text-muted"><?= esc($dispositivo['descripcion'] ?? 'Sin descripción') ?></span>
                                    </td>
                                    
                                    <!-- Dirección MAC -->
                                    <td>
                                        <code class="text-info"><?= esc($dispositivo['mac_address']) ?></code>
                                    </td>
                                    
                                    <!-- Información del administrador propietario -->
                                    <td>
                                        <div class="fw-medium"><?= esc($dispositivo['nombre_admin'] ?? '-') ?></div>
                                        <small class="text-muted"><?= esc($dispositivo['email_admin'] ?? '-') ?></small>
                                    </td>
                                    
                                    <!-- === ESTADO DEL DISPOSITIVO === -->
                                    <td>
                                        <?php
                                        // Determinar clase CSS según el estado
                                        $estadoClass = '';
                                        switch ($dispositivo['estado']) {
                                            case 'activo':
                                                $estadoClass = 'active';
                                                break;
                                            case 'pendiente':
                                                $estadoClass = 'pending';
                                                break;
                                            case 'inactivo':
                                                $estadoClass = 'inactive';
                                                break;
                                        }
                                        ?>
                                        <!-- Badge con el estado del dispositivo -->
                                        <span class="badge-status <?= $estadoClass ?>">
                                            <?= ucfirst($dispositivo['estado']) ?>
                                        </span>
                                    </td>
                                    
                                    <!-- Fecha de última actualización -->
                                    <td>
                                        <div class="text-nowrap">
                                            <?= isset($dispositivo['fecha_actualizacion']) ? 
                                                date('d/m/Y H:i', strtotime($dispositivo['fecha_actualizacion'])) : 
                                                '<span class="text-muted">Nunca</span>' ?>
                                        </div>
                                    </td>
                                    
                                    <!-- === BOTONES DE ACCIÓN === -->
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Botón para ver detalles -->
                                            <button type="button" class="btn-action btn-view" 
                                                    onclick="verDetalles(<?= $dispositivo['id_dispositivo'] ?>)"
                                                    title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- Botón para editar dispositivo -->
                                            <button type="button" class="btn-action btn-edit" 
                                                    onclick="editarDispositivo(<?= $dispositivo['id_dispositivo'] ?>, '<?= esc($dispositivo['nombre'], 'js') ?>', '<?= esc($dispositivo['descripcion'] ?? '', 'js') ?>')" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <!-- Botón para historial de cortes -->
                                            <a href="<?= base_url('energia/cortes?dispositivo=' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn-action btn-control" 
                                               title="Historial de Cortes">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </a>
                                            
                                            <!-- === BOTONES DE ACTIVAR/DESACTIVAR === -->
                                            <?php if (in_array($dispositivo['estado'], ['pendiente', 'inactivo'])): ?>
                                                <!-- Botón para activar dispositivo -->
                                                <a href="<?= base_url('admin/dispositivos/activar/' . $dispositivo['id_dispositivo']) ?>" 
                                                   class="btn-action btn-activate" 
                                                   onclick="return confirm('¿Estás seguro de activar este dispositivo?')"
                                                   title="Activar">
                                                    <i class="fas fa-power-off"></i>
                                                </a>
                                            <?php elseif ($dispositivo['estado'] === 'activo'): ?>
                                                <!-- Botón para desactivar dispositivo -->
                                                <a href="<?= base_url('admin/dispositivos/desactivar/' . $dispositivo['id_dispositivo']) ?>" 
                                                   class="btn-action btn-deactivate" 
                                                   onclick="return confirm('¿Estás seguro de desactivar este dispositivo?')"
                                                   title="Desactivar">
                                                    <i class="fas fa-power-off"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <!-- Botón para eliminar dispositivo -->
                                            <button type="button" class="btn-action btn-delete" 
                                                    onclick="eliminarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            
                                            <!-- Botón para ver lecturas de energía -->
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

<!-- === MODAL PARA VER DETALLES === -->
<div class="modal fade" id="detallesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalles del Dispositivo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <div id="detallesContenido"></div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- === MODAL PARA EDITAR DISPOSITIVO === -->
<div class="modal fade" id="editarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar dispositivo
                </h5>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gold" onclick="guardarEdicion()">
                    <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- === JAVASCRIPT MEJORADO === -->
<script>
// === FUNCIÓN PARA VER DETALLES DEL DISPOSITIVO ===
function verDetalles(id) {
    // Mostrar indicador de carga
    document.getElementById('detallesContenido').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-gold" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-muted">Cargando detalles del dispositivo...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('detallesModal'));
    modal.show();
    
    // Hacer petición AJAX para obtener detalles del dispositivo
    fetch(`<?= base_url('admin/dispositivos/detalles/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const dispositivo = data.dispositivo;
                // Formatear la información del dispositivo
                document.getElementById('detallesContenido').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">ID</label>
                                <p class="fw-medium">${dispositivo.id_dispositivo}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Nombre</label>
                                <p class="fw-medium">${dispositivo.nombre}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">MAC Address</label>
                                <p><code>${dispositivo.mac_address}</code></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Estado</label>
                                <p><span class="badge-status ${dispositivo.estado}">${dispositivo.estado}</span></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Última Conexión</label>
                                <p>${dispositivo.ultima_conexion || 'No disponible'}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Descripción</label>
                                <p>${dispositivo.descripcion || 'Sin descripción'}</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('detallesContenido').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error al cargar los detalles: ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('detallesContenido').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles del dispositivo
                </div>
            `;
        });
}

// === FUNCIÓN PARA ELIMINAR DISPOSITIVO ===
function eliminarDispositivo(id) {
    // Confirmar antes de eliminar con un modal más atractivo
    if (confirm('¿Estás seguro de eliminar este dispositivo?\nEsta acción no se puede deshacer.')) {
        // Mostrar indicador de carga
        const originalText = event.target.innerHTML;
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        event.target.disabled = true;
        
        // Hacer petición AJAX para eliminar el dispositivo
        fetch(`<?= base_url('admin/dispositivos/eliminar/') ?>${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Mostrar mensaje de éxito y recargar
                showToast('Dispositivo eliminado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('Error al eliminar: ' + data.message, 'error');
                event.target.innerHTML = originalText;
                event.target.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error al eliminar el dispositivo', 'error');
            event.target.innerHTML = originalText;
            event.target.disabled = false;
        });
    }
}

// === FUNCIÓN PARA EDITAR DISPOSITIVO ===
function editarDispositivo(id, nombre, descripcion) {
    document.getElementById('editId').value = id;
    document.getElementById('editNombre').value = nombre || '';
    document.getElementById('editDescripcion').value = descripcion || '';
    document.getElementById('msgEditarAdmin').innerHTML = '';
    
    const modal = new bootstrap.Modal(document.getElementById('editarModal'));
    modal.show();
}

// === FUNCIÓN PARA GUARDAR EDICIÓN ===
function guardarEdicion() {
    const form = document.getElementById('formEditarAdmin');
    const msg = document.getElementById('msgEditarAdmin');
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    
    // Validación básica
    if (!form.editNombre.value.trim()) {
        msg.innerHTML = '<div class="premium-alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>El nombre es requerido</div>';
        return;
    }
    
    msg.innerHTML = '';
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
    submitBtn.disabled = true;
    
    const formData = new FormData(form);
    
    fetch(form.action, { 
        method: 'POST', 
        body: formData 
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            msg.innerHTML = '<div class="premium-alert alert-success"><i class="fas fa-check-circle me-2"></i>Dispositivo actualizado correctamente</div>';
            setTimeout(() => { 
                location.reload(); 
            }, 1000);
        } else {
            msg.innerHTML = '<div class="premium-alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>' + (res.error || 'Error al actualizar') + '</div>';
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(err => {
        msg.innerHTML = '<div class="premium-alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error de conexión</div>';
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// === FUNCIÓN PARA MOSTRAR TOASTS ===
function showToast(message, type = 'info') {
    // Crear contenedor de toasts si no existe
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    const toastId = 'toast-' + Date.now();
    const bgColor = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${icon} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
    toast.show();
    
    // Eliminar el toast del DOM cuando se oculte
    toastElement.addEventListener('hidden.bs.toast', function () {
        toastElement.remove();
    });
}

// === INICIALIZACIÓN ===
document.addEventListener('DOMContentLoaded', function() {
    // Agregar estilos para los toasts
    const toastStyles = document.createElement('style');
    toastStyles.textContent = `
        .toast-container { z-index: 9999; }
        .toast { backdrop-filter: blur(10px); }
    `;
    document.head.appendChild(toastStyles);
});
</script>

<?= $this->endSection() ?>