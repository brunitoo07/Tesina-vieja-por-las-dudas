<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;
use App\Models\InvitacionModel;
use App\Controllers\CCorreo;


class Admin extends BaseController
{
    protected $usuarioModel;
    protected $rolesModel;
    protected $db;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolesModel = new RolesModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            if (session()->get('error')) {
                session()->remove('error');
            }
            return redirect()->to('/autenticacion/login');
        }

        $data = [
            'usuarios' => $this->usuarioModel->where('id_rol', 2)->findAll() ?? [],
            'admins' => $this->usuarioModel->where('id_rol', 1)->findAll() ?? [],
            'ultimosUsuarios' => $this->usuarioModel->orderBy('created_at', 'DESC')->limit(5)->findAll() ?? [],
            'totalDispositivos' => 0,
        ];

        return view('admin/dashboard', $data);
    }

    public function enviarInvitacion()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $email = $this->request->getPost('email');
        $idRol = $this->request->getPost('id_rol');

        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            session()->set('error', 'Email inválido');
            return redirect()->back();
        }

        // Verificar si el email ya está registrado
        $usuarioModel = new UsuarioModel();
        if ($usuarioModel->where('email', $email)->first()) {
            session()->set('error', 'Este email ya está registrado');
            return redirect()->back();
        }

        // Verificar si ya existe una invitación pendiente para este email
        $invitacionModel = new InvitacionModel();
        $invitacionExistente = $invitacionModel->where('email', $email)
                                             ->where('estado', 'pendiente')
                                             ->where('fecha_expiracion >', date('Y-m-d H:i:s'))
                                             ->first();

        if ($invitacionExistente) {
            session()->set('error', 'Ya existe una invitación pendiente para este email');
            return redirect()->back();
        }

        // Crear nueva invitación
        $token = $invitacionModel->crearInvitacion($email, $idRol);
        if (!$token) {
            session()->set('error', 'Error al crear la invitación');
            return redirect()->back();
        }

        // Enviar correo con el enlace de invitación
        $emailService = \Config\Services::email();
        $emailService->setFrom('medidorinteligente467@gmail.com', 'EcoVolt');
        $emailService->setTo($email);
        $emailService->setSubject('Invitación para unirte a EcoVolt');
        
        $enlaceInvitacion = base_url('admin/registro/invitado/' . $token);
        $mensaje = "¡Hola!\n\n";
        $mensaje .= "Has sido invitado a unirte a EcoVolt. Para completar tu registro, haz clic en el siguiente enlace:\n\n";
        $mensaje .= $enlaceInvitacion . "\n\n";
        $mensaje .= "Este enlace expirará en 7 días.\n\n";
        $mensaje .= "Saludos,\n";
        $mensaje .= "El equipo de EcoVolt";
        
        $emailService->setMessage($mensaje);

        if ($emailService->send()) {
            session()->set('exito', 'Invitación enviada correctamente');
        } else {
            session()->set('error', 'Error al enviar el correo de invitación');
        }

        return redirect()->back();
    }

    public function invitar($token = null)
    {
        // Verificar si el usuario es admin
        $isAdmin = session()->get('logged_in') && session()->get('rol') === 'admin';

        if ($token) {
            // Si hay token, es para el registro del usuario invitado
            $model = new \App\Models\InvitacionModel();
            $invitacion = $model->validarInvitacion($token);
        
            if (!$invitacion) {
                return redirect()->to('/')->with('error', 'Token inválido o expirado');
            }
        
            return view('admin/invitar_usuario', [
                'email' => $invitacion['email'],
                'id_rol' => $invitacion['id_rol'],
                'token' => $token,
                'isAdmin' => false
            ]);
        }

        // Si no hay token y no es admin, redirigir al login
        if (!$isAdmin) {
            return redirect()->to('/autenticacion/login');
        }

        // Si es admin, mostrar el formulario de invitación
        return view('admin/invitar_usuario', [
            'isAdmin' => true
        ]);
    }
    
    public function guardarUsuario()
    {
        $usuarioModel = new UsuarioModel();
        $invitacionModel = new InvitacionModel();
        $correoController = new CCorreo();

        $email = $this->request->getPost('email');
        $token = $this->request->getPost('token');
        $idRol = $this->request->getPost('id_rol');
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $contrasena = $this->request->getPost('contrasena');
        $confirmar_contrasena = $this->request->getPost('confirmar_contrasena');

        // Verificar si el email ya está registrado
        if ($usuarioModel->where('email', $email)->first()) {
            session()->set('error', 'Email ya registrado: ' . $email);
            return redirect()->back();
        }

        // Verificar que las contraseñas coincidan
        if ($contrasena !== $confirmar_contrasena) {
            session()->set('error', 'Las contraseñas no coinciden');
            return redirect()->back();
        }

        // Verificar la longitud mínima de la contraseña
        if (strlen($contrasena) < 8) {
            session()->set('error', 'La contraseña debe tener al menos 8 caracteres');
            return redirect()->back();
        }

        // Verificar la invitación
        $invitacion = $invitacionModel->where('token', $token)
                                    ->where('email', $email)
                                    ->where('estado', 'pendiente')
                                    ->first();

        if (!$invitacion) {
            session()->set('error', 'Invitación no encontrada o inválida.');
            return redirect()->back();
        }

        // Crear el nuevo usuario
        $usuarioData = [
            'email' => $email,
            'contrasena' => $contrasena,
            'id_rol' => $idRol,
            'nombre' => $nombre,
            'apellido' => $apellido
        ];

        try {
            // Insertar el usuario
            $usuarioModel->insert($usuarioData);
            $idUsuario = $usuarioModel->getInsertID();

            // Actualizar el estado de la invitación
            $invitacionModel->update($invitacion['id_invitacion'], ['estado' => 'aceptada']);

            // Redirigir al login con mensaje de éxito
            session()->set('exito', 'Registro completado exitosamente. Ahora puedes iniciar sesión.');
            return redirect()->to('autenticacion/login');
        } catch (\Exception $e) {
            log_message('error', 'Error al guardar usuario: ' . $e->getMessage());
            session()->set('error', 'Error al completar el registro. Por favor, intente nuevamente.');
            return redirect()->back();
        }
    }
    

    

    public function listarAdmins()
{
    if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
        return redirect()->to('/autenticacion/login');
    }

    $data['usuarios'] = $this->usuarioModel
        ->select('usuario.*, roles.nombre_rol as rol')
        ->join('roles', 'roles.id_rol = usuario.id_rol')
        ->where('usuario.id_rol', 1) // Solo admins (id_rol = 1)
        ->findAll();

    return view('admin/gestionarUsuarios', $data); // Reutiliza la misma vista
}

