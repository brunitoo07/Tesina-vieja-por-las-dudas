<?php

namespace App\Controllers;

use App\Models\EnergiaModel;
use App\Models\DispositivoModel;
use App\Models\LimiteConsumoModel;

class Energia extends BaseController
{
    protected $energiaModel;
    protected $limiteModel;

    public function __construct()
    {
        $this->energiaModel = new EnergiaModel();
        $this->limiteModel = new LimiteConsumoModel();
    }

    public function recibirDatos()
    {
        $json = file_get_contents('php://input');
        log_message('debug', '📩 JSON recibido: ' . $json);
        
        $inputData = $this->request->getJSON();
        if (!$inputData || !isset($inputData->mac_address)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Formato JSON inválido o falta MAC address'
            ]);
        }
    
        $energiaModel = new EnergiaModel();
        $dispositivo = $dispositivoModel->where('mac_address', $inputData->mac_address)->first();
        
        if (!$dispositivo) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Dispositivo no registrado'
            ]);
        }
    
        $data = [
            'id_dispositivo' => (int) $dispositivo['id'],
            'id_usuario' => (int) $dispositivo['id_usuario'],
            'voltaje' => isset($inputData->voltaje) ? (float) $inputData->voltaje : null,
            'corriente' => isset($inputData->corriente) ? (float) $inputData->corriente : null,
            'potencia' => isset($inputData->potencia) ? (float) $inputData->potencia : null,
            'kwh' => (float) $inputData->kwh,
            'fecha' => date('Y-m-d H:i:s')
        ];
    
        log_message('debug', 'Datos preparados: ' . print_r($data, true));
    
        try {
            $insertId = $this->energiaModel->insert($data);
            log_message('debug', 'Insert realizado. ID: ' . $insertId);
            
            return $this->response->setJSON([
                'status' => 'success',
                'inserted_id' => $insertId
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al insertar: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error en base de datos',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getLatestData()
    {
        $ultimoDato = $this->energiaModel->getLatestData();
        echo json_encode(!empty($ultimoDato) ? $ultimoDato[0] : []);
    }

    public function index()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaActual = date('Y-m-d H:i:s'); 

        $direction = $this->request->getGet('direction') ?? 'DESC';
        $id_usuario = session()->get('id_usuario');

        $data['energia'] = $this->energiaModel->where('id_usuario', $id_usuario)
                                               ->orderBy('id', $direction)
                                               ->findAll();

        $data['direction'] = $direction;

        $data['ultimoDato'] = $this->energiaModel->where('id_usuario', $id_usuario)
                                                 ->orderBy('id', 'DESC')
                                                 ->first();

        // Limite de consumo desde la tabla nueva
        $limiteData = $this->limiteModel->getLimite($id_usuario);
        $limite_consumo = $limiteData ? $limiteData['limite_consumo'] : 10;
        $data['limite_consumo'] = $limite_consumo;

        // Consumo diario
        $data['consumo_diario'] = $this->energiaModel->getConsumoDiario($id_usuario);

        $advertencia = null;
        if ($data['consumo_diario'] > $limite_consumo) {
            $advertencia = "¡Advertencia! Has superado el límite de consumo diario de $limite_consumo kWh.";
        }

        $data['advertencia'] = $advertencia;
        $data['fechaActual'] = $fechaActual;

        return view('consumo', $data);
    }

    public function actualizarLimite()
    {
        $id_usuario = session()->get('id_usuario');
        $nuevo_limite = $this->request->getPost('nuevo_limite');
        
        $this->limiteModel->setLimite($id_usuario, $nuevo_limite);

        return redirect()->to('/energia');
    }

    public function verDatos($id)
    {
        $data['energia'] = $this->energiaModel->find($id);
        if (!$data['energia']) {
            return redirect()->to('/energia')->with('error', 'Registro no encontrado');
        }
        return view('energia/ver_datos', $data);
    }
}
