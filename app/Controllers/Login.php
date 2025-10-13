<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

/**
 * CONTROLADOR DE LOGIN - EcoVolt
 * 
 * Este controlador maneja la autenticación de usuarios en el sistema.
 * Procesa las credenciales, valida el estado del usuario y crea la sesión.
 * 
 * FUNCIONALIDADES:
 * - Autenticación de usuarios con email y contraseña
 * - Validación del estado de la cuenta (activa/inactiva)
 * - Creación de sesión de usuario
 * - Redirección según el rol del usuario
 * - Logging de intentos de login para auditoría
 * 
 * ROLES Y REDIRECCIONES:
 * - Rol 1 (Admin): Redirige a /admin/dashboard
 * - Rol 2 (Usuario normal): Redirige a /dashboard
 * - Rol 3 (Supervisor): Redirige a /dashboard
 */
class Login extends BaseController
{
    /** @var UsuarioModel Modelo para operaciones de usuario */
    protected $usuarioModel;

    /**
     * Constructor del controlador
     * Inicializa el modelo de usuario
     */
    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Autentica a un usuario en el sistema
     * 
     * PROCESO DE AUTENTICACIÓN:
     * 1. Obtiene email y contraseña del formulario
     * 2. Busca el usuario en la base de datos
     * 3. Verifica que la cuenta esté activa
     * 4. Valida la contraseña usando password_verify()
     * 5. Crea la sesión del usuario
     * 6. Redirige según el rol
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse Redirección según el resultado
     */
    public function autenticar()
    {
        // Obtener credenciales del formulario
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');

        log_message('debug', 'Intento de inicio de sesión para email: ' . $email);

        // Buscar el usuario por email
        $usuario = $this->usuarioModel->where('email', $email)->first();
        
        if (!$usuario) {
            log_message('debug', 'Usuario no encontrado para email: ' . $email);
            return redirect()->back()->with('error', 'Credenciales inválidas.');
        }

        // Log de información del usuario encontrado (sin datos sensibles)
        log_message('debug', 'Usuario encontrado: ' . json_encode([
            'id' => $usuario['id_usuario'],
            'email' => $usuario['email'],
            'estado' => $usuario['estado'],
            'rol' => $usuario['id_rol']
        ]));

        // Verificar que la cuenta esté activa
        if ($usuario['estado'] !== 'activo') {
            log_message('debug', 'Usuario no activo: ' . $usuario['estado']);
            return redirect()->back()->with('error', 'Tu cuenta no está activa. Por favor, verifica tu email.');
        }

        // Verificar la contraseña usando password_verify()
        if (!password_verify($contrasena, $usuario['contrasena'])) {
            log_message('debug', 'Contraseña incorrecta para usuario: ' . $email);
            return redirect()->back()->with('error', 'Credenciales inválidas.');
        }

        log_message('debug', 'Inicio de sesión exitoso para usuario: ' . $email);

        // Crear datos de sesión
        $sesionData = [
            'id_usuario' => $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'id_rol' => $usuario['id_rol'],
            'logged_in' => true
        ];

        // Establecer la sesión
        session()->set($sesionData);

        // Redirigir según el rol del usuario
        if ($usuario['id_rol'] == 1) { 
            // Administrador
            return redirect()->to('admin/dashboard');
        } else { 
            // Usuario normal o supervisor
            return redirect()->to('dashboard');
        }
    }
} 