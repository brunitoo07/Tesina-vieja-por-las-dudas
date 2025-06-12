<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;
use App\Models\CompraModel;
use App\Models\DispositivoModel;

class Compra extends BaseController
{
    protected $usuarioModel;
    protected $rolesModel;
    protected $compraModel;
    protected $dispositivoModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolesModel = new RolesModel();
        $this->compraModel = new CompraModel();
        $this->dispositivoModel = new DispositivoModel();
    }

    public function index()
    {
        // Verificar si hay datos de registro en sesión
        if (!session()->has('datos_compra')) {
            return redirect()->to('registro-compra');
        }

        // Obtener datos de la sesión
        $datosCompra = session()->get('datos_compra');
        $idDispositivo = session()->get('id_dispositivo');
        
        // Obtener información del dispositivo
        $dispositivo = $this->dispositivoModel->find($idDispositivo);
        
        if (!$dispositivo) {
            return redirect()->to('registro-compra')->with('error', 'Dispositivo no encontrado.');
        }
        
        $data = [
            'dispositivo' => $dispositivo,
            'datos_compra' => $datosCompra
        ];
        
        return view('compra/index', $data);
    }

    public function simularCompra()
    {
        // Simulamos una compra exitosa
        session()->set('compra_exitosa', true);
        return redirect()->to('compra/completada');
    }

    public function procesarPago()
    {
        try {
            // Obtener datos del pago de PayPal
            $paymentData = $this->request->getJSON();
            
            if (!$paymentData) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se recibieron datos de pago'
                ]);
            }

            // Obtener datos de la sesión
            $idUsuario = session()->get('id_usuario_registro');
            $idDispositivo = session()->get('id_dispositivo');
            $datosCompra = session()->get('datos_compra');

            if (!$idUsuario || !$idDispositivo || !$datosCompra) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sesión expirada. Por favor, intenta de nuevo.'
                ]);
            }

            // Verificar stock nuevamente antes de procesar el pago
            $dispositivo = $this->dispositivoModel->find($idDispositivo);
            if (!$dispositivo) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Lo sentimos, el dispositivo no existe.'
                ]);
            }

            // Crear la compra
            $compraData = [
                'id_usuario' => $idUsuario,
                'id_dispositivo' => $idDispositivo,
                'direccion_envio' => $datosCompra['direccion'],
                'estado' => 'completada',
                'fecha_compra' => date('Y-m-d H:i:s'),
                'payment_id' => $paymentData->id
            ];

            if (!$this->compraModel->insert($compraData)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al registrar la compra'
                ]);
            }

            // Actualizar el estado del dispositivo a activo
            $this->dispositivoModel->update($idDispositivo, ['estado' => 'activo']);

            // Guardar datos necesarios en sesión antes de limpiar
            $email = $datosCompra['email'];
            $nombre = $datosCompra['nombre'];

            // Enviar email de confirmación de compra
            $this->enviarEmailConfirmacionCompra($email, $nombre, $dispositivo);

            // Marcar la compra como exitosa en la sesión
            session()->set('compra_exitosa', true);
            session()->set('payment_id', $paymentData->id);

            return $this->response->setJSON([
                'success' => true,
                'redirect' => base_url('registro-compra/pago-exitoso')
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en procesarPago: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ha ocurrido un error al procesar el pago. Por favor, inténtalo de nuevo.'
            ]);
        }
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

    private function enviarEmailConfirmacionCompra($emailDestino, $nombre, $dispositivo)
    {
        $emailService = \Config\Services::email();

        $emailService->setFrom('noreply@ecomonitor.com', 'EcoVolt');
        $emailService->setTo($emailDestino);
        $emailService->setSubject('Confirmación de Compra - EcoVolt Pro');

        $mensaje = view('emails/confirmacion_compra', [
            'nombre' => $nombre,
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y'),
            'direccion' => session()->get('datos_compra')['direccion'],
            'precio' => number_format($dispositivo['precio'], 2)
        ]);

        $emailService->setMessage($mensaje);

        if (!$emailService->send()) {
            log_message('error', 'Error al enviar email de confirmación de compra: ' . $emailService->printDebugger(['headers']));
            return false;
        }

        return true;
    }

    public function completada()
    {
        if (!session()->get('compra_exitosa')) {
            return redirect()->to('compra');
        }

        // Obtener datos de la compra de la sesión
        $paymentData = [
            'payment_id' => session()->get('payment_id'),
            'payment_status' => session()->get('payment_status'),
            'payment_amount' => session()->get('payment_amount'),
            'selected_device' => session()->get('selected_device')
        ];

        // Limpiar la sesión de compra
        session()->remove(['compra_exitosa', 'payment_id', 'payment_status', 'payment_amount', 'selected_device']);

        $data = [
            'mensaje' => '¡Compra completada con éxito!',
            'payment_data' => $paymentData,
            'siguiente_paso' => 'Por favor, regístrate para acceder a tu panel de administración.'
        ];

        return redirect()->to('registro-compra');
    }

    public function pagoExitoso()
    {
        if (!session()->get('compra_exitosa')) {
            return redirect()->to('registro-compra')->with('error', 'Sesión expirada. Intenta de nuevo.');
        }

        // Limpiar la sesión después de mostrar la página de éxito
        session()->remove(['id_usuario_registro', 'token_activacion', 'id_dispositivo', 'datos_compra', 'compra_exitosa', 'payment_id']);

        return view('registro_compra/pago_exitoso');
    }
} 