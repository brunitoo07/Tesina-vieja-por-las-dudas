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
    // 1. Validación básica del JSON
    $inputData = $this->request->getJSON();
    if (!$inputData || !isset($inputData->mac_address)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Formato JSON inválido o falta MAC address'
        ]);
    }

    // 2. Obtener dispositivo
    $dispositivoModel = new DispositivoModel();
    $dispositivo = $dispositivoModel->where('mac_address', $inputData->mac_address)->first();
    
    if (!$dispositivo) {
        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Dispositivo no registrado'
        ]);
    }

    // 3. Validar campos numéricos
    $camposRequeridos = [
        'voltaje' => 'float',
        'corriente' => 'float', 
        'potencia' => 'float',
        'kwh' => 'float'
    ];
    
    foreach ($camposRequeridos as $campo => $tipo) {
        if (!isset($inputData->$campo)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => "Campo {$campo} es requerido"
            ]);
        }
        
        if (!is_numeric($inputData->$campo)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => "Campo {$campo} debe ser numérico"
            ]);
        }
        // Ver los datos que se intentarán insertar
log_message('debug', print_r($data, true));

// Ver la consulta SQL generada
log_message('debug', $this->energiaModel->getLastQuery());
    }

    // 4. Preparar datos con tipos correctos
    $data = [
        'id_dispositivo' => (int) $dispositivo['id'],
        'id_usuario' => (int) $dispositivo['id_usuario'],
        'voltaje' => (float) $inputData->voltaje,
        'corriente' => (float) $inputData->corriente,
        'potencia' => (float) $inputData->potencia,
        'kwh' => (float) $inputData->kwh,
        'fecha' => date('Y-m-d H:i:s')
    ];

    // 5. Log para depuración (opcional pero recomendado)
    log_message('debug', 'Insertando en energía: '.print_r($data, true));

    // 6. Insertar con manejo de errores
    try {
        $this->energiaModel->insert($data);
        return $this->response->setJSON(['status' => 'success']);
    } catch (\Exception $e) {
        log_message('error', 'Error en insert: '.$e->getMessage());
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Error en base de datos',
            'error_detail' => $e->getMessage() // Solo en desarrollo
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
