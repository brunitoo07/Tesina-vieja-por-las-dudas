<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Dispositivo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Configurar Dispositivo</h1>
        <form id="configurar-dispositivo" method="POST" action="/dispositivo/configurar">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Dispositivo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="ssid" class="form-label">SSID (Red WiFi)</label>
                <input type="text" class="form-control" id="ssid" name="ssid" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña WiFi</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Configuración</button>
        </form>
    </div>
</body>
</html>