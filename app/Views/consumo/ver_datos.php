<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line me-2"></i>
            Lecturas de Energía - <?= esc($dispositivo['nombre']) ?>
        </h1>
        <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
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
            <?php if (empty($lecturas)): ?>
                <div class="alert alert-info">
                    No hay lecturas disponibles para este dispositivo.
                </div>
            <?php else: ?>
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
                                    <td><?= date('d/m/Y H:i:s', strtotime($lectura['fecha'])) ?></td>
                                    <td><?= number_format($lectura['voltaje'], 2) ?></td>
                                    <td><?= number_format($lectura['corriente'], 2) ?></td>
                                    <td><?= number_format($lectura['potencia'], 2) ?></td>
                                    <td><?= number_format($lectura['kwh'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($lecturas)): ?>
    const ctx = document.getElementById('graficoConsumo').getContext('2d');
    let graficoConsumo;
    let lecturas = <?= json_encode($lecturas) ?>;
    
    // Función para actualizar el gráfico
    function actualizarGrafico(nuevasLecturas) {
        const labels = nuevasLecturas.map(l => new Date(l.fecha).toLocaleString());
        const datos = nuevasLecturas.map(l => l.potencia);

        if (graficoConsumo) {
            graficoConsumo.data.labels = labels;
            graficoConsumo.data.datasets[0].data = datos;
            graficoConsumo.update();
        } else {
            graficoConsumo = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Potencia (W)',
                        data: datos,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1,
                        fill: false
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
        }
    }

    // Función para actualizar la tabla
    function actualizarTabla(nuevasLecturas) {
        const tbody = document.querySelector('#tablaLecturas tbody');
        tbody.innerHTML = '';
        
        nuevasLecturas.forEach(lectura => {
            tbody.innerHTML += `
                <tr>
                    <td>${new Date(lectura.fecha).toLocaleString()}</td>
                    <td>${Number(lectura.voltaje).toFixed(2)}</td>
                    <td>${Number(lectura.corriente).toFixed(2)}</td>
                    <td>${Number(lectura.potencia).toFixed(2)}</td>
                    <td>${Number(lectura.kwh).toFixed(2)}</td>
                </tr>
            `;
        });
    }

    // Función para obtener las últimas lecturas
    function obtenerUltimasLecturas() {
        fetch(`<?= base_url('energia/getLatestDataByMac/' . $dispositivo['mac_address']) ?>`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar las lecturas
                    lecturas = [data.data, ...lecturas.slice(0, 99)]; // Mantener solo las últimas 100 lecturas
                    actualizarGrafico(lecturas);
                    actualizarTabla(lecturas);
                }
            })
            .catch(error => console.error('Error al obtener las lecturas:', error));
    }

    // Inicializar el gráfico
    actualizarGrafico(lecturas);
    actualizarTabla(lecturas);

    // Actualizar cada 5 segundos
    setInterval(obtenerUltimasLecturas, 5000);
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?> 