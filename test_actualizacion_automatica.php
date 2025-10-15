<?php
/**
 * Script simple para probar la actualización automática
 * 
 * Este script envía datos cada 10 segundos para simular
 * un dispositivo ESP32 enviando lecturas continuas.
 */

// Configuración
$baseUrl = 'http://localhost/Tesina';
$macAddress = 'AA:BB:CC:DD:EE:FF'; // Cambia por la MAC de tu dispositivo

echo "🔄 SIMULADOR DE ACTUALIZACIÓN AUTOMÁTICA\n";
echo "=====================================\n\n";

echo "📡 Enviando datos cada 10 segundos...\n";
echo "🌐 URL: $baseUrl/nuevos_datos\n";
echo "📱 MAC: $macAddress\n\n";
echo "💡 Abre la vista de dispositivo en tu navegador para ver las actualizaciones en tiempo real.\n";
echo "⏹️  Presiona Ctrl+C para detener.\n\n";

$contador = 0;

while (true) {
    $contador++;
    
    // Generar datos simulados con variaciones
    $voltaje = 220 + (rand(-10, 10) / 10); // 219-221 V
    $corriente = 0.5 + (rand(0, 50) / 100); // 0.5-1.0 A
    $potencia = $voltaje * $corriente;
    $kwhAcumulado = 0.001 + ($contador * 0.0001); // Incremento gradual
    
    $datos = [
        'voltaje' => round($voltaje, 2),
        'corriente' => round($corriente, 2),
        'potencia' => round($potencia, 2),
        'kwh_acumulado' => round($kwhAcumulado, 4),
        'mac_address' => $macAddress
    ];
    
    echo "📊 Envío #$contador - " . date('H:i:s') . " - ";
    echo "V: {$datos['voltaje']}V, A: {$datos['corriente']}A, W: {$datos['potencia']}W, kWh: {$datos['kwh_acumulado']}kWh - ";
    
    // Enviar datos
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/nuevos_datos');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data && $data['success']) {
            echo "✅ Enviado\n";
        } else {
            echo "❌ Error: " . ($data['error'] ?? 'Desconocido') . "\n";
        }
    } else {
        echo "❌ HTTP $httpCode\n";
    }
    
    // Esperar 10 segundos
    sleep(10);
}
?>
