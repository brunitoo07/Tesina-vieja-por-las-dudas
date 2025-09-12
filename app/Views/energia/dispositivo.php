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

    <!-- Gráfico general de consumo -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Gráfico de Consumo</h6>
            <div class="d-flex align-items-center">
                <div id="estadoActualizacion" class="badge badge-success mr-2">
                    <i class="fas fa-check-circle"></i> Conectado
                </div>
                <small class="text-muted">Actualización automática cada 5s</small>
            </div>
        </div>
        <div class="card-body">
            <!-- Alerta de sin energía -->
            <div id="alertaSinEnergia" class="alert alert-danger text-center mb-3" style="display: none;">
                <h4 class="alert-heading">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                    ¡SIN ENERGÍA!
                </h4>
                <p class="mb-0" style="font-size: 1.2em; font-weight: bold;">
                    No hay consumo en el sistema. Verifique la conexión eléctrica.
                </p>
            </div>
           
            <canvas id="graficoConsumo" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Valores actuales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Voltaje</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="valorVoltaje">0 V</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bolt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Corriente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="valorCorriente">0 A</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wave-square fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Potencia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="valorPotencia">0 W</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Energía</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="valorKwh">0 kWh</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-battery-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Formulario para comparar valor de kWh -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">💰 Calcular Costo de Energía</h6>
    </div>
    <div class="card-body">
        <form id="formKwh" class="row g-3">
            <div class="col-md-4">
                <label for="valorKwh" class="form-label">Valor de kWh ($)</label>
                <input type="number" step="0.01" class="form-control" id="inputKwh" placeholder="Ej: 150.50" required>
                </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-calculator me-2"></i> Calcular
                </button>
            </div>
        </form>

        <div id="resultadoCosto" class="mt-3" style="display:none;">
            <h6 class="fw-bold">Resultado:</h6>
            <p>Total de Energía consumida: <span id="totalKwh"></span> kWh</p>
            <p>Costo estimado: <span id="costoTotal"></span> $</p>
            <a href="<?= base_url('facturas/generarPDF/'.$dispositivo['id_dispositivo']) ?>" 
               class="btn btn-primary" target="_blank">
                <i class="fas fa-file-pdf me-2"></i> Descargar Informe PDF
            </a>
        
        </div>
    </div>
</div>
    <!-- Mensajes de estado en tiempo real -->
    <div id="logsEstado" class="mb-3"></div>

    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">⚡ Configuración de Límite de Consumo</h6>
    </div>
    <div class="card-body">
    <form id="formLimite" action="<?= base_url('energia/actualizarLimite') ?>" method="post">
        <div class="form-group">
            <label for="limite_consumo">Límite de Consumo (kWh)</label>
            <input type="number" step="0.01" class="form-control" 
                   id="limite_consumo" name="limite_consumo" 
                   value="<?= esc($limite_consumo) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email de notificación (opcional)</label>
            <input type="email" class="form-control" 
                   id="email" name="email" 
                   value="<?= esc(session()->get('email')) ?>">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar Configuración
        </button>
    </form>
    <div id="msgLimite" class="mt-3"></div>
