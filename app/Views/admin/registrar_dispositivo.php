<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Registrar Nuevo Dispositivo</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/dispositivos/guardar') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Dispositivo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Inicial</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" value="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="precio" name="precio" min="0" step="0.01" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mac_address" class="form-label">MAC Address</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="mac_address" name="mac_address" 
                                       pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$" 
                                       placeholder="XX:XX:XX:XX:XX:XX" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="generarMAC()">
                                    <i class="fas fa-random"></i> Generar
                                </button>
                            </div>
                            <small class="form-text text-muted">Formato: XX:XX:XX:XX:XX:XX (donde X es un número hexadecimal)</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Registrar Dispositivo
                            </button>
                            <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generarMAC() {
    const hex = '0123456789ABCDEF';
    let mac = '';
    
    for (let i = 0; i < 6; i++) {
        for (let j = 0; j < 2; j++) {
            mac += hex.charAt(Math.floor(Math.random() * 16));
        }
        if (i < 5) mac += ':';
    }
    
    document.getElementById('mac_address').value = mac;
}

// Validación del formulario
document.querySelector('form').addEventListener('submit', function(e) {
    const mac = document.getElementById('mac_address').value;
    const macRegex = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/;
    
    if (!macRegex.test(mac)) {
        e.preventDefault();
        alert('Por favor, ingrese una dirección MAC válida en el formato XX:XX:XX:XX:XX:XX');
    }
});
</script>

<?= $this->endSection() ?> 