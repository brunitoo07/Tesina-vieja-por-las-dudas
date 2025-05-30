<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;

class Dispositivo extends BaseController
{
    protected $dispositivoModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
    }

    public function buscar()
    {
        // Simular búsqueda de dispositivos ESP32 en modo AP
        // En una implementación real, esto se conectaría al ESP32 en modo AP
        // y obtendría la lista de dispositivos disponibles
        $dispositivos = [
            [
                'mac_address' => 'AA:BB:CC:DD:EE:FF',
                'nombre' => 'ESP32_1',
                'signal_strength' => -65
            ],
            [
                'mac_address' => '11:22:33:44:55:66',
                'nombre' => 'ESP32_2',
                'signal_strength' => -70
            ]
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'dispositivos' => $dispositivos
        ]);
    }

    public function redes()
    {
        // Simular obtención de redes WiFi disponibles
        // En una implementación real, esto se conectaría al ESP32
        // y obtendría la lista de redes WiFi disponibles
        $redes = [
            [
                'ssid' => 'MiRedWiFi',
                'signal_strength' => -50
            ],
            [
                'ssid' => 'RedVecina',
                'signal_strength' => -75
            ]
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'redes' => $redes
        ]);
    }

    public function configurar()
    {
        $rules = [
            'mac_address' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]',
            'nombre' => 'required|min_length[3]|max_length[100]',
            'ssid' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'mac_address' => $this->request->getJSON()->mac_address,
            'nombre' => $this->request->getJSON()->nombre,
            'ssid' => $this->request->getJSON()->ssid,
            'password' => $this->request->getJSON()->password
        ];

        // En una implementación real, esto enviaría la configuración al ESP32
        // y esperaría la confirmación de que se conectó exitosamente

        // Simular éxito en la configuración
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Dispositivo configurado exitosamente'
        ]);
    }

    public function activar()
    {
        $macAddress = $this->request->getPost('mac_address');

        if (!$macAddress) {
            return $this->fail('Falta la dirección MAC');
        }

        // Buscar el dispositivo por la MAC
        $dispositivo = $this->dispositivoModel->where('mac_address', $macAddress)
                                            ->where('estado', 'pendiente')
                                            ->first();

        if (!$dispositivo) {
            return $this->fail('Dispositivo no encontrado o ya está activo');
        }

        // Activar el dispositivo
        if ($this->dispositivoModel->cambiarEstado($dispositivo['id_dispositivo'], 'activo')) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Dispositivo activado correctamente',
                'data' => [
                    'id_dispositivo' => $dispositivo['id_dispositivo'],
                    'mac_address' => $macAddress,
                    'estado' => 'activo'
                ]
            ]);
        }

        return $this->fail('Error al activar el dispositivo');
    }

    public function iniciarConfiguracion()
    {
        $macSimulada = $this->request->getPost('mac_simulada');

        if (!$macSimulada) {
            return $this->fail('Falta la MAC simulada');
        }

        $dispositivo = $this->dispositivoModel->where('mac_simulada', $macSimulada)
                                            ->where('estado', 'pendiente')
                                            ->first();

        if (!$dispositivo) {
            return $this->fail('Dispositivo no encontrado o ya está configurado');
        }

        if ($this->dispositivoModel->iniciarConfiguracion($dispositivo['id_dispositivo'])) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Modo configuración iniciado',
                'data' => [
                    'id_dispositivo' => $dispositivo['id_dispositivo'],
                    'estado' => 'configurando'
                ]
            ]);
        }

        return $this->fail('Error al iniciar la configuración');
    }

    public function actualizarEstado()
    {
        $macAddress = $this->request->getPost('mac_address');
        $estado = $this->request->getPost('estado');

        if (!$macAddress || !$estado) {
            return $this->fail('Faltan datos requeridos');
        }

        $dispositivo = $this->dispositivoModel->where('mac_address', $macAddress)->first();

        if (!$dispositivo) {
            return $this->fail('Dispositivo no encontrado');
        }

        if ($this->dispositivoModel->cambiarEstado($dispositivo['id_dispositivo'], $estado)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Estado actualizado correctamente'
            ]);
        }

        return $this->fail('Error al actualizar el estado');
    }

    public function actualizarStock()
    {
        $idDispositivo = $this->request->getPost('id_dispositivo');
        $cantidad = $this->request->getPost('cantidad');

        if (!$idDispositivo || !$cantidad) {
            return $this->fail('Faltan datos requeridos');
        }

        if ($this->dispositivoModel->actualizarStock($idDispositivo, $cantidad)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Stock actualizado correctamente'
            ]);
        }

        return $this->fail('Error al actualizar el stock');
    }
} 