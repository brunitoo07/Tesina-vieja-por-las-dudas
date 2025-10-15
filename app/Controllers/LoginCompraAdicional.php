<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

/**
 * CONTROLADOR DE LOGIN PARA COMPRA ADICIONAL - EcoVolt
 * 
 * Este controlador maneja la autenticación específica para usuarios
 * que quieren comprar un dispositivo adicional a su cuenta existente.
 * 
 * FUNCIONALIDADES:
 * - Autenticación de usuarios existentes
 * - Validación del estado de la cuenta (activa)
 * - Redirección directa a la compra adicional
 * - Logging de intentos de login para auditoría
 */
class LoginCompraAdicional extends BaseController
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
     * Muestra el formulario de login para compra adicional
     * 
     * @return string Vista del formulario de login
     */
    public function index()
    {
        // Si ya está logueado, redirigir directamente a la compra
        if (session()->get('logged_in')) {
            return redirect()->to('compra-existente');
        }

        return view('autenticacion/login_compra_adicional');
    }

    /**
     * Autentica a un usuario para compra adicional
     * 
     * PROCESO DE AUTENTICACIÓN:
     * 1. Obtiene email y contraseña del formulario
     * 2. Busca el usuario en la base de datos
     * 3. Verifica que la cuenta esté activa
     * 4. Valida la contraseña usando password_verify()
     * 5. Crea la sesión del usuario
     * 6. Redirige a la compra adicional
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function autenticar()
    {
        // Obtener credenciales del formulario
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');

        log_message('debug', 'Intento de login para compra adicional - Email: ' . $email);

        // Buscar el usuario por email
        $usuario = $this->usuarioModel->where('email', $email)->first();
        
        if (!$usuario) {
            log_message('debug', 'Usuario no encontrado para compra adicional - Email: ' . $email);
            return redirect()->back()->with('error', 'Credenciales inválidas.');
        }

        // Log de información del usuario encontrado (sin datos sensibles)
        log_message('debug', 'Usuario encontrado para compra adicional: ' . json_encode([
            'id' => $usuario['id_usuario'],
            'email' => $usuario['email'],
            'estado' => $usuario['estado'],
            'rol' => $usuario['id_rol']
        ]));

        // Verificar que la cuenta esté activa
        if ($usuario['estado'] !== 'activo') {
            log_message('debug', 'Usuario no activo para compra adicional: ' . $usuario['estado']);
            return redirect()->back()->with('error', 'Tu cuenta no está activa. Por favor, verifica tu email.');
        }

        // Verificar la contraseña usando password_verify()
        if (!password_verify($contrasena, $usuario['contrasena'])) {
            log_message('debug', 'Contraseña incorrecta para compra adicional - Email: ' . $email);
            return redirect()->back()->with('error', 'Credenciales inválidas.');
        }

        log_message('debug', 'Login exitoso para compra adicional - Email: ' . $email);

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

        // Redirigir directamente a la compra adicional
        log_message('debug', 'Redirigiendo a compra adicional para usuario: ' . $email);
        return redirect()->to('compra-existente')->with('success', '¡Bienvenido! Ahora puedes comprar tu dispositivo adicional.');
    }
}
