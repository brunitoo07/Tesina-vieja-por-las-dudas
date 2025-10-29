<?php

namespace App\Controllers;

use App\Models\EnergiaModel;

class TelegramSimple extends BaseController
{
    protected $energiaModel;
    protected $token;
    protected $chatId;

    public function __construct()
    {
        $this->energiaModel = new EnergiaModel();
        $this->token = "7316812708:AAHf-eFsfkckmEnIgDPaadEYhSLjeOxOBl0";
        $this->chatId = "6746907650";
    }

    public function webhook()
    {
        $input = $this->request->getJSON(true);
        if (!$input) {
            return $this->response->setJSON(['ok' => false]);
        }

        $chatId = $input['message']['chat']['id'] ?? null;
        $text = trim($input['message']['text'] ?? '');

        if (!$chatId) {
            return $this->response->setJSON(['ok' => true]);
        }

        // Respuesta simple para comandos básicos
        $response = $this->procesarComando($text);
        
        // Enviar respuesta
        $this->enviarMensaje($response, $chatId);

        return $this->response->setJSON(['ok' => true]);
    }

    private function procesarComando($text)
    {
        $texto = strtolower(trim($text));
        
        if (strpos($texto, '/start') === 0) {
            return "🤖 *Bot de Alertas EcoVolt*\n\n" .
                   "Este bot está configurado para enviar solo alertas y notificaciones.\n\n" .
                   "🔗 **Para consultas completas:**\n" .
                   "• Panel web: http://192.168.2.182/Tesina/public/\n" .
                   "• Asistente virtual: http://192.168.2.182/Tesina/chat\n\n" .
                   "📱 **Recibirás notificaciones automáticas cuando:**\n" .
                   "• Se superen los límites de consumo\n" .
                   "• Haya problemas en el sistema\n" .
                   "• Se requiera atención inmediata";
        }
        
        if (strpos($texto, '/ayuda') === 0 || strpos($texto, 'ayuda') !== false) {
            return "❓ *Ayuda - Bot de Alertas*\n\n" .
                   "Este bot solo envía alertas automáticas.\n\n" .
                   "🔗 **Para consultas completas:**\n" .
                   "• Panel web: http://192.168.2.182/Tesina/public/\n" .
                   "• Asistente virtual: http://192.168.2.182/Tesina/chat\n\n" .
                   "📱 **Recibirás alertas cuando:**\n" .
                   "• Consumo excesivo\n" .
                   "• Problemas del sistema\n" .
                   "• Atención requerida";
        }
        
        // Para cualquier otro mensaje
        return "🤖 *Bot de Alertas EcoVolt*\n\n" .
               "Este bot está configurado para alertas automáticas.\n\n" .
               "🔗 **Para consultas completas:**\n" .
               "• Panel web: http://192.168.2.182/Tesina/public/\n" .
               "• Asistente virtual: http://192.168.2.182/Tesina/chat";
    }

    /**
     * Enviar mensaje genérico (método auxiliar)
     */
    private function enviarMensaje($mensaje, $chatId = null)
    {
        $chatId = $chatId ?: $this->chatId;
        $botToken = $this->token;
        
        if (!$botToken) {
            log_message('error', 'Token de bot Telegram no configurado');
            return false;
        }
        
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        
        $data = [
            'chat_id' => $chatId,
            'text' => $mensaje,
            'parse_mode' => 'Markdown'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            log_message('info', "Mensaje Telegram enviado a $chatId");
            return true;
        } else {
            log_message('error', "Error Telegram HTTP $httpCode: " . $response);
            return false;
        }
    }

    /**
     * Enviar notificación a un usuario específico por su ID
     */
    public function enviarNotificacionUsuario($idUsuario, $mensaje)
    {
        try {
            // Buscar el chat_id del usuario en la base de datos
            $telegramUserModel = new \App\Models\TelegramUserModel();
            $usuarioTelegram = $telegramUserModel->where('id_usuario', $idUsuario)
                                               ->where('is_active', 1)
                                               ->where('notificaciones_activas', 1)
                                               ->first();
            
            if (!$usuarioTelegram || empty($usuarioTelegram['chat_id'])) {
                log_message('warning', "Usuario $idUsuario no tiene chat_id de Telegram configurado");
                return false;
            }
            
            // Enviar mensaje al chat_id específico
            return $this->enviarMensaje($mensaje, $usuarioTelegram['chat_id']);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en enviarNotificacionUsuario: ' . $e->getMessage());
            return false;
        }
    }

    public function enviarAlerta($mensaje)
    {
        return $this->enviarMensaje($mensaje);
    }
}