<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar EcoMonitor Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .pricing-header {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        .feature-list {
            list-style: none;
            padding-left: 0;
        }
        .feature-list li {
            padding: 0.5rem 0;
        }
        .feature-list i {
            color: #4CAF50;
            margin-right: 10px;
        }
        .price-tag {
            font-size: 3rem;
            font-weight: bold;
            color: #4CAF50;
        }
        .btn-purchase {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            border: none;
            padding: 1rem 2rem;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }
        .btn-purchase:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
        }
    </style>
</head>
<body>
    <div class="pricing-header">
        <h1 class="display-4">EcoMonitor Pro</h1>
        <p class="lead">La solución completa para el monitoreo de energía</p>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body text-center p-5">
                        <h2 class="card-title mb-4">Plan Premium</h2>
                        <div class="price-tag mb-4">$99.99</div>
                        <p class="text-muted mb-4">Acceso completo a todas las funcionalidades</p>
                        
                        <ul class="feature-list text-start mb-5">
                            <li><i class="fas fa-check-circle"></i> Panel de administración completo</li>
                            <li><i class="fas fa-check-circle"></i> Monitoreo en tiempo real</li>
                            <li><i class="fas fa-check-circle"></i> Reportes detallados</li>
                            <li><i class="fas fa-check-circle"></i> Alertas personalizadas</li>
                            <li><i class="fas fa-check-circle"></i> Soporte prioritario 24/7</li>
                            <li><i class="fas fa-check-circle"></i> Actualizaciones gratuitas</li>
                        </ul>

                        <form action="<?= base_url('compra/simularCompra') ?>" method="post">
                            <button type="submit" class="btn btn-primary btn-purchase btn-lg w-100">
                                <i class="fas fa-shopping-cart me-2"></i>Comprar Ahora
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="fas fa-lock me-2"></i>Pago 100% seguro
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
