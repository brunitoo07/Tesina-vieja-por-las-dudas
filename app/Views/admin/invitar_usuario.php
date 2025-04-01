<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
.invite-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-radius: 15px 15px 0 0 !important;
    padding: 1.5rem;
}

.card-header h3 {
    margin: 0;
    font-weight: 600;
    font-size: 1.5rem;
}

.card-body {
    padding: 2rem;
    background-color: #fff;
    border-radius: 0 0 15px 15px;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004094);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

.alert {
    border: none;
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert-info {
    background-color: #cce5ff;
    color: #004085;
    border-left: 4px solid #007bff;
}

.debug-info {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin-top: 1rem;
    font-size: 0.9rem;
}

.debug-info h5 {
    color: #495057;
    margin-bottom: 1rem;
}

.debug-info p {
    margin-bottom: 0.5rem;
}

.debug-info strong {
    color: #007bff;
}
</style>

<div class="invite-container">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Invitar Usuario</h3>
        </div>
        <div class="card-body">
            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->has('success')) : ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session('success') ?>
                </div>
                <?php if (isset($ultimo_email)) : ?>
                    <div class="alert alert-info">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Detalles del último email enviado
                        </h5>
                        <div class="debug-info">
                            <p><strong>Para:</strong> <?= $ultimo_email['to'] ?></p>
                            <p><strong>Asunto:</strong> <?= $ultimo_email['subject'] ?></p>
                            <p><strong>Enviado:</strong> <?= $ultimo_email['timestamp'] ?></p>
                            <?php if (isset($ultimo_email['debug'])) : ?>
                                <div class="mt-3">
                                    <p><strong>Información de depuración:</strong></p>
                                    <pre class="bg-light p-2 rounded"><?= print_r($ultimo_email['debug'], true) ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form action="<?= base_url('admin/invitarUsuario') ?>" method="post">
                <div class="mb-4">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Correo Electrónico
                    </label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           value="<?= old('email') ?>"
                           placeholder="ejemplo@correo.com"
                           required>
                </div>

                <div class="mb-4">
                    <label for="rol" class="form-label">
                        <i class="fas fa-user-tag me-2"></i>Rol
                    </label>
                    <select class="form-select" id="rol" name="rol" required>
                        <option value="">Seleccionar rol</option>
                        <option value="1" <?= old('rol') == '1' ? 'selected' : '' ?>>Administrador</option>
                        <option value="2" <?= old('rol') == '2' ? 'selected' : '' ?>>Usuario</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Invitación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 