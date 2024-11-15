<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use Config\Services;

class CCorreo extends Controller
{
    public function index()
    {
        // Verifica si hay datos de usuario en la sesión
        if (session()->get('userData')) {
            return redirect()->to('/');
        }
        return view('autenticacion/correo');  
    }
    

    public function correo() 
    {
        $usuarioModel = new UsuarioModel();

        $emailUsuario = $this->request->getPost('email');
        $informacionUsuario = $usuarioModel->obtenerUsuarioEmail($emailUsuario);
      
        if ($informacionUsuario) {
            $codigo = rand(100000, 999999); // Generar un código de 6 dígitos

            $array = [
                'id_usuario' => $informacionUsuario['id_usuario'],
                'codigo' => $codigo,
                'expiracion' => date('Y-m-d H:i:s', strtotime('+15 minutes')), // Código válido por 15 minutos
            ];

            if ($usuarioModel->insertarCodigo($array)) {
                session()->set('emailValido', $emailUsuario);
                
                // Enviar el correo
                $email = \Config\Services::email();
                $email->setFrom('medidorinteligente467@gmail.com', 'Medidor inteligente');
                $email->setTo($emailUsuario);
                $email->setSubject('Código de verificación para restablecer contraseña');
                $email->setMessage("Use el siguiente código para restablecer su contraseña: $codigo. El código es válido por 15 minutos.");

                if ($email->send()) {
                    session()->set('exito', 'Ingresa el código enviado por email.');
                    return redirect()->to('autenticacion/nueva_contrasena');
                } else {
                    session()->set('error', 'Error al enviar el correo. Inténtalo de nuevo.');
                    log_message('error', 'Error al enviar el correo de restablecimiento de contraseña.');
                    return redirect()->back();
                }
            } else {
                session()->set('error', 'Error al generar el código. Inténtalo de nuevo.');
                return redirect()->back();
            }
        } else {
            session()->set('error', 'Correo no encontrado.');
            return redirect()->back();
        }
    }
}

