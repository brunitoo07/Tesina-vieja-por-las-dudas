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

        return view('admin/dashboard');
    }

    public function invitarUsuario()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $rol = $this->request->getPost('rol');

            try {
                // Generar token único para la invitación
                $token = bin2hex(random_bytes(32));
                
                // Guardar la invitación en la base de datos
                $invitacion = [
                    'email' => $email,
                    'rol' => $rol,
                    'token' => $token,
                    'fecha_expiracion' => date('Y-m-d H:i:s', strtotime('+24 hours')),
                    'estado' => 'pendiente'
                ];
                
                $this->db->table('invitaciones')->insert($invitacion);

                // Preparar datos para el correo
                $registroUrl = base_url('registro/invitacion/' . $token);
                $emailData = [
                    'rol' => $rol == '1' ? 'Administrador' : 'Usuario',
                    'registroUrl' => $registroUrl
                ];

                // Configurar y enviar el correo
                $emailService = \Config\Services::email();

                // Configuración detallada
                $config = [
                    'protocol' => 'smtp',
                    'SMTPHost' => 'smtp.gmail.com',
                    'SMTPUser' => 'medidorinteligente467@gmail.com',
                    'SMTPPass' => 'dhkfxnzspdrjdia',
                    'SMTPPort' => 465,
                    'SMTPCrypto' => 'ssl',
                    'mailType' => 'html',
                    'charset' => 'UTF-8',
                    'wordWrap' => true,
                    'wrapChars' => 76,
                    'validate' => true,
                    'priority' => 1,
                    'SMTPTimeout' => 60,
                    'SMTPKeepAlive' => true,
                    'newline' => "\r\n"
                ];

                // Inicializar email con la configuración
                $emailService->initialize($config);

                // Configurar el email
                $emailService->setFrom('medidorinteligente467@gmail.com', 'Medidor Inteligente');
                $emailService->setTo($email);
                $emailService->setSubject('Invitación a Medidor Inteligente');
                
                // Cargar y establecer el mensaje
                $mensaje = view('emails/invitacion', $emailData);
                $emailService->setMessage($mensaje);

                // Intentar enviar el email con depuración
                $sent = false;
                try {
                    $sent = $emailService->send(true);
                    $debugInfo = $emailService->printDebugger(['headers', 'subject', 'body']);
                } catch (\Exception $e) {
                    $debugInfo = [
                        'Error: ' . $e->getMessage(),
                        'Trace: ' . $e->getTraceAsString()
                    ];
                }

                if ($sent) {
                    // Guardar información de depuración
                    session()->set('ultimo_email', [
                        'to' => $email,
                        'subject' => 'Invitación a Medidor Inteligente',
                        'timestamp' => date('Y-m-d H:i:s'),
                        'debug' => $debugInfo,
                        'config' => $config
                    ]);

                    return redirect()->back()->with('success', 
                        'Invitación enviada correctamente a ' . $email . '. ' .
                        'Por favor, solicite al usuario que revise su bandeja de entrada y spam.'
                    );
                } else {
                    // Si falla el envío, mostrar detalles del error
                    log_message('error', 'Error al enviar email. Detalles: ' . print_r($debugInfo, true));
                    
                    // Eliminar la invitación
                    $this->db->table('invitaciones')->where('token', $token)->delete();
                    
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Error al enviar la invitación. Detalles del error: ' . implode("\n", $debugInfo));
                }
            } catch (\Exception $e) {
                log_message('error', 'Error en invitarUsuario: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ha ocurrido un error: ' . $e->getMessage());
            }
        }

        return view('admin/invitar_usuario');
    }

    public function gestionarUsuarios()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $data['usuarios'] = $this->usuarioModel->findAll();
        return view('admin/gestionarUsuarios', $data);
    }

    public function cambiarRol()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $usuario_id = $this->request->getPost('usuario_id');
        $nuevo_rol = $this->request->getPost('rol');

        if ($this->usuarioModel->update($usuario_id, ['rol' => $nuevo_rol])) {
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

        if ($this->usuarioModel->delete($usuario_id)) {
            return redirect()->back()->with('success', 'Usuario eliminado correctamente');
        } else {
            return redirect()->back()->with('error', 'Error al eliminar el usuario');
        }
    }
} 