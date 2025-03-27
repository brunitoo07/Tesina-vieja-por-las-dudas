<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="card-title mb-4">Plan Premium</h2>
                        <p class="lead">$85 USD</p>
                        <ul class="list-unstyled mb-4">
                            <li><i class="fas fa-check text-success me-2"></i>Monitoreo ilimitado</li>
                            <li><i class="fas fa-check text-success me-2"></i>Soporte prioritario</li>
                            <li><i class="fas fa-check text-success me-2"></i>Actualizaciones gratuitas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Acceso como administrador</li>
                        </ul>
                        <div id="paypal-button-container"></div>
                        <!-- Botón para volver al inicio -->
                        <a href="http://localhost/CODEIGNITERNUEVO/public/" class="btn btn-secondary mt-3">
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AeP3vW1w2uEU1Pjp8dC-qF6Q4LJ5elKP8nyc99-RfpbpAzcE2cLH6AgEv9QUfE3fag0DIB_nNSVHfjVQ&currency=USD"></script>
    <script>
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color: 'blue',
                shape: 'rect',
                label: 'pay'
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        description: "Plan Premium EcoVolt",
                        amount: {
                            value: '85.00',
                            currency_code: 'USD'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    console.log('Pago exitoso', orderData);
                    window.location.href = '<?= base_url('compra/completada') ?>';
                });
            },
            onError: function(err) {
                console.error('Error en el pago:', err);
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