public function gestionarUsuarios()
{
    if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
        return redirect()->to('/autenticacion/login');
    }

    $data['usuarios'] = $this->usuarioModel
        ->select('usuario.*, roles.nombre_rol as rol')
        ->join('roles', 'roles.id_rol = usuario.id_rol')
        ->findAll();

    return view('admin/gestionarUsuarios', $data);
}

public function cambiarRol()
{
    if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
        return redirect()->to('/autenticacion/login');
    }

    $usuario_id = $this->request->getPost('usuario_id');
    $id_rol = $this->request->getPost('id_rol');

    // Verificar que el usuario existe
    $usuario = $this->usuarioModel->find($usuario_id);
    if (!$usuario) {
        return redirect()->back()->with('error', 'Usuario no encontrado');
    }

    // Verificar que el rol existe
    $rol = $this->rolesModel->find($id_rol);
    if (!$rol) {
        return redirect()->back()->with('error', 'Rol no válido');
    }

    // Verificar que no se está cambiando el último admin
    if ($usuario['id_rol'] == 1 && $id_rol != 1) {
        $admins = $this->usuarioModel->where('id_rol', 1)->countAllResults();
        if ($admins <= 1) {
            return redirect()->back()->with('error', 'No se puede quitar el último administrador');
        }
    }

    try {
        $this->usuarioModel->update($usuario_id, ['id_rol' => $id_rol]);
        return redirect()->back()->with('success', 'Rol actualizado correctamente');
    } catch (\Exception $e) {
        log_message('error', 'Error al cambiar rol: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al actualizar el rol');
    }
}

public function eliminarUsuario()
{
    if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
        return redirect()->to('/autenticacion/login');
    }

    $usuario_id = $this->request->getPost('usuario_id');

    // Verificar que el usuario existe
    $usuario = $this->usuarioModel->find($usuario_id);
    if (!$usuario) {
        return redirect()->back()->with('error', 'Usuario no encontrado');
    }

    // Verificar que no se está eliminando el último admin
    if ($usuario['id_rol'] == 1) {
        $admins = $this->usuarioModel->where('id_rol', 1)->countAllResults();
        if ($admins <= 1) {
            return redirect()->back()->with('error', 'No se puede eliminar el último administrador');
        }
    }

    try {
        $this->usuarioModel->delete($usuario_id);
        return redirect()->back()->with('success', 'Usuario eliminado correctamente');
    } catch (\Exception $e) {
        log_message('error', 'Error al eliminar usuario: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al eliminar el usuario');
    }
}

}