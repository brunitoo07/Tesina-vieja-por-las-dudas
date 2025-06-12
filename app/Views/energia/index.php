<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Energía en Tiempo Real</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <style>
        .notification-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }
        .table-responsive {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .alert-limit {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }
        .consumo-warning {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255,0,0,0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255,0,0,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,0,0,0); }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Notificación de Límite -->
    <div id="notificationBadge" class="notification-badge" style="display: none;">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <span id="notificationText"></span>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-4">Monitoreo de Energía</h2>
                
                <!-- Tarjetas de resumen -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Potencia Actual</h5>
                                <h3 class="card-text" id="potencia-actual">0 W</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Voltaje</h5>
                                <h3 class="card-text" id="voltaje-actual">0 V</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Corriente</h5>
                                <h3 class="card-text" id="corriente-actual">0 A</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Consumo Total</h5>
                                <h3 class="card-text" id="consumo-total">0 kWh</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Consumo de Energía en Tiempo Real</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="consumoChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Distribución de Consumo</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="distribucionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de historial -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Historial de Lecturas</h5>
                        <button class="btn btn-primary btn-sm" onclick="actualizarDatos()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Voltaje (V)</th>
                                        <th>Corriente (A)</th>
                                        <th>Potencia (W)</th>
                                        <th>Consumo (kWh)</th>
                                        <th>MAC Address</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-lecturas">
                                    <?php foreach ($lecturas as $lectura): ?>
                                    <tr class="<?php echo $lectura['limite_superado'] ? 'alert-limit' : ''; ?>">
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($lectura['fecha'])); ?></td>
                                        <td><?php echo number_format($lectura['voltaje'], 2); ?></td>
                                        <td><?php echo number_format($lectura['corriente'], 4); ?></td>
                                        <td><?php echo number_format($lectura['potencia'], 2); ?></td>
                                        <td><?php echo number_format($lectura['kwh'], 4); ?></td>
                                        <td><?php echo $lectura['mac_address']; ?></td>
                                        <td>
                                            <?php if ($lectura['limite_superado']): ?>
                                                <span class="badge bg-warning">Límite superado</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Normal</span>
                                            <?php endif; ?>
                                        </td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variables globales para los gráficos
        let consumoChart;
        let distribucionChart;
        const limiteConsumo = <?php echo $limite_consumo; ?>;
        
        // Inicializar gráficos
        function inicializarGraficos() {
            // Gráfico de consumo
            const ctxConsumo = document.getElementById('consumoChart').getContext('2d');
            consumoChart = new Chart(ctxConsumo, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Consumo (kWh)',
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

            // Gráfico de distribución
            const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');
            distribucionChart = new Chart(ctxDistribucion, {
                type: 'doughnut',
                data: {
                    labels: ['Potencia', 'Voltaje', 'Corriente'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        // Actualizar datos
        function actualizarDatos() {
            fetch('/energia/getLatestData')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        return;
                    }

                    // Actualizar tarjetas
                    document.getElementById('potencia-actual').textContent = data.potencia.toFixed(2) + ' W';
                    document.getElementById('voltaje-actual').textContent = data.voltaje.toFixed(2) + ' V';
                    document.getElementById('corriente-actual').textContent = data.corriente.toFixed(4) + ' A';
                    document.getElementById('consumo-total').textContent = data.kwh.toFixed(4) + ' kWh';

                    // Actualizar gráfico de consumo
                    const fecha = new Date(data.fecha);
                    consumoChart.data.labels.push(fecha.toLocaleTimeString());
                    consumoChart.data.datasets[0].data.push(data.kwh);

                    if (consumoChart.data.labels.length > 20) {
                        consumoChart.data.labels.shift();
                        consumoChart.data.datasets[0].data.shift();
                    }

                    // Actualizar gráfico de distribución
                    distribucionChart.data.datasets[0].data = [
                        data.potencia,
                        data.voltaje,
                        data.corriente
                    ];

                    consumoChart.update();
                    distribucionChart.update();

                    // Actualizar tabla
                    actualizarTabla();
                })
                .catch(error => console.error('Error:', error));
        }

        // Actualizar tabla
        function actualizarTabla() {
            fetch('/energia')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const nuevaTabla = doc.getElementById('tabla-lecturas');
                    document.getElementById('tabla-lecturas').innerHTML = nuevaTabla.innerHTML;
                })
                .catch(error => console.error('Error:', error));
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            inicializarGraficos();
            actualizarDatos();
            setInterval(actualizarDatos, 5000); // Actualizar cada 5 segundos
        });
    </script>
</body>
</html>