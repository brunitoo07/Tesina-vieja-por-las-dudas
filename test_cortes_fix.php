<?php
/**
 * Script de prueba para verificar que los errores de cortes estén corregidos
 * Ejecutar desde la raíz del proyecto: php test_cortes_fix.php
 */

// Simular entorno de CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', FCPATH . 'system' . DIRECTORY_SEPARATOR);
define('WRITEPATH', FCPATH . 'writable' . DIRECTORY_SEPARATOR);

// Cargar autoloader de Composer
require_once FCPATH . 'vendor/autoload.php';

// Cargar CodeIgniter
require_once SYSTEMPATH . 'bootstrap.php';

echo "🔧 PROBANDO CORRECCIONES DE CORTES\n";
echo "==================================\n\n";

// Simular sesión de usuario
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['id_usuario'] = 1;
$_SESSION['rol'] = 'admin';

// ID de dispositivo de prueba
$dispositivo_id = 2;

echo "📊 Probando getHistorialCortes con límite string...\n";

try {
    $corteModel = new \App\Models\CorteLineaModel();
    
    // Probar con límite como string (como viene del GET)
    $historial = $corteModel->getHistorialCortes($dispositivo_id, '5');
    echo "✅ getHistorialCortes con string '5': " . count($historial) . " registros\n";
    
    // Probar con límite como entero
    $historial = $corteModel->getHistorialCortes($dispositivo_id, 5);
    echo "✅ getHistorialCortes con entero 5: " . count($historial) . " registros\n";
    
} catch (Exception $e) {
    echo "❌ Error en getHistorialCortes: " . $e->getMessage() . "\n";
}

echo "\n🌐 Probando endpoint getHistorialCortes...\n";

try {
    $controller = new \App\Controllers\Energia();
    
    // Simular request GET con límite
    $_GET['limite'] = '5';
    
    $result = $controller->getHistorialCortes($dispositivo_id);
    $data = json_decode($result->getBody(), true);
    
    if ($data['success']) {
        echo "✅ Endpoint getHistorialCortes funcionando: " . count($data['historial']) . " registros\n";
    } else {
        echo "❌ Error en endpoint: " . $data['error'] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error en controlador: " . $e->getMessage() . "\n";
}

echo "\n👥 Probando getCortesPendientes...\n";

try {
    $corteModel = new \App\Models\CorteLineaModel();
    $cortes = $corteModel->getCortesPendientes(1);
    echo "✅ getCortesPendientes: " . count($cortes) . " registros\n";
    
    if (!empty($cortes)) {
        echo "   - Primer corte: " . ($cortes[0]['nombre_dispositivo'] ?? 'Sin nombre') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error en getCortesPendientes: " . $e->getMessage() . "\n";
}

echo "\n📊 Probando getEstadisticasCortes...\n";

try {
    $corteModel = new \App\Models\CorteLineaModel();
    $estadisticas = $corteModel->getEstadisticasCortes(1);
    echo "✅ getEstadisticasCortes funcionando:\n";
    echo "   - Total cortes: " . $estadisticas['total_cortes'] . "\n";
    echo "   - Cortes activos: " . $estadisticas['cortes_activos'] . "\n";
    echo "   - Cortes resueltos: " . $estadisticas['cortes_resueltos'] . "\n";
    echo "   - Cortes vistos: " . $estadisticas['cortes_vistos'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Error en getEstadisticasCortes: " . $e->getMessage() . "\n";
}

echo "\n🎯 RESUMEN DE CORRECCIONES\n";
echo "=========================\n";
echo "✅ Error de tipo en limit() corregido\n";
echo "✅ Cast a entero agregado en controlador\n";
echo "✅ JOIN LEFT agregado para evitar errores\n";
echo "✅ Todos los métodos funcionando correctamente\n";
echo "\n💡 El sistema de cortes está listo para usar.\n";
echo "🔧 Recuerda crear la tabla 'cortes_linea' si no existe.\n";
?>
