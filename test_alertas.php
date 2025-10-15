<?php
/**
 * Script de prueba para verificar el sistema de alertas
 * 
 * Este script simula el envío de datos de un dispositivo ESP32
 * para probar las alertas de límite de consumo.
 * 
 * USO:
 * 1. Configura un límite de consumo en el panel web
 * 2. Ejecuta este script con: php test_alertas.php
 * 3. Verifica que se envíen las alertas por email y Telegram
 */

// Configuración
$baseUrl = 'http://localhost/Tesina'; // Cambia por tu URL
$macAddress = 'AA:BB:CC:DD:EE:FF'; // Cambia por la MAC de tu dispositivo
$limiteConsumo = 0.005; // Límite bajo para probar fácilmente

echo "🧪 SCRIPT DE PRUEBA DE ALERTAS ECOVOLT\n";
echo "=====================================\n\n";

// Función para enviar datos al endpoint
function enviarDatos($url, $datos) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response
    ];
}

// Función para obtener el límite actual
function obtenerLimite($url, $mac) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/energia/getlimite?mac=' . urlencode($mac));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response
    ];
}

echo "1. 📡 Verificando conexión con el servidor...\n";
$limiteResponse = obtenerLimite($baseUrl, $macAddress);

if ($limiteResponse['code'] === 200) {
    $limiteData = json_decode($limiteResponse['response'], true);
    if ($limiteData && $limiteData['success']) {
        echo "   ✅ Conexión exitosa\n";
        echo "   📏 Límite actual: " . $limiteData['limite_consumo'] . " kWh\n\n";
    } else {
        echo "   ❌ Error en la respuesta del servidor\n";
        echo "   Respuesta: " . $limiteResponse['response'] . "\n\n";
        exit(1);
    }
} else {
    echo "   ❌ Error de conexión (HTTP " . $limiteResponse['code'] . ")\n";
    echo "   Respuesta: " . $limiteResponse['response'] . "\n\n";
    exit(1);
}

echo "2. 🔧 Configurando datos de prueba...\n";
echo "   📧 MAC Address: $macAddress\n";
echo "   ⚡ Límite de prueba: $limiteConsumo kWh\n\n";

echo "3. 📊 Enviando lecturas de prueba...\n";

// Simular lecturas incrementales
$lecturas = [
    ['voltaje' => 220.5, 'corriente' => 0.5, 'potencia' => 110.25, 'kwh_acumulado' => 0.001],
    ['voltaje' => 221.0, 'corriente' => 0.6, 'potencia' => 132.60, 'kwh_acumulado' => 0.002],
    ['voltaje' => 220.8, 'corriente' => 0.7, 'potencia' => 154.56, 'kwh_acumulado' => 0.003],
    ['voltaje' => 221.2, 'corriente' => 0.8, 'potencia' => 176.96, 'kwh_acumulado' => 0.004],
    ['voltaje' => 220.9, 'corriente' => 0.9, 'potencia' => 198.81, 'kwh_acumulado' => 0.005], // Límite alcanzado
    ['voltaje' => 221.1, 'corriente' => 1.0, 'potencia' => 221.10, 'kwh_acumulado' => 0.006], // Límite superado
];

foreach ($lecturas as $i => $lectura) {
    $lectura['mac_address'] = $macAddress;
    
    echo "   📈 Lectura " . ($i + 1) . ": " . $lectura['kwh_acumulado'] . " kWh";
    
    if ($lectura['kwh_acumulado'] >= $limiteConsumo) {
        echo " ⚠️  (LÍMITE ALCANZADO/SUPERADO)";
    }
    
    $response = enviarDatos($baseUrl . '/nuevos_datos', $lectura);
    
    if ($response['code'] === 200) {
        $data = json_decode($response['response'], true);
        if ($data && $data['success']) {
            echo " ✅";
        } else {
            echo " ❌";
        }
    } else {
        echo " ❌ (HTTP " . $response['code'] . ")";
    }
    
    echo "\n";
    
    // Pausa entre lecturas
    sleep(2);
}

echo "\n4. 📧 Verificando alertas...\n";
echo "   📬 Revisa tu email para la alerta de consumo\n";
echo "   📱 Revisa Telegram para la notificación\n";
echo "   🌐 Revisa el panel web para el modal de alerta\n\n";

echo "5. 🔄 Para probar nuevamente:\n";
echo "   - Ve al panel de administración\n";
echo "   - Usa la función 'Resetear Notificaciones'\n";
echo "   - O espera 1 hora para que se resetee automáticamente\n\n";

echo "✅ PRUEBA COMPLETADA\n";
echo "===================\n";
echo "Si no recibiste las alertas, verifica:\n";
echo "- Configuración de email en app/Config/Email.php\n";
echo "- Token de Telegram en el controlador\n";
echo "- Que el dispositivo esté registrado en la base de datos\n";
echo "- Logs del servidor para errores\n";
?>
