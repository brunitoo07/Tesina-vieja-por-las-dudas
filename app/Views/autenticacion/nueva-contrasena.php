<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .password-input {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">
                <i class="fas fa-bolt me-2"></i>EcoVolt
            </h2>

            <?php if (session()->get('exito')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-envelope me-2"></i><?= session()->get('exito') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->get('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->get('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('actualizar-contrasena') ?>" method="post" id="formCambioContrasena">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código de Verificación</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" required 
                           value="<?= old('codigo') ?>">
                    <small class="text-muted">Ingresa el código que recibiste por correo electrónico.</small>
                </div>

                <div class="mb-3 password-input">
                    <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('nueva_contrasena')"></i>
                    <small class="text-muted">La contraseña debe tener al menos 6 caracteres, una mayúscula y un símbolo (!@#$%).</small>
                </div>

                <div class="mb-3 password-input">
                    <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('confirmar_contrasena')"></i>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i>Cambiar Contraseña
                    </button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="<?= base_url('autenticacion/login') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Login
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar/ocultar contraseña
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
