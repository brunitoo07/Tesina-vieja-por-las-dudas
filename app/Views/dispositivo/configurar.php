<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Configurar Dispositivo</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-wifi me-1"></i>
            Configuración de Red WiFi
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle me-2"></i>Instrucciones:</h5>
                <ol>
                    <li>Conecta tu teléfono a la red WiFi "EcoVolt_Setup" (contraseña: 12345678)</li>
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
                <div class="mb-4">
                    <h5>Redes WiFi Disponibles</h5>
                    <div id="networks-list" class="list-group">
                        <!-- Las redes se cargarán aquí dinámicamente -->
                    </div>
                </div>

                <div id="connect-form" style="display: none;">
                    <h5>Conectar a Red WiFi</h5>
                    <form id="wifi-form" class="needs-validation" novalidate>
                        <input type="hidden" id="selected-ssid" name="ssid">
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">
                                Por favor ingresa la contraseña de la red WiFi
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-wifi me-2"></i>Conectar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wifiStatus = document.getElementById('wifi-status');
    const wifiConfig = document.getElementById('wifi-config');
    const networksList = document.getElementById('networks-list');
    const connectForm = document.getElementById('connect-form');
    const wifiForm = document.getElementById('wifi-form');
    const selectedSsid = document.getElementById('selected-ssid');

    // Verificar conexión al dispositivo
    checkDeviceConnection();

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
                networksList.innerHTML = '';
                networks.forEach(network => {
                    const signalStrength = getSignalStrength(network.rssi);
                    networksList.innerHTML += `
                        <a href="#" class="list-group-item list-group-item-action" 
                           onclick="selectNetwork('${network.ssid}')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">${network.ssid}</h6>
                                <small>${signalStrength}</small>
                            </div>
                        </a>
                    `;
                });
            })
            .catch(error => {
                console.error('Error al cargar redes:', error);
                wifiStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Error al cargar las redes WiFi';
                wifiStatus.className = 'alert alert-danger';
            });
    }

    function getSignalStrength(rssi) {
        if (rssi >= -50) return '<i class="fas fa-wifi text-success"></i> Excelente';
        if (rssi >= -70) return '<i class="fas fa-wifi text-primary"></i> Buena';
        if (rssi >= -80) return '<i class="fas fa-wifi text-warning"></i> Regular';
        return '<i class="fas fa-wifi text-danger"></i> Débil';
    }

    window.selectNetwork = function(ssid) {
        selectedSsid.value = ssid;
        connectForm.style.display = 'block';
        connectForm.scrollIntoView({ behavior: 'smooth' });
    };

    wifiForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }

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