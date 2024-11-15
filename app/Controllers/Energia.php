<?php

namespace App\Controllers;

use App\Models\EnergiaModel;

class Energia extends BaseController
{
    public function __construct()
    {
        $this->energiaModel = new EnergiaModel();
    }

    // Recibe los datos del ESP32 y los inserta en la base de datos
    public function recibirDatos()
    {
        $inputData = $this->request->getJSON();

        // Datos que recibimos del ESP32
        $data = [
            'voltaje' => $inputData->voltaje,
            'corriente' => $inputData->corriente,
            'potencia' => $inputData->potencia,
            'kwh' => $inputData->kwh,
            'fecha' => date('Y-m-d H:i:s'),
        ];

        // Insertamos los datos en la base de datos
        $this->energiaModel->insertData($data);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function getLatestData()
    {
        // Obtener solo el último registro de consumo
        $ultimoDato = $this->energiaModel->getLatestData();
    
        // Si hay datos, devolverlos como JSON
        if (!empty($ultimoDato)) {
            echo json_encode($ultimoDato[0]);
        } else {
            echo json_encode([]);
        }
    }

    public function index()
    {
        // Configurar zona horaria de Argentina
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    
        // Obtener fecha y hora actual
        $fechaActual = date('Y-m-d H:i:s'); // Formato: Año-Mes-Día Hora:Minuto:Segundo

        // Obtener el parámetro de orden y la dirección de la URL, con valores predeterminados
        $direction = $this->request->getGet('direction') ?? 'DESC';

        // Obtener todos los registros de consumo en el orden seleccionado
        $data['energia'] = $this->energiaModel->orderBy('id', $direction)->findAll();

        // Pasar la dirección actual para la vista
        $data['direction'] = $direction;

        // Obtener solo el último registro (para los datos en tiempo real)
        $data['ultimoDato'] = $this->energiaModel->getLatestData();

        // Obtener el límite de consumo y el consumo diario
        $limite_consumo = $this->energiaModel->getLimiteConsumo();
        $consumo_diario = $this->energiaModel->getConsumoDiario();

        // Verificar si el consumo diario supera el límite
        // Configurar advertencia si se supera el límite
    $advertencia = null;
    if ($consumo_diario > $limite_consumo) {
        $advertencia = "¡Advertencia! Has superado el límite de consumo diario de $limite_consumo kWh.";
        }

        // Pasar los datos a la vista
        $data['fechaActual'] = $fechaActual;
        $data['limite_consumo'] = $limite_consumo;
        $data['consumo_diario'] = $consumo_diario;
        $data['advertencia'] = $advertencia;

        return view('consumo', $data);
    }
    

    public function actualizarLimite()
    {
        // Obtener el nuevo límite de consumo desde el formulario
        $nuevo_limite = $this->request->getPost('nuevo_limite');

        // Actualizar el límite de consumo en la base de datos
        $this->energiaModel->actualizarLimiteConsumo($nuevo_limite);

        // Redirigir después de actualizar el límite
        return redirect()->to('/energia');
    }
    
}
