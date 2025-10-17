<?php

namespace App\Controllers;

use App\Models\EnergiaModel;
use App\Models\DispositivoModel;
use App\Models\LimiteConsumoModel;
use App\Models\UsuarioModel;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * CONTROLADOR DE ENERGÍA - EcoVolt
 * 
 * Este controlador maneja todas las operaciones relacionadas con el monitoreo
 * y gestión de consumo de energía eléctrica de los dispositivos IoT.
 * 
 * FUNCIONALIDADES PRINCIPALES:
 * - Visualización de datos de consumo en tiempo real
 * - Recepción de datos de dispositivos ESP32
 * - Gestión de límites de consumo
 * - Notificaciones por email y Telegram
 * - Generación de reportes PDF
 * - Filtrado y paginación de lecturas
 * - Cálculo de costos basado en tarifas
 * 
 * ENDPOINTS PRINCIPALES:
 * - index(): Dashboard principal de energía
 * - recibirNuevosDatos(): API para recibir datos de ESP32
 * - getLatestData(): Obtener última lectura en tiempo real
 * - actualizarLimite(): Configurar límites de consumo
 * - generarPDF(): Generar reportes de consumo
 * - getlimite(): API pública para ESP32 obtener límites
 * 
 * NOTIFICACIONES:
 * - Email: Alertas cuando se supera el límite de consumo
 * - Telegram: Notificaciones en tiempo real al bot
 * - Control de frecuencia: Evita spam de notificaciones
 */
class Energia extends BaseController
{
    // ==================== PROPIEDADES DEL CONTROLADOR ====================
    
    /** @var EnergiaModel Modelo para operaciones de lecturas de energía */
    protected $energiaModel;
    
    /** @var DispositivoModel Modelo para operaciones de dispositivos */
    protected $dispositivoModel;
    
    /** @var LimiteConsumoModel Modelo para gestión de límites */
    protected $limiteModel;
    
    /** @var UsuarioModel Modelo para operaciones de usuarios */
    protected $userModel; 

    /**
     * Constructor del controlador
     * Inicializa todos los modelos necesarios
     */
    public function __construct()
    {
        $this->energiaModel = new EnergiaModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->limiteModel = new LimiteConsumoModel();
        $this->userModel = new UsuarioModel();
    }

    // ==================== MÉTODOS PRINCIPALES ====================
    
