<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;
use App\Models\LecturaModel;

class Consumo extends BaseController
{
    protected $dispositivoModel;
    protected $lecturaModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
        $this->lecturaModel = new LecturaModel();
    }

    public function verDatos($id)
    {
        $dispositivo = $this->dispositivoModel->find($id);
        
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'Dispositivo no encontrado');
        }

        $lecturas = $this->lecturaModel->where('id_dispositivo', $id)
                                      ->orderBy('fecha', 'DESC')
                                      ->findAll();

        return view('consumo/ver_datos', [
            'dispositivo' => $dispositivo,
            'lecturas' => $lecturas
        ]);
    }

    public function grafico($id)
    {
        $lecturas = $this->lecturaModel->where('id_dispositivo', $id)
                                      ->orderBy('fecha', 'ASC')
                                      ->findAll();

        $datos = [
            'labels' => [],
            'consumo' => []
        ];

        foreach ($lecturas as $lectura) {
            $datos['labels'][] = date('d/m H:i', strtotime($lectura['fecha']));
            $datos['consumo'][] = $lectura['consumo'];
        }

        return $this->response->setJSON($datos);
    }
} 