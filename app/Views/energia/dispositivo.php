<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-microchip"></i> 
                        Dispositivo: <?= esc($dispositivo['nombre']) ?>
                    </h3>
                    <div class="card-tools">
                        <span class="badge bg-info">
                            MAC: <?= esc($dispositivo['mac_address']) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tarjetas de información -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-bolt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Potencia Actual</span>
                                    <span class="info-box-number" id="potencia-actual">0 W</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-plug"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Voltaje</span>
                                    <span class="info-box-number" id="voltaje-actual">0 V</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-bolt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Corriente</span>
                                    <span class="info-box-number" id="corriente-actual">0 A</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-tachometer-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Consumo Total</span>
                                    <span class="info-box-number" id="consumo-total">0 kWh</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Consumo en Tiempo Real</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="consumoChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Distribución de Potencia</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="distribucionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de lecturas -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Historial de Lecturas</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Voltaje (V)</th>
                                                    <th>Corriente (A)</th>
                                                    <th>Potencia (W)</th>
                                                    <th>Consumo (kWh)</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabla-lecturas">
                                                <!-- Las lecturas se cargarán dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notificación de límite superado -->
<div id="notificationBadge" class="notification-badge" style="display: none;">
    <div class="notification-content">
        <i class="fas fa-exclamation-triangle"></i>
        <span id="notificationText"></span>
    </div>
</div>

<?= $this->section('styles') ?>
<style>
.notification-badge {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #ffc107;
    color: #000;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 1000;
    animation: slideIn 0.5s ease-out;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.alert-limit {
    background-color: #fff3cd !important;
}

.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: 0.25rem;
    background-color: #fff;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: .5rem;
    position: relative;
    width: 100%;
}

.info-box-icon {
    border-radius: 0.25rem;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
    flex-shrink: 0;
    align-items: center;
}

.info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    flex: 1;
    padding: 0 10px;
}

.info-box-text {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.info-box-number {
    display: block;
    font-weight: 700;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Variables globales para los gráficos
    let consumoChart;
    let distribucionChart;
    const DISPOSITIVO_ID = <?= $dispositivo['id_dispositivo'] ?>;
    
    // Función para actualizar los datos en tiempo real
    function actualizarDatos() {
        fetch(`/Tesina/public/energia/getLatestDataByDevice/${DISPOSITIVO_ID}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar tarjetas
                    document.getElementById('potencia-actual').textContent = `${data.data.potencia.toFixed(2)} W`;
                    document.getElementById('voltaje-actual').textContent = `${data.data.voltaje.toFixed(2)} V`;
                    document.getElementById('corriente-actual').textContent = `${data.data.corriente.toFixed(4)} A`;
                    document.getElementById('consumo-total').textContent = `${data.data.kwh.toFixed(4)} kWh`;

                    // Actualizar gráfico de consumo
                    const fecha = new Date(data.data.fecha);
                    consumoChart.data.labels.push(fecha.toLocaleTimeString());
                    consumoChart.data.datasets[0].data.push(data.data.kwh);

                    // Mantener solo los últimos 20 puntos
                    if (consumoChart.data.labels.length > 20) {
                        consumoChart.data.labels.shift();
                        consumoChart.data.datasets[0].data.shift();
                    }

                    consumoChart.update();

                    // Actualizar gráfico de distribución
                    distribucionChart.data.datasets[0].data = [
                        data.data.potencia,
                        data.data.voltaje * data.data.corriente - data.data.potencia
                    ];
                    distribucionChart.update();

                    // Verificar límite de consumo
                    if (data.data.kwh > data.limite_consumo) {
                        document.getElementById('notificationBadge').style.display = 'block';
                        document.getElementById('notificationText').textContent = 
                            `¡Alerta! El consumo (${data.data.kwh.toFixed(4)} kWh) ha superado el límite (${data.limite_consumo} kWh)`;
                    } else {
                        document.getElementById('notificationBadge').style.display = 'none';
                    }

                    // Actualizar tabla
                    const tabla = document.getElementById('tabla-lecturas');
                    const nuevaFila = document.createElement('tr');
                    nuevaFila.className = data.data.limite_superado ? 'alert-limit' : '';
                    nuevaFila.innerHTML = `
                        <td>${fecha.toLocaleString()}</td>
                        <td>${data.data.voltaje.toFixed(2)}</td>
                        <td>${data.data.corriente.toFixed(4)}</td>
                        <td>${data.data.potencia.toFixed(2)}</td>
                        <td>${data.data.kwh.toFixed(4)}</td>
                        <td>
                            ${data.data.limite_superado ? 
                                '<span class="badge bg-warning">Límite superado</span>' : 
                                '<span class="badge bg-success">Normal</span>'}
                        </td>
                    `;
                    tabla.insertBefore(nuevaFila, tabla.firstChild);

                    // Mantener solo las últimas 10 filas
                    while (tabla.children.length > 10) {
                        tabla.removeChild(tabla.lastChild);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }

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
                labels: ['Potencia Activa', 'Potencia Reactiva'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 99, 132)']
                }]
            },
            options: {
                responsive: true
            }
        });
    }

    // Inicializar gráficos al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        inicializarGraficos();
        actualizarDatos(); // Primera actualización
        setInterval(actualizarDatos, 5000); // Actualizar cada 5 segundos
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 