    /**
     * Dashboard principal de energía
     * 
     * Muestra las últimas 50 lecturas del dispositivo más reciente del usuario.
     * Si no tiene dispositivos, redirige a la página de dispositivos.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\View\View
     */
    public function index()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        try {
            $idUsuario = session()->get('id_usuario');
            
            // Obtener dispositivos del usuario
            $dispositivos = $this->dispositivoModel->where('id_usuario', $idUsuario)->findAll();
            
            if (empty($dispositivos)) {
                return redirect()->to('/dispositivos')->with('error', 'No tienes dispositivos registrados');
            }

            // Usar el dispositivo más reciente
            $ultimoDispositivo = end($dispositivos);
            
            // Obtener las últimas 50 lecturas del dispositivo
            $lecturas = $this->energiaModel->where('id_dispositivo', $ultimoDispositivo['id_dispositivo'])
                                         ->orderBy('fecha', 'DESC')
                                         ->limit(50)
                                         ->findAll();

            // Obtener límite de consumo configurado
            $limite = $this->limiteModel->getLimiteByDispositivo($ultimoDispositivo['id_dispositivo']);
            $limite_consumo = $limite ? $limite['limite_consumo'] : 10; // Valor por defecto: 10 kWh

            log_message('info', 'Dispositivo ID: ' . $ultimoDispositivo['id_dispositivo']);
            log_message('info', 'Número de lecturas encontradas: ' . count($lecturas));

            // Cargar la vista con los datos
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

    /**
     * Obtiene la última lectura de energía en tiempo real (AJAX)
     * 
     * Usado por el frontend para actualizar datos sin recargar la página.
     * Retorna la última lectura del dispositivo más reciente del usuario.
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface Respuesta JSON con los datos
     */
    public function getLatestData()
    {
        // Verificar autenticación
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'No autorizado'])->setStatusCode(401);
        }

        $idUsuario = session()->get('id_usuario');
        
        // Obtener el dispositivo más reciente del usuario
        $dispositivo = $this->dispositivoModel->where('id_usuario', $idUsuario)
                                             ->orderBy('id_dispositivo', 'DESC')
                                             ->first();

        if (!$dispositivo) {
            return $this->response->setJSON(['error' => 'No se encontró dispositivo'])->setStatusCode(404);
        }

        // Obtener la última lectura del dispositivo
        $ultimaLectura = $this->energiaModel->where('id_dispositivo', $dispositivo['id_dispositivo'])
                                           ->orderBy('fecha', 'DESC')
                                           ->first();

        if (!$ultimaLectura) {
            return $this->response->setJSON(['error' => 'No hay lecturas disponibles'])->setStatusCode(404);
        }

        // Verificar si se superó el límite de consumo
        $limite = $this->limiteModel->getLimiteByDispositivo($dispositivo['id_dispositivo']);
        $limiteConsumo = $limite ? $limite['limite_consumo'] : 10;
        $ultimaLectura['limite_superado'] = $ultimaLectura['kwh_acumulado'] > $limiteConsumo;

        return $this->response->setJSON([
            'success' => true,
            'data' => $ultimaLectura,
            'limite_consumo' => $limiteConsumo
        ]);
    }



    /**
     * Recibe datos de consumo de energía desde dispositivos ESP32
     * 
     * ENDPOINT API para que los dispositivos IoT envíen sus lecturas.
     * Valida los datos, verifica límites y envía notificaciones si es necesario.
     * 
     * DATOS REQUERIDOS:
     * - voltaje: Voltaje medido en voltios
     * - corriente: Corriente medida en amperios  
     * - potencia: Potencia calculada en watts
     * - kwh: Consumo en kilovatios-hora
     * - mac_address: Dirección MAC del dispositivo
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface Respuesta JSON con el resultado
     */
    public function recibirNuevosDatos()
    {
        try {
            // Obtener datos JSON del request
            $data = $this->request->getJSON(true);

            if (!$data) {
                return $this->response->setJSON(['error' => 'No se recibieron datos'])->setStatusCode(400);
            }

            // Validar campos requeridos
            $requiredFields = ['voltaje', 'corriente', 'potencia', 'kwh_acumulado', 'mac_address'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    return $this->response->setJSON(['error' => "Campo requerido faltante: $field"])->setStatusCode(400);
                }
            }

            // Formatear MAC address (quitar separadores y agregar :)
            $mac_sin_formato = strtoupper($data['mac_address']);
            $mac_formateada = implode(':', str_split($mac_sin_formato, 2));
            
            // Buscar el dispositivo por MAC
            $dispositivo = $this->dispositivoModel->where('mac_address', $mac_formateada)->first();
            if (!$dispositivo) {
                return $this->response->setJSON(['error' => 'Dispositivo no encontrado'])->setStatusCode(404);
            }

            // Preparar datos de la lectura
            $lectura = [
                'id_dispositivo' => $dispositivo['id_dispositivo'],
                'id_usuario' => $dispositivo['id_usuario'],
                'voltaje' => $data['voltaje'],
                'corriente' => $data['corriente'],
                'potencia' => $data['potencia'],
               'kwh_acumulado' => $data['kwh_acumulado'], // Compatibilidad
                'mac_address' => $mac_formateada,
                'fecha' => date('Y-m-d H:i:s'),
                'limite_superado' => 0
            ];

            // Verificar límite de consumo y enviar notificaciones si es necesario
            // Esta es la ÚNICA vez que se debe verificar el límite para evitar spam
            $this->verificarLimite($lectura, $dispositivo['id_dispositivo'], $dispositivo['id_usuario']);

            // Insertar la lectura en la base de datos
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


/**
 * Obtiene el último kWh acumulado desde la base de datos
 * Endpoint público para que el ESP32 continúe desde el último valor
 * 
 * @return \CodeIgniter\HTTP\ResponseInterface
 */
public function getUltimoKwh()
{
    try {
        $db = \Config\Database::connect();
        
        // Obtener parámetros
        $mac_address = $this->request->getGet('mac') ?? null;
        $ip_address = $this->request->getIPAddress();
        
        // Buscar el último registro por MAC si se proporciona
        if ($mac_address) {
            $mac_formateada = implode(':', str_split(strtoupper($mac_address), 2));
            
            $query = $db->query("
                SELECT kwh_acumulado 
                FROM energia 
                WHERE mac_address = ? 
                ORDER BY fecha DESC, id DESC 
                LIMIT 1
            ", [$mac_formateada]);
        } else {
            // Si no hay MAC, obtener el último registro general
            $query = $db->query("
                SELECT kwh_acumulado 
                FROM energia 
                ORDER BY fecha DESC, id DESC 
                LIMIT 1
            ");
        }
        
        $result = $query->getRow();
        
        if ($result && $result->kwh_acumulado > 0) {
            $ultimo_kwh = (float)$result->kwh_acumulado;
            log_message('info', "Último kWh obtenido: $ultimo_kwh para MAC: $mac_address");
            
            return $this->response->setJSON([
                'success' => true,
                'ultimo_kwh' => $ultimo_kwh,
                'mac_address' => $mac_address,
                'timestamp' => date('Y-m-d H:i:s'),
                'ip_address' => $ip_address
            ]);
        } else {
            // Si no hay registros, devolver 0
            return $this->response->setJSON([
                'success' => true,
                'ultimo_kwh' => 0.0,
                'message' => 'No se encontraron registros previos, empezando desde 0',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }
        
    } catch (\Exception $e) {
        log_message('error', 'Error en getUltimoKwh: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Error al obtener último kWh',
            'ultimo_kwh' => 0.0,
            'timestamp' => date('Y-m-d H:i:s')
        ])->setStatusCode(500);
    }
}


    public function actualizarLimite()
{
        // Solo admin o supervisor
        $rol = session()->get('rol');
        if ($rol !== 'admin' && $rol !== 'supervisor') {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No autorizado'
            ])->setStatusCode(403);
        }
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
        $configEmail = new \Config\Email();
        $emailObj->setFrom($configEmail->fromEmail, $configEmail->fromName);
        $emailObj->setTo($email);
        $emailObj->setSubject('Confirmación de límite de consumo');
        $emailObj->setMailType('html');
        $emailObj->setMessage("<p>Se ha configurado un límite de consumo de <b>$limite kWh</b> para su dispositivo.</p>");

        if (!$emailObj->send()) {
            log_message('error', 'Fallo email Confirmación de límite: ' . print_r($emailObj->printDebugger(['headers','subject']), true));
        }
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
        $configEmail = new \Config\Email();
        $emailObj->setFrom($configEmail->fromEmail, $configEmail->fromName);
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
            log_message('error', 'No se pudo enviar la alerta de consumo a ' . $email . ' - ' . print_r($emailObj->printDebugger(['headers','subject']), true));
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

        // ---------------------- LÍMITE (SOLO VERIFICAR, NO NOTIFICAR EN CONSULTAS) ----------------------
        // Removido: No verificar límites en consultas para evitar spam
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

        // ---------------------- LÍMITE (SOLO VERIFICAR, NO NOTIFICAR EN CONSULTAS) ----------------------
        // Removido: No verificar límites en consultas para evitar spam
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
        if ($limite && $lectura['kwh_acumulado'] > $limite['limite_consumo']) {
            $lectura['limite_superado'] = 1;
            if (isset($lectura['id'])) {
                $this->energiaModel->update($lectura['id'], ['limite_superado' => 1]);
            }

            // Registrar corte de línea en la base de datos
            $corteModel = new \App\Models\CorteLineaModel();
            $corteModel->registrarCorte(
                $id_dispositivo, 
                $idUsuario, 
                $lectura['kwh_acumulado'], 
                $limite['limite_consumo']
            );

            // Enviar notificaciones (email Y telegram) controlando frecuencia - SOLO UNA VEZ POR HORA
            if (!$limite['notificacion_enviada'] || (strtotime($limite['ultima_notificacion']) < strtotime('-1 hour'))) {
                
                // 📧 ENVIAR EMAIL
                $this->enviarNotificacionEmail($idUsuario, $lectura['kwh_acumulado'], $limite['limite_consumo'], $id_dispositivo);
                
                // 📱 ENVIAR TELEGRAM (en el mismo bloque para evitar spam)
                $dispositivo = $this->dispositivoModel->find($id_dispositivo);
                $nombreDispositivo = $dispositivo ? $dispositivo['nombre'] : "Dispositivo ID $id_dispositivo";
                
                $mensaje = "🚨 *ALERTA DE CONSUMO EXCESIVO*\n\n";
                $mensaje .= "🔌 *Dispositivo:* {$nombreDispositivo}\n";
                $mensaje .= "📏 *Límite configurado:* {$limite['limite_consumo']} kWh\n";
                $mensaje .= "⚡ *Consumo actual:* " . number_format($lectura['kwh_acumulado'], 4) . " kWh\n";
                $mensaje .= "📅 *Fecha:* " . date('d/m/Y H:i:s') . "\n\n";
                $mensaje .= "⚠️ *Acción requerida:*\n";
                $mensaje .= "• Verificar dispositivos conectados\n";
                $mensaje .= "• Revisar configuración de límites\n";
                $mensaje .= "• Considerar desconectar equipos no esenciales\n\n";
                $mensaje .= "🔗 *Panel de control:*\n";
                $mensaje .= base_url('energia/dispositivo/' . $id_dispositivo) . "\n\n";
                $mensaje .= "💡 *Usa /start para ver opciones del bot*";
                
                $this->alertaTelegram($mensaje);
                
                // ✅ ACTUALIZAR BD - Solo después de enviar AMBAS notificaciones
                $this->limiteModel->actualizarNotificacion($limite['id']);
            }
        } else if ($limite) {
            // Prealerta: si supera el 90% del límite pero aún no lo pasó
            $umbralPrealerta = 0.9 * (float)$limite['limite_consumo'];
            $corteModel = new \App\Models\CorteLineaModel();

            if ($lectura['kwh_acumulado'] >= $umbralPrealerta && $lectura['kwh_acumulado'] < (float)$limite['limite_consumo']) {
                log_message('info', '[PREALERTA] Evaluando prealerta. consumo=' . $lectura['kwh_acumulado'] . ' umbral90=' . $umbralPrealerta . ' limite=' . $limite['limite_consumo'] . ' dispositivo=' . $id_dispositivo);
                // Anti-spam: solo una vez cada 24 horas por dispositivo
                $ultimaPrealerta = $corteModel->getUltimaPrealerta($id_dispositivo);
                $hace24hs = strtotime('-24 hours');
                $ultimaFecha = $ultimaPrealerta ? strtotime($ultimaPrealerta['fecha_corte']) : 0;
                log_message('info', '[PREALERTA] Última prealerta datetime=' . ($ultimaPrealerta['fecha_corte'] ?? 'NULL') . ' ts=' . $ultimaFecha . ' vs hace24=' . $hace24hs);

                if ($ultimaFecha < $hace24hs) {
                    log_message('info', '[PREALERTA] Enviando prealerta y registrando en BD...');
                    // Registrar/actualizar prealerta
                    $corteModel->registrarPrealerta($id_dispositivo, $idUsuario, $lectura['kwh_acumulado'], $limite['limite_consumo']);

                    // Enviar correo de prealerta
                    $this->enviarEmailPrealerta($idUsuario, $id_dispositivo, $lectura['kwh_acumulado'], $limite['limite_consumo']);

                    // Telegram opcional (no invasivo)
                    $dispositivo = $this->dispositivoModel->find($id_dispositivo);
                    $nombreDispositivo = $dispositivo ? $dispositivo['nombre'] : "Dispositivo ID $id_dispositivo";
                    $mensaje = "⚠️ *PREALERTA DE CONSUMO*\n\n";
                    $mensaje .= "🔌 *Dispositivo:* {$nombreDispositivo}\n";
                    $mensaje .= "📏 *Límite:* {$limite['limite_consumo']} kWh\n";
                    $mensaje .= "⚡ *Consumo actual:* " . number_format($lectura['kwh_acumulado'], 4) . " kWh\n";
                    $mensaje .= "⛽ *Umbral prealerta (90%):* " . number_format($umbralPrealerta, 4) . " kWh\n";
                    $mensaje .= "📅 *Fecha:* " . date('d/m/Y H:i:s') . "\n\n";
                    $mensaje .= "🔗 *Panel:*\n" . base_url('energia/dispositivo/' . $id_dispositivo);
                    $this->alertaTelegram($mensaje);
                }
                else {
                    log_message('info', '[PREALERTA] Omitida por anti-spam (enviada hace menos de 24h).');
                }
            } else {
                log_message('info', '[PREALERTA] No aplica: consumo por debajo del 90% o ya en límite/superado. consumo=' . $lectura['kwh_acumulado'] . ' umbral90=' . $umbralPrealerta . ' limite=' . $limite['limite_consumo']);
                // Si está por debajo del 90%, marcar prealertas como resueltas
                $corteModel->marcarPrealertaResuelta($id_dispositivo);
            }

            // Si el consumo está dentro del límite, marcar cortes como resueltos
            $corteModel->marcarComoResuelto($id_dispositivo);
        }
    }

    private function enviarEmailPrealerta($idUsuario, $idDispositivo, $consumoActual, $limite)
    {
        $limiteModel = new \App\Models\LimiteConsumoModel();
        $limiteData = $limiteModel->getLimiteByDispositivo($idDispositivo);
        if (!$limiteData || !$limiteData['email_notificacion']) {
            return;
        }

        $email = \Config\Services::email();
        $user = $this->userModel->find($idUsuario);
        $configEmail = new \Config\Email();
        $dispositivo = $this->dispositivoModel->find($idDispositivo);

        $email->setFrom($configEmail->fromEmail, $configEmail->fromName);
        $email->setTo($limiteData['email_notificacion']);
        $email->setSubject('⚠️ Prealerta: consumo acercándose al límite');

        $html = view('emails/alerta_prealerta', [
            'nombre' => $user['nombre'] ?? null,
            'consumoActual' => $consumoActual,
            'limiteConfigurado' => $limite,
            'idDispositivo' => $idDispositivo,
            'dispositivoNombre' => $dispositivo['nombre'] ?? null,
            'momento' => date('Y-m-d H:i:s'),
            'urlPanel' => base_url('energia/dispositivo/' . $idDispositivo)
        ]);
        $email->setMessage($html);
        $email->setMailType('html');
        $email->send();
    }



public function generarPDF($dispositivo_id)
{
    // Verificar que el usuario esté logueado
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('login'))
            ->with('error', 'Debes iniciar sesión para generar PDFs.');
    }

    try {
        $db = \Config\Database::connect();
        
        // Obtener datos básicos del dispositivo
        $usuario = $db->table('usuario')
            ->join('dispositivos', 'dispositivos.id_usuario = usuario.id_usuario')
            ->where('dispositivos.id_dispositivo', $dispositivo_id)
            ->select('usuario.*, dispositivos.nombre as nombre_dispositivo')
            ->get()
            ->getRowArray();

        if (!$usuario) {
            return redirect()->to(base_url('energia/dispositivo/' . $dispositivo_id))
                ->with('error', 'Dispositivo no encontrado.');
        }

        // Verificar que el dispositivo pertenezca al usuario logueado
        if ($usuario['id_usuario'] != session()->get('id_usuario')) {
            return redirect()->to(base_url('energia'))
                ->with('error', 'No tienes permisos para generar PDFs de este dispositivo.');
        }

        // Obtener lecturas recientes (limitado)
        $lecturas = $db->table('energia')
            ->where('id_dispositivo', $dispositivo_id)
            ->orderBy('fecha', 'DESC')
            ->limit(30)
            ->get()
            ->getResultArray();

        // Calcular promedios
        $promedios = [
            'voltaje' => 0,
            'corriente' => 0,
            'potencia' => 0
        ];
        
        // CORRECCIÓN: kwh_acumulado ya es acumulado, no sumar todas las lecturas
        $total_kwh = 0;
        $consumoDiario = [];
        $picoPotencia = ['valor' => 0.0, 'fecha' => null];
        if (!empty($lecturas)) {
            $suma_voltaje = 0;
            $suma_corriente = 0;
            $suma_potencia = 0;
            
            // El total_kwh debe ser el ÚLTIMO valor de kwh_acumulado, no la suma
            $total_kwh = (float)$lecturas[0]['kwh_acumulado']; // Primera lectura (más reciente)
            
            foreach ($lecturas as $l) {
                $suma_voltaje += (float)$l['voltaje'];
                $suma_corriente += (float)$l['corriente'];
                $suma_potencia += (float)$l['potencia'];
                // NO sumar kwh_acumulado aquí, ya es acumulado

                // Agrupar por día para consumo diario
                $dia = date('Y-m-d', strtotime($l['fecha']));
                if (!isset($consumoDiario[$dia])) {
                    $consumoDiario[$dia] = 0.0;
                }
                // Para consumo diario, usar la diferencia entre lecturas consecutivas
                // Por simplicidad, usaremos el valor de kwh_acumulado de cada día
                $consumoDiario[$dia] = (float)$l['kwh_acumulado'];

                // Detectar pico de potencia
                if ((float)$l['potencia'] > $picoPotencia['valor']) {
                    $picoPotencia['valor'] = (float)$l['potencia'];
                    $picoPotencia['fecha'] = $l['fecha'];
                }
            }
            
            $promedios['voltaje'] = $suma_voltaje / count($lecturas);
            $promedios['corriente'] = $suma_corriente / count($lecturas);
            $promedios['potencia'] = $suma_potencia / count($lecturas);
            // Ordenar consumo diario por fecha ascendente
            ksort($consumoDiario);
        }

        // Obtener tarifa
        $tarifaSession = session()->get('tarifa_kwh');
        $tarifaParam = $this->request->getGet('tarifa');
        $precioKwh = is_numeric($tarifaParam) ? (float)$tarifaParam : (is_numeric($tarifaSession) ? (float)$tarifaSession : 150.0);
        $precioTotal = $precioKwh * $total_kwh;

        // Obtener información de cortes de línea
        $corteModel = new \App\Models\CorteLineaModel();
        $cortes = $corteModel->where('id_dispositivo', $dispositivo_id)
                            ->orderBy('fecha_corte', 'DESC')
                            ->limit(10)
                            ->findAll();
        
        $estadisticasCortes = $corteModel->getEstadisticasCortes($usuario['id_usuario']);

        // Totales mensuales (para comparativo en PDF) - usar tabla de resumen
        $rowsMensuales = $db->table('energia_resumen_diario')
            ->select("DATE_FORMAT(fecha, '%Y-%m') AS ym, MAX(kwh_fin_dia) AS total_kwh", false)
            ->where('id_dispositivo', $dispositivo_id)
            ->groupBy("DATE_FORMAT(fecha, '%Y-%m')", false)
            ->orderBy("DATE_FORMAT(fecha, '%Y-%m')", 'ASC', false)
            ->get()->getResultArray();

        $totalesMensuales = [];
        $prev = null;
        foreach ($rowsMensuales as $r) {
            $label = date('m/Y', strtotime($r['ym'].'-01'));
            $kwhMes = (float)$r['total_kwh'];
            $variacion = null;
            if ($prev !== null && $prev > 0) {
                $variacion = (($kwhMes - $prev) / $prev) * 100.0;
            }
            $totalesMensuales[] = [
                'ym' => $r['ym'],
                'label' => $label,
                'kwh' => $kwhMes,
                'variacion' => $variacion,
            ];
            $prev = $kwhMes;
        }

        // Generar texto del informe
        $informeTexto = "Informe de consumo energético para el dispositivo <b>{$usuario['nombre_dispositivo']}</b>. ";
        if (!empty($lecturas)) {
            $informeTexto .= "Basado en las últimas " . count($lecturas) . " lecturas, el consumo acumulado actual es de <b>" . number_format($total_kwh, 2) . " kWh</b>, ";
            $informeTexto .= "equivalente a <b>$" . number_format($precioTotal, 2) . "</b> según la tarifa configurada.";
        }

        // Agregar información de cortes de línea
        if (!empty($cortes)) {
            $informeTexto .= "<br><br><b>📊 RESUMEN DE CORTES DE LÍNEA:</b><br>";
            $informeTexto .= "• Total de cortes registrados: <b>" . $estadisticasCortes['total_cortes'] . "</b><br>";
            $informeTexto .= "• Cortes activos: <b>" . $estadisticasCortes['cortes_activos'] . "</b><br>";
            $informeTexto .= "• Cortes resueltos: <b>" . $estadisticasCortes['cortes_resueltos'] . "</b><br>";
            
            if (!empty($cortes)) {
                $informeTexto .= "<br><b>🔍 ÚLTIMOS CORTES REGISTRADOS:</b><br>";
                foreach (array_slice($cortes, 0, 5) as $corte) {
                    $fecha = date('d/m/Y H:i', strtotime($corte['fecha_corte']));
                    $estado = $corte['resuelto'] ? 'Resuelto' : 'Activo';
                    $informeTexto .= "• $fecha - Consumo: " . number_format($corte['consumo_actual'], 2) . " kWh - Estado: $estado<br>";
                }
            }
        } else {
            $informeTexto .= "<br><br><b>📊 RESUMEN DE CORTES DE LÍNEA:</b><br>";
            $informeTexto .= "• No se registraron cortes de línea para este dispositivo.";
        }

        // Recomendaciones básicas basadas en patrones
        $recomendaciones = [];
        $promedioDiario = 0.0;
        if (!empty($consumoDiario)) {
            $promedioDiario = array_sum($consumoDiario) / max(1, count($consumoDiario));
            $maxDia = max($consumoDiario);
            if ($maxDia > $promedioDiario * 1.5) {
                $recomendaciones[] = 'Se detectan días con consumo significativamente superior al promedio. Revise dispositivos de alto consumo en esas fechas.';
            }
        }
        if ($picoPotencia['valor'] > 0 && $picoPotencia['valor'] > ($promedios['potencia'] ?: 0) * 1.8) {
            $recomendaciones[] = 'Hubo picos de potencia elevados. Considere distribuir el uso de equipos para evitar sobrecargas.';
        }
        if ($precioKwh >= 0.01 && $total_kwh > 0 && $precioTotal > 0) {
            $recomendaciones[] = 'Para reducir costos, ajuste horarios de uso a períodos de menor tarifa si su proveedor ofrece tarifa variable.';
        }
        if (empty($recomendaciones)) {
            $recomendaciones[] = 'El consumo se mantiene estable. Continúe monitoreando y manteniendo hábitos eficientes.';
        }

        // Crear PDF con opciones y metadatos
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml(view('energia/pdf', [
            'usuario' => $usuario,
            'lecturas' => $lecturas,
            'promedios' => $promedios,
            'total_kwh' => $total_kwh,
            'precioTotal' => $precioTotal,
            'precioKwh' => $precioKwh,
            'informeTexto' => $informeTexto,
            'consumoDiario' => $consumoDiario,
            'promedioDiario' => $promedioDiario,
            'picoPotencia' => $picoPotencia,
            'recomendaciones' => $recomendaciones,
            'totalesMensuales' => $totalesMensuales,
        ]));
        
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Metadatos PDF
        $dompdf->addInfo('Title', 'EcoVolt - Informe de Energía');
        $dompdf->addInfo('Author', 'EcoVolt');
        $dompdf->addInfo('Subject', 'Consumo energético y estimación de factura');
        
        $nombreArchivo = "Informe_" . preg_replace('/[^a-zA-Z0-9_-]/', '_', $usuario['nombre_dispositivo']) . "_" . date('Y_m_d') . ".pdf";
        $dompdf->stream($nombreArchivo, ["Attachment" => true]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error generando PDF: ' . $e->getMessage());
        log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        
        // Mostrar error más específico
        $errorMessage = 'Error al generar el PDF. ';
        if (strpos($e->getMessage(), 'Class \'Dompdf\\Dompdf\' not found') !== false) {
            $errorMessage .= 'La librería DomPDF no está instalada. Ejecuta: composer require dompdf/dompdf';
        } elseif (strpos($e->getMessage(), 'View not found') !== false) {
            $errorMessage .= 'La vista PDF no se encontró. Verifica que existe app/Views/energia/pdf.php';
        } else {
            $errorMessage .= $e->getMessage();
        }
        
        return redirect()->to(base_url('energia/dispositivo/' . $dispositivo_id))
            ->with('error', $errorMessage);
    }
}




    private function enviarNotificacionEmail($idUsuario, $consumoActual, $limite, $idDispositivo = null)
    {
        // Obtener el email de notificación del límite configurado
        $limiteModel = new \App\Models\LimiteConsumoModel();
        $limiteData = $limiteModel->getLimiteByDispositivo($idDispositivo);
        
        if (!$limiteData || !$limiteData['email_notificacion']) {
            log_message('info', 'No hay email de notificación configurado para el dispositivo ' . $idDispositivo);
            return;
        }

        $email = \Config\Services::email();
        $user = $this->userModel->find($idUsuario);
        $configEmail = new \Config\Email();

        $email->setFrom($configEmail->fromEmail, $configEmail->fromName);
        $email->setTo($limiteData['email_notificacion']);
        $email->setSubject('⚠️ Alerta de Consumo de Energía');

        $dispositivo = $this->dispositivoModel->find($idDispositivo);
        
        $data = [
            'nombre' => $user['nombre'] ?? null,
            'consumoActual' => $consumoActual,
            'limiteConfigurado' => $limite,
            'idDispositivo' => $idDispositivo,
            'dispositivoNombre' => $dispositivo ? $dispositivo['nombre'] : null,
            'momento' => date('Y-m-d H:i:s'),
            'urlPanel' => base_url('energia/dispositivo/' . $idDispositivo)
        ];

        $html = view('emails/alerta_consumo', $data);
        $email->setMessage($html);
        $email->setMailType('html');
        
        if ($email->send()) {
            log_message('info', 'Alerta de consumo enviada correctamente a ' . $limiteData['email_notificacion']);
        } else {
            log_message('error', 'Error al enviar alerta de consumo a ' . $limiteData['email_notificacion'] . ' - ' . print_r($email->printDebugger(['headers','subject']), true));
        }
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

    public function getlimite()
    {
        // Endpoint público para que el ESP32 obtenga el límite de consumo
        // No requiere autenticación ya que es para dispositivos IoT
        
        try {
            $limiteModel = new \App\Models\LimiteConsumoModel();
            
            // Obtener parámetros opcionales
            $mac_address = $this->request->getGet('mac') ?? null;
            $ip_address = $this->request->getIPAddress();
            
            // Buscar límite específico por MAC si se proporciona
            if ($mac_address) {
                $dispositivo = $this->dispositivoModel->where('mac_address', $mac_address)->first();
                if ($dispositivo) {
                    $limite = $limiteModel->getLimiteByDispositivo($dispositivo['id_dispositivo']);
                    if ($limite) {
                        log_message('info', "Límite obtenido para dispositivo MAC: $mac_address, límite: {$limite['limite_consumo']}");
                        return $this->response->setJSON([
                            'success' => true,
                            'limite_consumo' => $limite['limite_consumo'],
                            'dispositivo_id' => $dispositivo['id_dispositivo'],
                            'mac_address' => $mac_address,
                            'timestamp' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
            
            // Si no hay MAC o no se encuentra dispositivo específico, usar el límite más reciente
            $limite = $limiteModel->orderBy('created_at', 'DESC')->first();
            $limite_consumo = $limite ? $limite['limite_consumo'] : 0.004; // Valor por defecto como en tu código
            
            log_message('info', "Límite obtenido (general): $limite_consumo kWh, IP: $ip_address");
            
            return $this->response->setJSON([
                'success' => true,
                'limite_consumo' => $limite_consumo,
                'timestamp' => date('Y-m-d H:i:s'),
                'ip_address' => $ip_address
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getlimite: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al obtener límite',
                'limite_consumo' => 0.004, // Valor por defecto en caso de error
                'timestamp' => date('Y-m-d H:i:s')
            ])->setStatusCode(500);
        }
    }

    public function filtrarLecturas($id_dispositivo)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
        }

        try {
            $fechaDesde = $this->request->getGet('fecha_desde');
            $fechaHasta = $this->request->getGet('fecha_hasta');
            $limite = (int)($this->request->getGet('limite') ?? 25);
            $orden = $this->request->getGet('orden') ?? 'DESC';
            $page = max(1, (int)($this->request->getGet('page') ?? 1));
            $offset = ($page - 1) * $limite;

            $query = $this->energiaModel->where('id_dispositivo', $id_dispositivo);

            // Aplicar filtros de fecha
            if ($fechaDesde) {
                $query->where('DATE(fecha) >=', $fechaDesde);
            }
            if ($fechaHasta) {
                $query->where('DATE(fecha) <=', $fechaHasta);
            }

            // Total para paginación
            $totalQuery = clone $query;
            $total = $totalQuery->countAllResults(false);

            // Aplicar límite, offset y orden
            $lecturas = $query->orderBy('fecha', $orden)
                            ->limit($limite, $offset)
                            ->findAll();

            // Formatear datos para la respuesta
            $lecturasFormateadas = array_map(function($lectura) {
                return [
                    'fecha' => date('d/m/Y H:i:s', strtotime($lectura['fecha'])),
                    'voltaje' => number_format($lectura['voltaje'], 2),
                    'corriente' => number_format($lectura['corriente'], 2),
                    'potencia' => number_format($lectura['potencia'], 2),
                    'kwh_acumulado' => number_format($lectura['kwh_acumulado'], 2)
                ];
            }, $lecturas);

            return $this->response->setJSON([
                'success' => true,
                'lecturas' => $lecturasFormateadas,
                'total' => (int)$total,
                'page' => (int)$page,
                'pages' => (int)ceil(max(1,$total)/max(1,$limite)),
                'filtros' => [
                    'fecha_desde' => $fechaDesde,
                    'fecha_hasta' => $fechaHasta,
                    'limite' => (int)$limite,
                    'orden' => $orden
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error filtrando lecturas: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error al filtrar lecturas'
            ])->setStatusCode(500);
        }
    }

    public function getMonthlyTotals($id_dispositivo)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
        }

        try {
            $dispositivo = $this->dispositivoModel->find($id_dispositivo);
            if (!$dispositivo) {
                return $this->response->setJSON(['success' => false, 'error' => 'Dispositivo no encontrado'])->setStatusCode(404);
            }

            $db = \Config\Database::connect();
            // Agrupación por año-mes
            $rows = $db->table('energia')
                ->select("DATE_FORMAT(fecha, '%Y-%m') AS ym, SUM(kwh_acumulado) AS total_kwh", false)
                ->where('id_dispositivo', $id_dispositivo)
                ->groupBy("DATE_FORMAT(fecha, '%Y-%m')", false)
                ->orderBy("DATE_FORMAT(fecha, '%Y-%m')", 'ASC', false)
                ->get()->getResultArray();

            $data = array_map(function($r) {
                return [
                    'ym' => $r['ym'],
                    'label' => date('m/Y', strtotime($r['ym'].'-01')),
                    'total_kwh' => (float)$r['total_kwh']
                ];
            }, $rows);

            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getMonthlyTotals: '.$e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => 'Error al obtener totales mensuales'])->setStatusCode(500);
        }
    }

    public function actualizarDispositivo()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
        }

        // Solo dueño, admin o supervisor
        $rol = session()->get('rol');
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
        if ($dispositivo['id_usuario'] != $idUsuario && $rol !== 'admin' && $rol !== 'supervisor') {
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

/**
 * Resetear notificaciones de límite (solo para desarrollo/admin)
 * Útil para testing de alertas
 */
public function resetNotificaciones($id_dispositivo = null)
{
    // Solo admin o supervisor
    $rol = session()->get('rol');
    if ($rol !== 'admin' && $rol !== 'supervisor') {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'No autorizado'
        ])->setStatusCode(403);
    }

    $limiteModel = new \App\Models\LimiteConsumoModel();
    
    if ($id_dispositivo) {
        // Resetear para un dispositivo específico
        $limite = $limiteModel->getLimiteByDispositivo($id_dispositivo);
        if ($limite) {
            $limiteModel->update($limite['id'], [
                'notificacion_enviada' => 0,
                'ultima_notificacion' => null
            ]);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notificaciones reseteadas para el dispositivo ' . $id_dispositivo
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No se encontró límite para el dispositivo ' . $id_dispositivo
            ]);
        }
    } else {
        // Resetear todas las notificaciones
        $limiteModel->set('notificacion_enviada', 0)
                   ->set('ultima_notificacion', null)
                   ->update();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Todas las notificaciones han sido reseteadas'
        ]);
    }
}

