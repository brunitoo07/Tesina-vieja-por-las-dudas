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
$routes->get('autenticacion/nueva-contrasena', 'CNuevacontrasena::index');
$routes->post('actualizar-contrasena', 'CNuevacontrasena::actualizar');
$routes->post('autenticacion/registrarse', 'CAutenticacion::registrarse');
$routes->post('iniciarSesion', 'CAutenticacion::iniciarSesion');
$routes->post('autenticacion/iniciarSesion', 'CAutenticacion::iniciarSesion');
$routes->get('cerrarSesion', 'CAutenticacion::cerrarSesion');
$routes->get('autenticacion/cerrarSesion', 'CAutenticacion::cerrarSesion');

// *** RUTAS PARA REGISTRO DE INVITADOS (¡CORREGIDAS Y AÑADIDAS AQUÍ!) ***
$routes->get('registro/invitado/(:segment)', 'CAutenticacion::registroInvitado/$1'); // Para el enlace del email (GET)
$routes->post('registro/procesarInvitado', 'CAutenticacion::procesarRegistroInvitado'); // Para el envío del formulario (POST)
// ********************************************************************

$routes->post('admin/guardarUsuario', 'Admin::guardarUsuario'); // Esta ruta es de administrador, pero no depende de la anterior.

// Rutas de compra
$routes->get('compra', 'Compra::index');
$routes->post('compra/procesarPago', 'Compra::procesarPago');
$routes->get('compra/completada', 'Compra::completada');

// Rutas de registro de compra
$routes->get('registro-compra', 'RegistroCompra::mostrarFormulario');
$routes->post('registro-compra/procesar', 'RegistroCompra::procesarFormulario');
$routes->get('registro-compra/pago-exitoso', 'RegistroCompra::pagoExitoso');
$routes->get('registro-compra/activar/(:segment)', 'RegistroCompra::activar/$1');

// Rutas protegidas (requieren autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Home::index');
    $routes->get('perfil/perfil', 'CUsuario::perfil');
    $routes->post('perfil/perfil', 'CUsuario::perfil');
    $routes->get('usuario/cambiarContrasena', 'CUsuario::cambiarContrasena');
    $routes->post('usuario/cambiarContrasena', 'CUsuario::cambiarContrasena');
    $routes->get('dispositivo/agregar', 'Dispositivo::agregar');
    $routes->post('dispositivo/agregar', 'Dispositivo::agregar');
    $routes->get('dispositivo/eliminar/(:num)', 'Dispositivo::eliminar/$1');
    $routes->get('energia/verDatos/(:num)', 'Consumo::verDatos/$1');
    $routes->get('home', 'Home::index');
    $routes->get('home/bienvenida', 'Home::bienvenida');
    $routes->get('energia', 'Energia::index');
    $routes->get('energia/verDatos/(:num)', 'Energia::verDatos/$1');
    $routes->post('/energia/recibirDatos', 'Energia::recibirDatos');

    $routes->get('energia/getLatestData', 'Energia::getLatestData');
    $routes->post('energia/actualizarLimite', 'Energia::actualizarLimite');
    $routes->get('usuario', 'CUsuario::index');
});

// Rutas del panel de administración
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('gestionarUsuarios', 'Admin::gestionarUsuarios');
    $routes->get('invitar', 'Admin::invitar');
    $routes->post('enviarInvitacion', 'Admin::enviarInvitacion');
    $routes->post('eliminarUsuario', 'Admin::eliminarUsuario');
    $routes->post('cambiarRol', 'Admin::cambiarRol');
    $routes->get('gestionarUsuarios/admin', 'Admin::listarAdmins');
    $routes->get('usuario', 'Admin::gestionarUsuarios');
    $routes->post('aprobarDispositivo', 'Admin::aprobarDispositivo');
});

// Rutas del supervisor
$routes->group('supervisor', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Supervisor::index');
    $routes->get('gestionarUsuarios', 'Supervisor::gestionarUsuarios');
    $routes->get('invitar', 'Supervisor::invitar');
    $routes->post('enviarInvitacion', 'Supervisor::enviarInvitacion');
    $routes->get('misUsuarios', 'Supervisor::misUsuarios');
    $routes->get('dispositivosUsuarios/(:num)', 'Supervisor::dispositivosUsuarios/$1');
    $routes->post('dispositivo/cambiarEstado', 'Supervisor::cambiarEstadoDispositivo');
    $routes->get('dispositivo/obtener/(:num)', 'Supervisor::obtenerDispositivo/$1');
    $routes->post('dispositivo/actualizar', 'Supervisor::actualizarDispositivo');
    $routes->post('cambiarRol', 'Supervisor::cambiarRol');
    $routes->post('eliminarUsuario', 'Supervisor::eliminarUsuario');
    $routes->get('usuario', 'Supervisor::gestionarUsuarios');
});

$routes->get('home/manual', 'Home::manual');

// Rutas de dispositivos
$routes->group('dispositivo', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dispositivo::index');
    $routes->get('agregar', 'Dispositivo::agregar');
    $routes->post('guardar', 'Dispositivo::guardar');
    $routes->post('eliminar/(:num)', 'Dispositivo::eliminar/$1');
    $routes->get('configurar', 'Dispositivo::configurar');
    $routes->post('configurar', 'Dispositivo::configurar');
});

$routes->get('consumo/ver/(:num)', 'Consumo::verDatos/$1');
$routes->get('consumo/grafico/(:num)', 'Consumo::grafico/$1');
$routes->get('mediciones/(:num)', 'Mediciones::index/$1');
$routes->get('energia', 'Energia::index');

// NUEVA RUTA DE PRUEBA
$routes->post('/nuevos_datos', 'Energia::recibirNuevosDatos');