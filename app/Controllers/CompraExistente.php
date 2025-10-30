<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\CompraModel;
use App\Models\DispositivoModel;
use App\Models\DireccionModel;

class CompraExistente extends BaseController
{
    protected $usuarioModel;
    protected $compraModel;
    protected $dispositivoModel;
    protected $direccionModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->compraModel = new CompraModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->direccionModel = new DireccionModel();
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

        // Obtener dirección del usuario
        $direccion = $this->direccionModel->where('id_usuario', $idUsuario)->first();

        // Contar dispositivos del usuario
        $dispositivos_count = $this->dispositivoModel->where('id_usuario', $idUsuario)->countAllResults();

        $data = [
            'usuario' => $usuario,
            'direccion' => $direccion,
            'dispositivos_count' => $dispositivos_count
        ];

        return view('compra/usuario_existente', $data);
    }

    public function guardarDireccion()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Debes iniciar sesión para actualizar tu dirección.'
            ]);
        }

        $idUsuario = session()->get('id_usuario');

        // Validar los datos del formulario
        $validation = \Config\Services::validation();
        $validation->setRules([
            'calle' => 'required|min_length[3]|max_length[100]',
            'numero' => 'required|max_length[10]',
            'ciudad' => 'required|min_length[3]|max_length[50]',
            'codigo_postal' => 'required|max_length[10]',
            'pais' => 'required|min_length[3]|max_length[50]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        // Preparar datos de la dirección
        $direccionData = [
            'calle' => $this->request->getPost('calle'),
            'numero' => $this->request->getPost('numero'),
            'ciudad' => $this->request->getPost('ciudad'),
            'codigo_postal' => $this->request->getPost('codigo_postal'),
            'pais' => $this->request->getPost('pais'),
            'id_usuario' => $idUsuario
        ];

        // Verificar si ya existe una dirección para actualizar o crear nueva
        $direccionExistente = $this->direccionModel->where('id_usuario', $idUsuario)->first();
        
        try {
            if ($direccionExistente) {
                // Actualizar dirección existente
                $this->direccionModel->update($direccionExistente['direccion_id'], $direccionData);
                $message = 'Dirección actualizada correctamente.';
            } else {
                // Crear nueva dirección
                $this->direccionModel->insert($direccionData);
                $message = 'Dirección guardada correctamente.';
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al guardar dirección: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar la dirección. Por favor, intenta nuevamente.'
            ]);
        }
    }

    public function procesarCompra()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('login-compra-adicional')->with('error', 'Debes iniciar sesión para comprar un dispositivo adicional.');
        }

        $idUsuario = session()->get('id_usuario');
        $idDispositivo = $this->request->getPost('id_dispositivo');
        $confirmarDireccion = $this->request->getPost('confirmar_direccion');

        // Validar que se seleccionó un dispositivo
        if (!$idDispositivo) {
            return redirect()->back()->with('error', 'Debes seleccionar un dispositivo.');
        }

        // Verificar disponibilidad del dispositivo
        $dispositivo = $this->dispositivoModel->find($idDispositivo);
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'El dispositivo seleccionado no existe.');
        }

        // Si no confirma la dirección, mostrar error
        if ($confirmarDireccion !== 'si') {
            return redirect()->back()->with('error', 'Debes confirmar que la dirección de envío es correcta.');
        }

        // Obtener la dirección actual del usuario
        $direccion = $this->direccionModel->where('id_usuario', $idUsuario)->first();
        if (!$direccion) {
            return redirect()->back()->with('error', 'No se encontró una dirección de envío. Por favor, actualiza tu dirección.');
        }

        // Guardar datos en sesión para el proceso de pago
        $datosCompra = [
            'id_usuario' => $idUsuario,
            'id_dispositivo' => $idDispositivo,
            'direccion_envio' => $this->formatearDireccion($direccion),
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

            // Obtener la dirección actual del usuario para la compra
            $direccion = $this->direccionModel->where('id_usuario', $idUsuario)->first();
            $direccionEnvio = $direccion ? $this->formatearDireccion($direccion) : 'Dirección del usuario existente';

            // Crear la compra
            $compraData = [
                'id_usuario' => $idUsuario,
                'id_dispositivo' => $idDispositivo,
                'direccion_envio' => $direccionEnvio,
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
            $this->enviarEmailConfirmacionCompra($usuario['email'], $usuario['nombre'], $dispositivo, $paymentData->id, $direccionEnvio);

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
        
        // Obtener la dirección del usuario
        $direccion = $this->direccionModel->where('id_usuario', $datosCompra['id_usuario'])->first();
        
        // Formatear la dirección para mostrar
        $direccionFormateada = 'Dirección no especificada';
        if ($direccion) {
            $direccionFormateada = $this->formatearDireccion($direccion);
        }
    
        // Preparar datos para la vista
        $data = [
            'nombre' => $usuario['nombre'],
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y'),
            'direccion' => $direccionFormateada,
            'direccionCompleta' => $direccion // Por si quieres mostrar los campos individualmente
        ];
    
        // Limpiar la sesión después de obtener los datos
        session()->remove(['datos_compra_existente', 'compra_exitosa_existente', 'payment_id_existente']);
    
        return view('compra/pago_exitoso_existente', $data);
    }

    private function formatearDireccion($direccion)
    {
        return $direccion['calle'] . ' ' . $direccion['numero'] . ', ' . 
               $direccion['ciudad'] . ', ' . $direccion['codigo_postal'] . ', ' . 
               $direccion['pais'];
    }
    
    private function enviarEmailConfirmacionCompra($emailDestino, $nombre, $dispositivo, $paymentId, $direccionEnvio)
    {
        $emailService = \Config\Services::email();

        $emailService->setFrom('noreply@ecomonitor.com', 'EcoVolt');
        $emailService->setTo($emailDestino);
        $emailService->setSubject('Confirmación de Compra Adicional - EcoVolt Pro');

        $mensaje = view('emails/confirmacion_compra_existente', [
            'nombre' => $nombre,
            'dispositivo' => $dispositivo,
            'fecha' => date('d/m/Y H:i:s'),
            'direccion' => $direccionEnvio,
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