/**
 * Obtener cortes pendientes para el usuario actual
 */
public function getCortesPendientes()
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    $id_usuario = session()->get('id_usuario');
    $corteModel = new \App\Models\CorteLineaModel();
    
    $cortes = $corteModel->getCortesPendientes($id_usuario);
    
    return $this->response->setJSON([
        'success' => true,
        'cortes' => $cortes
    ]);
}

/**
 * Marcar un corte como visto por el usuario
 */
public function marcarCorteVisto($id_corte)
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    $corteModel = new \App\Models\CorteLineaModel();
    
    // Verificar que el corte pertenece al usuario actual
    $corte = $corteModel->find($id_corte);
    if (!$corte || $corte['id_usuario'] != session()->get('id_usuario')) {
        return $this->response->setJSON(['success' => false, 'error' => 'Corte no encontrado'])->setStatusCode(404);
    }

    $resultado = $corteModel->marcarComoVisto($id_corte);
    
    if ($resultado) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Corte marcado como visto'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Error al marcar como visto'
        ]);
    }
}

/**
 * Obtener estadísticas de cortes para el usuario actual
 */
public function getEstadisticasCortes()
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    $id_usuario = session()->get('id_usuario');
    $fecha_desde = $this->request->getGet('fecha_desde');
    $fecha_hasta = $this->request->getGet('fecha_hasta');
    
    $corteModel = new \App\Models\CorteLineaModel();
    $estadisticas = $corteModel->getEstadisticasCortes($id_usuario, $fecha_desde, $fecha_hasta);
    
    return $this->response->setJSON([
        'success' => true,
        'estadisticas' => $estadisticas
    ]);
}

