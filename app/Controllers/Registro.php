<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;

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
    $email = $this->request->getPost('email');
    $rol = $this->request->getPost('rol');

    // Generar token único
    helper('text');
    $token = random_string('alnum', 32);

    // Guardar token temporalmente en sesión o base de datos (opcional si tenés tabla)
    session()->set("invitaciones_$token", ['email' => $email, 'rol' => $rol]);

    // Crear link de registro
    $link = base_url("registro/invitado/$token");

    // Enviar email
    $emailService = \Config\Services::email();
    $emailService->setTo($email);
    $emailService->setFrom('medidorinteligente457@gmail.com', 'EcoVolt');
    $emailService->setSubject('Invitación a registrarte');

    $mensaje = view('emails/invitacion', ['link' => $link, 'rol' => $rol]);

    $emailService->setMessage($mensaje);

    if ($emailService->send()) {
        return redirect()->back()->with('mensaje', 'Invitación enviada correctamente');
    } else {
        return redirect()->back()->with('error', 'Error al enviar el correo');
    }
}

    // ... (otros métodos permanecen igual)

   

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
        $nuevo_rol = $this->request->getPost('rol');

        // Validar que no sea el último administrador
        if ($nuevo_rol == 2) {
            $totalAdmins = $this->usuarioModel->where('id_rol', 1)->countAllResults();
            $usuarioActual = $this->usuarioModel->find($usuario_id);
            
            if ($usuarioActual['id_rol'] == 1 && $totalAdmins <= 1) {
                return redirect()->back()->with('error', 'No puede cambiar el rol del último administrador');
            }
        }

        if ($this->usuarioModel->update($usuario_id, ['id_rol' => $nuevo_rol])) {
            return redirect()->back()->with('success', 'Rol actualizado correctamente');
        } else {
            return redirect()->back()->with('error', 'Error al actualizar el rol');
        }
    }

    public function eliminarUsuario()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $usuario_id = $this->request->getPost('usuario_id');
        $usuario = $this->usuarioModel->find($usuario_id);

        // Validar que no sea el último administrador
        if ($usuario['id_rol'] == 1) {
            $totalAdmins = $this->usuarioModel->where('id_rol', 1)->countAllResults();
            if ($totalAdmins <= 1) {
                return redirect()->back()->with('error', 'No puede eliminar al último administrador');
            }
        }

        if ($this->usuarioModel->delete($usuario_id)) {
            return redirect()->back()->with('success', 'Usuario eliminado correctamente');
        } else {
            return redirect()->back()->with('error', 'Error al eliminar el usuario');
        }
    }

    public function invitar($token = null)
    {
        if (!$token) {
            return redirect()->to('/')->with('error', 'Token inválido o no proporcionado');
        }
    
        $model = new \App\Models\InvitacionModel();
        $invitacion = $model->where('token', $token)->first();
    
        if (!$invitacion) {
            return redirect()->to('/')->with('error', 'Token inválido o expirado');
        }
    
        return view('admin/invitar_usuario', ['email' => $invitacion['email'], 'rol' => $invitacion['rol']]);

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

}