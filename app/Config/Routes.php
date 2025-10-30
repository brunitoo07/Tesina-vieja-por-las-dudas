<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas públicas (sin autenticación)
$routes->get('/', 'Home::index');
$routes->get('manual', 'Home::manual');


// Rutas del chat asistente
$routes->get('chat', 'Chat::index');
$routes->post('chat/process', 'Chat::process');


$routes->get('autenticacion/login', 'CAutenticacion::login');
$routes->get('autenticacion/register', 'CAutenticacion::register');
$routes->get('seleccion', 'SeleccionPostLogin::index');
$routes->get('tipo-compra', 'TipoCompra::index');

// Rutas para login específico de compra adicional
$routes->get('login-compra-adicional', 'LoginCompraAdicional::index');
$routes->post('login-compra-adicional/autenticar', 'LoginCompraAdicional::autenticar');
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

//recordar que el formulario de compra esta en la carpeta registro/compra.php y el registro de la compra con mensjaes y eso es registro-compra...
$routes->get('registro-compra', 'RegistroCompra::mostrarFormulario');
$routes->post('registro-compra/procesar', 'RegistroCompra::procesarFormulario');
$routes->get('registro-compra/pago-exitoso', 'RegistroCompra::pagoExitoso');
$routes->get('registro-compra/error', 'RegistroCompra::error');

// Rutas para usuarios existentes que quieren comprar otro medidor (ELIMINADAS - usar compra-existente)

// Rutas para compra de dispositivos adicionales (usuarios existentes)
$routes->get('compra-existente', 'CompraExistente::index');
$routes->post('compra-existente/procesar', 'CompraExistente::procesarCompra');
$routes->post('compra-existente/guardar-direccion', 'CompraExistente::guardarDireccion');
$routes->get('compra-existente/pago', 'CompraExistente::pago');
$routes->post('compra-existente/procesarPago', 'CompraExistente::procesarPago');
$routes->get('compra-existente/pago-exitoso', 'CompraExistente::pagoExitoso');


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
    $routes->get('energia', 'Energia::index');
    $routes->post('/energia/recibirDatos', 'Energia::recibirDatos');
    $routes->get('energia/getLatestData', 'Energia::getLatestData');
    $routes->post('energia/actualizarLimite', 'Energia::actualizarLimite');
    $routes->get('usuario', 'CUsuario::index');
    $routes->get('energia/dispositivo/(:num)', 'Energia::dispositivo/$1');
    $routes->get('energia/generarPDF/(:num)', 'Energia::generarPDF/$1');
    $routes->post('energia/actualizarDispositivo', 'Energia::actualizarDispositivo');


    // Rutas para el perfil de usuario
    $routes->get('usuario/perfil', 'Usuario::perfil');
    $routes->post('usuario/actualizar-perfil', 'Usuario::actualizarPerfil');
    $routes->post('usuario/cambiar-contrasena', 'Usuario::cambiarContrasena');
    $routes->get('dispositivo/control/(:num)', 'Dispositivo::control/$1');
    $routes->post('register_ip', 'Dispositivo::registerIP');
    $routes->get('obtener_ip/(:segment)', 'Dispositivo::getIP/$1');
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
    $routes->get('dispositivos/registrar', 'Admin\Dispositivos::registrar');
    $routes->get('dispositivos/buscar', 'Admin\Dispositivos::buscar');
    $routes->post('dispositivos/guardar', 'Admin\Dispositivos::guardar');
    $routes->get('dispositivos/activar/(:num)', 'Admin\Dispositivos::activar/$1');
    $routes->get('dispositivos/desactivar/(:num)', 'Admin\Dispositivos::desactivar/$1');
    $routes->post('dispositivos/eliminar/(:num)', 'Admin\Dispositivos::eliminar/$1');
    $routes->get('dispositivos/detalles/(:num)', 'Admin\Dispositivos::detalles/$1');
    $routes->post('dispositivos/actualizar', 'Admin\Dispositivos::actualizar');
    $routes->get('dispositivos/desactivar/(:num)', 'Dispositivos::desactivar/$1');
    // Ruta para resetear notificaciones (solo desarrollo/admin)
    $routes->get('energia/resetNotificaciones/(:num)', 'Energia::resetNotificaciones/$1'); 
    $routes->get('energia/resetNotificaciones', 'Energia::resetNotificaciones');
    // Ruta para ver energía desde admin
    $routes->get('energia', 'Energia::index');

  
});