/**
 * Obtener historial de cortes para un dispositivo específico
 */
public function getHistorialCortes($id_dispositivo)
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    $limite = (int)($this->request->getGet('limite') ?? 10);
    $corteModel = new \App\Models\CorteLineaModel();
    $historial = $corteModel->getHistorialCortes($id_dispositivo, $limite);
    
    return $this->response->setJSON([
        'success' => true,
        'historial' => $historial
    ]);
}

/**
 * Vista principal de cortes de línea
 */
public function cortes()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('login'));
    }

    // Obtener estadísticas de cortes
    $id_usuario = session()->get('id_usuario');
    $corteModel = new \App\Models\CorteLineaModel();
    $estadisticas = $corteModel->getEstadisticasCortes($id_usuario);
    
    // Obtener dispositivos del usuario para el filtro
    $dispositivos = $this->dispositivoModel->where('id_usuario', $id_usuario)->findAll();
    
    return view('energia/cortes', [
        'estadisticas' => $estadisticas,
        'dispositivos' => $dispositivos
    ]);
}

/**
 * Obtener dispositivos del usuario para filtros
 */
public function getDispositivosUsuario()
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    $id_usuario = session()->get('id_usuario');
    $dispositivos = $this->dispositivoModel->where('id_usuario', $id_usuario)->findAll();
    
    return $this->response->setJSON([
        'success' => true,
        'dispositivos' => $dispositivos
    ]);
}

