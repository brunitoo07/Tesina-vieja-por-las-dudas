<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;

class Dashboard extends BaseController
{
    protected $dispositivoModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
    }

    public function index()
    {
        // Obtener los últimos 5 dispositivos registrados
        $data['ultimosDispositivos'] = $this->dispositivoModel->orderBy('created_at', 'DESC')
                                                             ->limit(5)
                                                             ->find();

        return view('admin/dashboard', $data);
    }
} 