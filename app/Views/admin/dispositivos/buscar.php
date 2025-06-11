<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plug"></i> Configuración de Nuevo Dispositivo
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Mini Manual -->
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-info-circle"></i> Guía de Configuración:</h5>
                        <ol class="mb-0">
                            <li class="mb-2">
                                <strong>Preparación del Dispositivo:</strong>
                                <ul>
                                    <li>Conecta el medidor EcoVolt a la corriente eléctrica</li>
                                    <li>Espera a que el LED parpadee rápidamente (modo configuración)</li>
                                </ul>
                            </li>
                            <li class="mb-2">
                                <strong>Conexión Inicial:</strong>
                                <ul>
                                    <li>En tu teléfono, ve a Configuración > WiFi</li>
                                    <li>Conéctate a la red "EcoVolt-Config"</li>
                                    <li>La contraseña es: "12345678"</li>
                                </ul>
                            </li>
                            <li class="mb-2">
                                <strong>Configuración WiFi:</strong>
                                <ul>
                                    <li>Selecciona tu red WiFi de la lista</li>
                                    <li>Ingresa la contraseña de tu red WiFi</li>
                                    <li>Haz clic en "Conectar"</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Finalización:</strong>
                                <ul>
                                    <li>El dispositivo se reiniciará automáticamente</li>
                                    <li>Anota la dirección MAC que aparece en el dispositivo</li>
                                    <li>Usa esta MAC para registrar el dispositivo en tu cuenta</li>
                                </ul>
                            </li>
                        </ol>
                    </div>

                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/img/device-icon.png') ?>" alt="Medidor" class="img-fluid" style="max-width: 150px;">
                        <h4 class="mt-3">EcoVolt</h4>
                        <p class="text-muted">Sigue los pasos anteriores para configurar tu dispositivo</p>
                    </div>

                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> Importante:</h5>
                        <p class="mb-0">Una vez que hayas configurado el dispositivo y tengas su dirección MAC, haz clic en el botón de abajo para registrarlo en tu cuenta.</p>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="<?= base_url('dispositivo/agregar') ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus"></i> Registrar Nuevo Dispositivo
                        </a>
                        <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Dispositivos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 