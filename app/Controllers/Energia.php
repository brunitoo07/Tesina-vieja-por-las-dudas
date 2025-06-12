<?php

namespace App\Controllers;

use App\Models\EnergiaModel;
use App\Models\DispositivoModel;
use App\Models\LimiteConsumoModel;

class Energia extends BaseController
{
    protected $energiaModel;
    protected $dispositivoModel;
    protected $limiteModel;

    public function __construct()
    {
        $this->energiaModel = new EnergiaModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->limiteModel = new LimiteConsumoModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        try {
            $idUsuario = session()->get('id_usuario');
            
            // Obtener todos los dispositivos del usuario
            $dispositivos = $this->dispositivoModel->where('id_usuario', $idUsuario)->findAll();
            
            if (empty($dispositivos)) {
                return redirect()->to('/dispositivos')->with('error', 'No tienes dispositivos registrados');
            }

            // Obtener el último dispositivo
            $ultimoDispositivo = end($dispositivos);
            
            // Obtener las últimas 50 lecturas del dispositivo
            $lecturas = $this->energiaModel->where('id_dispositivo', $ultimoDispositivo['id_dispositivo'])
                                         ->orderBy('fecha', 'DESC')
                                         ->limit(50)
                                         ->findAll();

            // Obtener configuración de límites
            $limite = $this->limiteModel->getLimiteByDispositivo($ultimoDispositivo['id_dispositivo']);
            $limite_consumo = $limite ? $limite['limite_consumo'] : 10;

            // Log para debugging
            log_message('info', 'Dispositivo ID: ' . $ultimoDispositivo['id_dispositivo']);
            log_message('info', 'Número de lecturas encontradas: ' . count($lecturas));

            return view('energia/index', [
                'lecturas' => $lecturas,
                'dispositivo' => $ultimoDispositivo,
                'limite_consumo' => $limite_consumo
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en Energia::index: ' . $e->getMessage());
            return redirect()->to('/dashboard')->with('error', 'Error al cargar los datos de energía');
        }
    }

    public function getLatestData()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }

        $idUsuario = session()->get('id_usuario');
        $dispositivo = $this->dispositivoModel->where('id_usuario', $idUsuario)
                                            ->orderBy('id_dispositivo', 'DESC')
                                            ->first();

        if (!$dispositivo) {
            return $this->response->setJSON(['error' => 'No se encontró dispositivo'])->setStatusCode(404);
        }

        $ultimaLectura = $this->energiaModel->where('id_dispositivo', $dispositivo['id_dispositivo'])
                                          ->orderBy('fecha', 'DESC')
                                          ->first();

        if (!$ultimaLectura) {
            return $this->response->setJSON(['error' => 'No hay lecturas disponibles'])->setStatusCode(404);
        }

        // Verificar límite de consumo
        $limite = $this->limiteModel->getLimiteByDispositivo($dispositivo['id_dispositivo']);
        if ($limite && $ultimaLectura['kwh'] > $limite['limite_consumo']) {
            // Marcar la lectura como que superó el límite
            $this->energiaModel->update($ultimaLectura['id'], ['limite_superado' => 1]);
            
            // Enviar notificación si no se ha enviado recientemente
            if (!$limite['notificacion_enviada'] || 
                (strtotime($limite['ultima_notificacion']) < strtotime('-1 hour'))) {
                $this->enviarNotificacionEmail($idUsuario, $ultimaLectura['kwh'], $limite['limite_consumo']);
                $this->limiteModel->actualizarNotificacion($limite['id']);
            }
        }

        return $this->response->setJSON($ultimaLectura);
    }

