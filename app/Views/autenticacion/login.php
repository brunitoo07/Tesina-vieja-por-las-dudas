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
}

main {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1.5%;
}

.container-login {
    padding: 2.6rem;
    border-radius: 0.8rem;
    background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco semitransparente */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 20rem;
    text-align: center;
}

.container-login img {
    width: 5rem;
    margin-bottom: 0.5rem;
}

.container-login h2 {
    margin-bottom: 1rem;
    font-size: 1.5rem;
    color: #333;
}

.form-group {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.form-group label {
    margin-bottom: 0.5rem;
    font-size: 1rem;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
    font-size: 1rem;
}

.btn {
    background: hsl(234, 95%, 43%);
    color: white;
    border: none;
    padding: 0.6rem;
    font-size: 1.05rem;
    border-radius: 0.25rem;
    cursor: pointer;
    width: 106%;
    margin-top: 0.5rem;
}

.btn:hover {
    background: hsl(0, 0%, 0%);
}

.container-login a {
    font-size: 1rem;
    color: #007bff;
    text-decoration: none;
}

.container-login a:hover {
    text-decoration: underline;
}

.alert {
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    text-align: left;
}

.alert-exito {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
    width: 95%;
    text-align: center;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
    width: 95%;
    text-align: center;
}

.input-group {
    display: flex;
    align-items: center;
}

.input-group .form-control {
    flex: 1;
    margin-right: 0;
}

.input-group .input-group-text {
    cursor: pointer;
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    padding: 0.5rem;
}

.btn {
    background: hsl(234, 95%, 43%);
    color: white;
    border: none;
    padding: 0.6rem;
    font-size: 1.05rem;
    border-radius: 0.25rem;
    cursor: pointer;
    width: 100%;
    margin-top: 0.5rem;
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
