<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas públicas (sin autenticación)
$routes->get('/', 'Home::index');
$routes->get('manual', 'Home::manual');
$routes->get('home/index', 'Home::index');
$routes->get('autenticacion/login', 'CAutenticacion::login');
$routes->get('autenticacion/register', 'CAutenticacion::register');
$routes->get('autenticacion/correo', 'CCorreo::index');
$routes->post('correo', 'CCorreo::correo');
$routes->get('autenticacion/nueva_contrasena', 'CNuevacontrasena::index');
$routes->post('actualizar-contrasena', 'CNuevacontrasena::actualizar');
$routes->post('autenticacion/registrarse', 'CAutenticacion::registrarse');
$routes->post('iniciarSesion', 'CAutenticacion::iniciarSesion');
$routes->get('cerrarSesion', 'CAutenticacion::cerrarSesion');

// Rutas de compra (¡actualizadas!)
$routes->get('compra', 'Compra::index');
$routes->post('compra/simularCompra', 'Compra::simularCompra');
$routes->post('compra/procesarPago', 'Compra::procesarPago'); // ¡Nueva!
$routes->get('compra/completada', 'Compra::completada');

// Rutas protegidas (requieren autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('home', 'Home::index');
    $routes->get('home/bienvenida', 'Home::bienvenida');
    $routes->get('energia', 'Energia::index');
    $routes->get('energia/verDatos', 'Energia::verDatos');
    $routes->post('/energia/recibirDatos', 'Energia::recibirDatos');
    $routes->get('energia/getLatestData', 'Energia::getLatestData');
    $routes->post('energia/actualizarLimite', 'Energia::actualizarLimite');
    $routes->get('perfil/perfil', 'CUsuario::perfil');
});

$routes->get('home/manual', 'Home::manual');

// Rutas del panel de administración
// Fuera del grupo 'admin':
$routes->post('usuario/cambiarRol', 'Admin::cambiarRol', ['filter' => 'auth']); // Aplica el filtro 'auth' manualmente
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('gestionarUsuarios', 'Admin::gestionarUsuarios'); // 👈 así está bien
    $routes->get('invitar', 'Admin::invitarUsuario'); // Mostrar formulario
    $routes->post('invitar', 'Admin::invitarUsuario'); // Procesar envío

    $routes->post('eliminarUsuario', 'Admin::eliminarUsuario'); // ✅ correcto
    $routes->get('gestionarUsuarios/admin', 'Admin::listarAdmins');

});
