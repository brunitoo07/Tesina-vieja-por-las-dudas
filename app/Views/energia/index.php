<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturas de Energía</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Lecturas de Energía</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Voltaje (V)</th>
                    <th>Corriente (A)</th>
                    <th>Potencia (W)</th>
                    <th>Consumo (kWh)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lecturas as $lectura): ?>
                    <tr>
                        <td><?= $lectura['fecha'] ?></td>
                        <td><?= $lectura['voltaje'] ?></td>
                        <td><?= $lectura['corriente'] ?></td>
                        <td><?= $lectura['potencia'] ?></td>
                        <td><?= $lectura['kwh'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>