</div>


    <!-- Tabla de Lecturas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Historial de Lecturas</h6></div>
        <div class="card-body">
            <?php if (empty($lecturas)): ?>
                <div class="alert alert-info">No hay lecturas disponibles para este dispositivo.</div>
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
    
    let lecturas = <?= json_encode($lecturas) ?>;

    function ultimoValor(variable) {
        return lecturas.length ? lecturas[0][variable] : 0;
    }

    function mostrarMensaje(tipo, texto) {
        const mensajesEstado = document.getElementById('logsEstado');
        let clase = 'alert-secondary';

        switch (tipo) {
            case 'error': clase = 'alert-danger'; break;
            case 'alerta': clase = 'alert-warning'; break;
            case 'ok': clase = 'alert-success'; break;
            case 'info': clase = 'alert-info'; break;
        }

        mensajesEstado.innerHTML = `
            <div class="alert ${clase} text-center">
                ${texto}
            </div>
        `;
        console.log(`Mensaje tipo "${tipo}": ${texto}`);
    }

    function verificarEnergia() {
        const alerta = document.getElementById('alertaSinEnergia');
        const graficoCanvas = document.getElementById('graficoConsumo');
        const volt = ultimoValor('voltaje');
        const corr = ultimoValor('corriente');
        const pot = ultimoValor('potencia');
        const kwh = ultimoValor('kwh');

        const sinEnergia = volt < 0.1 && corr < 0.1 && pot < 0.1 && kwh < 0.1;

        if (sinEnergia) {
            alerta.style.display = 'block';
            graficoCanvas.style.display = 'none';
        } else {
            alerta.style.display = 'none';
            graficoCanvas.style.display = 'block';
        }
    }

    function actualizarValoresActuales() {
        document.getElementById('valorVoltaje').textContent = Number(ultimoValor('voltaje')).toFixed(2) + ' V';
        document.getElementById('valorCorriente').textContent = Number(ultimoValor('corriente')).toFixed(2) + ' A';
        document.getElementById('valorPotencia').textContent = Number(ultimoValor('potencia')).toFixed(2) + ' W';
        document.getElementById('valorKwh').textContent = Number(ultimoValor('kwh')).toFixed(2) + ' kWh';
        verificarEnergia();
    }

    // Crear gráfico de consumo
    const ctx = document.getElementById('graficoConsumo').getContext('2d');
    const graficoConsumo = new Chart(ctx, {
        type: 'line',
        data: {
            labels: lecturas.map(l => new Date(l.fecha).toLocaleString()),
            datasets: [{
                label: 'Potencia (W)', 
                data: lecturas.map(l => l.potencia), 
                borderColor: 'rgb(75,192,192)', 
                backgroundColor: 'rgba(75,192,192,0.2)', 
                tension: 0.1, 
                fill: true
            }]
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Potencia (W)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Fecha y Hora'
                    }
                }
            }
        }
    });

    function actualizarGrafico() {
        const labels = lecturas.map(l => new Date(l.fecha).toLocaleString());
        const datosPotencia = lecturas.map(l => l.potencia);
        
        graficoConsumo.data.labels = labels;
        graficoConsumo.data.datasets[0].data = datosPotencia;
        graficoConsumo.update();
    }

    function actualizarTabla() {
        const tbody = document.querySelector('#tablaLecturas tbody');
        if (!tbody) return;
        tbody.innerHTML = '';
        lecturas.forEach(l => {
            tbody.innerHTML += `<tr>
                <td>${new Date(l.fecha).toLocaleString()}</td>
                <td>${Number(l.voltaje).toFixed(2)}</td>
                <td>${Number(l.corriente).toFixed(2)}</td>
                <td>${Number(l.potencia).toFixed(2)}</td>
                <td>${Number(l.kwh).toFixed(2)}</td>
            </tr>`;
        });
    }

    // Función para obtener datos en tiempo real
    function obtenerDatosTiempoReal() {
        // Mostrar estado de actualización
        const estadoElement = document.getElementById('estadoActualizacion');
        estadoElement.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> Actualizando...';
        estadoElement.className = 'badge badge-info mr-2';
        
        fetch(`<?= base_url('energia/getLatestDataByDevice/' . $dispositivo['id_dispositivo']) ?>`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    // Obtener la nueva lectura
                    const nuevaLectura = data.data;

                    // Verificar si es una lectura nueva (comparar fecha)
                    const ultimaLecturaExistente = lecturas[0];
                    
                    if (!ultimaLecturaExistente || new Date(nuevaLectura.fecha) > new Date(ultimaLecturaExistente.fecha)) {
                        
                        // Agregar nueva lectura al inicio
                        lecturas.unshift(nuevaLectura);
                        
                        // Mantener solo las últimas 50 lecturas
                        if (lecturas.length > 50) {
                            lecturas = lecturas.slice(0, 50);
                        }
                        
                        // Actualizar todo
                        actualizarValoresActuales();
                        actualizarGrafico();
                        actualizarTabla();
                        
                        // Mostrar estado exitoso
                        estadoElement.innerHTML = '<i class="fas fa-check-circle"></i> Conectado';
                        estadoElement.className = 'badge badge-success mr-2';
                        
                        console.log('Datos actualizados correctamente:', nuevaLectura);
                    } else {
                        // Mostrar estado conectado (sin cambios)
                        estadoElement.innerHTML = '<i class="fas fa-check-circle"></i> Conectado';
                        estadoElement.className = 'badge badge-success mr-2';
                        console.log('No hay nuevas lecturas disponibles');
                    }

                    // Lógica para mensajes de estado
                    const limiteConsumo = (typeof data.limite_consumo !== 'undefined' && data.limite_consumo !== null)
                        ? Number(data.limite_consumo)
                        : (Number(document.getElementById('limite_consumo')?.value) || 10);

                    if (nuevaLectura.voltaje < 1 && nuevaLectura.corriente < 0.1) {
                        mostrarMensaje('error', '🚫 SIN ENERGÍA EN EL SISTEMA → Voltaje crítico, no hay consumo.');
                    } else if (nuevaLectura.potencia < 1) {
                        mostrarMensaje('info', '❌ NO HAY CONSUMO EN EL SISTEMA (0V, 0A, 0W, 0kWh).');
                    } else if (nuevaLectura.voltaje < 200) {
                        mostrarMensaje('alerta', '⚠️ Voltaje bajo detectado, verificar conexión eléctrica.');
                    } else if (Number(nuevaLectura.kwh) > limiteConsumo) {
                        mostrarMensaje('alerta', `⚠️ Límite de consumo superado (${Number(nuevaLectura.kwh).toFixed(2)} kWh > ${limiteConsumo} kWh). Línea NO esencial desconectada.`);
                    } else {
                        mostrarMensaje('ok', '✅ Consumo dentro del límite.');
                    }

                } else {
                    throw new Error('Respuesta del servidor no exitosa');
                }
            })
            .catch(error => {
                console.error('Error al obtener datos en tiempo real:', error);
                // Mostrar estado de error
                estadoElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error';
                estadoElement.className = 'badge badge-danger mr-2';
                mostrarMensaje('error', '❌ Error al conectar con el servidor. Verificando conexión...');
            });
    }

    // Inicializar todo
    actualizarValoresActuales();
    actualizarTabla();
    
    // Mostrar mensaje inicial
    mostrarMensaje('info', '🔄 Sistema iniciado. Conectando con el dispositivo...');
    
    // Configurar actualización automática
    console.log('Iniciando actualización automática cada 5 segundos...');
    const intervaloActualizacion = setInterval(obtenerDatosTiempoReal, 5000);
    
    // Primera actualización después de 1 segundo
    setTimeout(obtenerDatosTiempoReal, 1000);
    
    // Limpiar intervalo al cerrar la página
    window.addEventListener('beforeunload', function() {
        clearInterval(intervaloActualizacion);
    });
    
    <?php endif; ?>
});

