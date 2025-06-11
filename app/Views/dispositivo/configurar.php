<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header text-center">
                    <h3>Configuración del Medidor</h3>
                </div>
                <div class="card-body">
                    <div id="setup-form">
                        <div class="text-center mb-4">
                            <img src="<?= base_url('assets/img/device-icon.png') ?>" alt="Medidor" class="img-fluid" style="max-width: 150px;">
                            <h4 class="mt-3">Medidor de Energía</h4>
                            <p class="text-muted">MAC: <span id="mac-address">Cargando...</span></p>
                        </div>

                        <form id="wifi-form" class="mt-4">
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

                        <div id="connecting" class="text-center mt-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Conectando...</span>
                            </div>
                            <p class="mt-2">Conectando a la red...</p>
                        </div>

                        <div id="success" class="text-center mt-4" style="display: none;">
                            <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                            <h4 class="mt-3">¡Conectado!</h4>
                            <p>El dispositivo se ha conectado correctamente.</p>
                        </div>

                        <div id="error" class="text-center mt-4" style="display: none;">
                            <i class="fas fa-times-circle text-danger" style="font-size: 48px;"></i>
                            <h4 class="mt-3">Error de conexión</h4>
                            <p id="error-message"></p>
                            <button class="btn btn-outline-primary mt-2" onclick="retryConnection()">
                                Reintentar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Obtener la dirección MAC del dispositivo
async function getMacAddress() {
    try {
        const response = await fetch('<?= base_url('get-mac') ?>');
        const data = await response.json();
        if (data.status === 'success') {
            document.getElementById('mac-address').textContent = data.mac_address;
            return data.mac_address;
        }
    } catch (error) {
        console.error('Error al obtener MAC:', error);
    }
    return null;
}

// Escanear redes WiFi disponibles
async function scanNetworks() {
    try {
        const response = await fetch('<?= base_url('scan-wifi') ?>');
        const data = await response.json();
        if (data.status === 'success') {
            const select = document.getElementById('ssid');
            select.innerHTML = '<option value="">Seleccionar red...</option>';
            data.networks.forEach(network => {
                const option = document.createElement('option');
                option.value = network.ssid;
                option.textContent = network.ssid;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error al escanear redes:', error);
    }
}

// Conectar a la red WiFi
async function connectToWifi(event) {
    event.preventDefault();
    
    const form = event.target;
    const ssid = form.ssid.value;
    const password = form.password.value;
    const macAddress = await getMacAddress();

    if (!ssid || !password || !macAddress) {
        showError('Por favor, complete todos los campos');
        return;
    }

    showConnecting();

    try {
        const response = await fetch('<?= base_url('save-config') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                ssid,
                password,
                mac_address: macAddress
            })
        });

        const data = await response.json();
        
        if (data.status === 'success') {
            showSuccess();
            // Esperar 3 segundos y redirigir a la página principal
            setTimeout(() => {
                window.location.href = '<?= base_url() ?>';
            }, 3000);
        } else {
            showError(data.message || 'Error al conectar');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Error de conexión');
    }
}

function showConnecting() {
    document.getElementById('wifi-form').style.display = 'none';
    document.getElementById('connecting').style.display = 'block';
    document.getElementById('success').style.display = 'none';
    document.getElementById('error').style.display = 'none';
}

function showSuccess() {
    document.getElementById('wifi-form').style.display = 'none';
    document.getElementById('connecting').style.display = 'none';
    document.getElementById('success').style.display = 'block';
    document.getElementById('error').style.display = 'none';
}

function showError(message) {
    document.getElementById('wifi-form').style.display = 'none';
    document.getElementById('connecting').style.display = 'none';
    document.getElementById('success').style.display = 'none';
    document.getElementById('error').style.display = 'block';
    document.getElementById('error-message').textContent = message;
}

function retryConnection() {
    document.getElementById('wifi-form').style.display = 'block';
    document.getElementById('connecting').style.display = 'none';
    document.getElementById('success').style.display = 'none';
    document.getElementById('error').style.display = 'none';
}

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    getMacAddress();
    scanNetworks();
    document.getElementById('wifi-form').addEventListener('submit', connectToWifi);
});
</script>
<?= $this->endSection() ?>