/**
 * Obtener cortes con filtros aplicados
 */
public function getCortesFiltrados()
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    $id_usuario = session()->get('id_usuario');
    $corteModel = new \App\Models\CorteLineaModel();
    
    // Obtener filtros
    $filtros = [
        'dispositivo' => $this->request->getGet('dispositivo'),
        'estado' => $this->request->getGet('estado'),
        'fecha_desde' => $this->request->getGet('fecha_desde'),
        'fecha_hasta' => $this->request->getGet('fecha_hasta')
    ];
    
    // Construir query con filtros
    $query = $corteModel->select('cortes_linea.*, dispositivos.nombre as nombre_dispositivo')
                       ->join('dispositivos', 'dispositivos.id_dispositivo = cortes_linea.id_dispositivo', 'left')
                       ->where('cortes_linea.id_usuario', $id_usuario);
    
    // Aplicar filtros
    if ($filtros['dispositivo']) {
        $query->where('cortes_linea.id_dispositivo', $filtros['dispositivo']);
    }
    
    if ($filtros['estado']) {
        switch ($filtros['estado']) {
            case 'activo':
                $query->where('cortes_linea.resuelto', 0);
                break;
            case 'resuelto':
                $query->where('cortes_linea.resuelto', 1);
                break;
            case 'visto':
                $query->where('cortes_linea.vista_por_usuario', 1);
                break;
        }
    }
    
    if ($filtros['fecha_desde']) {
        $query->where('DATE(cortes_linea.fecha_corte) >=', $filtros['fecha_desde']);
    }
    
    if ($filtros['fecha_hasta']) {
        $query->where('DATE(cortes_linea.fecha_corte) <=', $filtros['fecha_hasta']);
    }
    
    $cortes = $query->orderBy('cortes_linea.fecha_corte', 'DESC')->findAll();
    
    // Obtener estadísticas con los mismos filtros
    $estadisticas = $corteModel->getEstadisticasCortes($id_usuario, $filtros['fecha_desde'], $filtros['fecha_hasta']);
    
    return $this->response->setJSON([
        'success' => true,
        'cortes' => $cortes,
        'estadisticas' => $estadisticas
    ]);
}

