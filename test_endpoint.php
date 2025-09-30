<?php
/**
 * Script de prueba para el endpoint getlimite
 * Ejecutar desde la línea de comandos: php test_endpoint.php
 */

// URL del endpoint
$url = 'http://192.168.0.138/Tesina/public/energia/getlimite';

echo "🧪 Probando endpoint: $url\n";
echo str_repeat("=", 50) . "\n";

// Probar sin parámetros
echo "1. Probando sin parámetros:\n";
$response = file_get_contents($url);
$data = json_decode($response, true);

if ($data) {
    echo "✅ Respuesta exitosa:\n";
    echo "   - Success: " . ($data['success'] ? 'true' : 'false') . "\n";
    echo "   - Límite: " . $data['limite_consumo'] . " kWh\n";
    echo "   - Timestamp: " . $data['timestamp'] . "\n";
    if (isset($data['ip_address'])) {
        echo "   - IP: " . $data['ip_address'] . "\n";
    }
} else {
    echo "❌ Error al decodificar JSON\n";
}

echo "\n" . str_repeat("-", 30) . "\n";

// Probar con parámetro MAC
echo "2. Probando con parámetro MAC:\n";
$url_con_mac = $url . '?mac=AA:BB:CC:DD:EE:FF';
echo "URL: $url_con_mac\n";

$response2 = file_get_contents($url_con_mac);
$data2 = json_decode($response2, true);

if ($data2) {
    echo "✅ Respuesta exitosa:\n";
    echo "   - Success: " . ($data2['success'] ? 'true' : 'false') . "\n";
    echo "   - Límite: " . $data2['limite_consumo'] . " kWh\n";
    echo "   - Timestamp: " . $data2['timestamp'] . "\n";
    if (isset($data2['mac_address'])) {
        echo "   - MAC: " . $data2['mac_address'] . "\n";
    }
    if (isset($data2['dispositivo_id'])) {
        echo "   - Dispositivo ID: " . $data2['dispositivo_id'] . "\n";
    }
} else {
    echo "❌ Error al decodificar JSON\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎯 Prueba completada. Verifica que las respuestas sean correctas.\n";
echo "💡 Si hay errores, revisa:\n";
echo "   - Que el servidor esté ejecutándose\n";
echo "   - Que la URL sea correcta\n";
echo "   - Que no haya errores en los logs de CodeIgniter\n";
?>
