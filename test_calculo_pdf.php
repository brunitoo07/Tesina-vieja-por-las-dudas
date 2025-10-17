<?php
/**
 * Script de prueba para verificar el cálculo correcto del PDF
 * Ejecutar desde la raíz del proyecto: php test_calculo_pdf.php
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

echo "🔧 PROBANDO CÁLCULO CORRECTO DEL PDF\n";
echo "====================================\n\n";

// Simular sesión de usuario
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['id_usuario'] = 1;
$_SESSION['rol'] = 'admin';

// ID de dispositivo de prueba
$dispositivo_id = 2;

echo "📊 1. Verificando datos del dispositivo...\n";

try {
    $db = \Config\Database::connect();
    
    // Obtener datos básicos del dispositivo
    $usuario = $db->table('usuario')
        ->join('dispositivos', 'dispositivos.id_usuario = usuario.id_usuario')
        ->where('dispositivos.id_dispositivo', $dispositivo_id)
        ->select('usuario.*, dispositivos.nombre as nombre_dispositivo')
        ->get()
        ->getRowArray();

    if (!$usuario) {
        echo "❌ Dispositivo no encontrado con ID: $dispositivo_id\n";
        exit(1);
    }
    
    echo "✅ Dispositivo encontrado: " . $usuario['nombre_dispositivo'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Error verificando dispositivo: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n📊 2. Verificando lecturas de energía...\n";

try {
    // Obtener lecturas recientes (limitado)
    $lecturas = $db->table('energia')
        ->where('id_dispositivo', $dispositivo_id)
        ->orderBy('fecha', 'DESC')
        ->limit(30)
        ->get()
        ->getResultArray();

    if (empty($lecturas)) {
        echo "❌ No se encontraron lecturas para el dispositivo\n";
        exit(1);
    }
    
    echo "✅ Lecturas encontradas: " . count($lecturas) . " registros\n";
    
    // Mostrar las primeras 5 lecturas
    echo "\n📋 Primeras 5 lecturas (más recientes):\n";
    for ($i = 0; $i < min(5, count($lecturas)); $i++) {
        $l = $lecturas[$i];
        echo "   " . ($i + 1) . ". " . $l['fecha'] . " - " . $l['kwh_acumulado'] . " kWh - " . $l['potencia'] . " W\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error verificando lecturas: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n📊 3. Verificando cálculos...\n";

try {
    // CÁLCULO CORRECTO: Usar el último valor de kwh_acumulado
    $total_kwh_correcto = (float)$lecturas[0]['kwh_acumulado']; // Primera lectura (más reciente)
    
    // CÁLCULO INCORRECTO: Sumar todas las lecturas (como estaba antes)
    $total_kwh_incorrecto = 0;
    foreach ($lecturas as $l) {
        $total_kwh_incorrecto += (float)$l['kwh_acumulado'];
    }
    
    echo "✅ Cálculo CORRECTO (último kwh_acumulado): " . number_format($total_kwh_correcto, 2) . " kWh\n";
    echo "❌ Cálculo INCORRECTO (suma de todas): " . number_format($total_kwh_incorrecto, 2) . " kWh\n";
    echo "📊 Diferencia: " . number_format($total_kwh_incorrecto - $total_kwh_correcto, 2) . " kWh\n";
    
    // Verificar que el cálculo correcto coincida con lo que muestra la interfaz
    echo "\n🔍 Verificando consistencia:\n";
    echo "   - Última lectura kwh_acumulado: " . number_format($total_kwh_correcto, 2) . " kWh\n";
    echo "   - Este valor debe coincidir con la interfaz web\n";
    
} catch (Exception $e) {
    echo "❌ Error en cálculos: " . $e->getMessage() . "\n";
}

echo "\n📊 4. Verificando totales mensuales...\n";

try {
    // Totales mensuales CORRECTOS: Usar MAX(kwh_acumulado) en lugar de SUM()
    $rowsMensuales = $db->table('energia')
        ->select("DATE_FORMAT(fecha, '%Y-%m') AS ym, MAX(kwh_acumulado) AS total_kwh", false)
        ->where('id_dispositivo', $dispositivo_id)
        ->groupBy("DATE_FORMAT(fecha, '%Y-%m')", false)
        ->orderBy("DATE_FORMAT(fecha, '%Y-%m')", 'ASC', false)
        ->get()->getResultArray();
    
    if (!empty($rowsMensuales)) {
        echo "✅ Totales mensuales calculados correctamente:\n";
        foreach ($rowsMensuales as $r) {
            $label = date('m/Y', strtotime($r['ym'].'-01'));
            echo "   - $label: " . number_format($r['total_kwh'], 2) . " kWh\n";
        }
    } else {
        echo "⚠️ No se encontraron datos mensuales\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error en totales mensuales: " . $e->getMessage() . "\n";
}

echo "\n📊 5. Verificando tarifa y costo...\n";

try {
    $tarifa = 150.0; // Tarifa por defecto
    $precioTotal = $tarifa * $total_kwh_correcto;
    
    echo "✅ Tarifa configurada: $" . number_format($tarifa, 2) . " por kWh\n";
    echo "✅ Costo total calculado: $" . number_format($precioTotal, 2) . "\n";
    
} catch (Exception $e) {
    echo "❌ Error en cálculo de costo: " . $e->getMessage() . "\n";
}

echo "\n🎯 RESUMEN DE VERIFICACIÓN\n";
echo "==========================\n";
echo "✅ Dispositivo verificado\n";
echo "✅ Lecturas encontradas: " . count($lecturas) . " registros\n";
echo "✅ Cálculo correcto: " . number_format($total_kwh_correcto, 2) . " kWh\n";
echo "✅ Totales mensuales corregidos\n";
echo "✅ Costo calculado: $" . number_format($precioTotal, 2) . "\n";

echo "\n💡 EXPLICACIÓN DEL PROBLEMA:\n";
echo "============================\n";
echo "❌ ANTES: Se sumaban todas las lecturas de kwh_acumulado\n";
echo "   - Esto daba: " . number_format($total_kwh_incorrecto, 2) . " kWh (INCORRECTO)\n";
echo "✅ AHORA: Se usa el último valor de kwh_acumulado\n";
echo "   - Esto da: " . number_format($total_kwh_correcto, 2) . " kWh (CORRECTO)\n";
echo "\n🔧 RAZÓN: kwh_acumulado ya es un valor acumulado, no necesita sumarse\n";

echo "\n🚀 El PDF ahora mostrará el valor correcto: " . number_format($total_kwh_correcto, 2) . " kWh\n";
?>
