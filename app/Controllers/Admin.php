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
            return redirect()->to('/autenticacion/login');
        }

        // Obtener datos para el dashboard con valores por defecto
        $data = [
            'usuarios' => $this->usuarioModel->where('id_rol', 2)->findAll() ?? [],
            'admins' => $this->usuarioModel->where('id_rol', 1)->findAll() ?? [],
            'ultimosUsuarios' => $this->usuarioModel->orderBy('created_at', 'DESC')->limit(5)->findAll() ?? [],
            'totalDispositivos' => 0, // Agrega esta línea si necesitas mostrar dispositivos
        ];

        return view('admin/dashboard', $data);
    }

    public function invitarUsuario()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $rol = $this->request->getPost('rol');

            // Validar que el email no esté ya registrado
            if ($this->usuarioModel->where('email', $email)->countAllResults() > 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Este correo electrónico ya está registrado.');
            }

            try {
                $token = bin2hex(random_bytes(32));
                
                $invitacion = [
                    'email' => $email,
                    'rol' => $rol,
                    'token' => $token,
                    'fecha_expiracion' => date('Y-m-d H:i:s', strtotime('+24 hours')),
                    'estado' => 'pendiente'
                ];
                
                $this->db->table('invitaciones')->insert($invitacion);

                $registroUrl = base_url('registro/invitacion/' . $token);
                $emailData = [
                    'rol' => $rol == 1 ? 'Administrador' : 'Usuario',
                    'registroUrl' => $registroUrl
                ];

                $emailService = \Config\Services::email();
                $config = [
                    'protocol' => 'smtp',
                    'SMTPHost' => 'smtp.gmail.com',
                    'SMTPUser' => 'medidorinteligente467@gmail.com',
                    'SMTPPass' => 'dhkfxnzspdrjdia',
                    'SMTPPort' => 465,
                    'SMTPCrypto' => 'ssl',
                    'mailType' => 'html',
                    'charset' => 'UTF-8'
                ];

                $emailService->initialize($config);
                $emailService->setFrom('medidorinteligente467@gmail.com', 'Medidor Inteligente');
                $emailService->setTo($email);
                $emailService->setSubject('Invitación a Medidor Inteligente');
                $emailService->setMessage(view('emails/invitacion', $emailData));

                if ($emailService->send()) {
                    return redirect()->back()->with('success', 
                        'Invitación enviada a ' . $email . '. Verifique su bandeja de entrada.'
                    );
                } else {
                    $this->db->table('invitaciones')->where('token', $token)->delete();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Error al enviar el correo: ' . $emailService->printDebugger(['headers']));
                }
            } catch (\Exception $e) {
                log_message('error', 'Error en invitarUsuario: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error: ' . $e->getMessage());
            }
        }

        return view('admin/invitar_usuario');
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
}