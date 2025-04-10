<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;
use App\Models\InvitacionModel;


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
        // Depuración de la sesión
        log_message('debug', '=== INICIO DE ENVIAR INVITACION ===');
        log_message('debug', 'Datos de sesión completos: ' . print_r(session()->get(), true));
        
        // Verificar que el usuario esté autenticado y sea administrador
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            log_message('error', 'Usuario no autenticado o no es admin');
            log_message('error', 'logged_in: ' . (session()->get('logged_in') ? 'true' : 'false'));
            log_message('error', 'rol: ' . session()->get('rol'));
            
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(401)->setJSON(['error' => 'No autorizado']);
            }
            return redirect()->to('/autenticacion/login');
        }

        $email = $this->request->getPost('email');
        $id_rol = $this->request->getPost('id_rol');
        
        log_message('debug', 'Datos del formulario:');
        log_message('debug', 'Email: ' . $email);
        log_message('debug', 'ID Rol: ' . $id_rol);
        
        // Obtener el ID del usuario desde userData
        $userData = session()->get('userData');
        $idUsuario = (int)($userData['id_usuario'] ?? 0);
        
        log_message('debug', 'ID de usuario obtenido: ' . $idUsuario);
        
        // Validar que el ID del usuario sea válido
        if ($idUsuario <= 0) {
            log_message('error', 'ID de usuario inválido: ' . $idUsuario);
            log_message('error', 'Datos de sesión al momento del error:');
            log_message('error', print_r(session()->get(), true));
            
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error' => 'ID de usuario inválido',
                    'debug' => [
                        'userData' => $userData,
                        'logged_in' => session()->get('logged_in'),
                        'rol' => session()->get('rol'),
                        'session_data' => session()->get()
                    ]
                ]);
            }
            return redirect()->back()->with('error', 'ID de usuario inválido');
        }

        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Email inválido']);
            }
            return redirect()->back()->with('error', 'Email inválido');
        }

        // Validar rol
        if (!in_array($id_rol, [1, 2, 3])) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Rol inválido']);
            }
            return redirect()->back()->with('error', 'Rol inválido');
        }

        $invitacionModel = new InvitacionModel();
        
        try {
            $invitacionId = $invitacionModel->crearInvitacion($email, $idUsuario, $id_rol);
            
            if ($invitacionId) {
                // Obtener el token de la invitación
                $invitacion = $invitacionModel->find($invitacionId);
                
                // Enviar email
                $emailService = \Config\Services::email();
                $emailService->setTo($email);
                $emailService->setSubject('Invitación para unirte a nuestro sistema');
                
                $data = [
                    'email' => $email,
                    'id_rol' => $id_rol,
                    'token' => $invitacion['token']
                ];
                
                $emailService->setMessage(view('emails/invitacion', $data));
                
                if ($emailService->send()) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON(['success' => true, 'message' => 'Invitación enviada correctamente']);
                    }
                    return redirect()->to('admin/invitar')->with('success', 'Invitación enviada correctamente');
                } else {
                    // Si falla el envío del email, eliminamos la invitación
                    $invitacionModel->delete($invitacionId);
                    if ($this->request->isAJAX()) {
                        return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al enviar el email']);
                    }
                    return redirect()->back()->with('error', 'Error al enviar el email');
                }
            }
            
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al crear la invitación']);
            }
            return redirect()->back()->with('error', 'Error al crear la invitación');
        } catch (\Exception $e) {
            log_message('error', 'Error en enviarInvitacion: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Error interno del servidor']);
            }
            return redirect()->back()->with('error', 'Error interno del servidor');
        }
    }

    public function invitar($token = null)
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        if ($token) {
            // Si hay token, es para el registro del usuario invitado
            $model = new \App\Models\InvitacionModel();
            $invitacion = $model->where('token', $token)->first();
        
            if (!$invitacion) {
                return redirect()->to('/')->with('error', 'Token inválido o expirado');
            }
        
            return view('admin/invitar_usuario', ['email' => $invitacion['email'], 'id_rol' => $invitacion['id_rol']]);
        }

        // Si no hay token, mostrar el formulario de invitación
        return view('admin/invitar_usuario');
    }
    
    public function guardarUsuario()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $rol = $this->request->getPost('rol');
    
        // Guardar en la base de datos, por ejemplo:
        $db = \Config\Database::connect();
        $builder = $db->table('usuario');
        $builder->insert([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $rol,
        ]);
    
        return redirect()->to('/alguna-ruta')->with('mensaje', 'Usuario registrado');
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