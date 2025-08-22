<?php
/**
 * Script de prueba para verificar el endpoint de recepción de datos del Arduino
 * Simula los datos que envía el ESP32
 */

// URL del endpoint
$url = 'http://localhost/Tesina/public/nuevos_datos';

// Datos de prueba (simulando los datos del Arduino)
$testData = [
    'mac_address' => 'AABBCCDDEEFF', // MAC sin formato como la envía el Arduino
    'voltaje' => 220.5,
    'corriente' => 0.85,
    'potencia' => 187.425,
    'kwh' => 0.000052 // Consumo acumulado
];

// Configurar la petición cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($testData))
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Ejecutar la petición
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

// Mostrar resultados
echo "=== PRUEBA DE ENDPOINT ARDUINO ===\n";
echo "URL: $url\n";
echo "Datos enviados: " . json_encode($testData, JSON_PRETTY_PRINT) . "\n";
echo "Código HTTP: $httpCode\n";

if ($error) {
    echo "Error cURL: $error\n";
} else {
    echo "Respuesta del servidor: $response\n";
}

// Decodificar respuesta JSON
$responseData = json_decode($response, true);
if ($responseData) {
    echo "Respuesta decodificada: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
}

echo "=== FIN DE PRUEBA ===\n";
?>
