<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Dispositivos</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-microchip me-1"></i>
                Dispositivos Registrados
            </div>
            <div>
                <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> Buscar Dispositivos
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
                            <th>Última Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dispositivos)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay dispositivos registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dispositivos as $dispositivo): ?>
                                <tr>
                                    <td><?= $dispositivo['id_dispositivo'] ?></td>
                                    <td><?= esc($dispositivo['nombre']) ?></td>
                                    <td><?= esc($dispositivo['mac_address']) ?></td>
                                    <td>
                                        <?php
                                        $estadoClass = '';
                                        switch ($dispositivo['estado']) {
                                            case 'activo':
                                                $estadoClass = 'success';
                                                break;
                                            case 'pendiente':
                                                $estadoClass = 'warning';
                                                break;
                                            case 'inactivo':
                                                $estadoClass = 'danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge bg-<?= $estadoClass ?>">
                                            <?= ucfirst($dispositivo['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= isset($dispositivo['fecha_actualizacion']) ? 
                                            date('d/m/Y H:i', strtotime($dispositivo['fecha_actualizacion'])) : 
                                            'Nunca' ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="verDetalles(<?= $dispositivo['id_dispositivo'] ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if (in_array($dispositivo['estado'], ['pendiente', 'inactivo'])): ?>
                                                <a href="<?= base_url('admin/dispositivos/activar/' . $dispositivo['id_dispositivo']) ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   onclick="return confirm('¿Estás seguro de activar este dispositivo?')">
                                                    <i class="fas fa-power-off"></i> Activar
                                                </a>
                                            <?php elseif ($dispositivo['estado'] === 'activo'): ?>
                                                <a href="<?= base_url('admin/dispositivos/desactivar/' . $dispositivo['id_dispositivo']) ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   onclick="return confirm('¿Estás seguro de desactivar este dispositivo?')">
                                                    <i class="fas fa-power-off"></i> Desactivar
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="eliminarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <a href="<?= base_url('energia/dispositivo/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-chart-line"></i> Ver Lecturas
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
                <h5 class="modal-title">Detalles del Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detallesContenido"></div>
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
</script>

<?= $this->endSection() ?> 