/**
 * Obtener detalle de un corte específico
 */
public function getDetalleCorte($id_corte)
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    $id_usuario = session()->get('id_usuario');
    $corteModel = new \App\Models\CorteLineaModel();
    
    $corte = $corteModel->select('cortes_linea.*, dispositivos.nombre as nombre_dispositivo')
                       ->join('dispositivos', 'dispositivos.id_dispositivo = cortes_linea.id_dispositivo', 'left')
                       ->where('cortes_linea.id', $id_corte)
                       ->where('cortes_linea.id_usuario', $id_usuario)
                       ->first();
    
    if (!$corte) {
        return $this->response->setJSON(['success' => false, 'error' => 'Corte no encontrado'])->setStatusCode(404);
    }
    
    return $this->response->setJSON([
        'success' => true,
        'corte' => $corte
    ]);
}

/**
 * Exportar cortes a Excel
 */
public function exportarCortesExcel()
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    $id_usuario = session()->get('id_usuario');
    $corteModel = new \App\Models\CorteLineaModel();
    
    // Obtener todos los cortes del usuario
    $cortes = $corteModel->select('cortes_linea.*, dispositivos.nombre as nombre_dispositivo')
                        ->join('dispositivos', 'dispositivos.id_dispositivo = cortes_linea.id_dispositivo', 'left')
                        ->where('cortes_linea.id_usuario', $id_usuario)
                        ->orderBy('cortes_linea.fecha_corte', 'DESC')
                        ->findAll();
    
    // Generar CSV (formato compatible con Excel)
    $filename = 'cortes_linea_' . date('Y-m-d_H-i-s') . '.csv';
    
    // Headers para descarga
        $this->response->setHeader('Content-Type', 'text/csv; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');
    
    // Crear contenido CSV
    $output = fopen('php://output', 'w');
    
    // BOM para UTF-8 (para que Excel reconozca correctamente los caracteres especiales)
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Encabezados
    fputcsv($output, [
        'ID',
        'Dispositivo',
        'Consumo Actual (kWh)',
        'Límite Configurado (kWh)',
        'Fecha del Corte',
        'Estado',
        'Visto por Usuario',
        'Fecha Vista',
        'Fecha Resolución'
    ]);
    
    // Datos
    foreach ($cortes as $corte) {
        fputcsv($output, [
            $corte['id'],
            $corte['nombre_dispositivo'] ?? 'Dispositivo ' . $corte['id_dispositivo'],
            number_format($corte['consumo_actual'], 4),
            number_format($corte['limite_configurado'], 4),
            date('d/m/Y H:i:s', strtotime($corte['fecha_corte'])),
            $corte['resuelto'] ? 'Resuelto' : 'Activo',
            $corte['vista_por_usuario'] ? 'Sí' : 'No',
            $corte['fecha_vista'] ? date('d/m/Y H:i:s', strtotime($corte['fecha_vista'])) : '',
            $corte['fecha_resolucion'] ? date('d/m/Y H:i:s', strtotime($corte['fecha_resolucion'])) : ''
        ]);
    }
    
    fclose($output);
    return $this->response;
}

