<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DispositivoModel;

class Dispositivo extends ResourceController
{
    protected $modelName = 'App\Models\DispositivoModel';
    protected $format    = 'json';

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
    }

    /**
     * Endpoint para que el ESP32 se registre/active.
     * Espera:
     * - `codigo_activacion`: El código alfanumérico generado por el admin.
     * - `mac_real_esp32`: La MAC física del ESP32.
     * - `nombre_esp32`: Un nombre opcional para identificar el ESP32 (e.g., "MiMedidor_sala").
     *
     * Este es el nuevo flujo de activación.
     */
    public function activarDispositivo()
    {
        $rules = [
            'codigo_activacion' => 'required|alpha_numeric|min_length[10]|max_length[32]',
            'mac_real_esp32'    => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]',
            'nombre_esp32'      => 'permit_empty|min_length[3]|max_length[100]'
        ];

        // Obtener datos del cuerpo de la solicitud JSON (típico para APIs de dispositivos)
        $data = $this->request->getJSON(true); // true para obtener como array asociativo

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $codigoActivacion = strtoupper($data['codigo_activacion']); // Asegurar mayúsculas
        $macRealEsp32 = strtoupper($data['mac_real_esp32']);       // Asegurar mayúsculas
        $nombreEsp32 = $data['nombre_esp32'] ?? null;

        // 1. Buscar el dispositivo por el código de activación
        $dispositivo = $this->dispositivoModel->getDispositivoByCodigoActivacion($codigoActivacion);

        if (!$dispositivo) {
            log_message('error', 'API_ACTIVACION: Código de activación inválido o no encontrado: ' . $codigoActivacion);
            return $this->failUnauthorized('Código de activación inválido o no encontrado.');
        }

        // 2. Verificar el estado del dispositivo
        if ($dispositivo['estado'] !== 'pendiente_configuracion') {
            log_message('error', 'API_ACTIVACION: Dispositivo con ID ' . $dispositivo['id_dispositivo'] . ' y código ' . $codigoActivacion . ' no está en estado "pendiente_configuracion". Estado actual: ' . $dispositivo['estado']);
            return $this->failForbidden('Este dispositivo no está listo para ser activado. Estado actual: ' . $dispositivo['estado']);
        }

        // 3. Verificar si la MAC Real ya está vinculada a otro dispositivo
        $existingMacReal = $this->dispositivoModel->getDispositivoByMacReal($macRealEsp32);
        if ($existingMacReal && $existingMacReal['id_dispositivo'] != $dispositivo['id_dispositivo']) {
            log_message('error', 'API_ACTIVACION: La MAC real ' . $macRealEsp32 . ' ya está vinculada al dispositivo ID: ' . $existingMacReal['id_dispositivo']);
            return $this->failConflict('La MAC real proporcionada ya está vinculada a otro dispositivo.');
        }

        // 4. Actualizar el dispositivo
        $updateData = [
            'estado'            => 'activo',
            'mac_real_esp32'    => $macRealEsp32,
            'codigo_activacion' => null, // Una vez usado, el código se anula
            'nombre'            => $nombreEsp32 ?? $dispositivo['nombre'] // Si el ESP32 manda un nombre, lo usamos, si no, mantenemos el que tenía.
        ];

        if ($this->dispositivoModel->update($dispositivo['id_dispositivo'], $updateData)) {
            log_message('info', 'API_ACTIVACION: Dispositivo ID ' . $dispositivo['id_dispositivo'] . ' activado exitosamente con MAC real: ' . $macRealEsp32);
            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Dispositivo activado y vinculado exitosamente.',
                'data'    => [
                    'id_dispositivo' => $dispositivo['id_dispositivo'],
                    'mac_simulada'   => $dispositivo['mac_address'], // MAC simulada asignada por el admin
                    'mac_real_esp32' => $macRealEsp32,               // MAC física del ESP32
                    'nombre_asignado'=> $updateData['nombre'],
                    'estado'         => 'activo'
                ]
            ]);
        } else {
            log_message('error', 'API_ACTIVACION: Error al actualizar el dispositivo ID ' . $dispositivo['id_dispositivo'] . ': ' . json_encode($this->dispositivoModel->errors()));
            return $this->failServerError('Error interno al activar el dispositivo. ' . json_encode($this->dispositivoModel->errors()));
        }
    }

    /**
     * Endpoint para que el ESP32 reporte su estado o "heartbeat".
     * Actualiza la fecha de la última conexión.
     * Espera:
     * - `mac_real_esp32`: La MAC física del ESP32.
     */
    public function reportarEstado()
    {
        $rules = [
            'mac_real_esp32' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]'
        ];

        $data = $this->request->getJSON(true);

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $macRealEsp32 = strtoupper($data['mac_real_esp32']);

        $dispositivo = $this->dispositivoModel->getDispositivoByMacReal($macRealEsp32);

        if (!$dispositivo) {
            log_message('warning', 'API_REPORTE: Dispositivo no encontrado con MAC real: ' . $macRealEsp32);
            return $this->failNotFound('Dispositivo no encontrado.');
        }

        // El campo 'updated_at' del modelo ya se actualiza automáticamente al hacer un `update`.
        // Si necesitas un campo específico como 'last_connection_at', añádelo a tu tabla.
        // Por ahora, simplemente actualizamos un campo dummy o esperamos que `updated_at` sea suficiente.
        // Podemos actualizar el estado a "activo" para asegurar que si se marcó inactivo, vuelva a activo.
        if ($dispositivo['estado'] !== 'activo') {
             $updateData = ['estado' => 'activo'];
        } else {
            // Si ya está activo, una actualización "vacía" basta para refrescar updated_at
            $updateData = []; // o un campo específico si existe como 'last_heartbeat'
        }

        if ($this->dispositivoModel->update($dispositivo['id_dispositivo'], $updateData)) {
            log_message('info', 'API_REPORTE: Dispositivo ID ' . $dispositivo['id_dispositivo'] . ' reportó estado. MAC real: ' . $macRealEsp32);
            return $this->respond([
                'status'  => 'success',
                'message' => 'Estado del dispositivo actualizado.',
                'data'    => [
                    'id_dispositivo' => $dispositivo['id_dispositivo'],
                    'mac_real_esp32' => $macRealEsp32,
                    'estado'         => $this->dispositivoModel->find($dispositivo['id_dispositivo'])['estado'] // Obtener el estado actualizado
                ]
            ]);
        } else {
            log_message('error', 'API_REPORTE: Error al actualizar el estado del dispositivo ID ' . $dispositivo['id_dispositivo'] . ': ' . json_encode($this->dispositivoModel->errors()));
            return $this->failServerError('Error interno al actualizar el estado del dispositivo.');
        }
    }

    // --- Métodos Anteriores que son Redundantes o Cambiados ---

    // `buscar()`, `redes()`, `configurar()`, `activar()`, `iniciarConfiguracion()`, `actualizarEstado()`, `actualizarStock()`
    // Estos métodos anteriores fueron eliminados o reemplazados.
    // - `buscar()` y `redes()` simulaban escaneos, que no son responsabilidad de la API de activación.
    // - `configurar()` simulaba la configuración del ESP32, ahora el ESP32 debe tener su propio firmware para eso.
    // - `activar()` y `iniciarConfiguracion()` fueron reemplazados por el único `activarDispositivo()`.
    // - `actualizarEstado()` es reemplazado por `reportarEstado()` y la lógica de `activarDispositivo()`.
    // - `actualizarStock()` es una operación de negocio que no debe ser expuesta a los dispositivos ESP32.
    //   Si necesitas que un ESP32 interactúe con el stock, debe haber una razón de negocio muy específica
    //   y una capa de autenticación mucho más robusta.
}