
  <!-- ESTE ES EL REMUNEN DE COMPRA QUE TE MANDA AÑL BOTON DE PAGAR CON PAYPAL-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AVc8Jj68sTx6Jv9nb46eoXNfoSgFcAr6C0ZQuogzyFuQ7dDwBPPSnqET1LM3vr1yi0c9tHp4mVuPxZlB&currency=ARS"></script>
    <style>
        /* ===============================
           PALETA DE COLORES PREMIUM ÚNICA
           =============================== */
        :root {
            --gold-primary: #D4AF37;
            --gold-secondary: #B8860B;
            --gold-light: #F7E98E;
            --gold-dark: #8B7355;
            --silver-primary: #C0C0C0;
            --silver-secondary: #A8A8A8;
            --silver-light: #E8E8E8;
            --black-primary: #0a0a0a;
            --black-secondary: #1a1a1a;
            --black-light: #2d2d2d;
            --white-primary: #ffffff;
            --white-secondary: #f8f9fa;
            --white-dark: #e9ecef;
            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            --gradient-silver: linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%);
            --gradient-dark: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            --gradient-rainbow: linear-gradient(45deg, #D4AF37, #C0C0C0, #B8860B, #A8A8A8, #D4AF37);
            --shadow-premium: 0 20px 60px rgba(212, 175, 55, 0.4);
            --shadow-dark: 0 20px 60px rgba(0, 0, 0, 0.6);
            --border-radius: 20px;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===============================
           FONDO ÚNICO CON EFECTOS 3D
           =============================== */
        body {
            min-height: 100vh;
            background: 
                radial-gradient(ellipse at top left, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(192, 192, 192, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at center, rgba(0, 0, 0, 0.05) 0%, transparent 70%),
                linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            position: relative;
            overflow-x: hidden;
            color: var(--white-primary);
        }

        /* ===============================
           EFECTO AURORA BOREAL ANIMADO
           =============================== */
        .bg-aurora {
            position: fixed;
            inset: 0;
            z-index: -3;
            pointer-events: none;
            background: 
                radial-gradient(ellipse 800px 400px at 20% 20%, rgba(212, 175, 55, 0.2), transparent 60%),
                radial-gradient(ellipse 600px 300px at 80% 80%, rgba(192, 192, 192, 0.15), transparent 60%),
                radial-gradient(ellipse 400px 200px at 50% 10%, rgba(184, 134, 11, 0.1), transparent 60%);
            filter: blur(1px) saturate(120%);
            animation: auroraMove 25s ease-in-out infinite alternate;
        }

        @keyframes auroraMove {
            0% { 
                transform: translateX(-5%) translateY(-3%) rotate(0deg) scale(1);
                filter: blur(1px) saturate(120%) hue-rotate(0deg);
            }
            33% { 
                transform: translateX(3%) translateY(-8%) rotate(1deg) scale(1.05);
                filter: blur(1.5px) saturate(140%) hue-rotate(10deg);
            }
            66% { 
                transform: translateX(-2%) translateY(2%) rotate(-0.5deg) scale(0.98);
                filter: blur(0.8px) saturate(110%) hue-rotate(-5deg);
            }
            100% { 
                transform: translateX(5%) translateY(5%) rotate(0.5deg) scale(1.02);
                filter: blur(1.2px) saturate(130%) hue-rotate(15deg);
            }
        }

        /* ===============================
           PARTÍCULAS FLOTANTES ÚNICAS
           =============================== */
        .bg-sparkles {
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            overflow: hidden;
        }

        .bg-sparkles span {
            position: absolute;
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, 
                rgba(255, 255, 255, 0.9) 0%, 
                rgba(247, 233, 142, 0.8) 30%, 
                rgba(212, 175, 55, 0.6) 60%, 
                transparent 100%);
            box-shadow: 
                0 0 20px rgba(212, 175, 55, 0.8),
                0 0 40px rgba(212, 175, 55, 0.4),
                inset 0 0 10px rgba(255, 255, 255, 0.3);
            opacity: 0.9;
            animation: floatSparkle calc(12s + var(--i) * 0.8s) linear infinite;
        }

        @keyframes floatSparkle {
            0% { 
                transform: translateY(110vh) translateX(0) scale(0.5) rotate(0deg);
                opacity: 0;
            }
            10% { 
                opacity: 0.9;
                transform: translateY(100vh) translateX(10px) scale(0.8) rotate(45deg);
            }
            50% { 
                transform: translateY(50vh) translateX(-20px) scale(1.2) rotate(180deg);
                opacity: 1;
            }
            90% { 
                opacity: 0.9;
                transform: translateY(10vh) translateX(15px) scale(0.9) rotate(315deg);
            }
            100% { 
                transform: translateY(-10vh) translateX(-10px) scale(0.6) rotate(360deg);
                opacity: 0;
            }
        }

        /* Posiciones únicas para cada partícula */
        .bg-sparkles span:nth-child(1) { left: 8%; --i: 1; }
        .bg-sparkles span:nth-child(2) { left: 18%; --i: 2; }
        .bg-sparkles span:nth-child(3) { left: 28%; --i: 3; }
        .bg-sparkles span:nth-child(4) { left: 38%; --i: 4; }
        .bg-sparkles span:nth-child(5) { left: 48%; --i: 5; }
        .bg-sparkles span:nth-child(6) { left: 58%; --i: 6; }
        .bg-sparkles span:nth-child(7) { left: 68%; --i: 7; }
        .bg-sparkles span:nth-child(8) { left: 78%; --i: 8; }
        .bg-sparkles span:nth-child(9) { left: 88%; --i: 9; }
        .bg-sparkles span:nth-child(10) { left: 25%; --i: 10; }
        .bg-sparkles span:nth-child(11) { left: 55%; --i: 11; }
        .bg-sparkles span:nth-child(12) { left: 85%; --i: 12; }

        /* ===============================
           HEADER ESPECTACULAR
           =============================== */
        .pricing-header {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .pricing-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 0, 0, 0.1) 0%, transparent 50%);
            animation: headerShimmer 6s ease-in-out infinite;
        }

        @keyframes headerShimmer {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        .pricing-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            background: linear-gradient(45deg, var(--black-primary), #333, var(--black-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textGlow 4s ease-in-out infinite alternate;
        }

        @keyframes textGlow {
            0% { filter: drop-shadow(0 0 5px rgba(0, 0, 0, 0.3)); }
            100% { filter: drop-shadow(0 0 15px rgba(0, 0, 0, 0.6)); }
        }

        .pricing-header p {
            font-size: 1.3rem;
            color: var(--black-secondary);
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        /* ===============================
           TARJETAS PREMIUM CON EFECTOS
           =============================== */
        .device-card {
            background: rgba(26, 26, 26, 0.9);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(212, 175, 55, 0.4);
            border-radius: var(--border-radius);
            padding: 2rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: 
                var(--shadow-dark),
                0 0 0 1px rgba(212, 175, 55, 0.1) inset,
                0 0 100px rgba(212, 175, 55, 0.1);
        }

        .device-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-rainbow);
            background-size: 200% 100%;
            animation: rainbowFlow 8s linear infinite;
        }

        @keyframes rainbowFlow {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        .device-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 
                0 30px 80px rgba(212, 175, 55, 0.3),
                0 0 0 2px rgba(212, 175, 55, 0.2) inset,
                0 0 120px rgba(212, 175, 55, 0.15);
        }

        .device-card h3 {
            color: var(--gold-primary);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .device-card p {
            color: var(--silver-light);
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .feature-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 2rem;
        }

        .feature-list li {
            padding: 0.8rem 0;
            color: var(--white-primary);
            font-size: 1rem;
            transition: var(--transition);
        }

        .feature-list li:hover {
            color: var(--gold-light);
            transform: translateX(10px);
        }

        .feature-list i {
            color: var(--gold-primary);
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .price-tag {
            font-size: 3rem;
            font-weight: 800;
            color: var(--gold-primary);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: priceGlow 3s ease-in-out infinite alternate;
        }

        @keyframes priceGlow {
            0% { text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); }
            100% { text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5), 0 0 20px rgba(212, 175, 55, 0.5); }
        }

        /* ===============================
           CARD DE RESUMEN PREMIUM
           =============================== */
        .card {
            background: rgba(26, 26, 26, 0.9);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(212, 175, 55, 0.4);
            border-radius: var(--border-radius);
            color: var(--white-primary);
            position: relative;
            overflow: hidden;
            box-shadow: 
                var(--shadow-dark),
                0 0 0 1px rgba(212, 175, 55, 0.1) inset,
                0 0 100px rgba(212, 175, 55, 0.1);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-rainbow);
            background-size: 200% 100%;
            animation: rainbowFlow 8s linear infinite;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 25px 70px rgba(212, 175, 55, 0.3),
                0 0 0 2px rgba(212, 175, 55, 0.2) inset,
                0 0 120px rgba(212, 175, 55, 0.15);
        }

        .card-title {
            color: var(--gold-primary);
            font-weight: 700;
            font-size: 1.8rem;
        }

        .card-body h4, .card-body strong {
            color: var(--gold-primary);
            font-weight: 600;
        }

        .card-body hr {
            border-color: rgba(212, 175, 55, 0.3);
            border-width: 2px;
        }

        .text-muted {
            color: var(--silver-secondary) !important;
        }

        /* ===============================
           SISTEMA DE DESCUENTOS
           =============================== */
        .discount-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
            animation: discountPulse 2s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes discountPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .discount-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: discountShine 3s ease-in-out infinite;
        }

        @keyframes discountShine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .original-price {
            text-decoration: line-through;
            color: var(--silver-secondary);
            font-size: 1.5rem;
            opacity: 0.7;
        }

        .discounted-price {
            color: #ff6b6b;
            font-size: 2.5rem;
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .savings-amount {
            color: #4ecdc4;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* ===============================
           PAYPAL BUTTON CUSTOMIZADO
           =============================== */
        #paypal-button-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            transition: var(--transition);
        }

        #paypal-button-container:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 123, 255, 0.3);
        }

        #paypal-button-container > div {
            max-width: 100%;
            border-radius: 15px !important;
            overflow: hidden;
            transition: var(--transition);
        }

        #paypal-button-container > div:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 30px rgba(0, 123, 255, 0.4);
        }

        /* ===============================
           ALERTAS MEJORADAS
           =============================== */
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid #28a745;
            color: #90EE90;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 2px solid #dc3545;
            color: #ff6b6b;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border: 2px solid #ffc107;
            color: #ffd700;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        /* ===============================
           EFECTOS DE MARKETING
           =============================== */
        .marketing-banner {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: marketingPulse 4s ease-in-out infinite;
        }

        @keyframes marketingPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        .marketing-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: marketingShine 5s ease-in-out infinite;
        }

        @keyframes marketingShine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .referral-code {
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid var(--gold-primary);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
        }

        .referral-code input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--gold-primary);
            border-radius: 8px;
            padding: 0.5rem;
            color: var(--white-primary);
            text-align: center;
            font-weight: 600;
        }

        .referral-code input:focus {
            outline: none;
            border-color: var(--gold-light);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }

        /* ===============================
           RESPONSIVE DESIGN
           =============================== */
        @media (max-width: 768px) {
            .pricing-header h1 {
                font-size: 2.5rem;
            }
            
            .device-card, .card {
                margin-bottom: 2rem;
            }
            
            .price-tag {
                font-size: 2.2rem;
            }
        }
    </style>