    public function getDataByPeriod($periodo)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }

        $idUsuario = session()->get('id_usuario');
        $dispositivo = $this->dispositivoModel->where('id_usuario', $idUsuario)
                                            ->orderBy('id_dispositivo', 'DESC')
                                            ->first();

        if (!$dispositivo) {
            return $this->response->setJSON(['error' => 'No se encontró dispositivo'])->setStatusCode(404);
        }

        $fechaInicio = date('Y-m-d H:i:s', strtotime("-1 $periodo"));
        
        $lecturas = $this->energiaModel->where('id_dispositivo', $dispositivo['id_dispositivo'])
                                     ->where('fecha >=', $fechaInicio)
                                     ->orderBy('fecha', 'ASC')
                                     ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $lecturas
        ]);
    }

    public function getConfig()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }

        try {
            $idUsuario = session()->get('id_usuario');
            $dispositivo = $this->dispositivoModel->where('id_usuario', $idUsuario)
                                                ->orderBy('id_dispositivo', 'DESC')
                                                ->first();

            if (!$dispositivo) {
                log_message('error', 'No se encontró dispositivo para el usuario: ' . $idUsuario);
                return $this->response->setJSON(['error' => 'No se encontró dispositivo'])->setStatusCode(404);
            }

            $limite = $this->limiteModel->getLimiteByDispositivo($dispositivo['id_dispositivo']);
            
            log_message('info', 'Obteniendo configuración para dispositivo: ' . $dispositivo['id_dispositivo']);

            return $this->response->setJSON([
                'success' => true,
                'limite_consumo' => $limite ? $limite['limite_consumo'] : 10,
                'email' => $limite ? $limite['email_notificacion'] : session()->get('email')
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al obtener configuración: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al obtener la configuración',
                'details' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function saveConfig()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }

        try {
            $idUsuario = session()->get('id_usuario');
            $dispositivo = $this->dispositivoModel->where('id_usuario', $idUsuario)
                                                ->orderBy('id_dispositivo', 'DESC')
                                                ->first();

            if (!$dispositivo) {
                log_message('error', 'No se encontró dispositivo para el usuario: ' . $idUsuario);
                return $this->response->setJSON(['error' => 'No se encontró dispositivo'])->setStatusCode(404);
            }

            $data = $this->request->getJSON(true);
            
            // Validar datos
            if (!isset($data['limite_consumo']) || !is_numeric($data['limite_consumo'])) {
                return $this->response->setJSON(['error' => 'El límite de consumo es requerido y debe ser numérico'])->setStatusCode(400);
            }

            $limite = $this->limiteModel->getLimiteByDispositivo($dispositivo['id_dispositivo']);
            
            if ($limite) {
                log_message('info', 'Actualizando límite existente para dispositivo: ' . $dispositivo['id_dispositivo']);
                $this->limiteModel->update($limite['id'], [
                    'limite_consumo' => $data['limite_consumo'],
                    'email_notificacion' => $data['email'] ?? null
                ]);
            } else {
                log_message('info', 'Creando nuevo límite para dispositivo: ' . $dispositivo['id_dispositivo']);
                $this->limiteModel->insert([
                    'id_usuario' => $idUsuario,
                    'id_dispositivo' => $dispositivo['id_dispositivo'],
                    'limite_consumo' => $data['limite_consumo'],
                    'email_notificacion' => $data['email'] ?? null
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Configuración guardada correctamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error al guardar configuración: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al guardar la configuración',
                'details' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function exportData()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }

        $idUsuario = session()->get('id_usuario');
        $dispositivo = $this->dispositivoModel->where('id_usuario', $idUsuario)
                                            ->orderBy('id_dispositivo', 'DESC')
                                            ->first();

        if (!$dispositivo) {
            return $this->response->setJSON(['error' => 'No se encontró dispositivo'])->setStatusCode(404);
        }

        $lecturas = $this->energiaModel->where('id_dispositivo', $dispositivo['id_dispositivo'])
                                     ->orderBy('fecha', 'DESC')
                                     ->findAll();

        $csv = "Fecha,Voltaje,Corriente,Potencia,Consumo,Límite Superado,MAC Address\n";
        foreach ($lecturas as $lectura) {
            $csv .= sprintf(
                "%s,%.2f,%.4f,%.2f,%.4f,%s,%s\n",
                $lectura['fecha'],
                $lectura['voltaje'],
                $lectura['corriente'],
                $lectura['potencia'],
                $lectura['kwh'],
                $lectura['limite_superado'] ? 'Sí' : 'No',
                $lectura['mac_address']
            );
        }

        return $this->response->setHeader('Content-Type', 'text/csv')
                            ->setHeader('Content-Disposition', 'attachment; filename="consumo_energia.csv"')
                            ->setBody($csv);
    }

    private function enviarNotificacionEmail($idUsuario, $consumoActual, $limite)
    {
        $email = \Config\Services::email();
        $user = $this->userModel->find($idUsuario);

        $email->setFrom('noreply@ecovolt.com', 'EcoVolt');
        $email->setTo($user['email']);
        $email->setSubject('¡Alerta de Consumo de Energía!');
        
        $mensaje = "Estimado usuario,\n\n";
        $mensaje .= "Le informamos que ha superado el límite de consumo diario establecido.\n\n";
        $mensaje .= "Consumo actual: " . number_format($consumoActual, 4) . " kWh\n";
        $mensaje .= "Límite establecido: " . number_format($limite, 4) . " kWh\n\n";
        $mensaje .= "Por favor, revise su consumo de energía y tome las medidas necesarias.\n\n";
        $mensaje .= "Saludos cordiales,\n";
        $mensaje .= "Equipo EcoVolt";

        $email->setMessage($mensaje);
        $email->send();
    }

    public function recibirDatos()
    {
        $json = $this->request->getJSON();

        if (!$json) {
            log_message('error', 'Datos recibidos no son JSON válido');
            return $this->response->setJSON(['error' => 'Datos inválidos'])->setStatusCode(400);
        }

        $dispositivo = $this->dispositivoModel->where('mac_address', $json->mac_address)->first();

        if (!$dispositivo) {
            log_message('error', 'Dispositivo no encontrado: ' . $json->mac_address);
            return $this->response->setJSON(['error' => 'Dispositivo no registrado'])->setStatusCode(404);
        }

        $data = [
            'id_dispositivo' => $dispositivo['id_dispositivo'],
            'id_usuario' => $dispositivo['id_usuario'],
            'voltaje' => $json->voltaje,
            'corriente' => $json->corriente,
            'potencia' => $json->potencia,
            'kwh' => $json->kwh,
            'fecha' => date('Y-m-d H:i:s'),
            'mac_address' => $json->mac_address,
            'limite_superado' => 0
        ];

        try {
            $this->energiaModel->insert($data);
            log_message('info', 'Datos guardados correctamente para dispositivo: ' . $json->mac_address);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Error al guardar datos: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error al guardar datos'])->setStatusCode(500);
        }
    }

    public function recibirNuevosDatos()
    {
        try {
            $data = $this->request->getJSON(true);
            
            if (!$data) {
                return $this->response->setJSON(['error' => 'No se recibieron datos'])->setStatusCode(400);
            }

            // Validar datos requeridos
            $requiredFields = ['voltaje', 'corriente', 'potencia', 'kwh', 'mac_address'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    return $this->response->setJSON(['error' => "Campo requerido faltante: $field"])->setStatusCode(400);
                }
            }

            // Formatear la MAC address
            $mac = $data['mac_address'];
            $mac = strtoupper($mac);
            $mac = implode(':', str_split($mac, 2));

            // Buscar el dispositivo por MAC
            $dispositivo = $this->dispositivoModel->where('mac_address', $mac)->first();
            
            if (!$dispositivo) {
                log_message('error', 'Dispositivo no encontrado con MAC: ' . $mac);
                return $this->response->setJSON(['error' => 'Dispositivo no encontrado'])->setStatusCode(404);
            }

            // Preparar datos para guardar
            $lectura = [
                'id_dispositivo' => $dispositivo['id_dispositivo'],
                'id_usuario' => $dispositivo['id_usuario'],
                'voltaje' => $data['voltaje'],
                'corriente' => $data['corriente'],
                'potencia' => $data['potencia'],
                'kwh' => $data['kwh'],
                'mac_address' => $mac,
                'fecha' => date('Y-m-d H:i:s')
            ];

            // Guardar la lectura
            $this->energiaModel->insert($lectura);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Datos recibidos correctamente'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en recibirNuevosDatos: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error al procesar los datos',
                'details' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function getLatestDataByMac($mac)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }

        // Formatear la MAC address
        $mac = strtoupper($mac);
        $mac = implode(':', str_split($mac, 2));

        // Buscar el dispositivo por MAC
        $dispositivo = $this->dispositivoModel->where('mac_address', $mac)->first();

        if (!$dispositivo) {
            return $this->response->setJSON(['error' => 'Dispositivo no encontrado'])->setStatusCode(404);
        }

        // Obtener la última lectura
        $ultimaLectura = $this->energiaModel->where('id_dispositivo', $dispositivo['id_dispositivo'])
                                          ->orderBy('fecha', 'DESC')
                                          ->first();

        if (!$ultimaLectura) {
            return $this->response->setJSON(['error' => 'No hay lecturas disponibles'])->setStatusCode(404);
        }

        // Verificar límite de consumo
        $limite = $this->limiteModel->getLimiteByDispositivo($dispositivo['id_dispositivo']);
        if ($limite && $ultimaLectura['kwh'] > $limite['limite_consumo']) {
            $this->energiaModel->update($ultimaLectura['id'], ['limite_superado' => 1]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $ultimaLectura,
            'dispositivo' => $dispositivo,
            'limite_consumo' => $limite ? $limite['limite_consumo'] : 10
        ]);
    }

    public function dispositivo($id_dispositivo)
    {
        // Verificar si el usuario está autenticado
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Obtener información del dispositivo
        $dispositivo = $this->dispositivoModel->find($id_dispositivo);

        if (!$dispositivo) {
            return redirect()->to('/admin/dispositivos')->with('error', 'Dispositivo no encontrado');
        }

        // Obtener las lecturas del dispositivo
        $lecturas = $this->energiaModel->where('id_dispositivo', $id_dispositivo)
                                     ->orderBy('fecha', 'DESC')
                                     ->findAll();

        // Obtener el límite de consumo
        $limite = $this->limiteModel->getLimiteByDispositivo($id_dispositivo);
        $limite_consumo = $limite ? $limite['limite_consumo'] : 10;

        $data = [
            'dispositivo' => $dispositivo,
            'lecturas' => $lecturas,
            'limite_consumo' => $limite_consumo
        ];

        return view('energia/dispositivo', $data);
    }

    public function getLatestDataByDevice($id_dispositivo)
    {
        // Verificar si el usuario está autenticado
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No autorizado'
            ]);
        }

        // Obtener información del dispositivo
        $dispositivoModel = new \App\Models\DispositivoModel();
        $dispositivo = $dispositivoModel->find($id_dispositivo);

        if (!$dispositivo) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Dispositivo no encontrado'
            ]);
        }

        // Verificar si el usuario tiene permiso para ver este dispositivo
        $idUsuario = session()->get('id_usuario');
        $idRol = session()->get('id_rol');
        
        // Permitir acceso si es el propietario del dispositivo o si es admin/supervisor
        if ($dispositivo['id_usuario'] !== $idUsuario && $idRol != 1 && $idRol != 3) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No tienes permiso para ver este dispositivo'
            ]);
        }

        // Obtener la última lectura del dispositivo
        $lecturaModel = new \App\Models\LecturaModel();
        $lectura = $lecturaModel->where('id_dispositivo', $id_dispositivo)
                              ->orderBy('fecha', 'DESC')
                              ->first();

        if (!$lectura) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No hay lecturas disponibles'
            ]);
        }

        // Obtener el límite de consumo del dispositivo
        $limiteConsumo = $dispositivo['limite_consumo'] ?? 1000; // Valor por defecto si no está configurado

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'fecha' => $lectura['fecha'],
                'voltaje' => $lectura['voltaje'],
                'corriente' => $lectura['corriente'],
                'potencia' => $lectura['potencia'],
                'kwh' => $lectura['kwh'],
                'mac_address' => $dispositivo['mac_address'],
                'limite_superado' => $lectura['kwh'] > $limiteConsumo
            ],
            'limite_consumo' => $limiteConsumo
        ]);
    }
}