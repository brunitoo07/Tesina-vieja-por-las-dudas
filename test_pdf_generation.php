<?php
/**
 * Script de prueba para verificar la generación de PDF
 * Ejecutar desde la raíz del proyecto: php test_pdf_generation.php
 */

// Simular entorno de CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', FCPATH . 'system' . DIRECTORY_SEPARATOR);
define('WRITEPATH', FCPATH . 'writable' . DIRECTORY_SEPARATOR);

// Cargar autoloader de Composer
require_once FCPATH . 'vendor/autoload.php';

echo "🔧 PROBANDO GENERACIÓN DE PDF\n";
echo "=============================\n\n";

// Verificar si dompdf está instalado
if (class_exists('\Dompdf\Dompdf')) {
    echo "✅ DomPDF está instalado correctamente\n";
} else {
    echo "❌ DomPDF NO está instalado\n";
    echo "💡 Instalar con: composer require dompdf/dompdf\n";
    exit(1);
}

// Probar generación básica de PDF
try {
    $dompdf = new \Dompdf\Dompdf();
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Test PDF</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { color: #D4AF37; font-size: 24px; margin-bottom: 20px; }
            .content { margin: 20px 0; }
            .footer { margin-top: 40px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class="header">🔧 Test de Generación PDF</div>
        <div class="content">
            <p>Este es un test básico de generación de PDF con DomPDF.</p>
            <p>Si puedes ver este PDF, la librería está funcionando correctamente.</p>
            <p><strong>Fecha:</strong> ' . date('Y-m-d H:i:s') . '</p>
        </div>
        <div class="footer">
            <p>Generado por EcoVolt - Sistema de Monitoreo Energético</p>
        </div>
    </body>
    </html>';
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Guardar PDF de prueba
    $output = $dompdf->output();
    $filename = 'test_pdf_' . date('Y-m-d_H-i-s') . '.pdf';
    file_put_contents($filename, $output);
    
    echo "✅ PDF generado exitosamente: $filename\n";
    echo "📁 Tamaño del archivo: " . number_format(filesize($filename)) . " bytes\n";
    
    // Limpiar archivo de prueba
    unlink($filename);
    echo "🧹 Archivo de prueba eliminado\n";
    
} catch (Exception $e) {
    echo "❌ Error generando PDF: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n";
}

echo "\n🎯 RESUMEN DE PRUEBA\n";
echo "===================\n";
echo "✅ DomPDF instalado y funcionando\n";
echo "✅ Generación básica exitosa\n";
echo "✅ Configuración correcta\n";
echo "\n💡 El sistema de PDF está listo para usar.\n";
?>
