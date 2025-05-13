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
        helper('text');
        log_message('info', 'Solicitud recibida en recibirDatos');

        $json = $this->request->getJSON();
        log_message('debug', 'Datos recibidos: ' . print_r($json, true));

        if (!$json) {
            log_message('error', 'No se recibieron datos JSON válidos');
            return $this->fail('No se recibieron datos válidos.', 400);
        }

        // Validación de campos
        $requiredFields = ['voltaje', 'corriente', 'potencia', 'kwh', 'mac_address'];
        foreach ($requiredFields as $field) {
            if (!isset($json->$field)) {
                log_message('error', "Campo faltante: {$field}");
                return $this->fail("Campo requerido faltante: {$field}", 400);
            }
        }

        // Buscar dispositivo
        log_message('info', "Buscando dispositivo con MAC: {$json->mac_address}");
        $dispositivoModel = new \App\Models\DispositivoModel();
        $dispositivo = $dispositivoModel->where('mac_address', $json->mac_address)->first();

        if (!$dispositivo) {
            log_message('error', "Dispositivo no encontrado para MAC: {$json->mac_address}");
            return $this->fail('Dispositivo no encontrado.', 404);
        }
        log_message('info', "Dispositivo encontrado - ID: {$dispositivo['id_dispositivo']}");

        // Insertar en energia
        $energiaData = [
            'id_dispositivo' => $dispositivo['id_dispositivo'],
            'id_usuario' => $dispositivo['id_usuario'],
            'voltaje' => (float)$json->voltaje,
            'corriente' => (float)$json->corriente,
            'potencia' => (float)$json->potencia,
            'kwh' => (float)$json->kwh,
            'fecha' => date('Y-m-d H:i:s'),
            'mac_address' => $json->mac_address
        ];

        log_message('debug', 'Datos para insertar en energia: ' . print_r($energiaData, true));

        // ********************* INICIO del bloque try...catch *********************
        try {
            if (!$this->energiaModel->insert($energiaData)) {
                $errors = $this->energiaModel->errors();
                log_message('error', 'Error al insertar en energia: ' . print_r($errors, true));
                return $this->fail('Error al guardar datos de energía.', 500);
            }

            log_message('info', 'Datos insertados correctamente en energia');
            return $this->respond(['message' => 'Datos almacenados correctamente.'], 200);

        } catch (\Exception $e) {
            log_message('error', 'Excepción al insertar en energia: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return $this->fail('Error al guardar datos de energía (excepción).', 500);
        }
        // ********************** FIN del bloque try...catch **********************
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

    // NUEVA FUNCIÓN DE PRUEBA
    public function recibirNuevosDatos()
    {
        helper('text');
        log_message('info', 'Solicitud recibida en recibirNuevosDatos');

        $json = $this->request->getJSON();
        log_message('debug', 'Datos recibidos en nuevos_datos: ' . print_r($json, true));

        if (!$json) {
            log_message('error', 'No se recibieron datos JSON válidos en nuevos_datos');
            return $this->fail('No se recibieron datos válidos.', 400);
        }

        $requiredFields = ['voltaje', 'corriente', 'potencia', 'kwh', 'mac_address'];
        foreach ($requiredFields as $field) {
            if (!isset($json->$field)) {
                log_message('error', "Campo faltante en nuevos_datos: {$field}");
                return $this->fail("Campo requerido faltante: {$field}", 400);
            }
        }

        $dispositivoModel = new \App\Models\DispositivoModel();
        $dispositivo = $dispositivoModel->where('mac_address', $json->mac_address)->first();

        if (!$dispositivo) {
            log_message('error', "Dispositivo no encontrado para MAC en nuevos_datos: {$json->mac_address}");
            return $this->fail('Dispositivo no encontrado.', 404);
        }
        log_message('info', "Dispositivo encontrado - ID en nuevos_datos: {$dispositivo['id_dispositivo']}");

        $energiaData = [
            'id_dispositivo' => $dispositivo['id_dispositivo'],
            'id_usuario' => $dispositivo['id_usuario'],
            'voltaje' => (float)$json->voltaje,
            'corriente' => (float)$json->corriente,
            'potencia' => (float)$json->potencia,
            'kwh' => (float)$json->kwh,
            'fecha' => date('Y-m-d H:i:s'),
            'mac_address' => $json->mac_address
        ];

        log_message('debug', 'Datos para insertar en energia (nuevos_datos): ' . print_r($energiaData, true));

        if (!$this->energiaModel->insert($energiaData)) {
            $errors = $this->energiaModel->errors();
            log_message('error', 'Error al insertar en energia (nuevos_datos): ' . print_r($errors, true));
            return $this->fail('Error al guardar datos de energía.', 500);
        }

        log_message('info', 'Datos insertados correctamente en energia (nuevos_datos)');
        return $this->respond(['message' => 'Datos almacenados correctamente (nuevos_datos).'], 200);
    }

}