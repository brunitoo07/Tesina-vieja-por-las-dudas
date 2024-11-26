<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('estilo/nueva-contrasena.css'); ?>">
    <link rel="shortcut icon" href="<?= base_url('imagenes/rayito.png'); ?>">
    
    <title>Cambiar Contraseña</title>
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
            font-family: 'Poppins', sans-serif;
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
            color: #fff;
        }

        /* Botón menú  */
        .menu-button {
            display: none;
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
            color: #ffffff;
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

        /* Contenedor de cambiar contraseña */
        .container-cambiar {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            margin: 3rem auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: hsl(251, 80%, 40%);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid hsl(251, 80%, 40%);
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: hsl(251, 80%, 40%);
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: hsl(251, 100%, 32%);
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 auto;
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
                color: #ffffff;
            }

            .menu-button::before {
                content: '\2630';
                font-size: 1.5rem;
            }
            /* Estilo del ojito */
 .eye-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #aaa;
    font-size: 1.2rem;
}

/* Cuando el input tiene focus */
input[type="password"]:focus + .eye-icon,
input[type="text"]:focus + .eye-icon {
    color: #4a90e2; /* Cambiar color cuando el input está en foco */
}
        
          }
    </style>
</head>

<body>
    <?= $this->include('common/header') ?>
    <main>
        <div class="container-cambiar">
            <h2>Cambiar Contraseña</h2>
            <?php if (session()->get('error')) : ?>
                <div class="alert alert-danger"><?= session()->get('error'); ?></div>
                <?php session()->remove('error'); ?>
            <?php endif ?>

            <?php if (session()->get('exito')) : ?>
                <div class="alert alert-success"><?= session()->get('exito'); ?></div>
                <?php session()->remove('exito'); ?>
            <?php endif ?>

            <form action="<?= base_url('actualizar-contrasena'); ?>" method="post">
                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input type="text" name="codigo" required placeholder="Ingrese el código">
                </div>

                <div class="form-group">
    <label for="nueva_contrasena">Nueva Contraseña</label>
    <input type="password" id="nueva_contrasena" name="nueva_contrasena" required placeholder="Ingrese una nueva contraseña">
    <i class="fa fa-eye eye-icon" id="togglePassword"></i>
</div>

<div class="form-group">
    <label for="confirmar_contrasena">Confirmar Contraseña</label>
    <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required placeholder="Confirme su nueva contraseña">
    <i class="fa fa-eye eye-icon" id="toggleConfirmPassword"></i>
</div>

<div class="form-group">
    <button type="submit">Actualizar Contraseña</button>
</div>

            </form>
        </div>
    </main>
    <script>
    // Toggle para "Nueva Contraseña"
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("nueva_contrasena");

    togglePassword.addEventListener("click", function () {
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });

    // Toggle para "Confirmar Contraseña"
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    const confirmPasswordField = document.getElementById("confirmar_contrasena");

    toggleConfirmPassword.addEventListener("click", function () {
        const type = confirmPasswordField.type === "password" ? "text" : "password";
        confirmPasswordField.type = type;
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
</script>


</body>

</html>
