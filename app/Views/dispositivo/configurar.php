<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-wifi"></i> Configuración del Medidor EcoVolt
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Mini Manual -->
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle"></i> Guía de Configuración:</h5>
                        <ol class="mb-0">
                            <li class="mb-2">
                                <strong>Preparación del Dispositivo:</strong>
                                <ul>
                                    <li>Conecta el medidor EcoVolt a la corriente eléctrica</li>
                                    <li>Espera a que el LED parpadee rápidamente (modo configuración)</li>
                                </ul>
                            </li>
                            <li class="mb-2">
                                <strong>Conexión Inicial:</strong>
                                <ul>
                                    <li>En tu teléfono, ve a Configuración > WiFi</li>
                                    <li>Conéctate a la red "EcoVolt-Config"</li>
                                    <li>La contraseña es: "12345678"</li>
                                </ul>
                            </li>
                            <li class="mb-2">
                                <strong>Configuración WiFi:</strong>
                                <ul>
                                    <li>Selecciona tu red WiFi de la lista</li>
                                    <li>Ingresa la contraseña de tu red WiFi</li>
                                    <li>Haz clic en "Conectar"</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Finalización:</strong>
                                <ul>
                                    <li>El dispositivo se reiniciará automáticamente</li>
                                    <li>Anota la dirección MAC que aparece abajo</li>
                                    <li>Usa esta MAC para registrar el dispositivo en tu cuenta</li>
                                </ul>
                            </li>
                        </ol>
                    </div>

                    <div id="setup-form">
                        <div class="text-center mb-4">
                            <img src="<?= base_url('assets/img/device-icon.png') ?>" alt="Medidor" class="img-fluid" style="max-width: 150px;">
                            <h4 class="mt-3">EcoVolt</h4>
                            <div class="alert alert-secondary">
                                <strong>Dirección MAC:</strong> 
                                <span id="mac-address" class="font-monospace"><?= esc($mac_address) ?></span>
                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyMacAddress()">
                                    <i class="fas fa-copy"></i> Copiar
                                </button>
                            </div>
                        </div>

                        <form id="wifi-form" class="mt-4">
                            <div class="mb-3">
                                <label for="ssid" class="form-label">Red WiFi</label>
                                <select class="form-select" id="ssid" name="ssid" required>
                                    <option value="">Seleccionar red...</option>
                                </select>
                                <div class="form-text">Selecciona la red WiFi a la que deseas conectar el dispositivo</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña WiFi</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Ingresa la contraseña de tu red WiFi</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-wifi"></i> Conectar
                                </button>
                                <a href="<?= base_url('dispositivo/agregar') ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-plus"></i> Registrar Dispositivo
                                </a>
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
                            <div class="alert alert-info">
                                <strong>Importante:</strong> Anota la dirección MAC mostrada arriba. La necesitarás para registrar el dispositivo en tu cuenta.
                            </div>
                            <a href="<?= base_url('dispositivo/agregar') ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Registrar Dispositivo
                            </a>
                        </div>

                        <div id="error" class="text-center mt-4" style="display: none;">
                            <i class="fas fa-times-circle text-danger" style="font-size: 48px;"></i>
                            <h4 class="mt-3">Error de conexión</h4>
                            <p id="error-message"></p>
                            <button class="btn btn-outline-primary mt-2" onclick="retryConnection()">
                                <i class="fas fa-redo"></i> Reintentar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función para copiar la dirección MAC
function copyMacAddress() {
    const macAddress = document.getElementById('mac-address').textContent;
    navigator.clipboard.writeText(macAddress).then(() => {
        alert('Dirección MAC copiada al portapapeles');
    }).catch(err => {
        console.error('Error al copiar:', err);
    });
}

// Función para mostrar/ocultar contraseña
document.querySelector('.toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Obtener la dirección MAC del dispositivo
async function getMacAddress() {
    const macAddress = document.getElementById('mac-address').textContent;
    if (macAddress && macAddress !== 'Cargando...') {
        return macAddress;
    }

    try {
        const response = await fetch(`<?= base_url('dispositivo/get-mac') ?>?mac_address=<?= esc($mac_address) ?>`);
        const data = await response.json();
        if (data.status === 'success') {
            document.getElementById('mac-address').textContent = data.mac_address;
            return data.mac_address;
        } else {
            showError(data.message || 'Error al obtener la dirección MAC');
        }
    } catch (error) {
        console.error('Error al obtener MAC:', error);
        showError('Error al obtener la dirección MAC');
    }
    return null;
}

// Escanear redes WiFi disponibles
async function scanNetworks() {
    try {
        const response = await fetch('<?= base_url('dispositivo/scan-wifi') ?>');
        const data = await response.json();
        if (data.status === 'success') {
            const select = document.getElementById('ssid');
            select.innerHTML = '<option value="">Seleccionar red...</option>';
            data.networks.forEach(network => {
                const option = document.createElement('option');
                option.value = network.ssid;
                option.textContent = `${network.ssid} (${network.signal_strength} dBm)`;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error al escanear redes:', error);
    }
}

// Actualizar contraseña WiFi
async function updateWifiPassword(event) {
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
        const response = await fetch('<?= base_url('dispositivo/update-wifi') ?>', {
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
        } else {
            showError(data.message || 'Error al actualizar la configuración');
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
    document.getElementById('wifi-form').addEventListener('submit', updateWifiPassword);
});
</script>
<?= $this->endSection() ?>