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
                        <p class="lead">$85</p>
                        <ul class="list-unstyled mb-4">
                            <li><i class="fas fa-check text-success me-2"></i>Monitoreo ilimitado</li>
                            <li><i class="fas fa-check text-success me-2"></i>Soporte prioritario</li>
                            <li><i class="fas fa-check text-success me-2"></i>Actualizaciones gratuitas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Acceso como administrador</li>
                        </ul>

                        <!-- Añadimos pestañas para métodos de pago -->
                        <ul class="nav nav-tabs mb-4" id="metodoPago" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="paypal-tab" data-bs-toggle="tab" href="#paypal" role="tab">
                                    <i class="fab fa-paypal"></i> PayPal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tarjeta-tab" data-bs-toggle="tab" href="#tarjeta" role="tab">
                                    <i class="fas fa-credit-card"></i> Tarjeta de Crédito/Débito
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="transferencia-tab" data-bs-toggle="tab" href="#transferencia" role="tab">
                                    <i class="fas fa-university"></i> Transferencia Bancaria
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="metodoPagoContent">
                            <!-- PayPal -->
                            <div class="tab-pane fade show active" id="paypal" role="tabpanel">
                                <div id="paypal-button-container"></div>
                            </div>

                            <!-- Tarjeta de Crédito/Débito -->
                            <div class="tab-pane fade" id="tarjeta" role="tabpanel">
                                <form id="form-tarjeta" class="needs-validation" novalidate>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" placeholder="Número de tarjeta" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="MM/AA" required>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" placeholder="CVV" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Pagar ahora</button>
                                </form>
                            </div>

                            <!-- Transferencia Bancaria -->
                            <div class="tab-pane fade" id="transferencia" role="tabpanel">
                                <div class="alert alert-info">
                                    <h5>Datos bancarios:</h5>
                                    <p>Banco: [Nombre del Banco]</p>
                                    <p>Cuenta: XXXX-XXXX-XXXX-XXXX</p>
                                    <p>CLABE: XXXXXXXXXXXXXXXXXX</p>
                                    <p>Beneficiario: EcoVolt</p>
                                </div>
                                <button class="btn btn-success" onclick="notificarTransferencia()">
                                    Notificar pago realizado
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts necesarios -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID&currency=MXN"></script>
    <script>
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
                        description: "Plan Premium EcoVolt",
                        amount: {
                            value: '85.00',
                            currency_code: 'MXN',
                            breakdown: {
                                item_total: {
                                    currency_code: 'MXN',
                                    value: '85.00'
                                }
                            }
                        },
                        items: [{
                            name: 'Plan Premium EcoVolt',
                            description: 'Suscripción Premium con acceso completo',
                            unit_amount: {
                                currency_code: 'MXN',
                                value: '85.00'
                            },
                            quantity: '1'
                        }]
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    // Capturamos los detalles de la transacción
                    console.log('Capture result', orderData);
                    
                    // Mostramos mensaje de éxito
                    Swal.fire({
                        title: '¡Pago Exitoso!',
                        text: '¡Gracias por tu compra! Tu pedido ha sido procesado.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        // Redirigimos a la página de confirmación
                        window.location.href = '<?= base_url('compra/completada') ?>';
                    });
                });
            },
            onError: function(err) {
                // Manejamos errores
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al procesar tu pago. Por favor, intenta nuevamente.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                console.error('Error:', err);
            },
            onCancel: function(data) {
                // Manejamos la cancelación
                Swal.fire({
                    title: 'Pago Cancelado',
                    text: 'Has cancelado el proceso de pago.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        }).render('#paypal-button-container');
    </script>
    
    <!-- Añadimos SweetAlert2 para mejores alertas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html> 