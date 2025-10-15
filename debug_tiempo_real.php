<?php
/**
 * Script de debugging para el sistema de tiempo real
 * 
 * Este script ayuda a identificar problemas con las actualizaciones en tiempo real
 */

// Configuración
$baseUrl = 'http://localhost/Tesina'; // Cambia por tu URL
$idDispositivo = 1; // Cambia por el ID de tu dispositivo

echo "🔍 DEBUGGING DEL SISTEMA DE TIEMPO REAL\n";
echo "=====================================\n\n";

// Función para hacer peticiones HTTP
function hacerPeticion($url, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

echo "1. 🔗 Probando endpoint de datos en tiempo real...\n";
$url = $baseUrl . '/energia/getLatestDataByDevice/' . $idDispositivo;
echo "   URL: $url\n";

$response = hacerPeticion($url);

echo "   HTTP Code: " . $response['code'] . "\n";
if ($response['error']) {
    echo "   Error cURL: " . $response['error'] . "\n";
}

if ($response['code'] === 200) {
    $data = json_decode($response['response'], true);
    if ($data) {
        echo "   ✅ Respuesta JSON válida\n";
        echo "   Success: " . ($data['success'] ? 'true' : 'false') . "\n";
        
        if (isset($data['data'])) {
            $lectura = $data['data'];
            echo "   📊 Datos de la lectura:\n";
            echo "      - ID: " . ($lectura['id'] ?? 'N/A') . "\n";
            echo "      - Fecha: " . ($lectura['fecha'] ?? 'N/A') . "\n";
            echo "      - Voltaje: " . ($lectura['voltaje'] ?? 'N/A') . " V\n";
            echo "      - Corriente: " . ($lectura['corriente'] ?? 'N/A') . " A\n";
            echo "      - Potencia: " . ($lectura['potencia'] ?? 'N/A') . " W\n";
            echo "      - kWh: " . ($lectura['kwh'] ?? 'N/A') . " kWh\n";
            echo "      - kWh Acumulado: " . ($lectura['kwh_acumulado'] ?? 'N/A') . " kWh\n";
            echo "      - Límite Superado: " . ($lectura['limite_superado'] ?? 'N/A') . "\n";
        } else {
            echo "   ❌ No hay datos en la respuesta\n";
        }
        
        if (isset($data['limite_consumo'])) {
            echo "   📏 Límite de consumo: " . $data['limite_consumo'] . " kWh\n";
        }
    } else {
        echo "   ❌ Respuesta JSON inválida\n";
        echo "   Respuesta cruda: " . substr($response['response'], 0, 200) . "...\n";
    }
} else {
    echo "   ❌ Error HTTP: " . $response['code'] . "\n";
    echo "   Respuesta: " . substr($response['response'], 0, 200) . "...\n";
}

echo "\n2. 🔄 Probando múltiples peticiones (simulando actualizaciones)...\n";

for ($i = 1; $i <= 3; $i++) {
    echo "   Petición $i: ";
    $response = hacerPeticion($url);
    
    if ($response['code'] === 200) {
        $data = json_decode($response['response'], true);
        if ($data && isset($data['data'])) {
            $fecha = $data['data']['fecha'] ?? 'N/A';
            $potencia = $data['data']['potencia'] ?? 'N/A';
            echo "✅ Fecha: $fecha, Potencia: $potencia W\n";
        } else {
            echo "❌ Sin datos\n";
        }
    } else {
        echo "❌ Error HTTP: " . $response['code'] . "\n";
    }
    
    sleep(2);
}

echo "\n3. 📊 Verificando estructura de datos...\n";

$response = hacerPeticion($url);
if ($response['code'] === 200) {
    $data = json_decode($response['response'], true);
    if ($data && isset($data['data'])) {
        $lectura = $data['data'];
        
        echo "   Campos disponibles en la lectura:\n";
        foreach ($lectura as $campo => $valor) {
            echo "      - $campo: " . (is_numeric($valor) ? $valor : "'$valor'") . "\n";
        }
        
        // Verificar campos críticos
        $camposCriticos = ['fecha', 'voltaje', 'corriente', 'potencia', 'kwh_acumulado'];
        echo "\n   Verificación de campos críticos:\n";
        foreach ($camposCriticos as $campo) {
            $existe = isset($lectura[$campo]);
            $valor = $existe ? $lectura[$campo] : 'NO EXISTE';
            echo "      - $campo: " . ($existe ? "✅ $valor" : "❌ $valor") . "\n";
        }
    }
}

echo "\n4. 🧪 Simulando envío de nuevos datos...\n";

// Simular envío de datos del ESP32
$datosSimulados = [
    'voltaje' => 220.5,
    'corriente' => 0.8,
    'potencia' => 176.4,
    'kwh_acumulado' => 0.001,
    'mac_address' => 'AA:BB:CC:DD:EE:FF' // Cambia por la MAC de tu dispositivo
];

$urlEnvio = $baseUrl . '/nuevos_datos';
echo "   Enviando datos a: $urlEnvio\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlEnvio);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datosSimulados));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$responseEnvio = curl_exec($ch);
$httpCodeEnvio = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   HTTP Code: $httpCodeEnvio\n";
if ($httpCodeEnvio === 200) {
    $dataEnvio = json_decode($responseEnvio, true);
    if ($dataEnvio && $dataEnvio['success']) {
        echo "   ✅ Datos enviados correctamente\n";
        
        // Esperar un momento y verificar si aparecen en el endpoint
        echo "   ⏳ Esperando 3 segundos...\n";
        sleep(3);
        
        echo "   🔍 Verificando si los datos aparecen en tiempo real...\n";
        $responseVerificacion = hacerPeticion($url);
        if ($responseVerificacion['code'] === 200) {
            $dataVerificacion = json_decode($responseVerificacion['response'], true);
            if ($dataVerificacion && isset($dataVerificacion['data'])) {
                $nuevaLectura = $dataVerificacion['data'];
                echo "   📊 Nueva lectura encontrada:\n";
                echo "      - Fecha: " . $nuevaLectura['fecha'] . "\n";
                echo "      - Potencia: " . $nuevaLectura['potencia'] . " W\n";
                echo "      - kWh Acumulado: " . $nuevaLectura['kwh_acumulado'] . " kWh\n";
            }
        }
    } else {
        echo "   ❌ Error en el envío: " . ($dataEnvio['error'] ?? 'Desconocido') . "\n";
    }
} else {
    echo "   ❌ Error HTTP: $httpCodeEnvio\n";
    echo "   Respuesta: " . substr($responseEnvio, 0, 200) . "...\n";
}

echo "\n✅ DEBUGGING COMPLETADO\n";
echo "=====================\n";
echo "Revisa los resultados arriba para identificar problemas.\n";
echo "Si todo está bien, el problema puede estar en el JavaScript del frontend.\n";
echo "Abre la consola del navegador (F12) para ver los logs detallados.\n";
?>
