<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;

class Dispositivo extends BaseController
{
    protected $dispositivoModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        log_message('debug', 'ID Usuario: ' . $idUsuario);
        
        $dispositivos = $this->dispositivoModel->obtenerDispositivosUsuario($idUsuario);
        log_message('debug', 'Dispositivos encontrados: ' . print_r($dispositivos, true));

        $data = [
            'dispositivos' => $dispositivos,
            'titulo' => 'Mis Dispositivos'
        ];

        return view('dispositivo/index', $data);
    }

    public function buscar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $data = [
            'titulo' => 'Buscar Dispositivos'
        ];

        return view('dispositivo/buscar', $data);
    }

    public function getMacAddress()
    {
        log_message('debug', 'Obteniendo dirección MAC...');
        // Por ahora, generamos una MAC aleatoria para pruebas
        $mac = sprintf('%02X:%02X:%02X:%02X:%02X:%02X',
            rand(0, 255), rand(0, 255), rand(0, 255),
            rand(0, 255), rand(0, 255), rand(0, 255)
        );
        
        return $this->response->setJSON([
            'status' => 'success',
            'mac_address' => $mac
        ]);
    }

    public function scanWifiNetworks()
    {
        log_message('debug', 'Iniciando búsqueda de dispositivos...');
        
        try {
            // Primero, buscar dispositivos en la base de datos
            $dispositivos = $this->dispositivoModel->where('estado', 'activo')->findAll();
            log_message('debug', 'Dispositivos encontrados en BD: ' . print_r($dispositivos, true));

            if (!empty($dispositivos)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'networks' => $dispositivos
                ]);
            }

            // Si no hay dispositivos en la BD, intentar buscar en la red
            log_message('debug', 'Intentando buscar dispositivo ESP32 en la red...');
            
            // Intentar obtener la dirección MAC del dispositivo ESP32
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://192.168.4.1/status");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            log_message('debug', 'Código de respuesta HTTP: ' . $httpCode);
            log_message('debug', 'Error de cURL: ' . $error);
            log_message('debug', 'Respuesta del dispositivo: ' . $response);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if ($data) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'networks' => [
                            [
                                'mac_address' => $data['mac_address'] ?? '00:00:00:00:00:00',
                                'nombre' => 'ESP32 Medidor',
                                'ultima_lectura' => date('Y-m-d H:i:s'),
                                'voltage' => $data['voltage'] ?? 0,
                                'current' => $data['current'] ?? 0,
                                'power' => $data['power'] ?? 0,
                                'energy' => $data['energy'] ?? 0
                            ]
                        ]
                    ]);
                }
            }

            // Si no se encuentra ningún dispositivo
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se encontraron dispositivos. Asegúrate de que el ESP32 esté encendido y conectado a la red.'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al escanear redes: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al buscar dispositivos: ' . $e->getMessage()
            ]);
        }
    }

    public function saveConfig()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No autorizado'
            ]);
        }

        $ssid = $this->request->getJSON()->ssid;
        $password = $this->request->getJSON()->password;
        $macAddress = $this->request->getJSON()->mac_address;

        log_message('debug', 'Guardando configuración WiFi: ' . json_encode([
            'ssid' => $ssid,
            'mac_address' => $macAddress
        ]));

        // Aquí iría la lógica para guardar la configuración en la base de datos
        // y enviar los datos al dispositivo

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Configuración guardada exitosamente'
        ]);
    }

    public function agregar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        return view('dispositivo/agregar');
    }

    public function guardar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }
    
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mac_address' => $this->request->getPost('mac_address'),
            'id_usuario' => session()->get('id_usuario'),
            'estado' => 1, // Valor por defecto
            'created_at' => date('Y-m-d H:i:s') // Campo requerido
        ];
    
        try {
            $this->dispositivoModel->insert($data);
            return redirect()->to('dispositivo')->with('success', 'Dispositivo guardado correctamente');
        } catch (\Exception $e) {
            log_message('error', 'Error al guardar dispositivo: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al guardar el dispositivo');
        }
    }

    public function eliminar($idDispositivo)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');

        if ($this->dispositivoModel->desvincularDispositivo($idDispositivo, $idUsuario)) {
            session()->set('exito', 'Dispositivo desvinculado correctamente');
        } else {
            session()->set('error', 'Error al desvincular el dispositivo');
        }

        return redirect()->to('dispositivo');
    }

    public function configurar($macAddress = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        if (!$macAddress) {
            return redirect()->to('dispositivo')->with('error', 'No se especificó la dirección MAC del dispositivo');
        }

        $data = [
            'mac_address' => $macAddress,
            'titulo' => 'Configurar Dispositivo'
        ];

        return view('dispositivo/configurar', $data);
    }

    public function getMac()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No autorizado'
            ]);
        }

        try {
            // Intentar obtener la dirección MAC del dispositivo ESP32
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://192.168.4.1/status");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if ($data && isset($data['mac_address'])) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'mac_address' => $data['mac_address']
                    ]);
                }
            }

            // Si no se puede obtener la MAC del dispositivo, intentar obtenerla de la base de datos
            $macAddress = $this->request->getGet('mac_address');
            if ($macAddress) {
                $dispositivo = $this->dispositivoModel->where('mac_address', $macAddress)->first();
                if ($dispositivo) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'mac_address' => $dispositivo['mac_address']
                    ]);
                }
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo obtener la dirección MAC del dispositivo'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener MAC: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al obtener la dirección MAC: ' . $e->getMessage()
            ]);
        }
    }

    public function updateWifi()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No autorizado'
            ]);
        }

        $json = $this->request->getJSON();
        $ssid = $json->ssid ?? null;
        $password = $json->password ?? null;
        $macAddress = $json->mac_address ?? null;

        if (!$ssid || !$password || !$macAddress) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Faltan datos requeridos'
            ]);
        }

        try {
            // Enviar la nueva configuración al dispositivo ESP32
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://192.168.4.1/update-wifi");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'ssid' => $ssid,
                'password' => $password
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if ($data && $data['status'] === 'success') {
                    // Actualizar la configuración en la base de datos
                    $this->dispositivoModel->where('mac_address', $macAddress)
                        ->set([
                            'wifi_ssid' => $ssid,
                            'wifi_password' => $password
                        ])
                        ->update();

                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Configuración WiFi actualizada correctamente'
                    ]);
                }
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al actualizar la configuración WiFi'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar WiFi: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al actualizar la configuración WiFi: ' . $e->getMessage()
            ]);
        }
    }
}