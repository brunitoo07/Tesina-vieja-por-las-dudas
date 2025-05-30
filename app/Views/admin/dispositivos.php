<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Dispositivos</h2>
        <div>
            <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-success me-2">
                <i class="fas fa-wifi"></i> Buscar Dispositivos
            </a>
            <a href="<?= base_url('admin/dispositivos/registrar') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Nuevo Dispositivo
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Dispositivos Pendientes -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <h3 class="card-title mb-0">Dispositivos Pendientes</h3>
        </div>
        <div class="card-body">
            <?php if (empty($dispositivosPendientes)) : ?>
                <p class="text-muted">No hay dispositivos pendientes.</p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>MAC Address</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dispositivosPendientes as $dispositivo) : ?>
                                <tr>
                                    <td><?= esc($dispositivo['nombre']) ?></td>
                                    <td>
                                        <code><?= esc($dispositivo['mac_address']) ?></code>
                                        <button class="btn btn-sm btn-outline-secondary ms-2" 
                                                onclick="copiarMAC('<?= esc($dispositivo['mac_address']) ?>')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                    <td><?= esc($dispositivo['stock']) ?></td>
                                    <td>$<?= number_format($dispositivo['precio'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-warning">Pendiente</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" 
                                                onclick="activarDispositivo('<?= $dispositivo['mac_address'] ?>')">
                                            <i class="fas fa-power-off"></i> Activar
                                        </button>
                                        <button class="btn btn-danger btn-sm" 
                                                onclick="eliminarDispositivo('<?= $dispositivo['mac_address'] ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Dispositivos Activos -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="card-title mb-0">Dispositivos Activos</h3>
        </div>
        <div class="card-body">
            <?php if (empty($dispositivosActivos)) : ?>
                <p class="text-muted">No hay dispositivos activos.</p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>MAC Address</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>Descripción</th>
                                <th>Última Actualización</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dispositivosActivos as $dispositivo) : ?>
                                <tr>
                                    <td><?= esc($dispositivo['nombre']) ?></td>
                                    <td>
                                        <code><?= esc($dispositivo['mac_address']) ?></code>
                                        <button class="btn btn-sm btn-outline-secondary ms-2" 
                                                onclick="copiarMAC('<?= esc($dispositivo['mac_address']) ?>')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                    <td><?= esc($dispositivo['stock']) ?></td>
                                    <td>$<?= number_format($dispositivo['precio'], 2) ?></td>
                                    <td><?= esc($dispositivo['descripcion']) ?></td>
                                    <td><?= $dispositivo['fecha_actualizacion'] ? date('d/m/Y H:i', strtotime($dispositivo['fecha_actualizacion'])) : 'Nunca' ?></td>
                                    <td>
                                        <span class="badge bg-success">Activo</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" 
                                                onclick="desactivarDispositivo('<?= $dispositivo['mac_address'] ?>')">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                        <button class="btn btn-info btn-sm" 
                                                onclick="verDetalles('<?= $dispositivo['mac_address'] ?>')">
                                            <i class="fas fa-chart-line"></i>
                                        </button>
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

<!-- Modal de Detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detallesContenido">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copiarMAC(mac) {
    navigator.clipboard.writeText(mac).then(() => {
        alert('MAC address copiada al portapapeles');
    });
}

function activarDispositivo(macAddress) {
    if (confirm('¿Estás seguro de que deseas activar este dispositivo?')) {
        fetch('<?= base_url('api/dispositivo/activar') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                mac_address: macAddress
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Error al activar el dispositivo: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al comunicarse con el servidor');
        });
    }
}

function desactivarDispositivo(macAddress) {
    if (confirm('¿Estás seguro de que deseas desactivar este dispositivo?')) {
        fetch('<?= base_url('api/dispositivo/actualizarEstado') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                mac_address: macAddress,
                estado: 'inactivo'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Error al desactivar el dispositivo: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al comunicarse con el servidor');
        });
    }
}

function eliminarDispositivo(macAddress) {
    if (confirm('¿Estás seguro de que deseas eliminar este dispositivo? Esta acción no se puede deshacer.')) {
        fetch('<?= base_url('api/dispositivo/eliminar') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                mac_address: macAddress
            })
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
            alert('Error al comunicarse con el servidor');
        });
    }
}

function verDetalles(macAddress) {
    fetch('<?= base_url('api/dispositivo/detalles') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            mac_address: macAddress
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('detallesContenido').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Información del Dispositivo</h6>
                        <p><strong>Nombre:</strong> ${data.data.nombre}</p>
                        <p><strong>MAC:</strong> ${data.data.mac_address}</p>
                        <p><strong>Estado:</strong> ${data.data.estado}</p>
                        <p><strong>Última Actualización:</strong> ${data.data.fecha_actualizacion}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Estadísticas</h6>
                        <p><strong>Stock Actual:</strong> ${data.data.stock}</p>
                        <p><strong>Precio:</strong> $${data.data.precio}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <h6>Descripción</h6>
                    <p>${data.data.descripcion || 'Sin descripción'}</p>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('detallesModal')).show();
        } else {
            alert('Error al obtener detalles: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al comunicarse con el servidor');
    });
}
</script>

<?= $this->endSection() ?> 