<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Vista principal (login)
$routes->get('/', 'CAutenticacion::login');

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

// Página de bienvenida
$routes->get('home/bienvenida', 'Home::index');
$routes->get('energia', 'Energia::index'); // Ruta para acceder a la vista de consumo
$routes->get('energia/verDatos', 'Energia::verDatos');  // Ruta para obtener los datos en formato JSON(esta es innecesaria,hacer prueba)


// Perfil del usuario
$routes->get('perfil/perfil', 'CUsuario::perfil');



$routes->get('/', 'Energia::index');  // Ruta para la vista principal
$routes->post('/energia/recibirDatos', 'Energia::recibirDatos');  // Ruta para recibir los datos del ESP32
$routes->get('energia/getLatestData', 'Energia::getLatestData');
$routes->post('energia/actualizarLimite', 'Energia::actualizarLimite');
