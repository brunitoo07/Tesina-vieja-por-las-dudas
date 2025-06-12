<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus"></i> Registrar Nuevo Dispositivo
                    </h3>
                </div>
                <div class="card-body">
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

                    <form action="<?= base_url('dispositivo/guardar') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Dispositivo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?= old('nombre') ?>" required>
                            <div class="form-text">Asigna un nombre para identificar tu dispositivo</div>
                        </div>

                        <div class="mb-3">
                            <label for="mac_address" class="form-label">Dirección MAC</label>
                            <input type="text" class="form-control" id="mac_address" name="mac_address" 
                                   pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$" 
                                   placeholder="XX:XX:XX:XX:XX:XX" 
                                   value="<?= old('mac_address') ?>" required>
                            <div class="form-text">Ingresa la dirección MAC que aparece en el dispositivo</div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción (opcional)</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" 
                                      rows="3"><?= old('descripcion') ?></textarea>
                            <div class="form-text">Añade una descripción para tu dispositivo</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Dispositivo
                            </button>
                            <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver a Mis Dispositivos
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formatear automáticamente la MAC mientras se escribe
    const macInput = document.getElementById('mac_address');
    macInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9A-Fa-f]/g, '');
        let formattedValue = '';
        
        for(let i = 0; i < value.length && i < 12; i++) {
            if(i > 0 && i % 2 === 0) {
                formattedValue += ':';
            }
            formattedValue += value[i];
        }
        
        e.target.value = formattedValue;
    });
});
</script>
<?= $this->endSection() ?> 