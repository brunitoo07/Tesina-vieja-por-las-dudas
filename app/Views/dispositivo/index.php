<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dispositivos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#wifiModal">
                            <i class="fas fa-wifi"></i> Configurar WiFi
                        </button>
                        <a href="<?= base_url('dispositivo/agregar') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Agregar Dispositivo
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>MAC Address</th>
                                    <th>Última Lectura</th>
                                    <th>Voltaje</th>
                                    <th>Corriente</th>
                                    <th>Potencia</th>
                                    <th>Energía (kWh)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dispositivos as $dispositivo): ?>
                                <tr>
                                    <td><?= $dispositivo['mac_address'] ?></td>
                                    <td>
                                        <?= $dispositivo['ultima_lectura'] ? 
                                            date('d/m/Y H:i:s', strtotime($dispositivo['ultima_lectura']['created_at'])) : 
                                            'Sin lecturas' ?>
                                    </td>
                                    <td>
                                        <?= $dispositivo['ultima_lectura'] ? 
                                            number_format($dispositivo['ultima_lectura']['voltaje'], 2) . ' V' : 
                                            '-' ?>
                                    </td>
                                    <td>
                                        <?= $dispositivo['ultima_lectura'] ? 
                                            number_format($dispositivo['ultima_lectura']['corriente'], 4) . ' A' : 
                                            '-' ?>
                                    </td>
                                    <td>
                                        <?= $dispositivo['ultima_lectura'] ? 
                                            number_format($dispositivo['ultima_lectura']['potencia'], 2) . ' W' : 
                                            '-' ?>
                                    </td>
                                    <td>
                                        <?= $dispositivo['ultima_lectura'] ? 
                                            number_format($dispositivo['ultima_lectura']['kwh'], 4) . ' kWh' : 
                                            '-' ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('dispositivo/ver/' . $dispositivo['mac_address']) ?>" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                        <a href="<?= base_url('dispositivo/configurar/' . $dispositivo['mac_address']) ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-cog"></i> Configurar
                                        </a>
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

<!-- Modal de Configuración WiFi -->
<div class="modal fade" id="wifiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configurar Red WiFi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle me-2"></i>Instrucciones:</h5>
                    <ol>
                        <li>Conecta tu teléfono a la red WiFi "Medidor-Config"</li>
                        <li>Una vez conectado, selecciona tu red WiFi de la lista</li>
                        <li>Ingresa la contraseña de tu red WiFi</li>
                        <li>El dispositivo se reiniciará y se conectará a tu red</li>
                    </ol>
                </div>

                <div id="wifi-status" class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Conectando a la red del dispositivo...
                </div>

                <div id="wifi-config" style="display: none;">
                    <form id="wifi-form">
                        <div class="mb-3">
                            <label for="ssid" class="form-label">Red WiFi</label>
                            <select class="form-select" id="ssid" name="ssid" required>
                                <option value="">Seleccionar red...</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-wifi"></i> Conectar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Actualizar la tabla cada 5 segundos
setInterval(function() {
    location.reload();
}, 5000);

// Funciones para el modal de WiFi
document.addEventListener('DOMContentLoaded', function() {
    const wifiModal = document.getElementById('wifiModal');
    const wifiStatus = document.getElementById('wifi-status');
    const wifiConfig = document.getElementById('wifi-config');
    const wifiForm = document.getElementById('wifi-form');

    wifiModal.addEventListener('show.bs.modal', function() {
        checkDeviceConnection();
    });

    function checkDeviceConnection() {
        fetch('http://192.168.4.1/scan')
            .then(response => {
                if (response.ok) {
                    wifiStatus.style.display = 'none';
                    wifiConfig.style.display = 'block';
                    loadNetworks();
                } else {
                    setTimeout(checkDeviceConnection, 2000);
                }
            })
            .catch(() => {
                setTimeout(checkDeviceConnection, 2000);
            });
    }

    function loadNetworks() {
        fetch('http://192.168.4.1/scan')
            .then(response => response.json())
            .then(networks => {
                const select = document.getElementById('ssid');
                select.innerHTML = '<option value="">Seleccionar red...</option>';
                networks.forEach(network => {
                    const option = document.createElement('option');
                    option.value = network.ssid;
                    option.textContent = network.ssid;
                    select.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar redes:', error);
                wifiStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Error al cargar las redes WiFi';
                wifiStatus.className = 'alert alert-danger';
            });
    }

    wifiForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('http://192.168.4.1/connect', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            wifiConfig.innerHTML = `
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle me-2"></i>¡Configuración exitosa!</h5>
                    <p>El dispositivo se está reiniciando para conectarse a la red seleccionada.</p>
                    <p>Por favor, espera unos momentos y vuelve a conectarte a tu red WiFi normal.</p>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            wifiConfig.innerHTML = `
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-circle me-2"></i>Error</h5>
                    <p>Hubo un error al conectar el dispositivo. Por favor, intenta nuevamente.</p>
                </div>
            `;
        });
    });
});
</script>
<?= $this->endSection() ?> 