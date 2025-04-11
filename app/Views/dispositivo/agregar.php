<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agregar Nuevo Dispositivo</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('dispositivo/guardar') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Dispositivo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="form-text">Ejemplo: Medidor Principal, Medidor Cocina, etc.</div>
                        </div>

                        <div class="mb-3">
                            <label for="mac_address" class="form-label">MAC Address</label>
                            <input type="text" class="form-control" id="mac_address" name="mac_address" required>
                            <div class="form-text">Formato: XX:XX:XX:XX:XX:XX (Ejemplo: 00:1A:2B:3C:4D:5E)</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Vincular Dispositivo</button>
                            <a href="<?= base_url('dispositivo') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('mac_address').addEventListener('input', function(e) {
    // Eliminar cualquier carácter que no sea hexadecimal
    let value = e.target.value.replace(/[^0-9A-Fa-f]/g, '');
    
    // Agregar dos puntos cada dos caracteres
    let formatted = '';
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 2 === 0) {
            formatted += ':';
        }
        formatted += value[i];
    }
    
    e.target.value = formatted.toUpperCase();
});
</script>
<?= $this->endSection() ?> 