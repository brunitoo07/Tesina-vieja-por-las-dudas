<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\CompraModel;
use App\Models\DispositivoModel;

class CompraExistente extends BaseController
{
    protected $usuarioModel;
    protected $compraModel;
    protected $dispositivoModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->compraModel = new CompraModel();
        $this->dispositivoModel = new DispositivoModel();
    }

    public function index()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('login-compra-adicional')->with('error', 'Debes iniciar sesión para comprar un dispositivo adicional.');
        }

        $idUsuario = session()->get('id_usuario');
        
        // Obtener datos del usuario
        $usuario = $this->usuarioModel->find($idUsuario);
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Usuario no encontrado.');
        }

        // Contar dispositivos del usuario
        $dispositivos_count = $this->dispositivoModel->where('id_usuario', $idUsuario)->countAllResults();

        $data = [
            'usuario' => $usuario,
            'dispositivos_count' => $dispositivos_count
        ];

        return view('compra/usuario_existente', $data);
    }

    public function procesarCompra()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('login-compra-adicional')->with('error', 'Debes iniciar sesión para comprar un dispositivo adicional.');
        }

        $idUsuario = session()->get('id_usuario');
        $idDispositivo = $this->request->getPost('id_dispositivo');

        // Validar que se seleccionó un dispositivo
        if (!$idDispositivo) {
            return redirect()->back()->with('error', 'Debes seleccionar un dispositivo.');
        }

        // Verificar disponibilidad del dispositivo
        $dispositivo = $this->dispositivoModel->find($idDispositivo);
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'El dispositivo seleccionado no existe.');
        }

        // Guardar datos en sesión para el proceso de pago
        $datosCompra = [
            'id_usuario' => $idUsuario,
            'id_dispositivo' => $idDispositivo,
            'tipo_compra' => 'usuario_existente'
        ];

        session()->set('datos_compra_existente', $datosCompra);

        return redirect()->to('compra-existente/pago');
    }

    public function pago()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('login-compra-adicional')->with('error', 'Debes iniciar sesión para comprar un dispositivo adicional.');
        }

        $datosCompra = session()->get('datos_compra_existente');
        if (!$datosCompra) {
            return redirect()->to('compra-existente')->with('error', 'Sesión expirada. Intenta de nuevo.');
        }

        $idUsuario = $datosCompra['id_usuario'];
        $idDispositivo = $datosCompra['id_dispositivo'];

        // Obtener datos del usuario y dispositivo
        $usuario = $this->usuarioModel->find($idUsuario);
        $dispositivo = $this->dispositivoModel->find($idDispositivo);

        if (!$usuario || !$dispositivo) {
            return redirect()->to('compra-existente')->with('error', 'Error al obtener los datos. Intenta de nuevo.');
        }

        $data = [
            'usuario' => $usuario,
            'dispositivo' => $dispositivo,
            'datos_compra' => $datosCompra
        ];

        return view('compra/pago_existente', $data);
    }

    public function procesarPago()
    {
        try {
            log_message('debug', '=== INICIO PROCESAR PAGO USUARIO EXISTENTE ===');
            
            // Verificar que el usuario esté logueado
            if (!session()->get('logged_in')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para comprar un dispositivo adicional.'
                ]);
            }

            // Obtener datos del pago de PayPal
            $paymentData = $this->request->getJSON();
            log_message('debug', 'Payment data recibido: ' . json_encode($paymentData));
            
            if (!$paymentData) {
                log_message('error', 'No se recibieron datos de pago');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se recibieron datos de pago'
                ]);
            }

            // Obtener datos de la sesión
            $datosCompra = session()->get('datos_compra_existente');
            
            log_message('debug', 'Datos de sesión: ' . json_encode($datosCompra));

            if (!$datosCompra) {
                log_message('error', 'Sesión expirada');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sesión expirada. Por favor, intenta de nuevo.'
                ]);
            }

            $idUsuario = $datosCompra['id_usuario'];
            $idDispositivo = $datosCompra['id_dispositivo'];

            // Verificar stock nuevamente antes de procesar el pago
            $dispositivo = $this->dispositivoModel->find($idDispositivo);
            if (!$dispositivo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Lo sentimos, el dispositivo no existe.'
                ]);
            }

            // Obtener datos del usuario
            $usuario = $this->usuarioModel->find($idUsuario);
            if (!$usuario) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ]);
            }

            // Crear la compra
            $compraData = [
                'id_usuario' => $idUsuario,
                'id_dispositivo' => $idDispositivo,
                'direccion_envio' => 'Dirección del usuario existente', // Puedes obtener la dirección del usuario
                'estado' => 'completada',
                'fecha_compra' => date('Y-m-d H:i:s'),
                'payment_id' => $paymentData->id
            ];

            log_message('debug', 'Creando compra: ' . json_encode($compraData));
            
            $this->compraModel->insert($compraData);

            // Actualizar el estado del dispositivo a activo
            $this->dispositivoModel->update($idDispositivo, [
                'estado' => 'activo',
                'stock' => $dispositivo['stock'] - 1
            ]);

            // Enviar email de confirmación de compra
            $this->enviarEmailConfirmacionCompra($usuario['email'], $usuario['nombre'], $dispositivo, $paymentData->id);

            // Marcar la compra como exitosa en la sesión
            session()->set('compra_exitosa_existente', true);
            session()->set('payment_id_existente', $paymentData->id);
            
            log_message('debug', 'Compra procesada exitosamente, redirigiendo...');

            return $this->response->setJSON([
                'success' => true,
                'redirect' => base_url('compra-existente/pago-exitoso')
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en procesarPago usuario existente: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ha ocurrido un error al procesar el pago. Por favor, inténtalo de nuevo.'
            ]);
        }
    }

    public function pagoExitoso()
    {
        if (!session()->get('compra_exitosa_existente')) {
            return redirect()->to('compra-existente')->with('error', 'Sesión expirada. Intenta de nuevo.');
        }

        // Obtener datos de la sesión antes de limpiarla
        $datosCompra = session()->get('datos_compra_existente');
        
        // Obtener información del dispositivo y usuario
        $dispositivo = $this->dispositivoModel->find($datosCompra['id_dispositivo']);
        $usuario = $this->usuarioModel->find($datosCompra['id_usuario']);
        
        // Preparar datos para la vista
        $data = [
            'nombre' => $usuario['nombre'],
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y'),
            'direccion' => 'Dirección del usuario existente'
        ];

        // Limpiar la sesión después de obtener los datos
        session()->remove(['datos_compra_existente', 'compra_exitosa_existente', 'payment_id_existente']);

        return view('compra/pago_exitoso_existente', $data);
    }

    private function enviarEmailConfirmacionCompra($emailDestino, $nombre, $dispositivo, $paymentId)
    {
        $emailService = \Config\Services::email();

        $emailService->setFrom('noreply@ecomonitor.com', 'EcoVolt');
        $emailService->setTo($emailDestino);
        $emailService->setSubject('Confirmación de Compra Adicional - EcoVolt Pro');

        $mensaje = view('emails/confirmacion_compra_existente', [
            'nombre' => $nombre,
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y H:i:s'),
            'direccion' => 'Dirección del usuario existente',
            'precio' => '150.00',
            'email' => $emailDestino,
            'payment_id' => $paymentId,
            'numero_pedido' => 'ECO-' . date('Ymd') . '-' . substr($paymentId, -6)
        ]);

        $emailService->setMessage($mensaje);

        if (!$emailService->send()) {
            log_message('error', 'Error al enviar email de confirmación de compra adicional: ' . $emailService->printDebugger(['headers']));
            return false;
        }

        return true;
    }
}
