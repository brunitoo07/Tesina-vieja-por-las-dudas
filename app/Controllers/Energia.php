<?php

namespace App\Controllers;

use App\Models\EnergiaModel;
use App\Models\DispositivoModel;
use App\Models\LimiteConsumoModel;
use App\Models\UsuarioModel;

use Dompdf\Dompdf;
use Dompdf\Options;


class Energia extends BaseController
{
    protected $energiaModel;
    protected $dispositivoModel;
    protected $limiteModel;
    protected $userModel; 

    public function __construct()
    {
        $this->energiaModel = new EnergiaModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->limiteModel = new LimiteConsumoModel();
        $this->userModel = new UsuarioModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        try {
            $idUsuario = session()->get('id_usuario');
            $dispositivos = $this->dispositivoModel->where('id_usuario', $idUsuario)->findAll();
            
            if (empty($dispositivos)) {
                return redirect()->to('/dispositivos')->with('error', 'No tienes dispositivos registrados');
            }

            $ultimoDispositivo = end($dispositivos);
            $lecturas = $this->energiaModel->where('id_dispositivo', $ultimoDispositivo['id_dispositivo'])
                                         ->orderBy('fecha', 'DESC')
                                         ->limit(50)
                                         ->findAll();

            $limite = $this->limiteModel->getLimiteByDispositivo($ultimoDispositivo['id_dispositivo']);
            $limite_consumo = $limite ? $limite['limite_consumo'] : 10;

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
    $limiteConsumo = $limite ? $limite['limite_consumo'] : 10;

    $ultimaLectura['limite_superado'] = $ultimaLectura['kwh'] > $limiteConsumo;

    return $this->response->setJSON([
        'success' => true,
        'data' => $ultimaLectura,
        'limite_consumo' => $limiteConsumo
    ]);
}


    public function recibirNuevosDatos()
    {
        try {
            $data = $this->request->getJSON(true);

            if (!$data) {
                return $this->response->setJSON(['error' => 'No se recibieron datos'])->setStatusCode(400);
            }

            $requiredFields = ['voltaje', 'corriente', 'potencia', 'kwh', 'mac_address'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    return $this->response->setJSON(['error' => "Campo requerido faltante: $field"])->setStatusCode(400);
                }
            }

            $mac_sin_formato = strtoupper($data['mac_address']);
            $mac_formateada = implode(':', str_split($mac_sin_formato, 2));
            
            $dispositivo = $this->dispositivoModel->where('mac_address', $mac_formateada)->first();
            if (!$dispositivo) {
                return $this->response->setJSON(['error' => 'Dispositivo no encontrado'])->setStatusCode(404);
            }

            $lectura = [
                'id_dispositivo' => $dispositivo['id_dispositivo'],
                'id_usuario' => $dispositivo['id_usuario'],
                'voltaje' => $data['voltaje'],
                'corriente' => $data['corriente'],
                'potencia' => $data['potencia'],
                'kwh' => $data['kwh'],
                'mac_address' => $mac_formateada,
                'fecha' => date('Y-m-d H:i:s'),
                'limite_superado' => 0
            ];

            // ---------------------- NUEVO: VERIFICAR LÍMITE ----------------------
            $this->verificarLimite($lectura, $dispositivo['id_dispositivo'], $dispositivo['id_usuario']);
            // ----------------------------------------------------------------------

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

    public function actualizarLimite()
{
    helper('email');

    $limite = $this->request->getVar('limite_consumo');
    $email = $this->request->getVar('email');
    $id_dispositivo = $this->request->getVar('id_dispositivo');

    if (!$limite || $limite <= 0) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'El límite debe ser mayor a 0'
        ]); 
    }

