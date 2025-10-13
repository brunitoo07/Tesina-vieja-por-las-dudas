<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Premium - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AVc8Jj68sTx6Jv9nb46eoXNfoSgFcAr6C0ZQuogzyFuQ7dDwBPPSnqET1LM3vr1yi0c9tHp4mVuPxZlB&currency=USD"></script>
    <style>
        /* === PALETA DE COLORES PREMIUM === */
        :root {
            --gold-primary: #D4AF37;
            --gold-secondary: #B8860B;
            --gold-light: #F7E98E;
            --gold-dark: #8B7355;
            --silver-primary: #C0C0C0;
            --silver-secondary: #A8A8A8;
            --silver-light: #E8E8E8;
            --black-primary: #1a1a1a;
            --black-secondary: #2d2d2d;
            --black-light: #404040;
            --white-primary: #ffffff;
            --white-secondary: #f8f9fa;
            --white-dark: #e9ecef;
            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            --gradient-silver: linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            --shadow-premium: 0 10px 30px rgba(212, 175, 55, 0.3);
            --shadow-dark: 0 10px 30px rgba(0, 0, 0, 0.3);
            --border-radius: 15px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* === ESTILOS GLOBALES PREMIUM === */
        body {
            background: var(--gradient-dark);
            color: var(--white-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* === HEADER PREMIUM === */
        .premium-header {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-premium);
        }

        .premium-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .premium-header p {
            font-size: 1.2rem;
            margin: 0.5rem 0 0 0;
            font-weight: 600;
        }

        /* === TARJETAS PREMIUM === */
        .premium-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-premium);
            backdrop-filter: blur(10px);
            transition: var(--transition);
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.4);
        }

        .premium-title {
            color: var(--gold-primary);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* === LISTA DE CARACTERÍSTICAS === */
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
        }

        .feature-list li {
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list i {
            color: var(--gold-primary);
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        /* === ETIQUETA DE PRECIO === */
        .price-tag {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 2rem 0;
            box-shadow: var(--shadow-premium);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* === IMAGEN DEL DISPOSITIVO === */
        .device-image {
            text-align: center;
            margin: 2rem 0;
        }

        .device-image i {
            font-size: 4rem;
            color: var(--gold-primary);
            text-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
        }

        .device-name {
            color: var(--gold-primary);
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin: 1rem 0;
        }

        .device-description {
            color: var(--silver-primary);
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* === BADGE DE SEGURIDAD === */
        .security-badge {
            background: var(--gradient-silver);
            color: var(--black-primary);
            padding: 1rem;
            border-radius: var(--border-radius);
            text-align: center;
            margin-top: 2rem;
            font-weight: 600;
            box-shadow: var(--shadow-dark);
        }

        .security-badge i {
            margin-right: 0.5rem;
        }

        /* === INFORMACIÓN DEL USUARIO === */
        .user-info {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .user-info h5 {
            color: var(--gold-primary);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .user-info p {
            margin: 0.5rem 0;
            color: var(--white-primary);
        }

        .user-info strong {
            color: var(--gold-primary);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .premium-header h1 {
                font-size: 2rem;
            }
            
            .premium-card {
                padding: 1.5rem;
            }
            
            .price-tag {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Premium -->
    <div class="premium-header">
        <div class="container">
            <h1><i class="fas fa-crown me-3"></i>EcoVolt Pro Premium</h1>
            <p>único plan premium disponible - Dispositivo + Soporte Premium Mensual</p>
            <div class="price-tag" style="font-size: 1.5rem; margin: 1rem 0 0 0;">$150 USD</div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Información del Producto -->
            <div class="col-md-6">
                <div class="premium-card">
                    <div class="device-image">
                        <i class="fas fa-microchip"></i>
                    </div>
                    
                    <h3 class="device-name"><?= esc($dispositivo['nombre']) ?></h3>
                    <p class="device-description">Dispositivo de monitoreo de energía inteligente premium para tu hogar.</p>
                    
                    <div style="background: var(--gradient-gold); color: var(--black-primary); padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                        <h4 style="margin: 0; font-weight: 700; font-size: 1.2rem;">
                            <i class="fas fa-crown me-2"></i>PLAN PREMIUM ÚNICO
                        </h4>
                        <p style="margin: 5px 0 0 0; font-weight: 600;">Dispositivo + Soporte Premium Mensual</p>
                    </div>
                    
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Monitoreo en tiempo real</li>
                        <li><i class="fas fa-check-circle"></i> Análisis detallado de consumo</li>
                        <li><i class="fas fa-check-circle"></i> Alertas personalizadas</li>
                        <li><i class="fas fa-check-circle"></i> Compatible con todos los sistemas</li>
                        <li><i class="fas fa-check-circle"></i> <strong>Soporte premium 24/7 todos los días</strong></li>
                        <li><i class="fas fa-check-circle"></i> Garantía extendida de 2 años</li>
                        <li><i class="fas fa-check-circle"></i> <strong>Soporte técnico mensual incluido</strong></li>
                        <li><i class="fas fa-check-circle"></i> <strong>Actualizaciones premium gratuitas</strong></li>
                    </ul>
                    
                    <div style="background: var(--black-light); padding: 15px; border-radius: 10px; margin: 20px 0; border: 2px solid var(--gold-primary);">
                        <p style="color: var(--gold-primary); font-weight: 600; margin: 0; text-align: center;">
                            <i class="fas fa-info-circle me-2"></i>
                            El soporte premium se renueva mensualmente
                        </p>
                    </div>
                    
                    <div class="price-tag">$150 USD</div>
                </div>
            </div>

            <!-- Resumen de Compra -->
            <div class="col-md-6">
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-shopping-cart me-2"></i>Resumen de Compra
                    </h3>
                    
                    <!-- Información del Usuario -->
                    <div class="user-info">
                        <h5><i class="fas fa-user me-2"></i>Datos de Envío</h5>
                        <p><strong>Nombre:</strong> <?= esc($datos_compra['nombre']) ?> <?= esc($datos_compra['apellido']) ?></p>
                        <p><strong>Email:</strong> <?= esc($datos_compra['email']) ?></p>
                        <p><strong>Dirección:</strong> <?= esc($datos_compra['direccion']) ?></p>
                    </div>

                    <hr style="border-color: var(--gold-primary); margin: 2rem 0;">

                    <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--black-light);">
                        <span style="color: var(--silver-primary); font-size: 1.1rem;">Dispositivo EcoVolt Pro:</span>
                        <span style="color: var(--white-primary); font-weight: 600; font-size: 1.1rem;">$150 USD</span>
                    </div>
                    <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--black-light);">
                        <span style="color: var(--silver-primary); font-size: 1.1rem;">Soporte Premium Mensual:</span>
                        <span style="color: var(--gold-primary); font-weight: 600; font-size: 1.1rem;">Incluido</span>
                    </div>
                    <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--black-light);">
                        <span style="color: var(--silver-primary); font-size: 1.1rem;">Envío:</span>
                        <span style="color: var(--gold-primary); font-weight: 600; font-size: 1.1rem;">Gratis</span>
                    </div>
                    <div style="background: var(--gradient-gold); color: var(--black-primary); padding: 10px; border-radius: 8px; margin: 15px 0; text-align: center;">
                        <p style="margin: 0; font-weight: 600; font-size: 0.9rem;">
                            <i class="fas fa-percentage me-1"></i>Descuento ya aplicado
                        </p>
                    </div>
                    <hr style="border-color: var(--gold-primary); margin: 2rem 0;">
                    <div class="mb-4" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; background: var(--black-light); border-radius: 10px; padding: 1rem;">
                        <strong style="color: var(--gold-primary); font-size: 1.3rem;">Total:</strong>
                        <strong class="price-tag" style="font-size: 2rem; margin: 0;">$150 USD</strong>
                    </div>

                    <div id="paypal-button-container"></div>
                    <p id="status" class="mt-3"></p>
                </div>

                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    Pago 100% seguro con PayPal - Protección del comprador incluida
                </div>
            </div>
        </div>
    </div>

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
                        description: "EcoVolt Pro Premium - Dispositivo + Soporte Mensual",
                        amount: {
                            currency_code: "USD",
                            value: '150.00',
                            breakdown: {
                                item_total: {
                                    currency_code: "USD",
                                    value: '150.00'
                                }
                            }
                        },
                        items: [{
                            name: "EcoVolt Pro Premium",
                            description: "Dispositivo de monitoreo + Soporte Premium Mensual",
                            unit_amount: {
                                currency_code: "USD",
                                value: '150.00'
                            },
                            quantity: "1"
                        }]
                    }]
                });
            },

            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Enviar datos al servidor
                    fetch('<?= base_url('compra/procesarPago') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(details)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById("status").innerHTML = `
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">¡Pago realizado con éxito!</h4>
                                    <p>¡Gracias por tu compra, ${details.payer.name.given_name} ${details.payer.name.surname}!</p>
                                    <hr>
                                    <p class="mb-0">ID de transacción: ${details.id}</p>
                                </div>`;
                            window.location.href = data.redirect;
                        } else {
                            document.getElementById("status").innerHTML = `
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading">Error en el pago</h4>
                                    <p>${data.message}</p>
                                </div>`;
                        }
                    })
                    .catch(error => {
                        document.getElementById("status").innerHTML = `
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Error en el pago</h4>
                                <p>Lo sentimos, ha ocurrido un error durante el proceso de pago. Por favor, inténtalo de nuevo.</p>
                            </div>`;
                    });
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
                        <p>Has cancelado el proceso de pago. Puedes intentarlo de nuevo cuando desees.</p>
                    </div>`;
            }
        }).render('#paypal-button-container');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>