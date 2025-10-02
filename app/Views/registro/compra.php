<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Compra - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
           CONTENEDOR PRINCIPAL PREMIUM COMPACTO
           =============================== */
        .form-container {
            max-width: 750px;
            margin: 1.5rem auto;
            padding: 0;
            border-radius: 18px;
            background: rgba(26, 26, 26, 0.9);
            backdrop-filter: blur(25px);
            border: 2px solid rgba(212, 175, 55, 0.4);
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.7),
                0 0 0 1px rgba(212, 175, 55, 0.15) inset,
                0 0 120px rgba(212, 175, 55, 0.15),
                0 0 200px rgba(212, 175, 55, 0.05);
            overflow: hidden;
            position: relative;
            transition: var(--transition);
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: var(--gradient-rainbow);
            background-size: 200% 100%;
            animation: rainbowFlow 6s linear infinite;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
        }

        @keyframes rainbowFlow {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        .form-container:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 
                0 35px 100px rgba(212, 175, 55, 0.4),
                0 0 0 2px rgba(212, 175, 55, 0.3) inset,
                0 0 150px rgba(212, 175, 55, 0.2),
                0 0 300px rgba(212, 175, 55, 0.1);
        }

        /* ===============================
           HEADER ESPECTACULAR COMPACTO
           =============================== */
        .header {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
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

        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            background: linear-gradient(45deg, var(--black-primary), #333, var(--black-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: textGlow 3s ease-in-out infinite alternate;
        }

        @keyframes textGlow {
            0% { filter: drop-shadow(0 0 5px rgba(0, 0, 0, 0.3)); }
            100% { filter: drop-shadow(0 0 15px rgba(0, 0, 0, 0.6)); }
        }

        .header p {
            font-size: 1.1rem;
            color: var(--black-secondary);
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        /* ===============================
           CONTENIDO DEL FORMULARIO COMPACTO
           =============================== */
        .content {
            padding: 2.5rem 2rem;
            background: rgba(255, 255, 255, 0.02);
        }

        /* ===============================
           CAMPOS DE FORMULARIO PREMIUM
           =============================== */
        .form-control {
            background: rgba(255, 255, 255, 0.06);
            border: 2px solid rgba(212, 175, 55, 0.4);
            border-radius: 10px;
            padding: 0.9rem 1.1rem;
            color: var(--white-primary);
            font-size: 0.95rem;
            transition: var(--transition);
            backdrop-filter: blur(12px);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--gold-primary);
            box-shadow: 
                0 0 0 3px rgba(212, 175, 55, 0.25),
                0 0 25px rgba(212, 175, 55, 0.4),
                0 0 50px rgba(212, 175, 55, 0.2);
            color: var(--white-primary);
            transform: translateY(-2px) scale(1.02);
        }

        .form-label {
            color: var(--gold-light);
            font-weight: 600;
            margin-bottom: 0.6rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===============================
           BOTÓN PRINCIPAL ESPECTACULAR
           =============================== */
        .btn-primary {
            background: var(--gradient-gold);
            color: var(--black-primary);
            border: none;
            padding: 1.1rem 1.8rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 12px 35px rgba(212, 175, 55, 0.5),
                0 0 0 1px rgba(212, 175, 55, 0.3) inset,
                0 0 60px rgba(212, 175, 55, 0.2);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.4), 
                transparent);
            transition: left 0.6s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--gold-light), var(--gold-primary));
            transform: translateY(-4px) scale(1.03);
            box-shadow: 
                0 20px 50px rgba(212, 175, 55, 0.7),
                0 0 0 2px rgba(212, 175, 55, 0.4) inset,
                0 0 80px rgba(212, 175, 55, 0.5),
                0 0 120px rgba(212, 175, 55, 0.3);
        }

        .btn-primary:active {
            transform: translateY(-1px) scale(0.98);
        }

        /* ===============================
           TÍTULOS DE SECCIÓN
           =============================== */
        h4 {
            color: var(--gold-primary);
            font-weight: 700;
            font-size: 1.2rem;
            margin: 2rem 0 1.2rem 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding-left: 1rem;
        }

        h4::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 30px;
            background: var(--gradient-gold);
            border-radius: 2px;
        }

        /* ===============================
           EFECTOS ESPECIALES ÚNICOS MEJORADOS
           =============================== */

        /* ===============================
           EFECTOS ADICIONALES ÚNICOS
           =============================== */
        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: var(--gradient-rainbow);
            background-size: 200% 100%;
            animation: rainbowFlow 6s linear infinite;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
            z-index: 1;
        }

        /* Efecto de brillo lateral */
        .form-container {
            position: relative;
        }

        .form-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(212, 175, 55, 0.1), 
                transparent);
            animation: sideShine 8s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes sideShine {
            0%, 100% { left: -100%; }
            50% { left: 100%; }
        }

        /* Efecto de pulso central único */
        .form-container {
            position: relative;
        }

        .form-container::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, 
                rgba(212, 175, 55, 0.05) 0%, 
                transparent 70%);
            animation: pulseGlow 6s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes pulseGlow {
            0%, 100% { 
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.4;
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0.8;
            }
        }

        /* ===============================
           RESPONSIVE DESIGN
           =============================== */
        @media (max-width: 768px) {
            .form-container {
                margin: 1rem;
                border-radius: 15px;
            }
            
            .header {
                padding: 2rem 1.5rem;
            }
            
            .header h1 {
                font-size: 2.2rem;
            }
            
            .content {
                padding: 2rem 1.5rem;
            }
            
            .btn-primary {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.8rem;
            }
            
            .content {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-aurora"></div>
    <div class="bg-sparkles" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
    <div class="container">
        <div class="form-container">
            <div class="header">
                <h1>Completa tu Registro</h1>
                <p class="lead">Por favor, completa tus datos para finalizar la compra</p>
            </div>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger m-3">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>

            <div class="content">
                <form action="<?= base_url('registro-compra/procesar') ?>" method="POST">
                    <input type="hidden" name="id_dispositivo" value="1">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    </div>

                    <h4>Dirección de Envío</h4>

                    <div class="mb-3">
                        <label for="calle" class="form-label">Calle</label>
                        <input type="text" class="form-control" id="calle" name="calle" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                        </div>
                        <div class="col-md-6">
                            <label for="pais" class="form-label">País</label>
                            <input type="text" class="form-control" id="pais" name="pais" required>
                        </div>
                    </div>

                    <div class="d-grid gap-3 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Continuar con el Pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
