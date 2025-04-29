<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Dispositivos de <?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
        </h1>
        <a href="<?= base_url('supervisor/gestionarUsuarios') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Resumen de dispositivos -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Dispositivos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($dispositivos) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-microchip fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Dispositivos Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($dispositivos, function($d) { return $d['estado'] === 'activo'; })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Consumo Total (24h)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($consumoTotal24h ?? 0, 2) ?> kWh
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bolt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Promedio Diario</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_format($promedioDiario ?? 0, 2) ?> kWh
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($dispositivos)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Este usuario no tiene dispositivos registrados.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($dispositivos as $dispositivo): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-microchip"></i> <?= esc($dispositivo['nombre']) ?>
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-link" type="button" id="dropdownMenuButton<?= $dispositivo['id_dispositivo'] ?>" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $dispositivo['id_dispositivo'] ?>">
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url('consumo/ver/' . $dispositivo['id_dispositivo']) ?>">
                                            <i class="fas fa-chart-line"></i> Ver Consumo
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="cambiarEstado(<?= $dispositivo['id_dispositivo'] ?>, '<?= $dispositivo['estado'] ?>')">
                                            <i class="fas fa-power-off"></i> Cambiar Estado
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="editarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="card-text">
                                        <strong>MAC Address:</strong> <?= esc($dispositivo['mac_address']) ?><br>
                                        <strong>Estado:</strong> 
                                        <span class="badge bg-<?= $dispositivo['estado'] === 'activo' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($dispositivo['estado']) ?>
                                        </span><br>
                                        <strong>Última Lectura:</strong> 
                                        <?= $dispositivo['ultima_lectura'] ? date('d/m/Y H:i', strtotime($dispositivo['ultima_lectura'])) : 'Sin lecturas' ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="chart<?= $dispositivo['id_dispositivo'] ?>" width="200" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                Registrado: <?= date('d/m/Y', strtotime($dispositivo['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para editar dispositivo -->
<div class="modal fade" id="editarDispositivoModal" tabindex="-1" aria-labelledby="editarDispositivoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarDispositivoModalLabel">Editar Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editarDispositivoForm">
                    <input type="hidden" id="id_dispositivo" name="id_dispositivo">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="mac_address" class="form-label">MAC Address</label>
                        <input type="text" class="form-control" id="mac_address" name="mac_address" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCambios()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-title {
        color: #4e73df;
        margin-bottom: 1rem;
    }
    .badge {
        padding: 0.5em 1em;
    }
    .dropdown-menu {
        min-width: 200px;
    }
    .dropdown-item i {
        width: 20px;
        text-align: center;
        margin-right: 8px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar gráficos para cada dispositivo
    <?php foreach ($dispositivos as $dispositivo): ?>
    fetch('<?= base_url("consumo/grafico/" . $dispositivo['id_dispositivo']) ?>')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('chart<?= $dispositivo['id_dispositivo'] ?>').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Consumo (kWh)',
                        data: data.consumo,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    <?php endforeach; ?>
});

function cambiarEstado(idDispositivo, estadoActual) {
    const nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';
    if (confirm(`¿Estás seguro de que deseas cambiar el estado del dispositivo a ${nuevoEstado}?`)) {
        fetch('<?= base_url("dispositivo/cambiarEstado") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id_dispositivo: idDispositivo,
                estado: nuevoEstado
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al cambiar el estado del dispositivo');
            }
        });
    }
}

function editarDispositivo(idDispositivo) {
    fetch(`<?= base_url("dispositivo/obtener/") ?>${idDispositivo}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('id_dispositivo').value = data.id_dispositivo;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('mac_address').value = data.mac_address;
            new bootstrap.Modal(document.getElementById('editarDispositivoModal')).show();
        });
}

function guardarCambios() {
    const formData = new FormData(document.getElementById('editarDispositivoForm'));
    fetch('<?= base_url("dispositivo/actualizar") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al actualizar el dispositivo');
        }
    });
}
</script>

<?= $this->endSection() ?> 