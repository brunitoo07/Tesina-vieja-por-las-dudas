<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;
use App\Models\CompraModel;
use App\Models\DispositivoModel;
use App\Models\DireccionModel;

class Compra extends BaseController
{
    protected $usuarioModel;
    protected $rolesModel;
    protected $compraModel;
    protected $dispositivoModel;
    protected $direccionModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolesModel = new RolesModel();
        $this->compraModel = new CompraModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->direccionModel = new DireccionModel();
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
            log_message('debug', '=== INICIO PROCESAR PAGO ===');
            
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
            $idDispositivo = session()->get('id_dispositivo');
            $datosCompra = session()->get('datos_compra');
            
            log_message('debug', 'Datos de sesión - idDispositivo: ' . $idDispositivo);
            log_message('debug', 'Datos de sesión - datosCompra: ' . json_encode($datosCompra));

            if (!$idDispositivo || !$datosCompra) {
                log_message('error', 'Sesión expirada - idDispositivo: ' . $idDispositivo . ', datosCompra: ' . (empty($datosCompra) ? 'vacío' : 'presente'));
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

            // CREAR USUARIO DESPUÉS DEL PAGO EXITOSO
            log_message('debug', 'Creando usuario con datos: ' . json_encode($datosCompra));
            
            $usuarioData = [
                'nombre' => $datosCompra['nombre'],
                'apellido' => $datosCompra['apellido'],
                'email' => $datosCompra['email'],
                'contrasena' => $datosCompra['contrasena'],
                'id_rol' => 1, // admin
                'estado' => 'activo', // Activo directamente después del pago
            ];

            log_message('debug', 'Datos de usuario a insertar: ' . json_encode($usuarioData));
            
            $this->usuarioModel->insert($usuarioData);
            $idUsuario = $this->usuarioModel->getInsertID();
            
            log_message('debug', 'Usuario creado con ID: ' . $idUsuario);

            // Crear dirección y asociar al usuario
            $direccionData = [
                'calle' => $datosCompra['calle'],
                'numero' => $datosCompra['numero'],
                'ciudad' => $datosCompra['ciudad'],
                'codigo_postal' => $datosCompra['codigo_postal'],
                'pais' => $datosCompra['pais'],
                'id_usuario' => $idUsuario
            ];
            $this->direccionModel->insert($direccionData);

            // Crear la compra
            $compraData = [
                'id_usuario' => $idUsuario,
                'id_dispositivo' => $idDispositivo,
                'direccion_envio' => $datosCompra['direccion'],
                'estado' => 'completada',
                'fecha_compra' => date('Y-m-d H:i:s'),
                'payment_id' => $paymentData->id
            ];

            $this->compraModel->insert($compraData);

            // Actualizar el estado del dispositivo a activo
            $this->dispositivoModel->update($idDispositivo, [
                'estado' => 'activo',
                'stock' => $dispositivo['stock'] - 1
            ]);

            // Enviar email de confirmación de compra
            log_message('debug', 'Enviando email a: ' . $datosCompra['email']);
            $this->enviarEmailConfirmacionCompra($datosCompra['email'], $datosCompra['nombre'], $dispositivo, $paymentData->id);
            log_message('debug', 'Email enviado correctamente');

            // Marcar la compra como exitosa en la sesión
            session()->set('compra_exitosa', true);
            session()->set('payment_id', $paymentData->id);
            
            log_message('debug', 'Compra procesada exitosamente, redirigiendo...');

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


    private function enviarEmailConfirmacionCompra($emailDestino, $nombre, $dispositivo, $paymentId)
    {
        $emailService = \Config\Services::email();

        $emailService->setFrom('noreply@ecomonitor.com', 'EcoVolt');
        $emailService->setTo($emailDestino);
        $emailService->setSubject('Confirmación de Compra - EcoVolt Pro');

        $datosCompra = session()->get('datos_compra');
        
        $mensaje = view('emails/confirmacion_compra', [
            'nombre' => $nombre,
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y H:i:s'),
            'direccion' => $datosCompra['direccion'],
            'precio' => number_format($dispositivo['precio'], 2),
            'email' => $emailDestino,
            'payment_id' => $paymentId,
            'numero_pedido' => 'ECO-' . date('Ymd') . '-' . substr($paymentId, -6)
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