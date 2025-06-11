<?php

namespace App\Controllers;

use App\Models\InvitacionModel;
use App\Models\UsuarioModel;
use App\Models\DispositivoModel;

class CAutenticacion extends BaseController
{
    protected $usuarioModel;
    protected $dispositivoModel;
    protected $invitacionModel;

    public function __construct()
    {
        // NO LLAMAMOS a parent::__construct() aquí, ya que causó el error "Cannot call constructor".
        // Esto indica que tu BaseController no tiene un constructor propio que necesite ser llamado,
        // o que tu configuración de CI es tal que no lo requiere/permite en esta extensión.

        $this->usuarioModel = new UsuarioModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->invitacionModel = new InvitacionModel();
    }

    public function login()
    {
        if (session()->get('userData')) {
            return redirect()->to('home/bienvenida');
        }

        return view('autenticacion/login');
    }

    public function register()
    {
        if (session()->get('userData')) {
            return redirect()->to('autenticacion/login');
        }

        $purchase = $this->request->getGet('purchase') === 'true';
        // Pasar el valor a la vista y usarlo en un campo oculto
        return view('autenticacion/register', [
            'purchase' => $purchase,
            'message' => $purchase ? '¡Complete su registro como ADMINISTRADOR!' : null
        ]);
    }

    public function registrarse()
    {
        // Usar $this->usuarioModel en lugar de crear una nueva instancia local
        // $usuarioModel = new UsuarioModel();

        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');
        $confirmar_contrasena = $this->request->getPost('confirmar_contrasena'); // Asegúrate de tener esto en tu formulario
        $purchase = $this->request->getPost('purchase') === 'true' ||
                    $this->request->getGet('purchase') === 'true'; // Captura GET y POST

        // Validación de nombre (solo letras y espacios)
        if (!preg_match('/^[a-zA-Z\s]+$/', $nombre)) {
            return redirect()->back()
                                ->withInput()
                                ->with('error', 'El nombre solo puede contener letras y espacios.');
        }

        // Validación de apellido (solo letras y espacios)
        if (!preg_match('/^[a-zA-Z\s]+$/', $apellido)) {
            return redirect()->back()
                                ->withInput()
                                ->with('error', 'El apellido solo puede contener letras y espacios.');
        }

        // Verificar si el email ya existe
        if ($this->usuarioModel->existenteEmail($email)) { // Usar $this->usuarioModel
            return redirect()->back()
                                ->withInput()
                                ->with('error', 'El correo electrónico ya está registrado.');
        }

        // Validación de contraseña
        if (strlen($contrasena) < 6 || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[!@#$%]/', $contrasena)) {
            return redirect()->back()
                                ->withInput()
                                ->with('password_error', 'La contraseña debe tener: 6+ caracteres, 1 mayúscula y 1 símbolo (!@#$%).');
        }

        // Validación de confirmación de contraseña
        if ($contrasena !== $confirmar_contrasena) {
            return redirect()->back()
                                ->withInput()
                                ->with('error', 'Las contraseñas no coinciden.');
        }


        $dataUsuario = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contrasena' => $contrasena,
            'id_rol' => $purchase ? 1 : 2, // 1=admin, 2=usuario
            'estado' => 'activo'
        ];

        try {
            if (!$this->usuarioModel->insertarUsuario($dataUsuario)) { // Usar $this->usuarioModel
                throw new \RuntimeException('Error al insertar usuario en la base de datos');
            }
            $nuevoAdminId = $this->usuarioModel->getInsertID(); // Usar $this->usuarioModel

            if ($purchase) {
                // Simular la generación de una MAC address aleatoria solo para administradores
                $macSimulada = $this->generarMacAleatoria();

                // Guardar el dispositivo simulado y asociarlo al administrador
                $this->dispositivoModel->save([ // Usar $this->dispositivoModel
                    'id_usuario' => $nuevoAdminId,
                    'nombre' => 'Dispositivo Admin ' . $nuevoAdminId, // Nombre por defecto
                    'mac_address' => $macSimulada,
                    'estado' => 'activo' // Estado inicial
                ]);
            }

            $mensaje = $purchase
                ? '¡Registro como administrador exitoso! Ahora puedes iniciar sesión.'
                : 'Registro exitoso. Bienvenido/a.';

            return redirect()->to('autenticacion/login')
                                ->with('success', $mensaje);

        } catch (\Exception $e) {
            log_message('error', 'Error en registro: ' . $e->getMessage());
            return redirect()->back()
                                ->withInput()
                                ->with('error', 'Error durante el registro. Por favor intente nuevamente.');
        }
    }

