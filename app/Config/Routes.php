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
$routes->get('compra/error', 'Compra::error');

// Rutas de registro de compra
$routes->get('registro-compra', 'RegistroCompra::mostrarFormulario');
$routes->post('registro-compra/procesar', 'RegistroCompra::procesarFormulario');
$routes->get('registro-compra/pago-exitoso', 'RegistroCompra::pagoExitoso');
$routes->get('registro-compra/activar/(:segment)', 'RegistroCompra::activar/$1');
$routes->get('registro-compra/error', 'RegistroCompra::error');

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

    // Rutas para el medidor de energía
    $routes->get('energy', 'EnergyController::index');
    $routes->get('energy/setup', 'EnergyController::setup');
    $routes->post('energy/save-config', 'EnergyController::saveConfig');
    $routes->post('energy/nuevos_datos', 'EnergyController::nuevos_datos');
    $routes->get('energy/ultimos_datos', 'EnergyController::getLatestData');
    $routes->get('energy/datos_dispositivo/(:segment)', 'EnergyController::getDeviceData/$1');
    $routes->get('energy/dispositivo/ver/(:segment)', 'EnergyController::verDetalles/$1');
    $routes->get('energy/get-mac', 'EnergyController::getMacAddress');
    $routes->get('energy/scan-wifi', 'EnergyController::scanWifiNetworks');

    // Rutas para el perfil de usuario
    $routes->get('usuario/perfil', 'Usuario::perfil');
    $routes->post('usuario/actualizar-perfil', 'Usuario::actualizarPerfil');
    $routes->post('usuario/cambiar-contrasena', 'Usuario::cambiarContrasena');
});

// Rutas del panel de administración
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('gestionarUsuarios', 'Admin::gestionarUsuarios');
    $routes->get('invitar', 'Admin::invitar');
    $routes->post('enviarInvitacion', 'Admin::enviarInvitacion');
    $routes->post('eliminarUsuario', 'Admin::eliminarUsuario');
    $routes->post('cambiarRol', 'Admin::cambiarRol');
    $routes->get('gestionarUsuarios/admin', 'Admin::listarAdmins');
    $routes->get('usuario', 'Admin::gestionarUsuarios');
    $routes->post('aprobarDispositivo', 'Admin::aprobarDispositivo');
    $routes->get('dispositivos', 'Admin\Dispositivos::index');
    $routes->get('dispositivos/registrar', 'Admin\Dispositivos::registrar');
    $routes->get('dispositivos/buscar', 'Admin\Dispositivos::buscar');
    $routes->post('dispositivos/guardar', 'Admin\Dispositivos::guardar');
    $routes->get('dispositivos/activar/(:num)', 'Admin\Dispositivos::activar/$1');
    $routes->get('dispositivos/desactivar/(:num)', 'Admin\Dispositivos::desactivar/$1');
    $routes->post('dispositivos/eliminar/(:num)', 'Admin\Dispositivos::eliminar/$1');
    $routes->get('dispositivos/detalles/(:num)', 'Admin\Dispositivos::detalles/$1');
    $routes->get('dispositivos/desactivar/(:num)', 'Dispositivos::desactivar/$1');
});

// Rutas del supervisor
$routes->group('supervisor', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Supervisor::index');
    $routes->get('gestionarUsuarios', 'Supervisor::gestionarUsuarios');
    $routes->get('invitar', 'Supervisor::invitar');
    $routes->post('enviarInvitacion', 'Supervisor::enviarInvitacion');
    $routes->get('misUsuarios', 'Supervisor::misUsuarios');
    $routes->get('dispositivosUsuarios/(:num)', 'Supervisor::dispositivosUsuarios/$1');
    $routes->get('supervisor/verLecturasDispositivo/(:num)', 'Supervisor::verLecturasDispositivo/$1');
    $routes->get('supervisor/obtenerLecturasDispositivo/(:num)', 'Supervisor::obtenerLecturasDispositivo/$1');
    $routes->post('dispositivo/cambiarEstado', 'Supervisor::cambiarEstadoDispositivo');
    $routes->get('dispositivo/obtener/(:num)', 'Supervisor::obtenerDispositivo/$1');
    $routes->post('dispositivo/actualizar', 'Supervisor::actualizarDispositivo');
    $routes->post('cambiarRol', 'Supervisor::cambiarRol');
    $routes->post('eliminarUsuario', 'Supervisor::eliminarUsuario');
    $routes->get('usuario', 'Supervisor::gestionarUsuarios');
});

$routes->get('home/manual', 'Home::manual');

// Rutas para dispositivos
$routes->group('dispositivo', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dispositivo::index');
    $routes->get('buscar', 'Dispositivo::buscar');
    $routes->get('get-mac', 'Dispositivo::getMacAddress');
    $routes->get('scan-wifi', 'Dispositivo::scanWifiNetworks');
    $routes->post('save-config', 'Dispositivo::saveConfig');
    $routes->get('agregar', 'Dispositivo::agregar');
    $routes->post('guardar', 'Dispositivo::guardar');
    $routes->get('eliminar/(:num)', 'Dispositivo::eliminar/$1');
    $routes->get('dispositivo/get-mac', 'Dispositivo::getMac');
    $routes->post('dispositivo/update-wifi', 'Dispositivo::updateWifi');
});

$routes->get('consumo/ver/(:num)', 'Consumo::verDatos/$1');
$routes->get('consumo/grafico/(:num)', 'Consumo::grafico/$1');
$routes->get('mediciones/(:num)', 'Mediciones::index/$1');
$routes->get('energia', 'Energia::index');

// NUEVA RUTA DE PRUEBA
$routes->match(['get', 'post'], '/nuevos_datos', 'Energia::recibirNuevosDatos');

// Rutas de API para dispositivos
$routes->group('api/dispositivo', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->get('buscar', 'Dispositivo::buscar');
    $routes->get('redes', 'Dispositivo::redes');
    $routes->post('configurar', 'Dispositivo::configurar');
});

// Rutas para dispositivos
$routes->group('admin/dispositivos', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Admin\Dispositivos::index');
    $routes->get('buscar', 'Admin\Dispositivos::buscar');
    $routes->get('scan-wifi', 'Admin\Dispositivos::scanWifiNetworks');
    $routes->get('registrar', 'Admin\Dispositivos::registrar');
    $routes->post('guardar', 'Admin\Dispositivos::guardar');
    $routes->get('eliminar/(:num)', 'Admin\Dispositivos::eliminar/$1');
    $routes->get('detalles/(:num)', 'Admin\Dispositivos::detalles/$1');
    $routes->get('desactivar/(:num)', 'Admin\Dispositivos::desactivar/$1');
});

// Rutas para energía
$routes->get('energia', 'Energia::index');
$routes->get('energia/exportar', 'Energia::exportar');
$routes->post('energia/recibirNuevosDatos', 'Energia::recibirNuevosDatos');
$routes->get('energia/dispositivo/(:num)', 'Energia::dispositivo/$1');
$routes->get('energia/getLatestDataByDevice/(:num)', 'Energia::getLatestDataByDevice/$1');

$routes->get('cambiar-idioma/(:segment)', 'Home::cambiar_idioma/$1');