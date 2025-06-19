<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('estilo/header.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('estilo/fuente.css'); ?>">
    <title>Header</title>
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

<header>
    <div class="container">
        <a href="<?= base_url('/'); ?>" class="logo">
            <img src="<?= base_url('imagenes/rayito.png'); ?>"><p>Medidor</p></a>

        <nav class="nav" id="nav">
            <ul>    
                <li>
                    <a href="<?= site_url('autenticacion/login'); ?>">Login</a>
                    <a href="<?= site_url('autenticacion/register'); ?>">Register</a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!-- Theme Switch -->
<div class="theme-switch" id="themeSwitch" title="Modo claro/oscuro">
    <i class="fas fa-moon"></i>
</div>

<!-- Selector de idioma solo si no está autenticado -->
<?php if (!session()->get('logged_in')): ?>
<div style="position: absolute; top: 10px; right: 60px;">
    <?php $idioma = service('request')->getLocale(); ?>
    <a href="<?= base_url('cambiar-idioma/es') ?>" title="Español">
        <img src="<?= base_url('imagenes/' . ($idioma === 'es' ? 'es' : 'es') . '.png') ?>" alt="Español" style="width:24px;<?= $idioma === 'es' ? 'border:2px solid #333;border-radius:50%;' : '' ?>">
    </a>
    <a href="<?= base_url('cambiar-idioma/en') ?>" title="English">
        <img src="<?= base_url('imagenes/' . ($idioma === 'en' ? 'en' : 'en') . '.png') ?>" alt="English" style="width:24px;<?= $idioma === 'en' ? 'border:2px solid #333;border-radius:50%;' : '' ?>">
    </a>
</div>
<?php endif; ?>
</body>
</html>
