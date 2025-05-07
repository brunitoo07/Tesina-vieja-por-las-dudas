<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumo Energético</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Consumo Energético</h1>

        <div class="alert alert-warning" role="alert" id="advertencia" style="display: none;">
            ¡Advertencia! Has superado el límite de consumo diario.
        </div>

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
            <tbody id="tabla-datos">
                <!-- Los datos se llenarán dinámicamente -->
            </tbody>
        </table>

        <form id="form-limite" class="mt-4">
            <div class="mb-3">
                <label for="limite" class="form-label">Límite de Consumo Diario (kWh):</label>
                <input type="number" class="form-control" id="limite" name="limite" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Límite</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/energia/getLatestData')
                .then(response => response.json())
                .then(data => {
                    const tabla = document.getElementById('tabla-datos');
                    const advertencia = document.getElementById('advertencia');

                    if (data.length > 0) {
                        data.forEach(dato => {
                            const fila = document.createElement('tr');
                            fila.innerHTML = `
                                <td>${dato.fecha}</td>
                                <td>${dato.voltaje}</td>
                                <td>${dato.corriente}</td>
                                <td>${dato.potencia}</td>
                                <td>${dato.kwh}</td>
                            `;
                            tabla.appendChild(fila);
                        });

                        if (data[0].advertencia) {
                            advertencia.style.display = 'block';
                        }
                    }
                });

            const form = document.getElementById('form-limite');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const limite = document.getElementById('limite').value;

                fetch('/energia/actualizarLimite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nuevo_limite: limite })
                })
                .then(response => {
                    if (response.ok) {
                        alert('Límite actualizado correctamente.');
                        location.reload();
                    } else {
                        alert('Error al actualizar el límite.');
                    }
                });
            });
        });
    </script>
</body>
</html>

