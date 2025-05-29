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
        // Obtener lista de dispositivos disponibles
        $dispositivos = $this->dispositivoModel->findAll();
        
        $data = [
            'dispositivos' => $dispositivos
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
        // Obtener datos del pago de PayPal
        $paymentData = $this->request->getJSON();
        
        if (!$paymentData) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se recibieron datos de pago'
            ]);
        }

        // Guardar información de la compra en sesión
        session()->set([
            'compra_exitosa' => true,
            'payment_id' => $paymentData->id,
            'payment_status' => $paymentData->status,
            'payment_amount' => $paymentData->amount->total,
            'selected_device' => $paymentData->purchase_units[0]->items[0]->name
        ]);

        return $this->response->setJSON([
            'success' => true,
            'redirect' => base_url('registro-compra')
        ]);
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
} 