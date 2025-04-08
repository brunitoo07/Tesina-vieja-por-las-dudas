<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;

class Compra extends BaseController
{
    protected $usuarioModel;
    protected $rolesModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolesModel = new RolesModel();
    }

    public function index()
    {
        // Agregamos un mensaje de depuración
        log_message('debug', 'Accediendo al método index del controlador Compra');
        
        try {
            return view('compra/index');
        } catch (\Exception $e) {
            log_message('error', 'Error al cargar la vista: ' . $e->getMessage());
            return 'Error al cargar la vista: ' . $e->getMessage();
        }
    }

    public function simularCompra()
    {
        // Simulamos una compra exitosa
        session()->set('compra_exitosa', true);
        return redirect()->to('compra/completada');
    }

    public function completada()
    {
        if (!session()->get('compra_exitosa')) {
            return redirect()->to('compra');
        }

        // Limpiamos la sesión de compra
        session()->remove('compra_exitosa');

        $data = [
            'mensaje' => '¡Compra completada con éxito!',
            'siguiente_paso' => 'Por favor, regístrate para acceder a tu panel de administración.'
        ];

        return redirect()->to('autenticacion/register?purchase=true');
     }

    public function procesarPago()
    {
        // Simulamos una compra exitosa
        session()->set('compra_exitosa', true);
        return redirect()->to('compra/completada');
    }
} 