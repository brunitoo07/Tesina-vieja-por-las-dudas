<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mediciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Mediciones del Dispositivo</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Valor</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mediciones as $medicion): ?>
                    <tr>
                        <td><?= $medicion['fecha_medicion'] ?></td>
                        <td><?= $medicion['valor'] ?></td>
                        <td><?= $medicion['unidad'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>