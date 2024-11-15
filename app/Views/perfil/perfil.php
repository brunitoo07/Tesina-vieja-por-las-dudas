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
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Perfil del Usuario</h1>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre); ?></p>
        <p><strong>Apellido:</strong> <?= htmlspecialchars($apellido); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
        
        <a href="<?= base_url('home/bienvenida') ?>" class="btn">Volver</a>
    </div>
</body>
</html>