    // --- FUNCIÓN PARA MOSTRAR EL FORMULARIO DE REGISTRO DE INVITADOS ---
    public function registroInvitado($token = null)
    {
        // Usar $this->invitacionModel en lugar de crear una nueva instancia local
        // $invitacionModel = new InvitacionModel();

        if (is_null($token)) {
            session()->setFlashdata('error', 'Enlace de invitación inválido.');
            return redirect()->to('autenticacion/login');
        }

        $invitacion = $this->invitacionModel->where('token', $token) // Usar $this->invitacionModel
                                      ->where('estado', 'pendiente')
                                      ->first();

        if (!$invitacion) {
            session()->setFlashdata('error', 'Invitación inválida o expirada.');
            return redirect()->to('autenticacion/login');
        }

        // Si la invitación es válida, carga la vista con el formulario de registro
        $data = [
            'email' => $invitacion['email'],
            'id_rol_invitado' => $invitacion['id_rol'],
            'token_invitacion' => $token,
            'nombre' => old('nombre', ''), // Para rellenar si el POST falla
            'apellido' => old('apellido', ''),
        ];

        return view('autenticacion/registro_invitado', $data);
    }

    // --- FUNCIÓN PARA PROCESAR EL ENVÍO DEL FORMULARIO DE REGISTRO DE INVITADOS ---
    public function procesarRegistroInvitado()
    {
        // LOGS INICIALES DE DATOS RECIBIDOS
        log_message('debug', 'Iniciando procesarRegistroInvitado.');
        log_message('debug', 'Datos recibidos del POST: ' . json_encode($this->request->getPost()));

        $token = $this->request->getPost('token_invitacion');
        $email = $this->request->getPost('email');
        $id_rol_invitado = $this->request->getPost('id_rol_invitado');
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $contrasena = $this->request->getPost('contrasena');
        $confirmar_contrasena = $this->request->getPost('confirmar_contrasena');

        log_message('debug', 'Token del formulario: ' . $token);
        log_message('debug', 'Email del formulario: ' . $email);
        log_message('debug', 'ID Rol Invitado del formulario: ' . $id_rol_invitado);


        // 1. Re-validar la invitación (seguridad)
        $invitacion = $this->invitacionModel->where('token', $token) // Usar $this->invitacionModel
                                            ->where('estado', 'pendiente')
                                            ->first();

        // LOGS DE VALIDACIÓN DE INVITACIÓN
        if (!$invitacion) {
            log_message('error', 'Invitación no encontrada o no está pendiente para el token: ' . $token);
            session()->setFlashdata('error', 'Error de validación de la invitación. Por favor, intenta de nuevo.');
            return redirect()->to('autenticacion/login');
        }

        log_message('debug', 'Invitación encontrada: ' . json_encode($invitacion));

        if ($invitacion['email'] !== $email) {
            log_message('error', 'El email de la invitación (' . $invitacion['email'] . ') no coincide con el del formulario (' . $email . ').');
            session()->setFlashdata('error', 'Error de validación de la invitación. Por favor, intenta de nuevo.');
            return redirect()->to('autenticacion/login');
        }

        if ((int)$invitacion['id_rol'] !== (int)$id_rol_invitado) {
            log_message('error', 'El ID de rol de la invitación (' . $invitacion['id_rol'] . ' - tipo: ' . gettype($invitacion['id_rol']) . ') no coincide con el del formulario (' . $id_rol_invitado . ' - tipo: ' . gettype($id_rol_invitado) . ').');
            session()->setFlashdata('error', 'Error de validación de la invitación. Por favor, intenta de nuevo.');
            return redirect()->to('autenticacion/login');
        }

        log_message('debug', 'Validación de invitación inicial OK.');

        // 2. Validaciones del formulario (reutiliza o adapta tus validaciones de 'registrarse()')
        if (!preg_match('/^[a-zA-Z\s]+$/', $nombre)) {
            log_message('error', 'Validación fallida: Nombre con caracteres inválidos.');
            session()->setFlashdata('error', 'El nombre solo puede contener letras y espacios.');
            return redirect()->back()->withInput();
        }
        if (!preg_match('/^[a-zA-Z\s]+$/', $apellido)) {
            log_message('error', 'Validación fallida: Apellido con caracteres inválidos.');
            session()->setFlashdata('error', 'El apellido solo puede contener letras y espacios.');
            return redirect()->back()->withInput();
        }
        // Verifica si el email ya existe en la tabla de usuarios (aunque ya fue validado en la invitación)
        if ($this->usuarioModel->existenteEmail($email)) { // Usar $this->usuarioModel
            log_message('error', 'Validación fallida: El email ya está registrado como usuario activo.');
            session()->setFlashdata('error', 'Este correo electrónico ya está registrado. Por favor, inicia sesión.');
            return redirect()->to('autenticacion/login');
        }
        // Validación de contraseña
        if (strlen($contrasena) < 6 || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[!@#$%]/', $contrasena)) {
            log_message('error', 'Validación fallida: Contraseña no cumple requisitos de complejidad.');
            session()->setFlashdata('password_error', 'La contraseña debe tener: 6+ caracteres, 1 mayúscula y 1 símbolo (!@#$%).');
            // Redirige de vuelta al formulario, manteniendo los datos previos
            return redirect()->back()->withInput();
        }

        // Si tienes campo de confirmación de contraseña
        if ($contrasena !== $confirmar_contrasena) {
            log_message('error', 'Validación fallida: Contraseñas no coinciden.');
            session()->setFlashdata('error', 'Las contraseñas no coinciden.');
            // Redirige de vuelta al formulario, manteniendo los datos previos
            return redirect()->back()->withInput();
        }

        log_message('debug', 'Validación de datos del formulario OK.');

        // 3. Crear el usuario
        $dataUsuario = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contrasena' => $contrasena,
            'id_rol' => $id_rol_invitado,
            'estado' => 'activo',
            'invitado_por' => $invitacion['invitado_por'] ?? null
        ];

        log_message('debug', 'Datos de usuario a insertar: ' . json_encode($dataUsuario));

        try {
            if (!$this->usuarioModel->insertarUsuario($dataUsuario)) { // Usar $this->usuarioModel
                throw new \RuntimeException('Error al registrar al usuario.');
            }
            log_message('info', 'Usuario registrado exitosamente con ID: ' . $this->usuarioModel->getInsertID());

            // Si el rol es administrador, simular la asignación de un dispositivo
            if ((int)$id_rol_invitado === 1) { // Asumiendo que 1 es el ID para 'admin'
                $nuevoUsuarioId = $this->usuarioModel->getInsertID(); // Usar $this->usuarioModel
                $macSimulada = $this->generarMacAleatoria(); // Asegúrate de tener esta función en tu controlador
                $this->dispositivoModel->save([ // Usar $this->dispositivoModel
                    'id_usuario' => $nuevoUsuarioId,
                    'nombre' => 'Dispositivo Admin ' . $nuevoUsuarioId,
                    'mac_address' => $macSimulada,
                    'estado' => 'activo'
                ]);
                log_message('info', 'Dispositivo simulado asignado al nuevo administrador: ' . $macSimulada);
            }

            // 4. Actualizar el estado de la invitación a 'completada'
            $this->invitacionModel->update($invitacion['id_invitacion'], ['estado' => 'completada']); // Usar $this->invitacionModel
            log_message('info', 'Estado de la invitación actualizada a "completada" para el token: ' . $token);

            session()->setFlashdata('success', '¡Registro completado exitosamente! Ahora puedes iniciar sesión.');
            return redirect()->to('autenticacion/login');

        } catch (\Exception $e) {
            log_message('critical', 'Excepción durante el procesamiento del registro de invitado: ' . $e->getMessage());
            session()->setFlashdata('error', 'Error al completar el registro. Por favor, inténtalo de nuevo.');
            return redirect()->back()->withInput();
        }
    }

