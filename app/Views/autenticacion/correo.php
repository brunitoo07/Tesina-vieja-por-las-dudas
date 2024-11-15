<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Enviar Correo</title>
    <style>
        /* Encabezado */
        header {
            background: linear-gradient(90deg, hsl(251, 100%, 32%) 0%, hsl(251, 80%, 40%) 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        body {
            background: url('<?= base_url('imagenes/bombilla.jpg'); ?>') no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        /* Contenedor principal */
        .container {
            background: linear-gradient(90deg, hsl(251, 100%, 32%) 0%, hsl(251, 80%, 40%) 100%);
            max-width: 980px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        /* Estilo del logo */
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo p {
            margin-left: 10px;
            font-size: 26px;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .logo img {
            height: 45px;
        }

        /* Estilos del menú */
        nav {
            position: relative;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: row;
        }

        nav li {
            margin-left: 10px;
        }

        nav a {
            text-decoration: none;
            color: #ffffff;
            padding: 0.85rem 1rem;
            border-radius: 4px;
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #08158d;
        }

        /* Botón menú para pantallas pequeñas */
        .menu-button {
            display: none; /* Oculto en pantallas grandes */
            font-size: 18px;
            color: #ffffff;
            background-color: transparent;
            border: 1px solid #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .menu-button:hover {
            background-color: #000286;
        }

        /* Animación para el menú desplegable */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilo para pantallas pequeñas */
        @media (max-width: 480px) {
            .container {
                flex-direction: column;
            }

            .logo p {
                font-size: 1.2rem;
            }

            .logo img {
                height: 2rem;
            }

            nav ul {
                display: none;
                flex-direction: column;
                background-color: hsl(236, 100%, 27%);
                border-radius: 5px;
                padding: 0.5rem;
                position: absolute;
                top: 4rem;
                right: 1rem;
                align-items: center;
                animation: slideDown 0.3s ease;
                z-index: 1;
            }

            nav ul.open {
                display: flex;
            }

            nav li {
                margin: 1rem;
            }

            .menu-button {
                display: block;
                font-size: 18px;
            }

            .menu-button::before {
                content: '\2630'; /* Símbolo del menú hamburguesa */
                font-size: 1.5rem;
            }
        }

        /* Estilos para el formulario */
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
    <?= $this->include('common/header') ?>
    <main>
        <div class="login-container">
            <form action="<?= base_url('correo'); ?>" method="post">
                <h1>Olvidé Mi Contraseña</h1>
                <p>Ingresa tu correo electrónico para recibir un código de verificación</p>
                <?php if (session()->get('exito')) : ?>
                    <div class="alert alert-exito">
                        <?= session()->get('exito'); ?>
                    </div>
                <?php endif ?>
                <?php if (session()->get('error')) : ?>
                    <div class="alert alert-danger"><?= session()->get('error'); ?></div>
                    <?php session()->remove('error'); ?>
                <?php endif ?>
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" required placeholder="Ingrese su correo">
                <button type="submit">Enviar Correo</button>
            </form>
        </div>
    </main>
</body>
</html>
