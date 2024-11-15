<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('estilo/header.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('estilo/fuente.css'); ?>">
    <title>Header</title>

    
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
</body>
</html>
