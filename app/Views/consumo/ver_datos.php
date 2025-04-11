<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Datos de Consumo - <?= $dispositivo['nombre'] ?></h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="consumoChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Historial de Lecturas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                    <td><?= number_format($lectura['consumo'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
        });
});
</script>
<?= $this->endSection() ?> 