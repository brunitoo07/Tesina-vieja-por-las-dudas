<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error en el Pago - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .error-header {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        .error-icon {
            font-size: 5rem;
            color: #f44336;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-header">
        <h1 class="display-4">¡Ups! Algo salió mal</h1>
        <p class="lead">Ha ocurrido un error al procesar tu pago</p>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-5">
                    <i class="fas fa-exclamation-circle error-icon"></i>
                    <h2>Error en el Proceso de Pago</h2>
                    <p class="lead"><?= esc($mensaje) ?></p>
                </div>

                <div class="text-center mt-5">
                    <p>Por favor, intenta nuevamente o contacta a nuestro servicio de atención al cliente.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= base_url('registro-compra') ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-redo me-2"></i>Intentar de nuevo
                        </a>
                        <a href="<?= base_url('autenticacion/login') ?>" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 