<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line me-2"></i>
            Lecturas de Energía - <?= esc($dispositivo['nombre']) ?>
        </h1>
        <a href="<?= base_url('supervisor/dispositivosUsuarios/' . $dispositivo['id_usuario']) ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <form id="filtroForm" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                </div>
                <div class="col-md-4">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i>Filtrar
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                        <i class="fas fa-times me-2"></i>Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Gráfico de Consumo</h6>
        </div>
        <div class="card-body">
            <canvas id="graficoConsumo"></canvas>
        </div>
    </div>

    <!-- Tabla de Lecturas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Lecturas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tablaLecturas">
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Voltaje (V)</th>
                            <th>Corriente (A)</th>
                            <th>Potencia (W)</th>
                            <th>Energía (kWh)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lecturas as $lectura): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i:s', strtotime($lectura['fecha_hora'])) ?></td>
                                <td><?= number_format($lectura['voltaje'], 2) ?></td>
                                <td><?= number_format($lectura['corriente'], 2) ?></td>
                                <td><?= number_format($lectura['potencia'], 2) ?></td>
                                <td><?= number_format($lectura['energia'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let graficoConsumo;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el gráfico
    const ctx = document.getElementById('graficoConsumo').getContext('2d');
    graficoConsumo = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Potencia (W)',
                data: [],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Cargar datos iniciales
    cargarLecturas();

    // Manejar el formulario de filtros
    document.getElementById('filtroForm').addEventListener('submit', function(e) {
        e.preventDefault();
        cargarLecturas();
    });
});

function cargarLecturas() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    
    fetch(`<?= base_url('supervisor/obtenerLecturasDispositivo/' . $dispositivo['id_dispositivo']) ?>?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                actualizarTabla(data.lecturas);
                actualizarGrafico(data.lecturas);
            } else {
                alert('Error al cargar las lecturas');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar las lecturas');
        });
}

function actualizarTabla(lecturas) {
    const tbody = document.querySelector('#tablaLecturas tbody');
    tbody.innerHTML = '';
    
    lecturas.forEach(lectura => {
        tbody.innerHTML += `
            <tr>
                <td>${new Date(lectura.fecha_hora).toLocaleString()}</td>
                <td>${Number(lectura.voltaje).toFixed(2)}</td>
                <td>${Number(lectura.corriente).toFixed(2)}</td>
                <td>${Number(lectura.potencia).toFixed(2)}</td>
                <td>${Number(lectura.energia).toFixed(2)}</td>
            </tr>
        `;
    });
}

function actualizarGrafico(lecturas) {
    const labels = lecturas.map(l => new Date(l.fecha_hora).toLocaleString());
    const datos = lecturas.map(l => l.potencia);

    graficoConsumo.data.labels = labels;
    graficoConsumo.data.datasets[0].data = datos;
    graficoConsumo.update();
}

function limpiarFiltros() {
    document.getElementById('fecha_inicio').value = '';
    document.getElementById('fecha_fin').value = '';
    cargarLecturas();
}
</script>
<?= $this->endSection() ?> 