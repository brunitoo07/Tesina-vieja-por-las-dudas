<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;
use App\Models\EnergiaModel;

class Consumo extends BaseController
{
    protected $dispositivoModel;
    protected $energiaModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
        $this->energiaModel = new EnergiaModel();
    }

    public function verDatos($id)
    {
        log_message('debug', 'Accediendo a verDatos con ID: ' . $id);
        
        $dispositivo = $this->dispositivoModel->find($id);
        log_message('debug', 'Datos del dispositivo: ' . print_r($dispositivo, true));
        
        if (!$dispositivo) {
            log_message('error', 'Dispositivo no encontrado con ID: ' . $id);
            return redirect()->back()->with('error', 'Dispositivo no encontrado');
        }

        $lecturas = $this->energiaModel->where('id_dispositivo', $id)
                                      ->orderBy('fecha', 'DESC')
                                      ->findAll();
        log_message('debug', 'Número de lecturas encontradas: ' . count($lecturas));
        log_message('debug', 'Primera lectura: ' . print_r(!empty($lecturas) ? $lecturas[0] : 'No hay lecturas', true));

        $data = [
            'dispositivo' => $dispositivo,
            'lecturas' => $lecturas
        ];
        log_message('debug', 'Datos enviados a la vista: ' . print_r($data, true));

        return view('consumo/ver_datos', $data);
    }

    public function grafico($id)
    {
        log_message('debug', 'Accediendo a grafico con ID: ' . $id);
        
        $lecturas = $this->energiaModel->where('id_dispositivo', $id)
                                      ->orderBy('fecha', 'ASC')
                                      ->findAll();
        log_message('debug', 'Número de lecturas para gráfico: ' . count($lecturas));

        $datos = [
            'labels' => [],
            'consumo' => []
        ];

        foreach ($lecturas as $lectura) {
            $datos['labels'][] = date('d/m H:i', strtotime($lectura['fecha']));
            $datos['consumo'][] = $lectura['kwh'];
        }

        log_message('debug', 'Datos del gráfico: ' . print_r($datos, true));
        return $this->response->setJSON($datos);
    }
} 