</script>
<<!-- 🚀 SCRIPT PARA CALCULAR COSTO DE ENERGÍA -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formKwh = document.getElementById('formKwh');
    const resultado = document.getElementById('resultadoCosto');
    const inputValorKwh = document.getElementById('inputKwh'); // ahora apunta al input correcto

    if (formKwh) {
        formKwh.addEventListener('submit', function(e) {
            e.preventDefault();

            const valorKwhUnitario = parseFloat(inputValorKwh.value);
            if (isNaN(valorKwhUnitario)) {
                alert('Ingrese un valor válido para el kWh');
                return;
            }

            // Calcular total de kWh consumidos
            let totalKwh = 0;
            <?php if (!empty($lecturas)): ?>
                <?php foreach ($lecturas as $lectura): ?>
                    totalKwh += <?= $lectura['kwh'] ?>;
                <?php endforeach; ?>
            <?php endif; ?>

            const costoTotal = (totalKwh * valorKwhUnitario).toFixed(2);

            document.getElementById('totalKwh').textContent = totalKwh.toFixed(2);
            document.getElementById('costoTotal').textContent = costoTotal;

            resultado.style.display = 'block';
        });
    }
});
</script>
<!-- 🚀 SCRIPT PARA GUARDAR LÍMITE DE CONSUMO -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formLimite');
    const msg = document.getElementById('msgLimite');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const data = {
    limite_consumo: document.getElementById('limite_consumo').value,
    email: document.getElementById('email').value,
    id_dispositivo: <?= $dispositivo['id_dispositivo'] ?>
};

           fetch("<?= base_url('energia/actualizarLimite') ?>", {

                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    msg.innerHTML = `<div class="alert alert-success">${response.message}</div>`;
                } else {
                    msg.innerHTML = `<div class="alert alert-danger">${response.error || 'Error desconocido'}</div>`;
                }
            })
            .catch(err => {
                msg.innerHTML = `<div class="alert alert-danger">Error al guardar configuración: ${err}</div>`;
            });
        });
    }
});
</script>

<!-- Se eliminó la suscripción a notificaciones push -->

<!-- Asistente Virtual -->
<?= $this->include('chat_profesional') ?>

<?= $this->endSection() ?>
