<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center text-success"><i class="fas fa-check-circle"></i> ¡Compra Exitosa!</h2>
            <p class="text-center">Gracias por tu compra, <strong><?= $usuario['nombre'] . ' ' . $usuario['apellido'] ?></strong>.</p>

            <h4 class="mt-4">Detalles de la Transacción</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>Email:</strong> <?= $usuario['email'] ?></li>
                <li class="list-group-item"><strong>Plan Adquirido:</strong> Plan Premium</li>
                <li class="list-group-item"><strong>Monto:</strong> $99.99 MXN</li>
                <li class="list-group-item"><strong>ID de Transacción:</strong> <?= $transaccion_id ?></li>
                <li class="list-group-item"><strong>Fecha de Compra:</strong> <?= date('d/m/Y H:i:s') ?></li>
            </ul>

            <div class="text-center mt-4">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">Ir al Panel</a>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5">
        <p>&copy; <?= date('Y') ?> EcoVolt. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
