<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Compra Completada! - EcoMonitor Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .success-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 2rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="success-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card p-5 text-center">
                        <div class="card-body">
                            <i class="fas fa-check-circle success-icon"></i>
                            <h1 class="display-4 mb-4">¡Gracias por tu compra!</h1>
                            <p class="lead mb-4">Tu pago ha sido procesado exitosamente y tu cuenta de EcoMonitor Pro ha sido activada.</p>
                            <hr class="my-4">
                            <div class="mb-4">
                                <h5>¿Qué sigue?</h5>
                                <p>Recibirás un correo electrónico con los detalles de tu compra y las instrucciones para comenzar a usar EcoMonitor Pro.</p>
                            </div>
                            <a href="/" class="btn btn-primary btn-lg">
                                <i class="fas fa-home me-2"></i>Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