    private function generarMacAleatoria() {
        // Implementación para generar una MAC aleatoria
        $mac = '';
        for ($i = 0; $i < 6; $i++) {
            $mac .= sprintf('%02x', rand(0, 255));
            if ($i < 5) {
                $mac .= ':';
            }
        }
        return strtoupper($mac);
    }

    public function iniciarSesion()
    {
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');

        log_message('debug', 'Intento de inicio de sesión para email: ' . $email);
        log_message('debug', 'Longitud de la contraseña ingresada: ' . strlen($contrasena));

        // Usar $this->usuarioModel
        $usuario = $this->usuarioModel->where('email', $email)->first();

        if (!$usuario) {
            log_message('debug', 'Usuario no encontrado para email: ' . $email);
            return redirect()->back()->with('error', 'Credenciales inválidas.');
        }

        log_message('debug', 'Usuario encontrado: ' . json_encode([
            'id' => $usuario['id_usuario'],
            'email' => $usuario['email'],
            'estado' => $usuario['estado'],
            'rol' => $usuario['id_rol']
        ]));

        if ($usuario['estado'] !== 'activo') {
            log_message('debug', 'Usuario no activo: ' . $usuario['estado']);
            return redirect()->back()->with('error', 'Tu cuenta no está activa. Por favor, verifica tu email.');
        }

        // Verificar la contraseña
        $passwordVerification = password_verify($contrasena, $usuario['contrasena']);
        log_message('debug', 'Resultado de verificación de contraseña: ' . ($passwordVerification ? 'true' : 'false'));
        log_message('debug', 'Hash almacenado en la base de datos: ' . $usuario['contrasena']);

        if (!$passwordVerification) {
            log_message('debug', 'Contraseña incorrecta para usuario: ' . $email);
            return redirect()->back()->with('error', 'Credenciales inválidas.');
        }

        log_message('debug', 'Inicio de sesión exitoso para usuario: ' . $email);

        // Determinar el rol basado en id_rol
        $rol = '';
        switch ($usuario['id_rol']) {
            case 1:
                $rol = 'admin';
                break;
            case 2:
                $rol = 'usuario';
                break;
            case 3:
                $rol = 'supervisor';
                break;
            default:
                $rol = 'usuario'; // Rol por defecto
                break;
        }

        // Crear sesión con todos los datos necesarios
        $userData = [
            'id_usuario' => $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'apellido' => $usuario['apellido'],
            'email' => $usuario['email'],
            'rol' => $rol,
            'logged_in' => true
        ];

        session()->set($userData);
        log_message('debug', 'Datos de sesión guardados: ' . json_encode($userData));

        return redirect()->to('/' . $rol);
    }

    public function cerrarSesion()
    {
        session()->destroy();
        return redirect()->to('autenticacion/login');
    }
}