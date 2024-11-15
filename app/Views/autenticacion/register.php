<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    background: rgba(255, 255, 255, 0.8); /* Fondo blanco semitransparente */
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
                <input type="password" name="contrasena" placeholder="Ingresa tu contraseña" required>

                <button type="submit">Registrarse</button>
            </form>
            <p>¿Ya tienes cuenta? <a href="<?= base_url('autenticacion/login'); ?>">Inicia sesión.</a></p>
        </div>
    </main>
</body>
</html>
