<?= $this->extend('layouts/main') ?>

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
                                            date('d/m/Y H:i:s', strtotime($dispositivo['ultima_lectura']['fecha'])) : 
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
                                        <a href="<?= base_url('dispositivo/ver/' . $dispositivo['id_dispositivo']) ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('dispositivo/editar/' . $dispositivo['id_dispositivo']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('dispositivo/control/' . $dispositivo['id_dispositivo']) ?>" class="btn btn-success btn-sm" title="Controlar Foco">
                                            <i class="fas fa-lightbulb"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<!-- Modal para configurar WiFi -->
<div class="modal fade" id="wifiModal" tabindex="-1" aria-labelledby="wifiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wifiModalLabel">Configurar WiFi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="wifiForm">
                    <div class="mb-3">
                        <label for="ssid" class="form-label">SSID (Nombre de la red)</label>
                        <input type="text" class="form-control" id="ssid" name="ssid" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="configurarWiFi()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarDispositivo(id) {
    if (confirm('¿Está seguro de que desea eliminar este dispositivo?')) {
        window.location.href = '<?= base_url('dispositivo/eliminar/') ?>' + id;
    }
}

function configurarWiFi() {
    const ssid = document.getElementById('ssid').value;
    const password = document.getElementById('password').value;
    
    if (!ssid || !password) {
        alert('Por favor complete todos los campos');
        return;
    }
    
    // Aquí iría la lógica para enviar la configuración al dispositivo
    alert('Configuración de WiFi enviada al dispositivo');
    $('#wifiModal').modal('hide');
}
</script>
<?= $this->endSection() ?> 