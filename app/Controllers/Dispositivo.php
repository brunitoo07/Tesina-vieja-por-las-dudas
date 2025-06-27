<?php
/**
* Controlador para la gestión de dispositivos del usuario común.
* Permite ver, buscar, configurar y eliminar los dispositivos propios.
* NO es para administración global.
*/      
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
            log_message('error', 'Intento de guardar dispositivo sin sesión iniciada');
            return redirect()->to('/autenticacion/login');
        }
    
        log_message('info', 'Iniciando guardado de dispositivo');
        log_message('info', 'Datos recibidos: ' . json_encode($this->request->getPost()));
    
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mac_address' => $this->request->getPost('mac_address'),
            'id_usuario' => session()->get('id_usuario'),
            'estado' => 'activo',
            'created_at' => date('Y-m-d H:i:s')
        ];
    
        log_message('info', 'Datos preparados para insertar: ' . json_encode($data));
    
        try {
            // Verificar si la MAC ya existe
            $dispositivoExistente = $this->dispositivoModel->where('mac_address', $data['mac_address'])->first();
            if ($dispositivoExistente) {
                log_message('warning', 'Intento de registrar MAC duplicada: ' . $data['mac_address']);
                session()->setFlashdata('error', 'La dirección MAC ya está registrada');
                return redirect()->back()->withInput();
            }

            log_message('info', 'Intentando insertar dispositivo en la base de datos');
            $resultado = $this->dispositivoModel->insert($data);
            
            if ($resultado) {
                log_message('info', 'Dispositivo guardado exitosamente. ID: ' . $resultado);
                session()->setFlashdata('success', 'Dispositivo guardado correctamente');
                return redirect()->to(base_url('admin/dispositivos'));
            } else {
                log_message('error', 'Error al insertar dispositivo. Errores del modelo: ' . json_encode($this->dispositivoModel->errors()));
                session()->setFlashdata('error', 'Error al guardar el dispositivo: ' . implode(', ', $this->dispositivoModel->errors()));
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al guardar dispositivo: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            session()->setFlashdata('error', 'Error al guardar el dispositivo: ' . $e->getMessage());
            return redirect()->back()->withInput();
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