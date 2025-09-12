<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Pago Exitoso! - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .success-header {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        .success-icon {
            font-size: 5rem;
            color: #4CAF50;
            margin-bottom: 1rem;
        }
        .order-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        [data-theme="dark"] body {
            background: #181a1b !important;
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] .success-header {
            background: linear-gradient(135deg, #23272b 0%, #181a1b 100%) !important;
            color: #ffd700 !important;
        }
        [data-theme="dark"] .order-details {
            background: #23272b !important;
            color: #f1f1f1 !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.5) !important;
        }
        [data-theme="dark"] .btn-primary {
            background: #222e3c !important;
            color: #fff !important;
            border: 1px solid #4a90e2 !important;
        }
        [data-theme="dark"] .btn-primary:hover {
            background: #4a90e2 !important;
            color: #fff !important;
        }
        [data-theme="dark"] .theme-switch {
            background: #23272b !important;
            color: #ffd700 !important;
        }
    </style>
    <script>
        // Script para modo claro/oscuro
        document.addEventListener('DOMContentLoaded', function() {
            const themeSwitch = document.getElementById('themeSwitch');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            let theme = localStorage.getItem('theme');
            if (!theme) {
                theme = prefersDark ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
            }
            document.documentElement.setAttribute('data-theme', theme);
            if(themeSwitch) themeSwitch.innerHTML = theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            if(themeSwitch) themeSwitch.onclick = function() {
                theme = (theme === 'dark') ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                themeSwitch.innerHTML = theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            };
        });
    </script>
</head>
<body>
    <!-- Theme Switch -->
    <div class="theme-switch" id="themeSwitch" title="Modo claro/oscuro" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <i class="fas fa-moon"></i>
    </div>

    <div class="success-header">
        <h1 class="display-4">¡Pago Exitoso!</h1>
        <p class="lead">Gracias por tu compra en EcoVolt</p>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-5">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h2>Gracias por tu compra, <?= esc($nombre) ?>!</h2>
                    <p class="lead">Tu pedido ha sido procesado correctamente.</p>
                </div>

                <div class="order-details">
                    <h3 class="mb-4">Detalles del pedido</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Producto:</strong> <?= esc($dispositivo['nombre']) ?></p>
                            <p><strong>Precio:</strong> $<?= number_format($dispositivo['precio'], 2) ?></p>
                            <p><strong>Fecha de compra:</strong> <?= $fecha ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Dirección de envío:</strong></p>
                            <p><?= esc($direccion) ?></p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p>Recibirás un correo electrónico con la confirmación y los detalles de tu compra.</p>
                    <p>Si tienes alguna pregunta, contáctanos a nuestro soporte.</p>
                    <a href="<?= base_url('autenticacion/login') ?>" class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
