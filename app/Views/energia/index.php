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
                    <th>ID</th>
                    <th>ID Dispositivo</th>
                    <th>ID Usuario</th>
                    <th>Voltaje (V)</th>
                    <th>Corriente (A)</th>
                    <th>Potencia (W)</th>
                    <th>Consumo (kWh)</th>
                    <th>Fecha</th>
                    <th>MAC Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lecturas as $lectura): ?>
                    <tr>
                        <td><?= $lectura['id'] ?></td>
                        <td><?= $lectura['id_dispositivo'] ?></td>
                        <td><?= $lectura['id_usuario'] ?></td>
                        <td><?= $lectura['voltaje'] ?></td>
                        <td><?= $lectura['corriente'] ?></td>
                        <td><?= $lectura['potencia'] ?></td>
                        <td><?= $lectura['kwh'] ?></td>
                        <td><?= $lectura['fecha'] ?></td>
                        <td><?= $lectura['mac_address'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>