/**
 * Exportar lecturas de energía a Excel
 */
public function exportarLecturasExcel($dispositivo_id)
{
    if (!session()->get('logged_in')) {
        return $this->response->setJSON(['success' => false, 'error' => 'No autorizado'])->setStatusCode(401);
    }

    // Verificar que el dispositivo pertenece al usuario
    $dispositivo = $this->dispositivoModel->find($dispositivo_id);
    if (!$dispositivo || $dispositivo['id_usuario'] != session()->get('id_usuario')) {
        return $this->response->setJSON(['success' => false, 'error' => 'Dispositivo no encontrado'])->setStatusCode(404);
    }

    // Obtener lecturas del dispositivo
    $lecturas = $this->energiaModel->where('id_dispositivo', $dispositivo_id)
                                  ->orderBy('fecha', 'DESC')
                                  ->findAll();
    
    // Generar CSV
    $filename = 'lecturas_energia_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $dispositivo['nombre']) . '_' . date('Y-m-d_H-i-s') . '.csv';
    
    // Headers para descarga
        $this->response->setHeader('Content-Type', 'text/csv; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');
    
    // Crear contenido CSV
    $output = fopen('php://output', 'w');
    
    // BOM para UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Encabezados
    fputcsv($output, [
        'Fecha y Hora',
        'Voltaje (V)',
        'Corriente (A)',
        'Potencia (W)',
        'Energía Acumulada (kWh)',
        'Límite Superado'
    ]);
    
    // Datos
    foreach ($lecturas as $lectura) {
        fputcsv($output, [
            date('d/m/Y H:i:s', strtotime($lectura['fecha'])),
            number_format($lectura['voltaje'], 2),
            number_format($lectura['corriente'], 2),
            number_format($lectura['potencia'], 2),
            number_format($lectura['kwh_acumulado'], 4),
            $lectura['limite_superado'] ? 'Sí' : 'No'
        ]);
    }
    
    fclose($output);
    return $this->response;
}
}





