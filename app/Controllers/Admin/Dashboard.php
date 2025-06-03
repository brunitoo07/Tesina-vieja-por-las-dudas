<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;
use App\Models\UsuarioModel;

class Dashboard extends BaseController
{
    protected $dispositivoModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        // Obtener el ID del usuario actual
        $idUsuario = session()->get('id_usuario');
        
        // Obtener los últimos 5 dispositivos registrados del administrador actual
        $data['ultimosDispositivos'] = $this->dispositivoModel->where('id_usuario', $idUsuario)
                                                             ->orderBy('created_at', 'DESC')
                                                             ->limit(5)
                                                             ->find();

        return view('admin/dashboard', $data);
    }
} 