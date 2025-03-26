<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas públicas (sin autenticación)
$routes->get('/', 'Home::index');
$routes->get('/manual', 'Home::manual');

// Vistas de autenticación
$routes->get('autenticacion/login', 'CAutenticacion::login');
$routes->get('autenticacion/register', 'CAutenticacion::register');

// Restablecimiento de contraseña
$routes->get('autenticacion/correo', 'CCorreo::index');
$routes->post('correo', 'CCorreo::correo');
$routes->get('autenticacion/nueva_contrasena', 'CNuevacontrasena::index');
$routes->post('actualizar-contrasena', 'CNuevacontrasena::actualizar');

// Funcionalidad de autenticación
$routes->post('registrarse', 'CAutenticacion::registrarse');
$routes->post('iniciarSesion', 'CAutenticacion::iniciarSesion');
$routes->get('cerrarSesion', 'CAutenticacion::cerrarSesion');

// Rutas protegidas (requieren autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('home/bienvenida', 'Home::index');
    $routes->get('energia', 'Energia::index');
    $routes->get('energia/verDatos', 'Energia::verDatos');
    $routes->post('/energia/recibirDatos', 'Energia::recibirDatos');
    $routes->get('energia/getLatestData', 'Energia::getLatestData');
    $routes->post('energia/actualizarLimite', 'Energia::actualizarLimite');
    $routes->get('perfil/perfil', 'CUsuario::perfil');
    $routes->get('compra/completada', 'Compra::completada');

});

// Rutas de compra
$routes->get('compra', 'Compra::index');
$routes->get('compra/completada', 'Compra::completada');
