<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar EcoMonitor Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AVc8Jj68sTx6Jv9nb46eoXNfoSgFcAr6C0ZQuogzyFuQ7dDwBPPSnqET1LM3vr1yi0c9tHp4mVuPxZlB&currency=USD"></script>
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
        #paypal-container {
            display: none;
            margin-top: 20px;
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

                        <button id="btn-comprar" class="btn btn-primary btn-purchase btn-lg w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Comprar Ahora
                        </button>

                        <!-- Contenedor de PayPal (se muestra tras hacer clic en comprar) -->
                        <div id="paypal-container">
                            <h4 class="mt-4">Finalizar Pago con PayPal</h4>
                            <div id="paypal-button-container"></div>
                            <p id="status" class="mt-3"></p>
                        </div>

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

    <script>
        document.getElementById('btn-comprar').addEventListener('click', function() {
            document.getElementById('paypal-container').style.display = 'block';
            this.style.display = 'none';
        });

        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'blue',
                shape:  'rect',
                label:  'pay'
            },
            
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        description: "EcoMonitor Pro - Plan Premium",
                        amount: {
                            currency_code: "USD",
                            value: '99.99',
                            breakdown: {
                                item_total: {
                                    currency_code: "USD",
                                    value: '99.99'
                                }
                            }
                        },
                        items: [{
                            name: "EcoMonitor Pro Premium",
                            description: "Acceso completo a todas las funcionalidades",
                            unit_amount: {
                                currency_code: "USD",
                                value: '99.99'
                            },
                            quantity: "1"
                        }]
                    }]
                });
            },

            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    document.getElementById("status").innerHTML = `
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">¡Pago completado con éxito!</h4>
                            <p>Gracias por tu compra, ${details.payer.name.given_name} ${details.payer.name.surname}</p>
                            <hr>
                            <p class="mb-0">ID de Transacción: ${details.id}</p>
                        </div>`;
                    
                    setTimeout(function() {
                        // Redirigir a la página de registro como administrador
                        window.location.href = '<?= base_url('autenticacion/register?purchase=true') ?>'; // Cambia esta ruta a la de tu página de registro
                    }, 3000);
                });
            },

            onError: function(err) {
                document.getElementById("status").innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Error en el pago</h4>
                        <p>Lo sentimos, ha ocurrido un error durante el proceso de pago. Por favor, inténtalo de nuevo.</p>
                    </div>`;
            },

            onCancel: function(data) {
                document.getElementById("status").innerHTML = `
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Pago cancelado</h4>
                        <p>Has cancelado el proceso de pago. Puedes intentarlo nuevamente cuando lo desees.</p>
                    </div>`;
            }
        }).render('#paypal-button-container');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
