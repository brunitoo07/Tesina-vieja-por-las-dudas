<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('estilo/loggin.css'); ?>">
    <link rel="shortcut icon" href="<?= base_url('imagenes/rayito.png'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <title>Login</title>
<style>
body {
    background: url('<?= base_url('imagenes/bombilla.jpg'); ?>') no-repeat center center;
    background-size: cover;
    height: 100vh;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

main {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1.5%;
    min-height: 100vh;
    background: rgba(0, 0, 0, 0.4);
}

.container-login {
    padding: 2.6rem;
    border-radius: 1.2rem;
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    width: 22rem;
    text-align: center;
    backdrop-filter: blur(10px);
    transform: translateY(0);
    transition: transform 0.3s ease;
}

.container-login:hover {
    transform: translateY(-5px);
}

.container-login img {
    width: 5.5rem;
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.container-login img:hover {
    transform: scale(1.1);
}

.container-login h2 {
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    color: #2c3e50;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.2rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.form-group label {
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
    color: #34495e;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.8rem;
    border: 2px solid #e0e0e0;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-control:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.btn {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border: none;
    padding: 0.8rem;
    font-size: 1.1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    width: 100%;
    margin-top: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn:hover {
    background: linear-gradient(135deg, #2980b9, #3498db);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

.container-login a {
    font-size: 0.95rem;
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s ease;
    font-weight: 500;
}

.container-login a:hover {
    color: #2980b9;
    text-decoration: none;
}

.alert {
    padding: 1rem;
    margin-bottom: 1.2rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.95rem;
    text-align: center;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.alert-exito {
    color: #155724;
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
}

.input-group {
    display: flex;
    align-items: center;
    border: 2px solid #e0e0e0;
    border-radius: 0.5rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.input-group:focus-within {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.input-group .form-control {
    border: none;
    border-radius: 0;
}

.input-group .input-group-text {
    cursor: pointer;
    background-color: #f8f9fa;
    border: none;
    padding: 0.8rem;
    color: #7f8c8d;
    transition: color 0.3s ease;
}

.input-group .input-group-text:hover {
    color: #3498db;
}

p {
    margin: 1rem 0;
    color: #7f8c8d;
    font-size: 0.95rem;
}
</style>
</head>
<body>

<main>
    <div class="container-login">
        <img src="<?= base_url('imagenes/login.png'); ?>" alt="Imagen de Login">
        <h2>Login</h2>
        <form action="<?= base_url('iniciarSesion'); ?>" method="post">
            <?php if (session()->get('exito')) : ?>
                <div class="alert alert-success"><?= session()->get('exito'); ?></div>
                <?php session()->remove('exito'); ?>
            <?php endif ?>
            <?php if (session()->get('error')) : ?>
                <div class="alert alert-danger"><?= session()->get('error'); ?></div>
                <?php session()->remove('error'); ?>
            <?php endif ?>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" class="form-control" name="email" placeholder="Ingresa su correo" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Ingresa su contraseña" required>
                    <span class="input-group-text" onclick="togglePasswordVisibility()">
                        <i id="eyeIcon" class="fas fa-eye"></i> <!-- Icono del ojito -->
                    </span>
                </div>
            </div>

            <div class="form-group">
                <button class="btn" type="submit">Login</button>
            </div>
            <p>¿No tienes cuenta? <a href="<?= base_url('autenticacion/register'); ?>">Crea tu usuario.</a></p>            
            <p>¿Olvidaste tu contraseña? <a href="<?= base_url('autenticacion/correo'); ?>">Restablece tu contraseña.</a></p>
        </form>
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