</head>
<body>
    <!-- Efectos de fondo únicos -->
    <div class="bg-aurora"></div>
    <div class="bg-sparkles" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>

    <div class="pricing-header">
        <h1 class="display-4">EcoVolt</h1>
        <p class="lead">Paga de forma segura con PayPal y recibe tu dispositivo en casa.</p>
    </div>

    <div class="container py-5">
        <!-- Banner de Marketing -->
        <div class="marketing-banner">
            <h4><i class="fas fa-gift me-2"></i>¡OFERTA ESPECIAL!</h4>
            <p class="mb-0">Primera compra: <strong>25% de descuento</strong> | Invita amigos y obtén <strong>10% adicional</strong></p>
        </div>

        <div class="row">
            <!-- Detalles del Dispositivo -->
            <div class="col-md-6">
                <h2 class="mb-4">Detalles del Producto</h2>
                <div class="device-card">
                    <div class="discount-badge mb-3">
                        <i class="fas fa-fire me-1"></i>25% OFF Primera Compra
                    </div>
                
                    <h3>Medidor de energia inteligente EcoVolt</h3>
                    <p class="text-muted">Dispositivo de monitoreo de energía inteligente para tu hogar.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Monitoreo en tiempo real</li>
                        <li><i class="fas fa-check-circle"></i> Análisis detallado de consumo</li>
                        <li><i class="fas fa-check-circle"></i> Alertas personalizadas</li>
                        <li><i class="fas fa-check-circle"></i> Compatible con todos los sistemas</li>
                    </ul>
                    
                    <!-- Sistema de Precios con Descuentos -->
                    <div class="price-section">
                        <div class="original-price">$150,000 ARS</div>
                        <div class="discounted-price">$112,500 ARS</div>
                        <div class="savings-amount">
                            <i class="fas fa-piggy-bank me-1"></i>Ahorras: $37,500 ARS
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de Compra -->
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="card-title mb-4">Resumen de Compra</h2>
                        
                        <!-- Código de Referido -->
                        <div class="referral-code mb-4">
                            <h6><i class="fas fa-users me-2"></i>Código de Referido (Opcional)</h6>
                            <input type="text" id="referralCode" placeholder="Ingresa código de amigo" maxlength="10">
                            <small class="text-muted d-block mt-2">Obtén 10% adicional si tienes un código de referido</small>
                        </div>
                        
                        <div class="mb-4">
                            <h4>Datos de Envío</h4>
                            <p class="mb-1"><strong>Nombre:</strong> <?= esc($datos_compra['nombre']) ?> <?= esc($datos_compra['apellido']) ?></p>
                            <p class="mb-1"><strong>Correo Electrónico:</strong> <?= esc($datos_compra['email']) ?></p>
                            <p class="mb-1"><strong>Dirección:</strong> <?= esc($datos_compra['direccion']) ?></p>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Precio Original:</span>
                            <span class="original-price">$150,000 ARS</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Descuento Primera Compra (25%):</span>
                            <span class="text-success">-$37,500 ARS</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3" id="referralDiscount" style="display: none;">
                            <span>Descuento Referido (10%):</span>
                            <span class="text-success">-$11,250 ARS</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal:</span>
                            <span id="subtotal">$112,500 ARS</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Envío:</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total:</strong>
                            <strong class="discounted-price" id="totalPrice">$112,500 ARS</strong>
                        </div>

                        <div id="paypal-button-container"></div>
                        <p id="status" class="mt-3"></p>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="fas fa-lock me-2"></i>Pago seguro con PayPal
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables de precios y descuentos
        const originalPrice = 150000;
        const firstTimeDiscount = 0.25; // 25%
        const referralDiscount = 0.10; // 10%
        let currentPrice = originalPrice * (1 - firstTimeDiscount); // Precio con descuento primera compra
        let hasReferralCode = false;

        // Función para calcular precios
        function calculatePrices() {
            const referralCode = document.getElementById('referralCode').value.trim().toUpperCase();
            const referralDiscountElement = document.getElementById('referralDiscount');
            const subtotalElement = document.getElementById('subtotal');
            const totalPriceElement = document.getElementById('totalPrice');
            
            // Verificar si hay código de referido válido
            if (referralCode && referralCode.length >= 3) {
                hasReferralCode = true;
                currentPrice = originalPrice * (1 - firstTimeDiscount) * (1 - referralDiscount);
                referralDiscountElement.style.display = 'flex';
                subtotalElement.textContent = `$${Math.round(currentPrice).toLocaleString()} ARS`;
                totalPriceElement.textContent = `$${Math.round(currentPrice).toLocaleString()} ARS`;
            } else {
                hasReferralCode = false;
                currentPrice = originalPrice * (1 - firstTimeDiscount);
                referralDiscountElement.style.display = 'none';
                subtotalElement.textContent = `$${Math.round(currentPrice).toLocaleString()} ARS`;
                totalPriceElement.textContent = `$${Math.round(currentPrice).toLocaleString()} ARS`;
            }
        }

        // Event listener para el código de referido
        document.getElementById('referralCode').addEventListener('input', calculatePrices);

        // Inicializar precios
        calculatePrices();

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
                        description: "EcoVolt Pro - Dispositivo de Monitoreo",
                        amount: {
                            currency_code: "ARS",
                            value: Math.round(currentPrice).toString(),
                            breakdown: {
                                item_total: {
                                    currency_code: "ARS",
                                    value: Math.round(currentPrice).toString()
                                }
                            }
                        },
                        items: [{
                            name: "EcoVolt Pro",
                            description: "Dispositivo de monitoreo de energía",
                            unit_amount: {
                                currency_code: "ARS",
                                value: Math.round(currentPrice).toString()
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
                        <h4 class="alert-heading"><?= lang('App.payment_cancelled') ?></h4>
                        <p><?= lang('App.payment_cancelled_msg') ?></p>
                    </div>`;
            }
        }).render('#paypal-button-container');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
