<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-wifi"></i> Buscar Dispositivos ESP32
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Para conectar un nuevo dispositivo ESP32:
                        <ol class="mb-0 mt-2">
                            <li>Asegúrate de que el ESP32 esté en modo de configuración (parpadeo rápido)</li>
                            <li>Haz clic en "Buscar Dispositivos"</li>
                            <li>Selecciona tu dispositivo de la lista</li>
                            <li>Ingresa la contraseña de tu red WiFi</li>
                        </ol>
                    </div>

                    <div class="text-center mb-4">
                        <button id="btnBuscar" class="btn btn-primary btn-lg" onclick="buscarDispositivos()">
                            <i class="fas fa-search"></i> Buscar Dispositivos
                        </button>
                    </div>

                    <div id="listaDispositivos" class="d-none">
                        <h5 class="mb-3">Dispositivos Encontrados</h5>
                        <div class="list-group" id="dispositivosLista">
                            <!-- Los dispositivos se cargarán aquí dinámicamente -->
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
                                        <div class="mb-3">
                                            <label class="form-label">Nombre del Dispositivo</label>
                                            <input type="text" class="form-control" id="nombreDispositivo" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Red WiFi</label>
                                            <select class="form-select" id="redWifi" required>
                                                <!-- Las redes WiFi se cargarán aquí -->
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Contraseña WiFi</label>
                                            <input type="password" class="form-control" id="passwordWifi" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" onclick="configurarDispositivo()">
                                        <i class="fas fa-save"></i> Guardar Configuración
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let dispositivoSeleccionado = null;

async function buscarDispositivos() {
    const btnBuscar = document.getElementById('btnBuscar');
    const listaDispositivos = document.getElementById('listaDispositivos');
    
    btnBuscar.disabled = true;
    btnBuscar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
    
    try {
        const response = await fetch('<?= base_url('api/dispositivo/buscar') ?>');
        const data = await response.json();
        
        if (data.status === 'success') {
            const dispositivosLista = document.getElementById('dispositivosLista');
            dispositivosLista.innerHTML = '';
            
            data.dispositivos.forEach(dispositivo => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                item.innerHTML = `
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${dispositivo.nombre || 'Dispositivo ESP32'}</h6>
                        <small>MAC: ${dispositivo.mac_address}</small>
                    </div>
                    <p class="mb-1">Señal: ${dispositivo.signal_strength} dBm</p>
                `;
                item.onclick = () => seleccionarDispositivo(dispositivo);
                dispositivosLista.appendChild(item);
            });
            
            listaDispositivos.classList.remove('d-none');
        } else {
            alert('Error al buscar dispositivos: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al comunicarse con el servidor');
    } finally {
        btnBuscar.disabled = false;
        btnBuscar.innerHTML = '<i class="fas fa-search"></i> Buscar Dispositivos';
    }
}

async function seleccionarDispositivo(dispositivo) {
    dispositivoSeleccionado = dispositivo;
    
    try {
        const response = await fetch('<?= base_url('api/dispositivo/redes') ?>');
        const data = await response.json();
        
        if (data.status === 'success') {
            const redWifi = document.getElementById('redWifi');
            redWifi.innerHTML = '';
            
            data.redes.forEach(red => {
                const option = document.createElement('option');
                option.value = red.ssid;
                option.textContent = `${red.ssid} (${red.signal_strength} dBm)`;
                redWifi.appendChild(option);
            });
            
            new bootstrap.Modal(document.getElementById('configModal')).show();
        } else {
            alert('Error al obtener redes WiFi: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al comunicarse con el servidor');
    }
}

async function configurarDispositivo() {
    if (!dispositivoSeleccionado) return;
    
    const nombre = document.getElementById('nombreDispositivo').value;
    const ssid = document.getElementById('redWifi').value;
    const password = document.getElementById('passwordWifi').value;
    
    try {
        const response = await fetch('<?= base_url('api/dispositivo/configurar') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                mac_address: dispositivoSeleccionado.mac_address,
                nombre: nombre,
                ssid: ssid,
                password: password
            })
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            alert('Dispositivo configurado exitosamente');
            window.location.href = '<?= base_url('admin/dispositivos') ?>';
        } else {
            alert('Error al configurar dispositivo: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al comunicarse con el servidor');
    }
}
</script>

<?= $this->endSection() ?> 