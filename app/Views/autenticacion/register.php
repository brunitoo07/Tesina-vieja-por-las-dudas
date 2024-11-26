<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="shortcut icon" href="<?= base_url('imagenes/rayito.png'); ?>">
    <title>Registro</title>
    <style>
        /* Estilos generales */
        body {
            background: url('<?= base_url('imagenes/bombilla.jpg'); ?>') no-repeat center center;
            background-size: cover;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        /* Contenedor principal del formulario */
        .login-container {
            max-width: 400px;
            margin: 3rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: hsl(251, 80%, 40%);
        }

        p {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        .input-group {
            display: flex;
            align-items: center;
            position: relative;
        }

        .input-group input {
            flex: 1;
        }

        .input-group-text {
        
            background: none;
            border: none;
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 37%;
            transform: translateY(-50%);
            color: hsl(251, 80%, 40%);
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background: hsl(251, 80%, 40%);
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        button:hover {
            background: hsl(251, 100%, 32%);
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .alert-exito {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <main>
        <div class="login-container">
            <h1>Registro</h1>
            <form action="<?= base_url('registrarse'); ?>" method="post">
                <?php if (session()->get('error')) : ?>
                    <div class="alert alert-danger"><?= session()->get('error'); ?></div>
                    <?php session()->remove('error'); ?>
                <?php endif ?>
                <?php if (session()->get('password_error')) : ?>
                    <div class="alert alert-danger"><?= session()->get('password_error'); ?></div>
                    <?php session()->remove('password_error'); ?>
                <?php endif ?>

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" placeholder="Ingresa tu nombre" required>

                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" placeholder="Ingresa tu apellido" required>

                <label for="email">Correo electrónico</label>
                <input type="email" name="email" placeholder="Ingresa tu correo electrónico" required>

                <label for="contrasena">Contraseña</label>
                <div class="input-group">
                    <input type="password" name="contrasena" id="contrasena" placeholder="Ingresa tu contraseña" required>
                    <span class="input-group-text" onclick="togglePasswordVisibility()">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </span>
                </div>

                <button type="submit">Registrarse</button>
            </form>
            <p>¿Ya tienes cuenta? <a href="<?= base_url('autenticacion/login'); ?>">Inicia sesión.</a></p>
        </div>
    </main>

    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("contrasena");
            var eyeIcon = document.getElementById("eyeIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text"; // Mostrar la contraseña
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash"); // Cambiar el icono al de ojo tachado
            } else {
                passwordField.type = "password"; // Ocultar la contraseña
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye"); // Cambiar el icono al de ojo abierto
            }
        }
    </script>
</body>
</html>
