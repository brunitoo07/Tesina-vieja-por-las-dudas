<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Dispositivos</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-microchip me-1"></i>
                Dispositivos Registrados
            </div>
            <div>
                <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i> Buscar Dispositivos
                </a>
                <a href="<?= base_url('admin/dispositivos/registrar') ?>" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Registrar Nuevo
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>MAC Address</th>
                            <th>Estado</th>
                            <th>Última Conexión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dispositivos as $dispositivo): ?>
                            <tr>
                                <td><?= $dispositivo['id_dispositivo'] ?></td>
                                <td><?= esc($dispositivo['nombre']) ?></td>
                                <td><?= esc($dispositivo['mac_address']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $dispositivo['estado'] === 'activo' ? 'success' : ($dispositivo['estado'] === 'pendiente' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($dispositivo['estado']) ?>
                                    </span>
                                </td>
                                <td><?= isset($dispositivo['ultima_conexion']) && $dispositivo['ultima_conexion'] ? date('d/m/Y H:i', strtotime($dispositivo['ultima_conexion'])) : 'Nunca' ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="verDetalles(<?= $dispositivo['id_dispositivo'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($dispositivo['estado'] === 'activo'): ?>
                                            <a href="<?= base_url('admin/dispositivos/desactivar/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn btn-sm btn-warning" 
                                               onclick="return confirm('¿Estás seguro de desactivar este dispositivo?')">
                                                <i class="fas fa-power-off"></i>
                                            </a>
                                        <?php elseif ($dispositivo['estado'] === 'inactivo'): ?>
                                            <a href="<?= base_url('admin/dispositivos/activar/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn btn-sm btn-success" 
                                               onclick="return confirm('¿Estás seguro de activar este dispositivo?')">
                                                <i class="fas fa-power-off"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="eliminarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesBody">
                <!-- Los detalles se cargarán aquí dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detallesModal = new bootstrap.Modal(document.getElementById('detallesModal'));
    
    window.verDetalles = async function(id) {
        try {
            const response = await fetch(`<?= base_url('admin/dispositivos/detalles') ?>/${id}`);
            const data = await response.json();
            
            if (data.status === 'success') {
                const dispositivo = data.dispositivo;
                document.getElementById('detallesBody').innerHTML = `
                    <dl class="row">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8">${dispositivo.id_dispositivo}</dd>
                        
                        <dt class="col-sm-4">Nombre</dt>
                        <dd class="col-sm-8">${dispositivo.nombre}</dd>
                        
                        <dt class="col-sm-4">MAC Address</dt>
                        <dd class="col-sm-8">${dispositivo.mac_address}</dd>
                        
                        <dt class="col-sm-4">Estado</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-${dispositivo.estado === 'activo' ? 'success' : (dispositivo.estado === 'pendiente' ? 'warning' : 'danger')}">
                                ${dispositivo.estado.charAt(0).toUpperCase() + dispositivo.estado.slice(1)}
                            </span>
                        </dd>
                        
                        <dt class="col-sm-4">Última Conexión</dt>
                        <dd class="col-sm-8">${dispositivo.ultima_conexion ? new Date(dispositivo.ultima_conexion).toLocaleString() : 'Nunca'}</dd>
                    </dl>
                `;
                detallesModal.show();
            } else {
                alert('Error al cargar los detalles del dispositivo');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar los detalles del dispositivo');
        }
    }

    window.eliminarDispositivo = function(id) {
        if (confirm('¿Está seguro de eliminar este dispositivo? Esta acción no se puede deshacer.')) {
            window.location.href = `<?= base_url('admin/dispositivos/eliminar') ?>/${id}`;
        }
    }
});
</script>
<?= $this->endSection() ?> 