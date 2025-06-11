<?php

namespace App\Controllers;

use App\Models\EnergyDataModel;

class EnergyController extends BaseController {
    protected $energyDataModel;

    public function __construct() {
        $this->energyDataModel = new EnergyDataModel();
        log_message('info', '[EnergyController] Inicializado');
    }

    public function index() {
        log_message('info', '[EnergyController::index] Accediendo a la vista principal');
        // Obtener los últimos datos de cada dispositivo
        $data['dispositivos'] = $this->energyDataModel->select('DISTINCT mac_address')
                                                     ->findAll();
        
        foreach ($data['dispositivos'] as &$dispositivo) {
            $ultimaLectura = $this->energyDataModel->where('mac_address', $dispositivo['mac_address'])
                                                  ->orderBy('created_at', 'DESC')
                                                  ->first();
            $dispositivo['ultima_lectura'] = $ultimaLectura;
        }

        log_message('info', '[EnergyController::index] Dispositivos encontrados: ' . count($data['dispositivos']));
        return view('dispositivo/index', $data);
    }

    public function setup() {
        log_message('info', '[EnergyController::setup] Accediendo a la página de configuración');
        // Esta es la página que se mostrará cuando el dispositivo se conecte por primera vez
        return view('dispositivo/configurar');
    }

    public function saveConfig() {
        log_message('info', '[EnergyController::saveConfig] Intentando guardar configuración');
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'ssid' => 'required',
                'password' => 'required',
                'mac_address' => 'required'
            ];

            if (!$this->validate($rules)) {
                log_message('error', '[EnergyController::saveConfig] Validación fallida: ' . json_encode($this->validator->getErrors()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Datos inválidos',
                    'errors' => $this->validator->getErrors()
                ])->setStatusCode(400);
            }

            $data = [
                'ssid' => $this->request->getPost('ssid'),
                'password' => $this->request->getPost('password'),
                'mac_address' => $this->request->getPost('mac_address')
            ];

            log_message('info', '[EnergyController::saveConfig] Configuración guardada para MAC: ' . $data['mac_address']);
            // Aquí podrías guardar la configuración en la base de datos si lo necesitas
            // Por ahora, solo retornamos los datos para que el Arduino los use

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Configuración guardada',
                'data' => $data
            ]);
        }

        log_message('error', '[EnergyController::saveConfig] Método no permitido');
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Método no permitido'
        ])->setStatusCode(405);
    }

    public function nuevos_datos() {
        log_message('info', '[EnergyController::nuevos_datos] Recibiendo nuevos datos');
        
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getJSON(true);
            
            if (!$data) {
                log_message('error', '[EnergyController::nuevos_datos] Datos JSON inválidos');
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Datos JSON inválidos'
                ])->setStatusCode(400);
            }

            log_message('info', '[EnergyController::nuevos_datos] Datos recibidos: ' . json_encode($data));

            // Validar los datos
            if (!$this->energyDataModel->validate($data)) {
                log_message('error', '[EnergyController::nuevos_datos] Validación fallida: ' . json_encode($this->energyDataModel->errors()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Datos inválidos',
                    'errors' => $this->energyDataModel->errors()
                ])->setStatusCode(400);
            }

            try {
                // Insertar los datos en la base de datos
                $this->energyDataModel->insert($data);
                log_message('info', '[EnergyController::nuevos_datos] Datos guardados correctamente');
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Datos guardados correctamente'
                ]);
            } catch (\Exception $e) {
                log_message('error', '[EnergyController::nuevos_datos] Error al guardar datos: ' . $e->getMessage());
                
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al guardar los datos'
                ])->setStatusCode(500);
            }
        }

        log_message('error', '[EnergyController::nuevos_datos] Método no permitido');
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Método no permitido'
        ])->setStatusCode(405);
    }

    public function getLatestData() {
        log_message('info', '[EnergyController::getLatestData] Obteniendo últimos datos');
        
        $data = $this->energyDataModel->orderBy('created_at', 'DESC')
                                    ->limit(1)
                                    ->first();
        
        if ($data) {
            log_message('info', '[EnergyController::getLatestData] Datos encontrados');
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        log_message('info', '[EnergyController::getLatestData] No hay datos disponibles');
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No hay datos disponibles'
        ])->setStatusCode(404);
    }

    public function getDeviceData($macAddress) {
        log_message('info', '[EnergyController::getDeviceData] Obteniendo datos para MAC: ' . $macAddress);
        
        $data = $this->energyDataModel->where('mac_address', $macAddress)
                                    ->orderBy('created_at', 'DESC')
                                    ->limit(100)
                                    ->find();
        
        if ($data) {
            log_message('info', '[EnergyController::getDeviceData] Datos encontrados: ' . count($data) . ' registros');
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        log_message('info', '[EnergyController::getDeviceData] No hay datos disponibles para MAC: ' . $macAddress);
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No hay datos disponibles para este dispositivo'
        ])->setStatusCode(404);
    }

    public function verDetalles($macAddress) {
        log_message('info', '[EnergyController::verDetalles] Viendo detalles para MAC: ' . $macAddress);
        
        $data['dispositivo'] = $this->energyDataModel->where('mac_address', $macAddress)
                                                    ->orderBy('created_at', 'DESC')
                                                    ->limit(100)
                                                    ->find();
        
        if (empty($data['dispositivo'])) {
            log_message('info', '[EnergyController::verDetalles] No se encontraron datos para MAC: ' . $macAddress);
            return redirect()->to('/')->with('error', 'No se encontraron datos para este dispositivo');
        }

        log_message('info', '[EnergyController::verDetalles] Datos encontrados: ' . count($data['dispositivo']) . ' registros');
        return view('dispositivo/configurar', $data);
    }

    public function getMacAddress() {
        log_message('info', '[EnergyController::getMacAddress] Obteniendo dirección MAC');
        
        // En un entorno real, esto vendría del dispositivo
        // Por ahora, generamos una MAC aleatoria para pruebas
        $mac = sprintf(
            '%02X:%02X:%02X:%02X:%02X:%02X',
            rand(0, 255),
            rand(0, 255),
            rand(0, 255),
            rand(0, 255),
            rand(0, 255),
            rand(0, 255)
        );
        
        log_message('info', '[EnergyController::getMacAddress] MAC generada: ' . $mac);
        
        return $this->response->setJSON([
            'status' => 'success',
            'mac_address' => $mac
        ]);
    }

    public function scanWifiNetworks() {
        log_message('info', '[EnergyController::scanWifiNetworks] Escaneando redes WiFi');
        
        // En un entorno real, esto vendría del dispositivo
        // Por ahora, retornamos algunas redes de ejemplo
        $networks = [
            ['ssid' => 'Red_1', 'signal' => -50],
            ['ssid' => 'Red_2', 'signal' => -60],
            ['ssid' => 'Red_3', 'signal' => -70]
        ];
        
        log_message('info', '[EnergyController::scanWifiNetworks] Redes encontradas: ' . count($networks));
        
        return $this->response->setJSON([
            'status' => 'success',
            'networks' => $networks
        ]);
    }
} 