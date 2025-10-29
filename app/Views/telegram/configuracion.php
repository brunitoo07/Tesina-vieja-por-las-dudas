<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Telegram - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .telegram-card {
            background: linear-gradient(135deg, #0088cc 0%, #229ED9 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .config-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }
        .status-activo {
            background: #28a745;
            color: white;
        }
        .status-inactivo {
            background: #dc3545;
            color: white;
        }
        .status-pendiente {
            background: #ffc107;
            color: #212529;
        }
        .qr-code {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 1rem 0;
        }
        .step-number {
            width: 40px;
            height: 40px;
            background: #0088cc;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }
        .step-content {
            flex: 1;
        }
        .copy-btn {
            cursor: pointer;
            transition: all 0.3s;
        }
        .copy-btn:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="telegram-card text-center">
                    <h1><i class="fab fa-telegram"></i> Configuración de Telegram</h1>
                    <p class="mb-0">Recibe notificaciones personalizadas de tus dispositivos EcoVolt</p>
                </div>

                <!-- Estado Actual -->
                <div class="config-section">
                    <h3><i class="fas fa-info-circle"></i> Estado Actual</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Estado:</strong>
                            <?php if ($telegramConfigurado): ?>
                                <span class="status-badge status-activo">
                                    <i class="fas fa-check"></i> Configurado
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-inactivo">
                                    <i class="fas fa-times"></i> No configurado
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Notificaciones:</strong>
                            <?php if ($telegramConfigurado && $telegramData['notificaciones_activas']): ?>
                                <span class="status-badge status-activo">
                                    <i class="fas fa-bell"></i> Activas
                                </span>
                            <?php elseif ($telegramConfigurado): ?>
                                <span class="status-badge status-inactivo">
                                    <i class="fas fa-bell-slash"></i> Desactivadas
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-pendiente">
                                    <i class="fas fa-clock"></i> Pendiente
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!$telegramConfigurado): ?>
                <!-- Instrucciones de Configuración -->
                <div class="config-section">
                    <h3><i class="fas fa-cog"></i> Configurar Telegram</h3>
                    <p>Sigue estos pasos para vincular tu cuenta de Telegram con EcoVolt:</p>

                    <div class="d-flex align-items-start mb-3">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h5>Busca el Bot de EcoVolt</h5>
                            <p>En Telegram, busca <strong>@EcoVoltBot</strong> o usa este enlace:</p>
                            <a href="https://t.me/EcoVoltBot" target="_blank" class="btn btn-primary">
                                <i class="fab fa-telegram"></i> Abrir Bot en Telegram
                            </a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h5>Inicia el Bot</h5>
                            <p>Envía el comando <code>/start</code> al bot para comenzar el proceso de registro.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h5>Registra tu Cuenta</h5>
                            <p>Envía el comando <code>/registrar</code> al bot. Te dará un código único.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h5>Vincular con tu Cuenta</h5>
                            <p>Una vez que tengas el código del bot, úsalo en el formulario de abajo para vincular tu cuenta.</p>
                        </div>
                    </div>

                    <!-- Formulario de Vinculación -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5><i class="fas fa-link"></i> Vincular Cuenta</h5>
                        </div>
                        <div class="card-body">
                            <form id="vincularTelegramForm">
                                <div class="mb-3">
                                    <label for="chatId" class="form-label">Código de Telegram</label>
                                    <input type="text" class="form-control" id="chatId" name="chat_id" 
                                           placeholder="Pega aquí el código que te dio el bot" required>
                                    <div class="form-text">Este código lo obtienes enviando /registrar al bot de Telegram.</div>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-link"></i> Vincular Cuenta
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <!-- Configuración Existente -->
                <div class="config-section">
                    <h3><i class="fas fa-cog"></i> Configuración Actual</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5><i class="fab fa-telegram"></i> Información de Telegram</h5>
                                    <p><strong>Usuario:</strong> <?= esc($telegramData['first_name'] ?? 'N/A') ?></p>
                                    <p><strong>Username:</strong> @<?= esc($telegramData['username'] ?? 'N/A') ?></p>
                                    <p><strong>Chat ID:</strong> <code><?= esc($telegramData['chat_id']) ?></code></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5><i class="fas fa-bell"></i> Notificaciones</h5>
                                    <p><strong>Estado:</strong> 
                                        <?php if ($telegramData['notificaciones_activas']): ?>
                                            <span class="text-success">Activas</span>
                                        <?php else: ?>
                                            <span class="text-danger">Desactivadas</span>
                                        <?php endif; ?>
                                    </p>
                                    <p><strong>Tipos:</strong></p>
                                    <?php 
                                    $tipos = json_decode($telegramData['tipo_notificaciones'], true);
                                    foreach ($tipos as $tipo => $activo): 
                                    ?>
                                        <span class="badge <?= $activo ? 'bg-success' : 'bg-secondary' ?> me-1">
                                            <?= ucfirst($tipo) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Controles -->
                    <div class="mt-4">
                        <h5>Gestión de Notificaciones</h5>
                        <div class="btn-group" role="group">
                            <?php if ($telegramData['notificaciones_activas']): ?>
                                <button class="btn btn-warning" onclick="desactivarNotificaciones()">
                                    <i class="fas fa-bell-slash"></i> Desactivar Notificaciones
                                </button>
                            <?php else: ?>
                                <button class="btn btn-success" onclick="activarNotificaciones()">
                                    <i class="fas fa-bell"></i> Activar Notificaciones
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-info" onclick="probarNotificacion()">
                                <i class="fas fa-paper-plane"></i> Probar Notificación
                            </button>
                            <button class="btn btn-danger" onclick="desvincularTelegram()">
                                <i class="fas fa-unlink"></i> Desvincular
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Información Adicional -->
                <div class="config-section">
                    <h3><i class="fas fa-question-circle"></i> ¿Qué Notificaciones Recibirás?</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-exclamation-triangle text-warning"></i> Alertas de Consumo</h5>
                            <ul>
                                <li>Cuando se supere el límite configurado</li>
                                <li>Prealertas al 90% del límite</li>
                                <li>Notificaciones de cortes de línea</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-cog text-info"></i> Configuraciones</h5>
                            <ul>
                                <li>Confirmación de límites establecidos</li>
                                <li>Cambios en configuración de dispositivos</li>
                                <li>Estados del sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Vincular Telegram
        document.getElementById('vincularTelegramForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const chatId = document.getElementById('chatId').value;
            
            fetch('<?= base_url('telegram/vincular') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ chat_id: chatId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Telegram vinculado exitosamente!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error al vincular Telegram: ' + error);
            });
        });

        // Desactivar notificaciones
        function desactivarNotificaciones() {
            if (confirm('¿Estás seguro de que quieres desactivar las notificaciones?')) {
                fetch('<?= base_url('telegram/desactivar') ?>', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Notificaciones desactivadas');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }

        // Activar notificaciones
        function activarNotificaciones() {
            fetch('<?= base_url('telegram/activar') ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Notificaciones activadas');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }

        // Probar notificación
        function probarNotificacion() {
            fetch('<?= base_url('telegram/probar') ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Notificación de prueba enviada');
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }

        // Desvincular Telegram
        function desvincularTelegram() {
            if (confirm('¿Estás seguro de que quieres desvincular tu cuenta de Telegram?')) {
                fetch('<?= base_url('telegram/desvincular') ?>', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Telegram desvinculado');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }
    </script>
</body>
</html>