// Rutas del supervisor
$routes->group('supervisor', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Supervisor::index');
    $routes->get('gestionarUsuarios', 'Supervisor::gestionarUsuarios');
    $routes->get('invitar', 'Supervisor::invitar');
    $routes->post('enviarInvitacion', 'Supervisor::enviarInvitacion');
    $routes->get('misUsuarios', 'Supervisor::misUsuarios');
    $routes->get('dispositivosUsuarios/(:num)', 'Supervisor::dispositivosUsuarios/$1');
    $routes->get('dispositivosGlobal', 'Supervisor::dispositivosGlobal');
    $routes->get('verLecturasDispositivo/(:num)', 'Supervisor::verLecturasDispositivo/$1');
    $routes->get('obtenerLecturasDispositivo/(:num)', 'Supervisor::obtenerLecturasDispositivo/$1');
    $routes->post('dispositivo/cambiarEstado', 'Supervisor::cambiarEstadoDispositivo');
    $routes->get('dispositivo/obtener/(:num)', 'Supervisor::obtenerDispositivo/$1');
    $routes->post('dispositivo/actualizar', 'Supervisor::actualizarDispositivo');
    $routes->post('cambiarRol', 'Supervisor::cambiarRol');
    $routes->post('eliminarUsuario', 'Supervisor::eliminarUsuario');
    $routes->get('usuario', 'Supervisor::gestionarUsuarios');
    $routes->post('eliminarDispositivo/(:num)', 'Supervisor::eliminarDispositivo/$1');
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
$routes->match(['GET', 'POST'], '/nuevos_datos', 'Energia::recibirNuevosDatos');

// Endpoint público para que el ESP32 obtenga el límite de consumo
$routes->get('energia/getlimite', 'Energia::getlimite');

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
$routes->get('energia/cortes', 'Energia::cortes');
$routes->post('energia/recibirNuevosDatos', 'Energia::recibirNuevosDatos');
$routes->get('energia/dispositivo/(:num)', 'Energia::dispositivo/$1');
$routes->get('energia/getLatestDataByDevice/(:num)', 'Energia::getLatestDataByDevice/$1');
$routes->get('energia/filtrarLecturas/(:num)', 'Energia::filtrarLecturas/$1');
$routes->post('energia/setTarifa', 'Energia::setTarifa');
$routes->get('energia/getUltimoKwh', 'Energia::getUltimoKwh');

// Rutas para sistema de cortes de línea
$routes->get('energia/getCortesPendientes', 'Energia::getCortesPendientes');
$routes->post('energia/marcarCorteVisto/(:num)', 'Energia::marcarCorteVisto/$1');
$routes->get('energia/getEstadisticasCortes', 'Energia::getEstadisticasCortes');
$routes->get('energia/getHistorialCortes/(:num)', 'Energia::getHistorialCortes/$1');
$routes->get('energia/getDispositivosUsuario', 'Energia::getDispositivosUsuario');
$routes->get('energia/getCortesFiltrados', 'Energia::getCortesFiltrados');
$routes->get('energia/getDetalleCorte/(:num)', 'Energia::getDetalleCorte/$1');
$routes->get('energia/exportarCortesExcel', 'Energia::exportarCortesExcel');
$routes->get('energia/exportarLecturasExcel/(:num)', 'Energia::exportarLecturasExcel/$1');

$routes->get('cambiar-idioma/(:segment)', 'Home::cambiar_idioma/$1');
$routes->post('telegram/webhook', 'TelegramSimple::webhook');
// Rutas para generar factura
$routes->get('facturas/generarPDF/(:num)', 'Facturas::generarPDF/$1');




 