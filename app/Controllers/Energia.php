<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EnergiaModel;
use App\Models\DispositivoModel;
use App\Models\LimiteConsumoModel;

class Energia extends ResourceController
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
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->fail('No se recibieron datos válidos.', 400);
        }

        $data = [
            'mac_address' => $json->mac_address ?? null,
            'voltaje' => $json->voltaje ?? null,
            'corriente' => $json->corriente ?? null,
            'potencia' => $json->potencia ?? null,
            'kwh' => $json->kwh ?? null,
            'fecha' => date('Y-m-d H:i:s'),
        ];

        $model = new EnergiaModel();

        if ($model->insert($data)) {
            return $this->respond(['message' => 'Datos almacenados correctamente.'], 200);
        } else {
            return $this->fail('Error al guardar los datos.', 500);
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
