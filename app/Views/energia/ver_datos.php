<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalles del Registro</h2>
        <a href="<?= base_url('energia') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i:s', strtotime($energia['fecha'])) ?></p>
                    <p><strong>Voltaje:</strong> <?= number_format($energia['voltaje'], 2) ?> V</p>
                    <p><strong>Corriente:</strong> <?= number_format($energia['corriente'], 2) ?> A</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Potencia:</strong> <?= number_format($energia['potencia'], 2) ?> W</p>
                    <p><strong>Consumo:</strong> <?= number_format($energia['kwh'], 2) ?> kWh</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 