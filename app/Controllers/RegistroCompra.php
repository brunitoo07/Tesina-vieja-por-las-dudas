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

        log_message('debug', '=== INICIO PROCESO DE COMPRA ===');
        log_message('debug', 'Datos recibidos: ' . json_encode([
            'nombre' => $nombre,
            'email' => $email,
            'id_dispositivo' => $id_dispositivo
        ]));

        // Validaciones básicas
        if (!$nombre || !$apellido || !$email || !$contrasena || !$calle || !$numero || !$ciudad || !$codigo_postal || !$pais || !$id_dispositivo) {
            log_message('debug', 'Faltan campos obligatorios en el registro');
            return redirect()->back()->with('error', 'Todos los campos son obligatorios.');
        }

        if ($this->usuarioModel->where('email', $email)->first()) {
            log_message('debug', 'Email ya registrado: ' . $email);
            return redirect()->back()->with('error', 'El email ya está registrado.');
        }

        // Verificar disponibilidad del dispositivo
        $dispositivo = $this->dispositivoModel->find($id_dispositivo);
        log_message('debug', 'Dispositivo encontrado: ' . json_encode($dispositivo));

        if (!$dispositivo) {
            log_message('debug', 'Dispositivo no encontrado: ' . $id_dispositivo);
            return redirect()->back()->with('error', 'El dispositivo seleccionado no existe.');
        }

        // Generar token de activación
        $token = bin2hex(random_bytes(32));

        // Crear usuario (rol admin, estado pendiente)
        $usuarioData = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contrasena' => $contrasena,
            'id_rol' => 1, // admin
            'estado' => 'pendiente',
            'token_activacion' => $token
        ];

        log_message('debug', 'Creando usuario con datos: ' . json_encode([
            'email' => $email,
            'rol' => 1,
            'estado' => 'pendiente'
        ]));

        $this->usuarioModel->insert($usuarioData);
        $idUsuario = $this->usuarioModel->getInsertID();

        log_message('debug', 'Usuario creado con ID: ' . $idUsuario);

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

        log_message('debug', 'Dirección creada con ID: ' . $direccion_id);

        // Guardar datos en sesión para el proceso de pago
        $datosCompra = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'direccion' => $calle . ' ' . $numero . ', ' . $ciudad . ', ' . $codigo_postal . ', ' . $pais
        ];

        session()->set('datos_compra', $datosCompra);
        session()->set('id_usuario_registro', $idUsuario);
        session()->set('id_dispositivo', $id_dispositivo);
        session()->set('token_activacion', $token);

        log_message('debug', 'Datos guardados en sesión: ' . json_encode([
            'id_usuario' => $idUsuario,
            'id_dispositivo' => $id_dispositivo
        ]));

        log_message('debug', '=== FIN PROCESO DE COMPRA ===');

        return redirect()->to('compra');
    }

    public function pagoExitoso()
    {
        $idUsuario = session()->get('id_usuario_registro');
        $token = session()->get('token_activacion');
        $idDispositivo = session()->get('id_dispositivo');

        log_message('debug', 'Procesando pago exitoso para usuario: ' . $idUsuario);

        if (!$idUsuario || !$token || !$idDispositivo) {
            log_message('debug', 'Sesión expirada o datos faltantes');
            return redirect()->to(base_url('registro-compra'))->with('error', 'Sesión expirada. Intenta de nuevo.');
        }

        $usuario = $this->usuarioModel->find($idUsuario);
        $dispositivo = $this->dispositivoModel->find($idDispositivo);

        if (!$usuario || !$dispositivo) {
            log_message('debug', 'Usuario o dispositivo no encontrado');
            return redirect()->to(base_url('registro-compra'))->with('error', 'Error al procesar la compra. Por favor, intenta de nuevo.');
        }

        log_message('debug', 'Datos del usuario antes de activación: ' . json_encode([
            'id' => $usuario['id_usuario'],
            'email' => $usuario['email'],
            'estado' => $usuario['estado'],
            'rol' => $usuario['id_rol']
        ]));

        // Activar cuenta automáticamente
        $this->usuarioModel->update($idUsuario, [
            'estado' => 'activo',
            'token_activacion' => null
        ]);

        log_message('debug', 'Usuario activado correctamente');

        // Actualizar estado de la compra
        $this->compraModel->where('id_usuario', $idUsuario)
                         ->set(['estado' => 'completada'])
                         ->update();

        log_message('debug', 'Compra marcada como completada');

        // Enviar email de confirmación de compra
        $this->enviarEmailConfirmacionCompra($usuario['email'], $usuario['nombre'], $dispositivo);

        // Preparar datos para la vista
        $data = [
            'nombre' => $usuario['nombre'],
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y'),
            'direccion' => session()->get('datos_compra')['direccion']
        ];

        // Limpiar sesión
        session()->remove(['id_usuario_registro', 'token_activacion', 'id_dispositivo', 'datos_compra']);

        log_message('debug', 'Redirigiendo a página de pago exitoso');

        // Mostrar página de pago exitoso
        return view('registro_compra/pago_exitoso', $data);
    }

    public function error()
    {
        return view('registro_compra/error', [
            'mensaje' => 'Ha ocurrido un error al procesar tu pago. Por favor, intenta de nuevo.'
        ]);
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
        $emailService->setFrom('noreply@ecomonitor.com', 'EcoVolt');
        $emailService->setSubject('Confirmación de Compra - EcoVolt Pro');
        
        $mensaje = view('emails/confirmacion_compra', [
            'nombre' => $nombre,
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y'),
            'direccion' => session()->get('datos_compra')['direccion'],
            'precio' => number_format($dispositivo['precio'], 2)
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