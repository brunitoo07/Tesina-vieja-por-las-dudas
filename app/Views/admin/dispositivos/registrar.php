<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Registrar Nuevo Dispositivo</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Formulario de Registro
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/dispositivos/guardar') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Dispositivo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= old('nombre') ?>" required>
                    <div class="form-text">Ingrese un nombre descriptivo para el dispositivo.</div>
                </div>

                <div class="mb-3">
                    <label for="mac_address" class="form-label">Dirección MAC</label>
                    <input type="text" class="form-control" id="mac_address" name="mac_address" value="<?= old('mac_address') ?>" 
                           pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$" required>
                    <div class="form-text">Formato: XX:XX:XX:XX:XX:XX o XX-XX-XX-XX-XX-XX</div>
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
        if (value.length > 12) value = value.substr(0, 12);
        
        let formatted = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 2 === 0) formatted += ':';
            formatted += value[i];
        }
        
        e.target.value = formatted;
    });
});
</script>
<?= $this->endSection() ?> 