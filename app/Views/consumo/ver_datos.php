<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Datos de Consumo - <?= esc($dispositivo['nombre']) ?></h1>
    </div>

    <?php if (empty($lecturas)): ?>
        <div class="alert alert-info">
            No hay lecturas disponibles para este dispositivo.
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Gráfico de Consumo</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="consumoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Historial de Lecturas</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Consumo (kWh)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lecturas as $lectura): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($lectura['fecha'])) ?></td>
                                        <td><?= number_format($lectura['kwh'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($lecturas)): ?>
    fetch('<?= base_url("consumo/grafico/{$dispositivo['id_dispositivo']}") ?>')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('consumoChart').getContext('2d');
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
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar el gráfico:', error);
        });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?> 