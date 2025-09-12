<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Compra - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color:#FFD700;
            --secondary-color:#6c757d;
            --transition-speed:0.3s;
        }

        body {
            min-height:100vh;
            background:
                radial-gradient(800px 400px at -10% -10%, rgba(255,215,0,0.15) 0%, rgba(255,215,0,0) 60%),
                radial-gradient(700px 420px at 110% 110%, rgba(108,117,125,0.18) 0%, rgba(108,117,125,0) 60%),
                linear-gradient(180deg, #ffffff 0%, #f7f7f7 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }

        .bg-aurora {
            position:fixed;
            inset:0;
            z-index:-2;
            pointer-events:none;
            background:
                radial-gradient(60% 80% at 10% 10%, rgba(255,215,0,0.18), transparent 60%),
                radial-gradient(60% 80% at 90% 90%, rgba(108,117,125,0.18), transparent 60%),
                radial-gradient(50% 60% at 70% 20%, rgba(0,0,0,0.08), transparent 60%);
            filter:saturate(110%);
            animation:moveAurora 18s ease-in-out infinite alternate;
        }
        @keyframes moveAurora { 0%{ transform:translateY(0) scale(1);} 50%{ transform:translateY(-10px) scale(1.02);} 100%{ transform:translateY(0) scale(1);} }

        .bg-sparkles {
            position:fixed;
            inset:0;
            z-index:-1;
            pointer-events:none;
            overflow:hidden;
        }
        .bg-sparkles span {
            position:absolute;
            display:block;
            width:6px;
            height:6px;
            border-radius:50%;
            background:radial-gradient(circle at 30% 30%, #fff 0%, #ffe88a 35%, #ffd700 70%, rgba(255,215,0,0) 71%);
            box-shadow:0 0 12px rgba(255,215,0,0.6), 0 0 24px rgba(255,215,0,0.35);
            opacity:0.85;
            animation:floatSparkle calc(9s + var(--i) * 0.7s) linear infinite;
        }
        @keyframes floatSparkle { 0%{ transform:translateY(110vh) translateX(0) scale(0.9); opacity:0;} 10%{ opacity:0.9;} 50%{ transform:translateY(50vh) translateX(20px) scale(1);} 90%{ opacity:0.9;} 100%{ transform:translateY(-10vh) translateX(-20px) scale(0.9); opacity:0;} }

        .form-container {
            max-width: 850px;
            margin: 3rem auto;
            padding: 0;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.12), 0 0 0 1px rgba(255,215,0,0.08) inset;
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(12px);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .header {
            background: linear-gradient(135deg, rgba(255,215,0,0.55) 0%, rgba(108,117,125,0.4) 100%);
            color:#000;
            padding: 2.5rem 1.5rem;
            text-align:center;
            border-radius: 15px 15px 0 0;
            margin-bottom: 0;
            position: relative;
        }
        .header h1 { font-size:2.4rem; font-weight:700; margin-bottom:0.5rem; }
        .header p { font-size:1.1rem; color:#3d3d3d; }

        .header::after {
            content:"";
            position:absolute;
            left:0; right:0; bottom:-1px;
            height:4px;
            background: linear-gradient(90deg, var(--primary-color), #bfa100, var(--secondary-color));
        }

        .content { padding:2rem 2.5rem; }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 0.6rem;
            padding:0.75rem 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255,215,0,0.2);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color:#000;
            border:none;
            padding: 1rem 1.5rem;
            border-radius: 0.6rem;
            font-size:1.05rem;
            font-weight:600;
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
            position:relative;
            overflow:hidden;
        }
        .btn-primary:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255,215,0,0.35), 0 0 0 1px rgba(255,215,0,0.35) inset;
        }
        .btn-primary::after {
            content:"";
            position:absolute;
            top:-50%;
            left:-60%;
            width:40%;
            height:200%;
            background: linear-gradient(120deg, rgba(255,255,255,0), rgba(255,255,255,0.55), rgba(255,255,255,0));
            transform: skewX(-20deg);
            animation: btnShine 3.2s ease-in-out infinite;
        }
        @keyframes btnShine { 0%{ left:-60%; } 60%{ left:120%; } 100%{ left:120%; } }

        h4 { font-weight:600; margin-top:2rem; margin-bottom:1rem; }

        /* Responsive */
        @media(max-width:768px) {
            .form-container { padding:0; }
            .header h1 { font-size:2rem; }
            .content { padding:1.5rem 1.5rem; }
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
