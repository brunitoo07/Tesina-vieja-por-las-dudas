<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Buscar Dispositivos ESP32</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-search me-1"></i>
            Dispositivos Disponibles
        </div>
        <div class="card-body">
            <div class="mb-3">
                <button id="btnBuscar" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> Buscar Dispositivos
                </button>
            </div>

            <div id="listaDispositivos" class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección MAC</th>
                            <th>Señal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="dispositivosBody">
                        <!-- Los dispositivos se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Configuración -->
<div class="modal fade" id="configModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configurar Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="configForm">
                    <input type="hidden" id="macAddress" name="mac_address">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Dispositivo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="ssid" class="form-label">Red WiFi</label>
                        <select class="form-select" id="ssid" name="ssid" required>
                            <option value="">Seleccionar red...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña WiFi</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfigurar">Configurar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnBuscar = document.getElementById('btnBuscar');
    const dispositivosBody = document.getElementById('dispositivosBody');
    const configModal = new bootstrap.Modal(document.getElementById('configModal'));
    const configForm = document.getElementById('configForm');
    const btnConfigurar = document.getElementById('btnConfigurar');

    // Función para buscar dispositivos
    async function buscarDispositivos() {
        try {
            const response = await fetch('<?= base_url('api/dispositivo/buscar') ?>');
            const data = await response.json();
            
            if (data.status === 'success') {
                mostrarDispositivos(data.dispositivos);
            } else {
                alert('Error al buscar dispositivos');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al buscar dispositivos');
        }
    }

    // Función para mostrar dispositivos en la tabla
    function mostrarDispositivos(dispositivos) {
        dispositivosBody.innerHTML = '';
        
        dispositivos.forEach(dispositivo => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${dispositivo.nombre}</td>
                <td>${dispositivo.mac_address}</td>
                <td>${dispositivo.signal_strength} dBm</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="configurarDispositivo('${dispositivo.mac_address}')">
                        <i class="fas fa-cog"></i> Configurar
                    </button>
                </td>
            `;
            dispositivosBody.appendChild(tr);
        });
    }

    // Función para cargar redes WiFi
    async function cargarRedes() {
        try {
            const response = await fetch('<?= base_url('api/dispositivo/redes') ?>');
            const data = await response.json();
            
            if (data.status === 'success') {
                const ssidSelect = document.getElementById('ssid');
                ssidSelect.innerHTML = '<option value="">Seleccionar red...</option>';
                
                data.redes.forEach(red => {
                    const option = document.createElement('option');
                    option.value = red.ssid;
                    option.textContent = `${red.ssid} (${red.signal_strength} dBm)`;
                    ssidSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar redes WiFi');
        }
    }

    // Función para configurar dispositivo
    window.configurarDispositivo = function(macAddress) {
        document.getElementById('macAddress').value = macAddress;
        cargarRedes();
        configModal.show();
    }

    // Evento para configurar dispositivo
    btnConfigurar.addEventListener('click', async function() {
        if (!configForm.checkValidity()) {
            configForm.reportValidity();
            return;
        }

        const formData = new FormData(configForm);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('<?= base_url('api/dispositivo/configurar') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                alert('Dispositivo configurado exitosamente');
                configModal.hide();
                buscarDispositivos(); // Actualizar lista
            } else {
                alert('Error al configurar dispositivo: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al configurar dispositivo');
        }
    });

    // Evento para buscar dispositivos
    btnBuscar.addEventListener('click', buscarDispositivos);
});
</script>
<?= $this->endSection() ?> 