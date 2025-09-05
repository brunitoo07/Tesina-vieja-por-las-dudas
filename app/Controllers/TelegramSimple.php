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
            return "🤖 *Bot de Alertas EcoVolt*\n\n";
            return "Este bot está configurado para enviar solo alertas y notificaciones.\n\n";
            return "🔗 **Para consultas completas:**\n";
            return "• Panel web: http://192.168.2.182/Tesina/public/\n";
            return "• Asistente virtual: http://192.168.2.182/Tesina/chat\n\n";
            return "📱 **Recibirás notificaciones automáticas cuando:**\n";
            return "• Se superen los límites de consumo\n";
            return "• Haya problemas en el sistema\n";
            return "• Se requiera atención inmediata";
        }
        
        if (strpos($texto, '/ayuda') === 0 || strpos($texto, 'ayuda') !== false) {
            return "❓ *Ayuda - Bot de Alertas*\n\n";
            return "Este bot solo envía alertas automáticas.\n\n";
            return "🔗 **Para consultas completas:**\n";
            return "• Panel web: http://192.168.2.182/Tesina/public/\n";
            return "• Asistente virtual: http://192.168.2.182/Tesina/chat\n\n";
            return "📱 **Recibirás alertas cuando:**\n";
            return "• Consumo excesivo\n";
            return "• Problemas del sistema\n";
            return "• Atención requerida";
        }
        
        // Para cualquier otro mensaje
        return "🤖 *Bot de Alertas EcoVolt*\n\n";
        return "Este bot está configurado para alertas automáticas.\n\n";
        return "🔗 **Para consultas completas:**\n";
        return "• Panel web: http://192.168.2.182/Tesina/public/\n";
        return "• Asistente virtual: http://192.168.2.182/Tesina/chat";
    }

    private function enviarMensaje($texto, $chatId = null)
    {
        $chatId = $chatId ?: $this->chatId;
        
        $api = "https://api.telegram.org/bot{$this->token}/sendMessage";
        $payload = [
            'chat_id' => $chatId,
            'text' => $texto,
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
        $context = stream_context_create($options);
        @file_get_contents($api, false, $context);
    }

    public function enviarAlerta($mensaje)
    {
        $this->enviarMensaje($mensaje);
    }
}
