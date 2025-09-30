<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class PDF extends BaseConfig
{
    /**
     * Configuración para la generación de PDFs
     */
    
    // Límites de memoria y procesamiento
    public $memoryLimit = '1024M';
    public $maxExecutionTime = 300; // 5 minutos
    
    // Límites de datos para evitar problemas de memoria
    public $maxLecturas = 1000;
    public $maxDiasResumen = 31;
    public $maxLecturasMostradas = 50;
    
    // Configuración de DomPDF
    public $dompdfOptions = [
        'defaultFont' => 'Arial',
        'isRemoteEnabled' => false,
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => false,
        'defaultMediaType' => 'print',
        'isFontSubsettingEnabled' => true,
        'debugPng' => false,
        'debugKeepTemp' => false,
        'debugCss' => false,
        'debugLayout' => false,
        'debugLayoutLines' => false,
        'debugLayoutBlocks' => false,
        'debugLayoutInline' => false,
        'debugLayoutPaddingBox' => false,
    ];
    
    // Configuración de imágenes
    public $imageConfig = [
        'maxWidth' => 200,
        'maxHeight' => 200,
        'compressionLevel' => 6,
        'fallbackToText' => true,
    ];
}
