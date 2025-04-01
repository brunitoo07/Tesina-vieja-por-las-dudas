<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthFilter;  // Agregar el filtro de autenticación

class Filters extends BaseFilters
{
    /**
     * Configura alias para las clases de filtros.
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => AuthFilter::class,
    ];

    /**
     * Filtros necesarios que se aplican antes y después de otras configuraciones.
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps', // Forzar solicitudes seguras globalmente
            'pagecache',  // Caché de páginas web
        ],
        'after' => [
            'pagecache',   // Caché de páginas web
            'performance', // Métricas de rendimiento
            'toolbar',     // Barra de herramientas de depuración
        ],
    ];

    /**
     * Filtros que se aplican de manera global antes y después de cada solicitud.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            'cors',  // Agrega aquí el filtro CORS para que se ejecute en todas las solicitudes
        ],
        'after'  => [
            'toolbar', // Barra de herramientas de depuración después de la solicitud
        ],
    ];

    /**
     * Filtros por método HTTP (GET, POST, etc.)
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * Filtros que deben ejecutarse antes o después de ciertos patrones de URI.
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [];
}
