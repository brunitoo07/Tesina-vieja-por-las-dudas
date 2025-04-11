<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Dispositivos</h2>
        <a href="<?= base_url('dispositivo/agregar') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Dispositivo
        </a>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('exito')): ?>
        <div class="alert alert-success">
            <?= session('exito') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($dispositivos)): ?>
        <div class="alert alert-info">
            No tienes dispositivos vinculados. Haz clic en "Agregar Dispositivo" para vincular uno.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($dispositivos as $dispositivo): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($dispositivo['nombre']) ?></h5>
                            <p class="card-text">
                                <strong>MAC Address:</strong> <?= esc($dispositivo['mac_address']) ?><br>
                                <strong>Estado:</strong> 
                                <span class="badge bg-<?= $dispositivo['estado'] === 'activo' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($dispositivo['estado']) ?>
                                </span>
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('energia/verDatos/' . $dispositivo['id_dispositivo']) ?>" 
                                   class="btn btn-info">
                                    <i class="fas fa-chart-line"></i> Ver Datos
                                </a>
                                <form action="<?= base_url('dispositivo/eliminar/' . $dispositivo['id_dispositivo']) ?>" 
                                      method="post" 
                                      onsubmit="return confirm('¿Estás seguro de que deseas desvincular este dispositivo?');">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Desvincular
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .card {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: 0.3s;
    }
    .card:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .card-body {
        padding: 1.5rem;
    }
    .card-title {
        color: #333;
        margin-bottom: 1rem;
    }
    .card-text {
        color: #666;
        margin-bottom: 1.5rem;
    }
    .btn {
        padding: 0.5rem 1rem;
    }
    .badge {
        padding: 0.5em 1em;
        font-size: 0.9em;
    }
</style>
<?= $this->endSection() ?> 