    if (!$id_dispositivo || $id_dispositivo <= 0) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Dispositivo no válido'
        ]);
    }

    $limiteModel = new \App\Models\LimiteConsumoModel();

    // Buscar si ya existe un límite para este dispositivo
    $limiteExistente = $limiteModel->where('id_dispositivo', $id_dispositivo)
                                   ->orderBy('created_at', 'DESC')
                                   ->first();

    if ($limiteExistente) {
        // Actualizar límite existente
        $limiteModel->update($limiteExistente['id'], [
            'limite_consumo' => $limite,
            'email_notificacion' => $email,
            'notificacion_enviada' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    } else {
        // Insertar nuevo registro si no existe
        $limiteModel->insert([
            'id_usuario' => session()->get('id_usuario'),
            'id_dispositivo' => $id_dispositivo,
            'limite_consumo' => $limite,
            'email_notificacion' => $email,
            'notificacion_enviada' => 0
        ]);
    }

    if ($email) {
        $emailObj = \Config\Services::email();

        $emailObj->setTo($email);
        $emailObj->setSubject('Confirmación de límite de consumo');
        $emailObj->setMessage("
            <p>Se ha configurado un límite de consumo de <b>$limite kWh</b> para su dispositivo.</p>
        ");

        $emailObj->send();
    }

    // ------------------ ALERTA TELEGRAM MEJORADA ------------------
    $dispositivo = $this->dispositivoModel->find($id_dispositivo);
    $nombreDispositivo = $dispositivo ? $dispositivo['nombre'] : "Dispositivo ID $id_dispositivo";
    
    $mensajeTelegram = "✅ *LÍMITE DE CONSUMO CONFIGURADO*\n\n";
    $mensajeTelegram .= "🔌 *Dispositivo:* {$nombreDispositivo}\n";
    $mensajeTelegram .= "📏 *Nuevo límite:* {$limite} kWh\n";
    $mensajeTelegram .= "📅 *Fecha:* " . date('d/m/Y H:i:s') . "\n\n";
    $mensajeTelegram .= "💡 *El sistema ahora monitoreará el consumo y te notificará si se supera este límite.*\n\n";
    $mensajeTelegram .= "🔗 *Panel de control:*\n";
    $mensajeTelegram .= base_url('energia/dispositivo/' . $id_dispositivo) . "\n\n";
    $mensajeTelegram .= "💡 *Usa /start para ver opciones del bot*";
    
    $this->alertaTelegram($mensajeTelegram);
    // ----------------------------------------------------------------
 
    return $this->response->setJSON([
        'success' => true,
        'message' => 'Límite de consumo actualizado correctamente'
    ]);
     
}


    
    public function alertaConsumo($id_dispositivo, $consumo_actual)
{
    helper('email');

    $limiteModel = new \App\Models\LimiteConsumoModel();

    // Obtener último límite configurado para este dispositivo
    $limite = $limiteModel->where('id_dispositivo', $id_dispositivo)
                          ->orderBy('created_at', 'DESC')
                          ->first();

    if (!$limite || !$limite['email_notificacion']) return;

    // Verificar si ya se envió la notificación
    if ($consumo_actual > $limite['limite_consumo'] && $limite['notificacion_enviada'] == 0) {
        $email = $limite['email_notificacion'];
        $emailObj = \Config\Services::email();

        $emailObj->setTo($email);
        $emailObj->setSubject('⚠️ Alerta de consumo superado');
        $dispositivo = $this->dispositivoModel->find($id_dispositivo);
        $html = view('emails/alerta_consumo', [
            'nombre' => null,
            'consumoActual' => (float)$consumo_actual,
            'limiteConfigurado' => (float)$limite['limite_consumo'],
            'idDispositivo' => $id_dispositivo,
            'dispositivoNombre' => $dispositivo['nombre'] ?? null,
            'momento' => date('Y-m-d H:i:s'),
            'urlPanel' => base_url('energia/dispositivo/' . $id_dispositivo)
        ]);
        $emailObj->setMessage($html);
        $emailObj->setMailType('html');

        if ($emailObj->send()) {
            $limiteModel->update($limite['id'], [
                'notificacion_enviada' => 1,
                'ultima_notificacion' => date('Y-m-d H:i:s')
            ]);
        } else {
            log_message('error', 'No se pudo enviar la alerta de consumo a ' . $email);
        }
    }
}





    public function getLatestDataByMac($mac)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }

        $mac = strtoupper($mac);
        $mac = implode(':', str_split($mac, 2));
        $dispositivo = $this->dispositivoModel->where('mac_address', $mac)->first();

        if (!$dispositivo) {
            return $this->response->setJSON(['error' => 'Dispositivo no encontrado'])->setStatusCode(404);
        }

        $ultimaLectura = $this->energiaModel->where('id_dispositivo', $dispositivo['id_dispositivo'])
                                          ->orderBy('fecha', 'DESC')
                                          ->first();

        if (!$ultimaLectura) {
            return $this->response->setJSON(['error' => 'No hay lecturas disponibles'])->setStatusCode(404);
        }

        // ---------------------- NUEVO: LÍMITE ----------------------
        $this->verificarLimite($ultimaLectura, $dispositivo['id_dispositivo'], $dispositivo['id_usuario']);
        // ----------------------------------------------------------------------

        return $this->response->setJSON([
            'success' => true,
            'data' => $ultimaLectura,
            'dispositivo' => $dispositivo
        ]);
    }

    public function getLatestDataByDevice($id_dispositivo)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
        }

        $dispositivo = $this->dispositivoModel->find($id_dispositivo);
        if (!$dispositivo) {
            return $this->response->setJSON(['success' => false, 'error' => 'Dispositivo no encontrado']);
        }

        $idUsuario = session()->get('id_usuario');
        $idRol = session()->get('id_rol');

        if ($dispositivo['id_usuario'] !== $idUsuario && $idRol != 1 && $idRol != 3) {
            return $this->response->setJSON(['success' => false, 'error' => 'No tienes permiso para ver este dispositivo'])->setStatusCode(403);
        }

        $lectura = $this->energiaModel->where('id_dispositivo', $id_dispositivo)
                              ->orderBy('fecha', 'DESC')
                              ->first();

        if (!$lectura) {
            return $this->response->setJSON(['success' => false, 'error' => 'No hay lecturas disponibles']);
        }

        // ---------------------- NUEVO: LÍMITE ----------------------
        $this->verificarLimite($lectura, $id_dispositivo, $dispositivo['id_usuario']);
        // ----------------------------------------------------------------------

        $limite = $this->limiteModel->getLimiteByDispositivo($id_dispositivo);
        $limiteConsumo = $limite ? $limite['limite_consumo'] : 10;

        return $this->response->setJSON([
            'success' => true,
            'data' => $lectura,
            'limite_consumo' => $limiteConsumo
        ]);
    }

    // ---------------------- FUNCIONES AUXILIARES NUEVAS ----------------------
    private function verificarLimite(&$lectura, $id_dispositivo, $idUsuario)
{
    $limite = $this->limiteModel->getLimiteByDispositivo($id_dispositivo);
    if ($limite && $lectura['kwh'] > $limite['limite_consumo']) {
        $lectura['limite_superado'] = 1;
        if (isset($lectura['id'])) {
            $this->energiaModel->update($lectura['id'], ['limite_superado' => 1]);
        }

        // Enviar notificación por email (controlando frecuencia)
        if (!$limite['notificacion_enviada'] || (strtotime($limite['ultima_notificacion']) < strtotime('-1 hour'))) {
            $this->enviarNotificacionEmail($idUsuario, $lectura['kwh'], $limite['limite_consumo']);
            $this->limiteModel->actualizarNotificacion($limite['id']);
        }

        // ------------------ ALERTA TELEGRAM MEJORADA ------------------
        $dispositivo = $this->dispositivoModel->find($id_dispositivo);
        $nombreDispositivo = $dispositivo ? $dispositivo['nombre'] : "Dispositivo ID $id_dispositivo";
        
        $mensaje = "🚨 *ALERTA DE CONSUMO EXCESIVO*\n\n";
        $mensaje .= "🔌 *Dispositivo:* {$nombreDispositivo}\n";
        $mensaje .= "📏 *Límite configurado:* {$limite['limite_consumo']} kWh\n";
        $mensaje .= "⚡ *Consumo actual:* " . number_format($lectura['kwh'], 4) . " kWh\n";
        $mensaje .= "📅 *Fecha:* " . date('d/m/Y H:i:s') . "\n\n";
        $mensaje .= "⚠️ *Acción requerida:*\n";
        $mensaje .= "• Verificar dispositivos conectados\n";
        $mensaje .= "• Revisar configuración de límites\n";
        $mensaje .= "• Considerar desconectar equipos no esenciales\n\n";
        $mensaje .= "🔗 *Panel de control:*\n";
        $mensaje .= base_url('energia/dispositivo/' . $id_dispositivo) . "\n\n";
        $mensaje .= "💡 *Usa /start para ver opciones del bot*";
        
        $this->alertaTelegram($mensaje);
        // ----------------------------------------------------------------
    }
}



