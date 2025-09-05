<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container mt-4">
    <div class="row">
        <!-- Tarjeta de Dispositivos -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-microchip"></i> Dispositivos
                    </h5>
                    <p class="card-text">Gestiona tus dispositivos ESP32 y monitorea su estado.</p>
                    <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-primary">
                        <i class="fas fa-cogs"></i> Gestionar Dispositivos
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Perfil -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-user-circle"></i> Mi Perfil
                    </h5>
                    <p class="card-text">Gestiona tu información personal y cambia tu contraseña.</p>
                    <a href="<?= base_url('usuario/perfil') ?>" class="btn btn-primary">
                        <i class="fas fa-user-edit"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Asistente Virtual -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-robot"></i> Asistente Virtual
                    </h5>
                    <p class="card-text">Consulta información del sistema, dispositivos y consumo de energía.</p>
                    <button onclick="toggleChat()" class="btn btn-success">
                        <i class="fas fa-comments"></i> Abrir Chat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Asistente Virtual -->
<?= $this->include('chat_profesional') ?>

<?= $this->endSection() ?> 