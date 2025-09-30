<?php
/**
 * Script para verificar la configuración de PHP para la generación de PDFs
 * Ejecutar desde la línea de comandos: php check_php_config.php
 */

echo "🔍 Verificando configuración de PHP para generación de PDFs\n";
echo str_repeat("=", 60) . "\n";

// Verificar versión de PHP
echo "📋 Información básica:\n";
echo "   - Versión PHP: " . PHP_VERSION . "\n";
echo "   - Sistema operativo: " . PHP_OS . "\n";
echo "   - Arquitectura: " . php_uname('m') . "\n\n";

// Verificar límites de memoria y tiempo
echo "⚙️ Configuración de memoria y tiempo:\n";
echo "   - Límite de memoria: " . ini_get('memory_limit') . "\n";
echo "   - Tiempo máximo de ejecución: " . ini_get('max_execution_time') . " segundos\n";
echo "   - Tamaño máximo de archivo: " . ini_get('upload_max_filesize') . "\n";
echo "   - Tamaño máximo de POST: " . ini_get('post_max_size') . "\n\n";

// Verificar extensiones necesarias
echo "🔧 Extensiones PHP:\n";
$extensiones = [
    'gd' => 'Manejo de imágenes (requerida para logos)',
    'mbstring' => 'Manejo de cadenas multibyte',
    'openssl' => 'Conexiones seguras',
    'curl' => 'Cliente HTTP',
    'json' => 'Manejo de JSON',
    'zip' => 'Compresión de archivos',
    'xml' => 'Procesamiento XML',
    'dom' => 'DOM (requerida para DomPDF)',
    'libxml' => 'Librería XML',
    'fileinfo' => 'Información de archivos'
];

foreach ($extensiones as $ext => $descripcion) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "   $status $ext: $descripcion\n";
}

echo "\n";

// Verificar configuración específica de GD
if (extension_loaded('gd')) {
    echo "🖼️ Configuración de GD:\n";
    $gdInfo = gd_info();
    echo "   - Versión GD: " . $gdInfo['GD Version'] . "\n";
    echo "   - Soporte JPEG: " . ($gdInfo['JPEG Support'] ? 'Sí' : 'No') . "\n";
    echo "   - Soporte PNG: " . ($gdInfo['PNG Support'] ? 'Sí' : 'No') . "\n";
    echo "   - Soporte GIF: " . ($gdInfo['GIF Read Support'] ? 'Sí' : 'No') . "\n";
    echo "   - Soporte WebP: " . ($gdInfo['WebP Support'] ? 'Sí' : 'No') . "\n";
} else {
    echo "❌ GD no está instalada - Los logos no se mostrarán en el PDF\n";
}

echo "\n";

// Verificar permisos de directorios
echo "📁 Verificación de directorios:\n";
$directorios = [
    'writable' => 'Directorio de escritura',
    'writable/cache' => 'Cache',
    'writable/logs' => 'Logs',
    'public/imagenes' => 'Imágenes públicas'
];

foreach ($directorios as $dir => $descripcion) {
    if (is_dir($dir)) {
        $permisos = substr(sprintf('%o', fileperms($dir)), -4);
        $escribible = is_writable($dir) ? '✅' : '❌';
        echo "   $escribible $dir ($permisos): $descripcion\n";
    } else {
        echo "   ❌ $dir: No existe\n";
    }
}

echo "\n";

// Verificar archivos importantes
echo "📄 Archivos importantes:\n";
$archivos = [
    'public/imagenes/logo.png' => 'Logo principal',
    'vendor/autoload.php' => 'Autoloader de Composer',
    'vendor/dompdf/dompdf/src/Dompdf.php' => 'DomPDF'
];

foreach ($archivos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        $tamaño = filesize($archivo);
        $tamañoFormateado = $tamaño > 1024 ? round($tamaño / 1024, 2) . ' KB' : $tamaño . ' bytes';
        echo "   ✅ $archivo ($tamañoFormateado): $descripcion\n";
    } else {
        echo "   ❌ $archivo: No existe\n";
    }
}

echo "\n";

// Recomendaciones
echo "💡 Recomendaciones:\n";
if (!extension_loaded('gd')) {
    echo "   - Instalar extensión GD: sudo apt-get install php-gd (Ubuntu/Debian)\n";
    echo "   - O en XAMPP: Descomentar extension=gd en php.ini\n";
}

$memoryLimit = ini_get('memory_limit');
if (intval($memoryLimit) < 512) {
    echo "   - Aumentar memory_limit a al menos 512M en php.ini\n";
}

if (ini_get('max_execution_time') < 300) {
    echo "   - Aumentar max_execution_time a 300 segundos en php.ini\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 Verificación completada.\n";
echo "💡 Si hay problemas, revisa la configuración de PHP y las extensiones.\n";
?>
