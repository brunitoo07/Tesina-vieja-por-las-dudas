<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\CompraModel;
use App\Models\DireccionModel;
use App\Models\DispositivoModel;

class RegistroCompra extends BaseController
{
    protected $usuarioModel;
    protected $compraModel;
    protected $direccionModel;
    protected $dispositivoModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->compraModel = new CompraModel();
        $this->direccionModel = new DireccionModel();
        $this->dispositivoModel = new DispositivoModel();
    }

    public function mostrarFormulario()
    {
        return view('registro/compra');
    }

    public function procesarFormulario()
    {
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');
        $calle = $this->request->getPost('calle');
        $numero = $this->request->getPost('numero');
        $ciudad = $this->request->getPost('ciudad');
        $codigo_postal = $this->request->getPost('codigo_postal');
        $pais = $this->request->getPost('pais');
        $id_dispositivo = $this->request->getPost('id_dispositivo');

        // Validaciones básicas
        if (!$nombre || !$apellido || !$email || !$contrasena || !$calle || !$numero || !$ciudad || !$codigo_postal || !$pais || !$id_dispositivo) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios.');
        }

        if ($this->usuarioModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'El email ya está registrado.');
        }

        // Verificar disponibilidad del dispositivo
        $dispositivo = $this->dispositivoModel->getDispositivoConStock($id_dispositivo);
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'El dispositivo seleccionado no está disponible.');
        }

        // Generar token de activación
        $token = bin2hex(random_bytes(32));

        // Crear usuario (rol admin, estado pendiente)
        $usuarioData = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
            'id_rol' => 1, // admin
            'estado' => 'pendiente',
            'token_activacion' => $token
        ];
        $this->usuarioModel->insert($usuarioData);
        $idUsuario = $this->usuarioModel->getInsertID();

        // Crear dirección y asociar al usuario
        $direccionData = [
            'calle' => $calle,
            'numero' => $numero,
            'ciudad' => $ciudad,
            'codigo_postal' => $codigo_postal,
            'pais' => $pais,
            'id_usuario' => $idUsuario
        ];
        $this->direccionModel->insert($direccionData);
        $direccion_id = $this->direccionModel->getInsertID();

        // Actualizar usuario con direccion_id
        $this->usuarioModel->update($idUsuario, ['direccion_id' => $direccion_id]);

        // Concatenar dirección para la compra
        $direccion_envio = "$calle $numero, $ciudad, $codigo_postal, $pais";

        // Crear compra (estado pendiente)
        $this->compraModel->insert([
            'id_usuario' => $idUsuario,
            'id_dispositivo' => $id_dispositivo,
            'direccion_envio' => $direccion_envio,
            'estado' => 'pendiente',
            'fecha_compra' => date('Y-m-d H:i:s')
        ]);

        // Actualizar stock del dispositivo
        $this->dispositivoModel->actualizarStock($id_dispositivo, 1);

        // Guardar en sesión para usar tras el pago
        session()->set([
            'id_usuario_registro' => $idUsuario,
            'token_activacion' => $token,
            'id_dispositivo' => $id_dispositivo
        ]);

        // Enviar email de bienvenida
        $this->enviarEmailBienvenida($email, $nombre, $token);

        return redirect()->to('/registro-compra/pago-exitoso');
    }

    public function pagoExitoso()
    {
        $idUsuario = session()->get('id_usuario_registro');
        $token = session()->get('token_activacion');
        $idDispositivo = session()->get('id_dispositivo');

        if (!$idUsuario || !$token || !$idDispositivo) {
            return redirect()->to('/registro-compra')->with('error', 'Sesión expirada. Intenta de nuevo.');
        }

        $usuario = $this->usuarioModel->find($idUsuario);
        $dispositivo = $this->dispositivoModel->find($idDispositivo);

        // Activar cuenta automáticamente
        $this->usuarioModel->update($idUsuario, [
            'estado' => 'activo',
            'token_activacion' => null
        ]);

        // Actualizar estado de la compra
        $this->compraModel->where('id_usuario', $idUsuario)
                         ->set(['estado' => 'completada'])
                         ->update();

        // Enviar email de confirmación de compra
        $this->enviarEmailConfirmacionCompra($usuario['email'], $usuario['nombre'], $dispositivo);

        // Limpiar sesión
        session()->remove(['id_usuario_registro', 'token_activacion', 'id_dispositivo']);

        // Mensaje de bienvenida en sesión
        session()->setFlashdata('success', '¡Cuenta activada! Cuando recibas tu producto, ingresa a tu cuenta y sigue el manual para asociar tu dispositivo.');

        // Redirigir al manual de usuario
        return redirect()->to(base_url('manual'));
    }

    protected function enviarEmailBienvenida($email, $nombre, $token)
    {
        $emailService = \Config\Services::email();
        
        $emailService->setTo($email);
        $emailService->setFrom('noreply@ecovolt.com', 'EcoVolt');
        $emailService->setSubject('¡Bienvenido a EcoVolt!');
        
        $mensaje = view('emails/bienvenida', [
            'nombre' => $nombre,
            'enlace_activacion' => base_url("registro-compra/activar/$token")
        ]);
        
        $emailService->setMessage($mensaje);
        $emailService->send();
    }

    protected function enviarEmailConfirmacionCompra($email, $nombre, $dispositivo)
    {
        $emailService = \Config\Services::email();
        
        $emailService->setTo($email);
        $emailService->setFrom('noreply@ecovolt.com', 'EcoVolt');
        $emailService->setSubject('¡Gracias por tu compra en EcoVolt!');
        
        $mensaje = view('emails/confirmacion_compra', [
            'nombre' => $nombre,
            'dispositivo' => $dispositivo,
            'manual_url' => base_url('manual')
        ]);
        
        $emailService->setMessage($mensaje);
        $emailService->send();
    }

    public function activar($token)
    {
        $usuario = $this->usuarioModel->where('token_activacion', $token)->first();
        
        if ($usuario) {
            // Activar cuenta
            $this->usuarioModel->update($usuario['id_usuario'], [
                'estado' => 'activo',
                'token_activacion' => null
            ]);
            
            return view('registro/activacion_exitosa', ['nombre' => $usuario['nombre']]);
        } else {
            // Buscar si el usuario ya está activado
            $usuarioYaActivo = $this->usuarioModel->where('estado', 'activo')
                                                 ->where('token_activacion', null)
                                                 ->first();
            
            if ($usuarioYaActivo) {
                return view('registro/activacion_ya_activada', ['nombre' => $usuarioYaActivo['nombre']]);
            } else {
                return view('registro/activacion_invalida');
            }
        }
    }
} 