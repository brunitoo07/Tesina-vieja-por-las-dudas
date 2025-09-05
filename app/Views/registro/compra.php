<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Compra - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-color:#FFD700; --secondary-color:#6c757d; --accent-color:#FFD700; --dark-color:#000000; --light-color:rgba(245,196,0,0.48); --transition-speed:0.3s; }
        body { min-height:100vh; background:
            radial-gradient(800px 400px at -10% -10%, rgba(255,215,0,0.15) 0%, rgba(255,215,0,0) 60%),
            radial-gradient(700px 420px at 110% 110%, rgba(108,117,125,0.18) 0%, rgba(108,117,125,0) 60%),
            linear-gradient(180deg, #ffffff 0%, #f7f7f7 100%); position:relative; }
        body::after { content:""; position:fixed; inset:0; pointer-events:none; }

        /* Auroras y destellos */
        .bg-aurora { position:fixed; inset:0; z-index:-2; pointer-events:none; background:
            radial-gradient(60% 80% at 10% 10%, rgba(255,215,0,0.18), transparent 60%),
            radial-gradient(60% 80% at 90% 90%, rgba(108,117,125,0.18), transparent 60%),
            radial-gradient(50% 60% at 70% 20%, rgba(0,0,0,0.08), transparent 60%); filter:saturate(110%); animation:moveAurora 18s ease-in-out infinite alternate; }
        @keyframes moveAurora { 0%{ transform:translateY(0) scale(1);} 50%{ transform:translateY(-10px) scale(1.02);} 100%{ transform:translateY(0) scale(1);} }
        .bg-sparkles { position:fixed; inset:0; z-index:-1; pointer-events:none; overflow:hidden; }
        .bg-sparkles span { position:absolute; display:block; width:6px; height:6px; border-radius:50%; background:radial-gradient(circle at 30% 30%, #fff 0%, #ffe88a 35%, #ffd700 70%, rgba(255,215,0,0.0) 71%); box-shadow:0 0 12px rgba(255,215,0,0.6), 0 0 24px rgba(255,215,0,0.35); opacity:0.85; animation:floatSparkle calc(9s + var(--i) * 0.7s) linear infinite; }
        @keyframes floatSparkle { 0%{ transform:translateY(110vh) translateX(0) scale(0.9); opacity:0;} 10%{ opacity:0.9;} 50%{ transform:translateY(50vh) translateX(20px) scale(1);} 90%{ opacity:0.9;} 100%{ transform:translateY(-10vh) translateX(-20px) scale(0.9); opacity:0;} }
        .bg-sparkles span:nth-child(1){left:5%;--i:1;} .bg-sparkles span:nth-child(2){left:15%;--i:2;} .bg-sparkles span:nth-child(3){left:25%;--i:3;}
        .bg-sparkles span:nth-child(4){left:35%;--i:4;} .bg-sparkles span:nth-child(5){left:45%;--i:5;} .bg-sparkles span:nth-child(6){left:55%;--i:6;}
        .bg-sparkles span:nth-child(7){left:65%;--i:7;} .bg-sparkles span:nth-child(8){left:75%;--i:8;} .bg-sparkles span:nth-child(9){left:85%;--i:9;}
        .bg-sparkles span:nth-child(10){left:30%;--i:10;} .bg-sparkles span:nth-child(11){left:60%;--i:11;} .bg-sparkles span:nth-child(12){left:90%;--i:12;}

        .form-container { max-width: 900px; margin: 2rem auto; padding: 0; border-radius: 12px; box-shadow: 0 12px 40px rgba(0,0,0,0.10), 0 0 0 1px rgba(255,215,0,0.10) inset, 0 0 24px rgba(255,215,0,0.15); background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); overflow: hidden; }
        .header { background: linear-gradient(135deg, rgba(255,215,0,0.55) 0%, rgba(108,117,125,0.4) 100%); color:#000; padding: 2rem; text-align:center; border-radius: 12px 12px 0 0; margin-bottom: 0; position: relative; }
        .header::after { content:""; position:absolute; left:0; right:0; bottom:-1px; height:3px; background: linear-gradient(90deg, var(--primary-color), #bfa100, var(--secondary-color)); }
        .content { padding: 2rem; }

        .form-control { border: 2px solid #e0e0e0; border-radius: 0.5rem; transition: all 0.3s ease; }
        .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(255,215,0,0.15); }

        .btn-primary { background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); color:#000; border:none; padding: 0.9rem 1.2rem; border-radius: 0.5rem; transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease; position:relative; overflow:hidden; }
        .btn-primary:hover { background: var(--primary-color); color:#000; transform: translateY(-1px); box-shadow: 0 8px 22px rgba(255,215,0,0.35), 0 0 0 1px rgba(255,215,0,0.35) inset; }
        .btn-primary::after { content:""; position:absolute; top:-50%; left:-60%; width:40%; height:200%; background: linear-gradient(120deg, rgba(255,255,255,0), rgba(255,255,255,0.55), rgba(255,255,255,0)); transform: skewX(-20deg); animation: btnShine 3.2s ease-in-out infinite; }
        @keyframes btnShine { 0%{ left:-60%; } 60%{ left:120%; } 100%{ left:120%; } }

        /* Modo oscuro */
        [data-theme="dark"] body { background: linear-gradient(180deg, #0f0f0f 0%, #0b0b0b 100%) !important; color:#f1f1f1 !important; }
        [data-theme="dark"] body::after { background-image: linear-gradient(transparent calc(100% - 1px), rgba(255,215,0,0.05) 1px), linear-gradient(90deg, transparent calc(100% - 1px), rgba(255,215,0,0.05) 1px); mix-blend-mode: normal; }
        [data-theme="dark"] .form-container { background: rgba(15,15,15,0.65) !important; color:#f1f1f1 !important; border:1px solid rgba(255,215,0,0.25) !important; box-shadow: 0 12px 40px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,215,0,0.12) inset !important; }
        [data-theme="dark"] .header { background: linear-gradient(135deg, rgba(255,215,0,0.35) 0%, rgba(108,117,125,0.25) 100%) !important; color:#f1f1f1 !important; }
        [data-theme="dark"] .form-control { background:#121212 !important; color:#f1f1f1 !important; border-color: var(--primary-color) !important; }
        [data-theme="dark"] .form-control:focus { background:#121212 !important; color:#fff !important; border-color: var(--primary-color) !important; box-shadow: 0 0 0 2px var(--primary-color) !important; }
        [data-theme="dark"] .btn-primary { background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)) !important; color:#000 !important; border:1px solid var(--primary-color) !important; }
        [data-theme="dark"] .btn-primary:hover { background: var(--primary-color) !important; color:#000 !important; }
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
                <div class="alert alert-danger">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('registro-compra/procesar') ?>" method="POST">
                <input type="hidden" name="id_dispositivo" value="1">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>

                <h4 class="mt-4 mb-3">Dirección de Envío</h4>
                
                <div class="mb-3">
                    <label for="calle" class="form-label">Calle</label>
                    <input type="text" class="form-control" id="calle" name="calle" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="codigo_postal" class="form-label">Código Postal</label>
                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pais" class="form-label">País</label>
                        <input type="text" class="form-control" id="pais" name="pais" required>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Continuar con el Pago</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 