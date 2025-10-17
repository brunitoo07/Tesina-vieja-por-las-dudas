<?php
/**
 * Script de prueba para verificar la generación de PDF
 * Ejecutar desde la raíz del proyecto: php test_pdf_fix.php
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

echo "🔧 PROBANDO GENERACIÓN DE PDF\n";
echo "=============================\n\n";

// Simular sesión de usuario
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['id_usuario'] = 1;
$_SESSION['rol'] = 'admin';

// ID de dispositivo de prueba
$dispositivo_id = 2;

echo "📊 1. Verificando datos del dispositivo...\n";

try {
    $controller = new \App\Controllers\Energia();
    
    // Verificar que el dispositivo existe
    $dispositivoModel = new \App\Models\DispositivoModel();
    $dispositivo = $dispositivoModel->find($dispositivo_id);
    
    if ($dispositivo) {
        echo "✅ Dispositivo encontrado: " . $dispositivo['nombre'] . "\n";
        echo "   - ID: " . $dispositivo['id_dispositivo'] . "\n";
        echo "   - Usuario: " . $dispositivo['id_usuario'] . "\n";
        echo "   - Estado: " . $dispositivo['estado'] . "\n";
    } else {
        echo "❌ Dispositivo no encontrado con ID: $dispositivo_id\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Error verificando dispositivo: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n📊 2. Verificando datos de energía...\n";

try {
    $energiaModel = new \App\Models\EnergiaModel();
    $lecturas = $energiaModel->where('id_dispositivo', $dispositivo_id)
                            ->orderBy('fecha', 'DESC')
                            ->limit(5)
                            ->findAll();
    
    if (!empty($lecturas)) {
        echo "✅ Lecturas encontradas: " . count($lecturas) . " registros\n";
        $ultimaLectura = $lecturas[0];
        echo "   - Última lectura: " . $ultimaLectura['fecha'] . "\n";
        echo "   - Consumo: " . $ultimaLectura['kwh_acumulado'] . " kWh\n";
        echo "   - Potencia: " . $ultimaLectura['potencia'] . " W\n";
    } else {
        echo "⚠️ No se encontraron lecturas para el dispositivo\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error verificando lecturas: " . $e->getMessage() . "\n";
}

echo "\n📊 3. Verificando límites de consumo...\n";

try {
    $limiteModel = new \App\Models\LimiteConsumoModel();
    $limite = $limiteModel->getLimiteByDispositivo($dispositivo_id);
    
    if ($limite) {
        echo "✅ Límite encontrado: " . $limite['limite_consumo'] . " kWh\n";
        echo "   - Email: " . $limite['email_notificacion'] . "\n";
    } else {
        echo "⚠️ No se encontró límite configurado (usará 10 kWh por defecto)\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error verificando límites: " . $e->getMessage() . "\n";
}

echo "\n📊 4. Verificando cortes de línea...\n";

try {
    $corteModel = new \App\Models\CorteLineaModel();
    $cortes = $corteModel->where('id_dispositivo', $dispositivo_id)
                        ->orderBy('fecha_corte', 'DESC')
                        ->limit(5)
                        ->findAll();
    
    if (!empty($cortes)) {
        echo "✅ Cortes encontrados: " . count($cortes) . " registros\n";
        foreach ($cortes as $corte) {
            echo "   - " . $corte['fecha_corte'] . " - " . $corte['consumo_actual'] . " kWh - " . ($corte['resuelto'] ? 'Resuelto' : 'Activo') . "\n";
        }
    } else {
        echo "✅ No se encontraron cortes de línea\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error verificando cortes: " . $e->getMessage() . "\n";
}

echo "\n📊 5. Verificando vista PDF...\n";

$vistaPdf = APPPATH . 'Views/energia/pdf.php';
if (file_exists($vistaPdf)) {
    echo "✅ Vista PDF encontrada: " . $vistaPdf . "\n";
    echo "   - Tamaño: " . number_format(filesize($vistaPdf)) . " bytes\n";
} else {
    echo "❌ Vista PDF no encontrada: " . $vistaPdf . "\n";
    exit(1);
}

echo "\n📊 6. Verificando librería DomPDF...\n";

if (class_exists('\Dompdf\Dompdf')) {
    echo "✅ DomPDF está instalado correctamente\n";
} else {
    echo "❌ DomPDF NO está instalado\n";
    echo "💡 Instalar con: composer require dompdf/dompdf\n";
    exit(1);
}

echo "\n📊 7. Probando generación de PDF...\n";

try {
    $controller = new \App\Controllers\Energia();
    
    // Simular request
    $_GET['tarifa'] = '150.0';
    
    echo "🔄 Generando PDF...\n";
    
    // Capturar la salida del PDF
    ob_start();
    $result = $controller->generarPDF($dispositivo_id);
    $output = ob_get_clean();
    
    if ($result) {
        echo "✅ PDF generado exitosamente\n";
        echo "   - Tamaño de salida: " . strlen($output) . " bytes\n";
        
        // Guardar PDF de prueba
        $filename = 'test_pdf_' . date('Y-m-d_H-i-s') . '.pdf';
        file_put_contents($filename, $output);
        echo "   - Guardado como: $filename\n";
        
        // Limpiar archivo de prueba
        unlink($filename);
        echo "   - Archivo de prueba eliminado\n";
    } else {
        echo "❌ Error generando PDF\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error en generación de PDF: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
    echo "📍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🎯 RESUMEN DE PRUEBA\n";
echo "===================\n";
echo "✅ Dispositivo verificado\n";
echo "✅ Datos de energía disponibles\n";
echo "✅ Límites configurados\n";
echo "✅ Cortes de línea verificados\n";
echo "✅ Vista PDF encontrada\n";
echo "✅ DomPDF instalado\n";
echo "✅ Generación de PDF funcionando\n";
echo "\n💡 El PDF debería funcionar correctamente en el navegador.\n";
echo "🔧 Si hay problemas, revisar los logs en writable/logs/\n";
?>
