<?php
/**
 * Script simple para probar la URL del PDF
 * Ejecutar desde la raíz del proyecto: php test_pdf_url.php
 */

echo "🔧 PROBANDO URL DEL PDF\n";
echo "======================\n\n";

// ID de dispositivo de prueba
$dispositivo_id = 2;

// URL base (ajustar según tu configuración)
$base_url = 'http://192.168.0.138/Tesina/public/';
$pdf_url = $base_url . 'energia/generarPDF/' . $dispositivo_id;

echo "📊 Información del test:\n";
echo "   - Dispositivo ID: $dispositivo_id\n";
echo "   - URL base: $base_url\n";
echo "   - URL del PDF: $pdf_url\n\n";

echo "🧪 Probando conexión...\n";

// Probar la conexión
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'method' => 'GET',
        'header' => 'User-Agent: Test PDF Generator'
    ]
]);

$result = @file_get_contents($pdf_url, false, $context);

if ($result !== false) {
    echo "✅ Conexión exitosa\n";
    echo "   - Tamaño de respuesta: " . strlen($result) . " bytes\n";
    echo "   - Tipo de contenido: " . (isset($http_response_header) ? implode(', ', $http_response_header) : 'No disponible') . "\n";
    
    // Verificar si es un PDF válido
    if (strpos($result, '%PDF') === 0) {
        echo "✅ Archivo PDF válido detectado\n";
        
        // Guardar PDF de prueba
        $filename = 'test_pdf_download_' . date('Y-m-d_H-i-s') . '.pdf';
        file_put_contents($filename, $result);
        echo "   - Guardado como: $filename\n";
        echo "   - Puedes abrirlo para verificar el contenido\n";
    } else {
        echo "⚠️ La respuesta no parece ser un PDF válido\n";
        echo "   - Primeros 100 caracteres: " . substr($result, 0, 100) . "\n";
    }
} else {
    echo "❌ Error de conexión\n";
    echo "   - Verificar que el servidor esté funcionando\n";
    echo "   - Verificar que la URL sea correcta\n";
    echo "   - Verificar que no haya errores en el código\n";
}

echo "\n🎯 INSTRUCCIONES PARA PROBAR EN EL NAVEGADOR:\n";
echo "=============================================\n";
echo "1. Abrir el navegador\n";
echo "2. Ir a: $pdf_url\n";
echo "3. Debería descargarse automáticamente el PDF\n";
echo "4. Si hay error, revisar la consola del navegador (F12)\n";
echo "5. Revisar los logs del servidor en writable/logs/\n";

echo "\n🔧 SOLUCIÓN DE PROBLEMAS:\n";
echo "========================\n";
echo "❌ Si aparece 'Error al generar el PDF':\n";
echo "   - Verificar que DomPDF esté instalado: composer require dompdf/dompdf\n";
echo "   - Verificar que la vista PDF exista: app/Views/energia/pdf.php\n";
echo "   - Revisar los logs en writable/logs/\n";
echo "\n❌ Si aparece 'Dispositivo no encontrado':\n";
echo "   - Verificar que el dispositivo ID $dispositivo_id exista\n";
echo "   - Verificar que el usuario tenga permisos\n";
echo "\n❌ Si aparece 'No hay lecturas disponibles':\n";
echo "   - Verificar que haya datos de energía para el dispositivo\n";
echo "   - Enviar algunos datos desde el ESP32\n";
?>
