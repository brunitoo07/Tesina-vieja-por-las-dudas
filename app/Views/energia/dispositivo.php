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
    const lecturas = <?= json_encode($lecturas) ?>;
    
    // Preparar datos para el gráfico
    const labels = lecturas.map(l => new Date(l.fecha).toLocaleString());
    const datos = lecturas.map(l => l.potencia);

    new Chart(ctx, {
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
    <?php endif; ?>
    });
</script>
<?= $this->endSection() ?> 