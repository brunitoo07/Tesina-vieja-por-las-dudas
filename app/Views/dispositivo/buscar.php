<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Buscar Dispositivos ESP32</h3>
        </div>
        <div class="card-body">
            <!-- Instrucciones -->
            <div class="alert alert-info mb-4">
                <h5><i class="fas fa-info-circle"></i> Instrucciones:</h5>
                <ol>
                    <li>Asegúrate de que el ESP32 esté encendido y conectado a tu red WiFi</li>
                    <li>Haz clic en "Buscar Dispositivos"</li>
                    <li>Si encuentras tu dispositivo, haz clic en "Vincular"</li>
                    <li>Asigna un nombre al dispositivo y guarda</li>
                </ol>
            </div>

            <!-- Estado de la conexión -->
            <div id="connectionStatus" class="alert alert-warning mb-4" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i> 
                <span id="statusMessage">Verificando conexión...</span>
            </div>

            <!-- Botón de búsqueda -->
            <div class="text-center mb-4">
                <button id="btnBuscar" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i> Buscar Dispositivos
                </button>
            </div>

            <!-- Tabla de dispositivos -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección MAC</th>
                            <th>Última Lectura</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="dispositivosBody">
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> Haz clic en "Buscar Dispositivos" para comenzar
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Vinculación -->
<div class="modal fade" id="vincularModal" tabindex="-1" role="dialog" aria-labelledby="vincularModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vincularModalLabel">Vincular Dispositivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="vincularForm">
                    <input type="hidden" id="macAddress" name="mac_address">
                    <div class="form-group">
                        <label for="nombreDispositivo">Nombre del Dispositivo</label>
                        <input type="text" class="form-control" id="nombreDispositivo" name="nombre" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="vincularButton">Vincular</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada, inicializando...');
    
    // Elementos del DOM
    const btnBuscar = document.getElementById('btnBuscar');
    const dispositivosBody = document.getElementById('dispositivosBody');
    const connectionStatus = document.getElementById('connectionStatus');
    const statusMessage = document.getElementById('statusMessage');
    
    // Función para buscar dispositivos
    async function buscarDispositivos() {
        console.log('Buscando dispositivos...');
        btnBuscar.disabled = true;
        btnBuscar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
        
        // Mostrar estado de búsqueda
        connectionStatus.style.display = 'block';
        statusMessage.innerHTML = 'Buscando dispositivos en la red...';
        connectionStatus.className = 'alert alert-info mb-4';
        
        try {
            const response = await fetch('<?= base_url('dispositivo/scan-wifi') ?>');
            const data = await response.json();
            console.log('Respuesta del servidor:', data);
            
            if (data.status === 'success' && data.networks && data.networks.length > 0) {
                mostrarDispositivos(data.networks);
                connectionStatus.style.display = 'none';
            } else {
                dispositivosBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i> ${data.message || 'No se encontraron dispositivos'}
                            </div>
                        </td>
                    </tr>
                `;
                connectionStatus.className = 'alert alert-warning mb-4';
                statusMessage.innerHTML = 'No se encontraron dispositivos. Verifica que el ESP32 esté encendido y conectado a la red.';
            }
        } catch (error) {
            console.error('Error:', error);
            dispositivosBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-times-circle"></i> Error al comunicarse con el servidor
                        </div>
                    </td>
                </tr>
            `;
            connectionStatus.className = 'alert alert-danger mb-4';
            statusMessage.innerHTML = 'Error al comunicarse con el servidor. Por favor, intenta de nuevo.';
        } finally {
            btnBuscar.disabled = false;
            btnBuscar.innerHTML = '<i class="fas fa-search"></i> Buscar Dispositivos';
        }
    }
    
    // Función para mostrar dispositivos
    function mostrarDispositivos(dispositivos) {
        dispositivosBody.innerHTML = '';
        
        dispositivos.forEach(dispositivo => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${dispositivo.nombre || 'Sin nombre'}</td>
                <td>${dispositivo.mac_address || 'N/A'}</td>
                <td>${dispositivo.ultima_lectura || 'N/A'}</td>
                <td>
                    <span class="badge badge-success">Activo</span>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="vincularDispositivo('${dispositivo.mac_address}')">
                        <i class="fas fa-link"></i> Vincular
                    </button>
                    <button class="btn btn-info btn-sm" onclick="verDetalles('${dispositivo.mac_address}')">
                        <i class="fas fa-eye"></i> Ver Detalles
                    </button>
                </td>
            `;
            dispositivosBody.appendChild(tr);
        });
    }
    
    // Función para vincular dispositivo
    window.vincularDispositivo = function(macAddress) {
        document.getElementById('macAddress').value = macAddress;
        $('#vincularModal').modal('show');
    }
    
    // Función para ver detalles
    window.verDetalles = function(macAddress) {
        window.location.href = `<?= base_url('dispositivo/ver/') ?>${macAddress}`;
    }
    
    // Evento para buscar dispositivos
    btnBuscar.addEventListener('click', buscarDispositivos);
    
    // Evento para vincular dispositivo
    document.getElementById('vincularButton').addEventListener('click', async function() {
        const form = document.getElementById('vincularForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('<?= base_url('dispositivo/guardar') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                alert('Dispositivo vinculado exitosamente');
                $('#vincularModal').modal('hide');
                buscarDispositivos(); // Actualizar lista
            } else {
                alert('Error al vincular dispositivo: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al vincular dispositivo');
        }
    });
    
    // Actualizar datos cada 30 segundos
    setInterval(buscarDispositivos, 30000);
});
</script>
<?= $this->endSection() ?> 