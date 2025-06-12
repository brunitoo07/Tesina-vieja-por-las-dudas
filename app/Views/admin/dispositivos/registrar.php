<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Registrar Nuevo Dispositivo</h1>
    
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Información del Dispositivo
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/dispositivos/guardar') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Dispositivo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= old('nombre') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="mac_address" class="form-label">Dirección MAC</label>
                    <input type="text" class="form-control" id="mac_address" name="mac_address" 
                           value="<?= old('mac_address') ?>" 
                           pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$"
                           placeholder="XX:XX:XX:XX:XX:XX" required>
                    <div class="form-text">Formato: XX:XX:XX:XX:XX:XX (donde X es un número hexadecimal)</div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= old('descripcion') ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const macInput = document.getElementById('mac_address');
    
    macInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9A-Fa-f]/g, '');
        let formattedValue = '';
        
        for (let i = 0; i < value.length && i < 12; i++) {
            if (i > 0 && i % 2 === 0) {
                formattedValue += ':';
            }
            formattedValue += value[i];
        }
        
        e.target.value = formattedValue;
    });
});
</script>
<?= $this->endSection() ?> 