<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= base_url('imagenes/rayito.png'); ?>">
    <title>Perfil del Usuario</title>
    <style>
        /* Estilos personalizados */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('<?= base_url('imagenes/perfil-bg.jpg'); ?>') no-repeat center center;
            background-size: cover;
            color: #f8f9fa;
        }
        
        .profile-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        
        h1 {
            color: #007bff;
            font-weight: bold;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1rem;
            color: #343a40;
            margin: 0.5rem 0;
        }

        p strong {
            color: #007bff;
        }

        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            border: none;
            padding: 0.7rem 2rem;
            font-size: 1rem;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s ease;
            margin-top: 1rem;
        }

        .btn:hover {
            background: #0056b3;
        }

        [data-theme="dark"] body {
            background: #181a1b !important;
            color: #f1f1f1 !important;
        }
        [data-theme="dark"] .profile-container {
            background: #23272b !important;
            color: #f1f1f1 !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.5) !important;
        }
        [data-theme="dark"] .btn {
            background: #222e3c !important;
            color: #fff !important;
            border: 1px solid #4a90e2 !important;
        }
        [data-theme="dark"] .btn:hover {
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
    <div class="profile-container">
        <h1>Perfil del Usuario</h1>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre); ?></p>
        <p><strong>Apellido:</strong> <?= htmlspecialchars($apellido); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
        
        <a href="<?= base_url('home/bienvenida') ?>" class="btn">Volver</a>
    </div>
</body>
</html>