// Dentro de la clase Energia existente
public function generarPDF($dispositivo_id)
{
    $db = \Config\Database::connect();
    
    // Parámetros: mes=YYYY-MM y tarifa=0.15 (opcional)
    $mesParam = $this->request->getGet('mes');
    // Prioridad de tarifa: sesión -> GET -> por defecto
    $tarifaSession = session()->get('tarifa_kwh');
    $tarifaParam = $this->request->getGet('tarifa');

    // Rango de fechas basado en el mes solicitado o el mes actual
    if ($mesParam && preg_match('/^\\d{4}-\\d{2}$/', $mesParam)) {
        $inicioMes = date('Y-m-01 00:00:00', strtotime($mesParam . '-01'));
        $finMes = date('Y-m-t 23:59:59', strtotime($mesParam . '-01'));
    } else {
        // Mes actual por defecto
        $inicioMes = date('Y-m-01 00:00:00');
        $finMes = date('Y-m-t 23:59:59');
    }

    // Obtener datos del usuario y dispositivo
    $usuario = $db->table('usuario')
        ->join('dispositivos', 'dispositivos.id_usuario = usuario.id_usuario')
        ->where('dispositivos.id_dispositivo', $dispositivo_id)
        ->select('usuario.*, dispositivos.nombre as nombre_dispositivo')
        ->get()
        ->getRowArray();

    log_message('info', "PDF: dispositivo={$dispositivo_id} mes={$mesParam} rango={$inicioMes} -> {$finMes}");

    // Obtener lecturas dentro del rango del mes solicitado
    $lecturas = $db->table('energia')
        ->where('id_dispositivo', $dispositivo_id)
        ->where('fecha >=', $inicioMes)
        ->where('fecha <=', $finMes)
        ->orderBy('fecha', 'ASC')
        ->get()
        ->getResultArray();

    $totalLecturas = count($lecturas);
    log_message('info', 'PDF: totalLecturas=' . $totalLecturas);

    // Fallback: si no hay lecturas y no se especificó mes, usar el último mes con datos
    if ($totalLecturas === 0 && empty($mesParam)) {
        $ultima = $db->table('energia')
            ->select('fecha')
            ->where('id_dispositivo', $dispositivo_id)
            ->orderBy('fecha', 'DESC')
            ->get(1)
            ->getRowArray();
        if ($ultima) {
            $mesUltimo = date('Y-m', strtotime($ultima['fecha']));
            $inicioMes = date('Y-m-01 00:00:00', strtotime($mesUltimo . '-01'));
            $finMes = date('Y-m-t 23:59:59', strtotime($mesUltimo . '-01'));
            log_message('info', "PDF: sin datos en mes actual, fallback a mes={$mesUltimo} rango={$inicioMes} -> {$finMes}");

            $lecturas = $db->table('energia')
                ->where('id_dispositivo', $dispositivo_id)
                ->where('fecha >=', $inicioMes)
                ->where('fecha <=', $finMes)
                ->orderBy('fecha', 'ASC')
                ->get()
                ->getResultArray();
            $totalLecturas = count($lecturas);
            log_message('info', 'PDF: totalLecturas (fallback)=' . $totalLecturas);
        }
    }

    $promedios = [
        'voltaje' => 0,
        'corriente' => 0,
        'potencia' => 0
    ];

    $total_kwh = 0;

    if ($totalLecturas > 0) {
        foreach ($lecturas as $l) {
            $promedios['voltaje'] += (float)$l['voltaje'];
            $promedios['corriente'] += (float)$l['corriente'];
            $promedios['potencia'] += (float)$l['potencia'];
            $total_kwh += (float)$l['kwh'];
        }
        foreach ($promedios as $key => $value) {
            $promedios[$key] = $value / $totalLecturas; // promedio
        }
    }
    log_message('info', 'PDF: total_kwh=' . $total_kwh . ' v_prom=' . ($promedios['voltaje'] ?: 0) . ' i_prom=' . ($promedios['corriente'] ?: 0) . ' p_prom=' . ($promedios['potencia'] ?: 0));

    // Resumen diario (SUM kWh y promedios diarios básicos)
    $resumenDiario = $db->table('energia')
        ->select("DATE(fecha) as dia, SUM(kwh) as kwh_dia, AVG(voltaje) as voltaje_prom, AVG(corriente) as corriente_prom, AVG(potencia) as potencia_prom")
        ->where('id_dispositivo', $dispositivo_id)
        ->where('fecha >=', $inicioMes)
        ->where('fecha <=', $finMes)
        ->groupBy('DATE(fecha)')
        ->orderBy('dia', 'ASC')
        ->get()
        ->getResultArray();

    // Calcular precio aproximado con precio por kWh
    // Prioridad: GET -> sesión. Si no hay, se bloquea la generación.
    if (is_numeric($tarifaParam)) {
        $precioKwh = (float)$tarifaParam;
    } elseif (is_numeric($tarifaSession)) {
        $precioKwh = (float)$tarifaSession;
    } else {
        // Sin tarifa: obligar a ingresar precio de kWh antes de generar
        return redirect()->to(base_url('energia/dispositivo/' . $dispositivo_id))
            ->with('error', 'Debes ingresar el precio del kWh antes de descargar el PDF.');
    }
    $precioTotal = $precioKwh * $total_kwh;
    log_message('info', 'PDF: tarifaParam=' . var_export($tarifaParam, true) . ' tarifaSession=' . var_export($tarifaSession, true));
    log_message('info', 'PDF: precioKwh=' . $precioKwh . ' precioTotal=' . $precioTotal);

    // Generar informe textual profesional
    $mesTitulo = date('F Y', strtotime($inicioMes));
    $informeTexto = "Durante el mes de <b>{$mesTitulo}</b>, el dispositivo <b>'{$usuario['nombre_dispositivo']}'</b> ha mostrado un consumo energético ";
    if ($totalLecturas > 0) {
        $voltProm = number_format($promedios['voltaje'], 2);
        $corrProm = number_format($promedios['corriente'], 2);
        $potProm = number_format($promedios['potencia'], 2);
        $total_kwh_fmt = number_format($total_kwh, 2);
        $precio_fmt = number_format($precioTotal, 2);

        $informeTexto .= "estable con un voltaje promedio de <b>$voltProm V</b>, corriente promedio de <b>$corrProm A</b> y potencia promedio de <b>$potProm W</b>. ";
        $informeTexto .= "La energía total consumida fue de <b>$total_kwh_fmt kWh</b>, lo que equivale aproximadamente a <b>$$precio_fmt</b> según el precio por kWh configurado.";
        $informeTexto .= " Se recomienda revisar los picos de consumo para optimizar el uso de energía y reducir costos.";
    } else {
        $informeTexto .= "y no se registraron lecturas durante este período.";
    }

    // Generar HTML del PDF
    $html = view('energia/pdf', [
        'usuario' => $usuario,
        'lecturas' => $lecturas,
        'promedios' => $promedios,
        'total_kwh' => $total_kwh,
        'precioTotal' => $precioTotal,
        'precioKwh' => $precioKwh,
        'informeTexto' => $informeTexto,
        'resumenDiario' => $resumenDiario,
    ]);

    // Crear PDF
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $nombreMes = date('Y_m', strtotime($inicioMes));
    $dompdf->stream("Informe_{$usuario['nombre']}_{$usuario['nombre_dispositivo']}_{$nombreMes}.pdf", ["Attachment" => true]);
}




    private function enviarNotificacionEmail($idUsuario, $consumoActual, $limite)
    {
        $email = \Config\Services::email();
        $user = $this->userModel->find($idUsuario);

        $email->setFrom('noreply@ecovolt.com', 'EcoVolt');
        $email->setTo($user['email']);
        $email->setSubject('⚠️ Alerta de Consumo de Energía');

        $data = [
            'nombre' => $user['nombre'] ?? null,
            'consumoActual' => $consumoActual,
            'limiteConfigurado' => $limite,
            'idDispositivo' => null,
            'dispositivoNombre' => null,
            'momento' => date('Y-m-d H:i:s'),
            'urlPanel' => base_url('energia')
        ];

        $html = view('emails/alerta_consumo', $data);
        $email->setMessage($html);
        $email->setMailType('html');
        $email->send();
    }

    // Se eliminaron los métodos de notificaciones Push
    public function dispositivo($id_dispositivo)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $dispositivo = $this->dispositivoModel->find($id_dispositivo);

        if (!$dispositivo) {
            return redirect()->to('/energia')->with('error', 'Dispositivo no encontrado');
        }

        $lecturas = $this->energiaModel
            ->where('id_dispositivo', $id_dispositivo)
            ->orderBy('fecha', 'DESC')
            ->limit(50)
            ->findAll();

        $limite = $this->limiteModel->getLimiteByDispositivo($id_dispositivo);
        $limite_consumo = $limite ? $limite['limite_consumo'] : 10;

        return view('energia/dispositivo', [
            'lecturas' => $lecturas,
            'dispositivo' => $dispositivo,
            'limite_consumo' => $limite_consumo
        ]);
    }

    public function setTarifa()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        if (!isset($data['tarifa_kwh']) || !is_numeric($data['tarifa_kwh'])) {
            return $this->response->setJSON(['success' => false, 'error' => 'Tarifa inválida'])->setStatusCode(400);
        }
        session()->set('tarifa_kwh', (float)$data['tarifa_kwh']);
        return $this->response->setJSON(['success' => true]);
    }

    public function actualizarDispositivo()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
        }

        $id = $this->request->getPost('id_dispositivo');
        $nombre = trim((string)$this->request->getPost('nombre'));
        $descripcion = (string)$this->request->getPost('descripcion');

        if (!$id || $nombre === '') {
            return $this->response->setJSON(['success' => false, 'error' => 'Datos inválidos'])->setStatusCode(400);
        }

        $dispositivo = $this->dispositivoModel->find($id);
        if (!$dispositivo) {
            return $this->response->setJSON(['success' => false, 'error' => 'Dispositivo no encontrado'])->setStatusCode(404);
        }

        // Verificar permiso: dueño o admin/supervisor
        $idUsuario = session()->get('id_usuario');
        $idRol = session()->get('id_rol');
        if ($dispositivo['id_usuario'] != $idUsuario && $idRol != 1 && $idRol != 3) {
            return $this->response->setJSON(['success' => false, 'error' => 'Sin permisos'])->setStatusCode(403);
        }

        $this->dispositivoModel->update($id, [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_actualizacion' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Dispositivo actualizado']);
    }
public function alertaTelegram($mensaje)
{
    $token = "7316812708:AAHf-eFsfkckmEnIgDPaadEYhSLjeOxOBl0";
    $chat_id = "6746907650";
    $api = "https://api.telegram.org/bot$token/sendMessage";
    $payload = [
        'chat_id' => $chat_id,
        'text' => $mensaje,
        'parse_mode' => 'Markdown',
        'disable_web_page_preview' => true,
    ];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($payload)
        ]
    ];
    $context  = stream_context_create($options);
    @file_get_contents($api, false, $context);
}
public function saveSubscription()
{
    $data = $this->request->getJSON(true);
    $userId = session()->get('id_usuario');
    
    // Guardar subscription en DB
    $db = \Config\Database::connect();
    $builder = $db->table('subscriptions');
    $builder->replace([
        'id_usuario' => $userId,
        'subscription' => json_encode($data)
    ]);

    return $this->response->setJSON(['success' => true]);
}
}


