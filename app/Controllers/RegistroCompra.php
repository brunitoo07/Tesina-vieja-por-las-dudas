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

        // NO CREAR USUARIO AÚN - Solo guardar datos en sesión para después del pago
        $datosCompra = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contrasena' => $contrasena,
            'calle' => $calle,
            'numero' => $numero,
            'ciudad' => $ciudad,
            'codigo_postal' => $codigo_postal,
            'pais' => $pais,
            'direccion' => $calle . ' ' . $numero . ', ' . $ciudad . ', ' . $codigo_postal . ', ' . $pais
        ];

        session()->set('datos_compra', $datosCompra);
        session()->set('id_dispositivo', $id_dispositivo);

        log_message('debug', 'Datos guardados en sesión: ' . json_encode([
            'id_dispositivo' => $id_dispositivo,
            'email' => $email
        ]));

        log_message('debug', '=== FIN PROCESO DE COMPRA ===');

        return redirect()->to('compra');
    }

    public function pagoExitoso()
    {
        if (!session()->get('compra_exitosa')) {
            return redirect()->to('registro-compra')->with('error', 'Sesión expirada. Intenta de nuevo.');
        }

        // Obtener datos de la sesión antes de limpiarla
        $datosCompra = session()->get('datos_compra');
        $idDispositivo = session()->get('id_dispositivo');
        
        // Obtener información del dispositivo
        $dispositivo = $this->dispositivoModel->find($idDispositivo);
        
        // Preparar datos para la vista
        $data = [
            'nombre' => $datosCompra['nombre'] ?? 'Usuario',
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y'),
            'direccion' => $datosCompra['direccion'] ?? 'No especificada'
        ];

        // Limpiar la sesión después de obtener los datos
        session()->remove(['id_usuario_registro', 'id_dispositivo', 'datos_compra', 'compra_exitosa', 'payment_id']);

        return view('registro_compra/pago_exitoso', $data);
    }

    public function error()
    {
        return view('registro_compra/error', [
            'mensaje' => 'Ha ocurrido un error al procesar tu pago. Por favor, intenta de nuevo.'
        ]);
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
}