<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\CompraModel;
use App\Models\DireccionModel;

class RegistroCompra extends BaseController
{
    public function mostrarFormulario()
    {
        return view('registro/compra');
    }

    public function procesarFormulario()
    {
        $usuarioModel = new UsuarioModel();
        $compraModel = new CompraModel();
        $direccionModel = new DireccionModel();

        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');
        $calle = $this->request->getPost('calle');
        $numero = $this->request->getPost('numero');
        $ciudad = $this->request->getPost('ciudad');
        $codigo_postal = $this->request->getPost('codigo_postal');
        $pais = $this->request->getPost('pais');

        // Validaciones básicas
        if (!$nombre || !$apellido || !$email || !$contrasena || !$calle || !$numero || !$ciudad || !$codigo_postal || !$pais) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios.');
        }
        if ($usuarioModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'El email ya está registrado.');
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
        $usuarioModel->insert($usuarioData);
        $idUsuario = $usuarioModel->getInsertID();

        // Crear dirección y asociar al usuario
        $direccionData = [
            'calle' => $calle,
            'numero' => $numero,
            'ciudad' => $ciudad,
            'codigo_postal' => $codigo_postal,
            'pais' => $pais,
            'id_usuario' => $idUsuario
        ];
        $direccionModel->insert($direccionData);
        $direccion_id = $direccionModel->getInsertID();

        // Actualizar usuario con direccion_id
        $usuarioModel->update($idUsuario, ['direccion_id' => $direccion_id]);

        // Concatenar dirección para la compra
        $direccion_envio = "$calle $numero, $ciudad, $codigo_postal, $pais";

        // Crear compra (estado pendiente)
        $compraModel->insert([
            'id_usuario' => $idUsuario,
            'direccion_envio' => $direccion_envio,
            'estado' => 'pendiente'
        ]);

        // Guardar en sesión para usar tras el pago
        session()->set('id_usuario_registro', $idUsuario);
        session()->set('token_activacion', $token);

        // Redirigir a PayPal (aquí solo simula, luego se integra real)
        return redirect()->to('/registro-compra/pago-exitoso');
    }

    // Simulación de pago exitoso (aquí iría la validación real de PayPal)
    public function pagoExitoso()
    {
        $idUsuario = session()->get('id_usuario_registro');
        $token = session()->get('token_activacion');
        if (!$idUsuario || !$token) {
            return redirect()->to('/registro-compra')->with('error', 'Sesión expirada. Intenta de nuevo.');
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($idUsuario);

        // Activar cuenta automáticamente
        $usuarioModel->update($idUsuario, [
            'estado' => 'activo',
            'token_activacion' => null
        ]);

        // (Opcional) Enviar email de bienvenida
        $emailService = \Config\Services::email();
        $emailService->setTo($usuario['email']);
        $emailService->setFrom('noreply@tusitio.com', 'EcoVolt');
        $emailService->setSubject('¡Bienvenido a EcoVolt!');
        $mensaje = "<p>Hola {$usuario['nombre']},</p><p>¡Tu cuenta ha sido activada automáticamente tras la compra! Cuando recibas tu producto, ingresa a tu cuenta y sigue el <a href='" . base_url('manual') . "'>manual de usuario</a> para asociar tu dispositivo.</p>";
        $emailService->setMessage($mensaje);
        $emailService->send();

        // Mensaje de bienvenida en sesión
        session()->setFlashdata('success', '¡Cuenta activada! Cuando recibas tu producto, ingresa a tu cuenta y sigue el manual para asociar tu dispositivo.');

        // Redirigir al manual de usuario
        return redirect()->to(base_url('manual'));
    }

    // Activación de cuenta por email
    public function activar($token)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('token_activacion', $token)->first();
        if ($usuario) {
            // Activar cuenta
            $usuarioModel->update($usuario['id_usuario'], [
                'estado' => 'activo',
                'token_activacion' => null
            ]);
            return view('registro/activacion_exitosa', ['nombre' => $usuario['nombre']]);
        } else {
            // Buscar si el usuario ya está activado
            $usuarioYaActivo = $usuarioModel->where('estado', 'activo')->where('token_activacion', null)->first();
            if ($usuarioYaActivo) {
                return view('registro/activacion_ya_activada', ['nombre' => $usuarioYaActivo['nombre']]);
            } else {
                return view('registro/activacion_invalida');
            }
        }
    }
} 