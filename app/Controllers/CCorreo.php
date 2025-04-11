<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use Config\Services;
use App\Models\CodigoModel;

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
        $email = $this->request->getPost('email');
        $usuarioModel = new UsuarioModel();
        $codigoModel = new CodigoModel();

        // Verificar si el email existe
        $usuario = $usuarioModel->where('email', $email)->first();
        if (!$usuario) {
            session()->set('error', 'El email no está registrado.');
            return redirect()->back();
        }

        // Generar código de recuperación
        $codigo = $this->generarCodigo();
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar el código en la base de datos
        $codigoModel->insert([
            'id_usuario' => $usuario['id_usuario'],
                'codigo' => $codigo,
            'expiracion' => $fechaExpiracion
        ]);

        // Enviar el código por correo
        $this->enviarCodigoPorCorreo($email, $codigo);

        // Guardar el email en la sesión
        session()->set('emailValido', $email);

        // Redirigir a la página de nueva contraseña
        return redirect()->to('autenticacion/nueva-contrasena');
    }

    private function generarCodigo()
    {
        // Implementa la lógica para generar un código único
        return rand(100000, 999999);
    }

    private function enviarCodigoPorCorreo($emailDestinatario, $codigo)
    {
                $email = \Config\Services::email();
        $email->setFrom('medidorinteligente467@gmail.com', 'EcoVol Medidor Inteligente');
        $email->setTo($emailDestinatario);
                $email->setSubject('Código de verificación para restablecer contraseña');

        $htmlMensaje = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .container {
                    background-color: #f9f9f9;
                    border-radius: 10px;
                    padding: 20px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .header {
                    text-align: center;
                    padding: 20px 0;
                    border-bottom: 2px solid #4CAF50;
                }
                .content {
                    padding: 20px 0;
                }
                .code {
                    background-color: #f1f1f1;
                    padding: 15px;
                    border-radius: 5px;
                    font-family: monospace;
                    font-size: 24px;
                    text-align: center;
                    margin: 20px 0;
                    letter-spacing: 5px;
                    color: #4CAF50;
                    font-weight: bold;
                }
                .footer {
                    text-align: center;
                    padding: 20px 0;
                    color: #666;
                    font-size: 12px;
                }
                .button {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Restablecer Contraseña</h2>
                </div>
                <div class="content">
                    <p>Hola,</p>
                    <p>Hemos recibido una solicitud para restablecer tu contraseña. Utiliza el siguiente código de verificación:</p>
                    <div class="code">' . $codigo . '</div>
                    <p>Este código es válido por 1 hora. Si no solicitaste este cambio, por favor ignora este correo.</p>
                    <p>Para restablecer tu contraseña, visita: <a href="' . base_url('autenticacion/nueva-contrasena') . '" class="button">Restablecer Contraseña</a></p>
                </div>
                <div class="footer">
                    <p>Este es un correo automático, por favor no responda.</p>
                    <p>&copy; ' . date('Y') . ' EcoVol Medidor Inteligente. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $email->setMessage($htmlMensaje);
        $email->setMailType('html');

                if ($email->send()) {
            session()->set('exito', 'Se ha enviado un código de verificación a tu correo electrónico.');
            return true;
                } else {
            session()->set('error', 'Error al enviar el correo. Por favor, inténtalo de nuevo.');
                    log_message('error', 'Error al enviar el correo de restablecimiento de contraseña.');
            return false;
        }
    }

    private function generarContrasenaTemporal()
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longitud = 16;
        $contrasena = '';
        
        for ($i = 0; $i < $longitud; $i++) {
            $contrasena .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        return $contrasena;
    }

    private function enviarCredencialesPorCorreo($emailDestinatario, $nombreUsuario, $contrasenaTemporal)
    {
        $email = \Config\Services::email();
        $email->setFrom('medidorinteligente467@gmail.com', 'EcoVol Medidor Inteligente');
        $email->setTo($emailDestinatario);
        $email->setSubject('¡Bienvenido a EcoVol!');

        $htmlMensaje = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .container {
                    background-color: #f9f9f9;
                    border-radius: 10px;
                    padding: 20px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .header {
                    text-align: center;
                    padding: 20px 0;
                    border-bottom: 2px solid #4CAF50;
                }
                .content {
                    padding: 20px 0;
                }
                .credentials {
                    background-color: #f1f1f1;
                    padding: 15px;
                    border-radius: 5px;
                    margin: 20px 0;
                }
                .footer {
                    text-align: center;
                    padding: 20px 0;
                    color: #666;
                    font-size: 12px;
                }
                .button {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>¡Bienvenido a EcoVol!</h2>
                </div>
                <div class="content">
                    <p>Hola ' . $nombreUsuario . ',</p>
                    <p>¡Tu cuenta ha sido creada exitosamente! Aquí están tus credenciales temporales:</p>
                    <div class="credentials">
                        <p><strong>Email:</strong> ' . $emailDestinatario . '</p>
                        <p><strong>Contraseña temporal:</strong> ' . $contrasenaTemporal . '</p>
                    </div>
                    <p>Por razones de seguridad, te recomendamos:</p>
                    <ul>
                        <li>Iniciar sesión con estas credenciales</li>
                        <li>Cambiar tu contraseña inmediatamente</li>
                    </ul>
                    <p>Puedes iniciar sesión aquí: <a href="' . base_url('autenticacion/login') . '" class="button">Iniciar Sesión</a></p>
                    <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
                </div>
                <div class="footer">
                    <p>Este es un correo automático, por favor no responda.</p>
                    <p>&copy; ' . date('Y') . ' EcoVol Medidor Inteligente. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $email->setMessage($htmlMensaje);
        $email->setMailType('html');

        if ($email->send()) {
            session()->set('exito', 'Las credenciales han sido enviadas a tu correo electrónico.');
            return true;
        } else {
            session()->set('error', 'Error al enviar el correo con las credenciales.');
            log_message('error', 'Error al enviar el correo de bienvenida.');
            return false;
        }
    }

    public function enviarCorreo($destinatario, $asunto, $mensaje)
    {
        $email = \Config\Services::email();

        $email->setFrom('medidorinteligente467@gmail.com', 'Medidor Inteligente');
        $email->setTo($destinatario);
        $email->setSubject($asunto);

        // Plantilla HTML mejorada para el correo
        $htmlMensaje = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .container {
                    background-color: #f9f9f9;
                    border-radius: 10px;
                    padding: 20px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .header {
                    text-align: center;
                    padding: 20px 0;
                    border-bottom: 2px solid #4CAF50;
                }
                .content {
                    padding: 20px 0;
                }
                .button {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
                }
                .footer {
                    text-align: center;
                    padding: 20px 0;
                    color: #666;
                    font-size: 12px;
                }
                .code {
                    background-color: #f1f1f1;
                    padding: 10px;
                    border-radius: 5px;
                    font-family: monospace;
                    font-size: 18px;
                    text-align: center;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Medidor Inteligente</h2>
                </div>
                <div class="content">
                    ' . $mensaje . '
                </div>
                <div class="footer">
                    <p>Este es un correo automático, por favor no responda.</p>
                    <p>&copy; ' . date('Y') . ' Medidor Inteligente. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $email->setMessage($htmlMensaje);
        $email->setMailType('html');

        if ($email->send()) {
            return true;
            } else {
            log_message('error', 'Error al enviar correo: ' . $email->printDebugger());
            return false;
        }
    }

    public function enviarCodigoVerificacion($emailDestinatario)
    {
        // Generar código de verificación
        $codigo = $this->generarCodigoVerificacion();
        
        // Guardar el código en la base de datos
        $this->guardarCodigo($emailDestinatario, $codigo);
        
        // Implementa la lógica para enviar el código por correo
        $email = \Config\Services::email();
        $email->setFrom('medidorinteligente467@gmail.com', 'Medidor inteligente');
        $email->setTo($emailDestinatario);
        $email->setSubject('Código de verificación para restablecer contraseña');

        // Plantilla HTML mejorada para el correo
        $htmlMensaje = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .container {
                    background-color: #f9f9f9;
                    border-radius: 10px;
                    padding: 20px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .header {
                    text-align: center;
                    padding: 20px 0;
                    border-bottom: 2px solid #4CAF50;
                }
                .content {
                    padding: 20px 0;
                }
                .code {
                    background-color: #f1f1f1;
                    padding: 10px;
                    border-radius: 5px;
                    font-family: monospace;
                    font-size: 24px;
                    text-align: center;
                    margin: 20px 0;
                    letter-spacing: 5px;
                }
                .footer {
                    text-align: center;
                    padding: 20px 0;
                    color: #666;
                    font-size: 12px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Restablecer Contraseña</h2>
                </div>
                <div class="content">
                    <p>Hemos recibido una solicitud para restablecer tu contraseña. Utiliza el siguiente código de verificación:</p>
                    <div class="code">' . $codigo . '</div>
                    <p>Este código es válido por 1 hora. Si no solicitaste este cambio, por favor ignora este correo.</p>
                </div>
                <div class="footer">
                    <p>Este es un correo automático, por favor no responda.</p>
                    <p>&copy; ' . date('Y') . ' Medidor Inteligente. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>';

        $email->setMessage($htmlMensaje);
        $email->setMailType('html');

        if ($email->send()) {
            return true;
        } else {
            log_message('error', 'Error al enviar correo: ' . $email->printDebugger());
            return false;
        }
    }
}

