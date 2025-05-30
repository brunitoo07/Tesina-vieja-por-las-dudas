<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pago Exitoso! - EcoMonitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .success-header {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        .success-icon {
            font-size: 5rem;
            color: #4CAF50;
            margin-bottom: 1rem;
        }
        .order-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="success-header">
        <h1 class="display-4">¡Pago Completado con Éxito!</h1>
        <p class="lead">Gracias por tu compra en EcoMonitor</p>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-5">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h2>¡Gracias por tu compra, <?= esc($nombre) ?>!</h2>
                    <p class="lead">Tu pedido ha sido procesado correctamente.</p>
                </div>

                <div class="order-details">
                    <h3 class="mb-4">Detalles del Pedido</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Producto:</strong> <?= esc($dispositivo['nombre']) ?></p>
                            <p><strong>Precio:</strong> $<?= number_format($dispositivo['precio'], 2) ?></p>
                            <p><strong>Fecha de compra:</strong> <?= $fecha ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Dirección de envío:</strong></p>
                            <p><?= esc($direccion) ?></p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p>Recibirás un email con la confirmación de tu compra y los detalles de envío.</p>
                    <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 