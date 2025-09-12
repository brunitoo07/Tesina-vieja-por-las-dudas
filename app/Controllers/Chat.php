<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Chat extends Controller
{
    public function process()
    {
        $json = $this->request->getJSON();
        $message = strtolower(trim($json->message ?? ''));

        // Respuesta por defecto
        $response = [
            "text" => "🤖 No entendí tu consulta. Podés elegir una opción:",
            "options" => ["Proyecto", "Consumo", "Ideas", "Servicio", "Contacto"]
        ];

        // Respuestas predefinidas
        if (strpos($message, 'proyecto') !== false) {
            $response = [
                "text" => "📘 El proyecto *EcoVolt* es un medidor de energía inteligente que mide voltaje, corriente, potencia y kWh en tiempo real. \
Permite visualizar los datos en una plataforma web amigable y exportarlos para análisis. Además, está diseñado para integrarse fácilmente en viviendas y pequeñas empresas.",
                "options" => ["Consumo", "Ideas", "Servicio", "Contacto"]
            ];
        } 
        elseif (strpos($message, 'consumo') !== false) {
            $response = [
                "text" => "⚡ Con EcoVolt podés consultar tu consumo eléctrico en tiempo real. \
El sistema registra los datos y los muestra de forma clara para ayudarte a identificar cuáles aparatos gastan más energía. \
También podés ver resúmenes diarios, mensuales y hasta generar un estimado de facturación.",
                "options" => ["Proyecto", "Ideas", "Servicio", "Contacto"]
            ];
        } 
        elseif (strpos($message, 'ideas') !== false || strpos($message, 'mejorar') !== false) {
            $response = [
                "text" => "💡 Algunas ideas para mejorar el sistema:\n\n- Agregar alertas de consumo cuando se superen ciertos límites.\n- Exportar datos en formatos como Excel o PDF.\n- Conectar con domótica para automatizar luces y electrodomésticos.\n- Integrar paneles solares para medir producción y consumo en conjunto.",
                "options" => ["Proyecto", "Consumo", "Servicio", "Contacto"]
            ];
        } 
        elseif (strpos($message, 'servicio') !== false || strpos($message, 'soporte') !== false) {
            $response = [
                "text" => "☎️ Nuestro servicio técnico está disponible de lunes a viernes de 9 a 18hs. \
Podemos asistirte con instalación, calibración y mantenimiento de tu medidor EcoVolt.",
                "options" => ["Proyecto", "Consumo", "Ideas", "Contacto"]
            ];
        } 
        elseif (strpos($message, 'contacto') !== false) {
            $response = [
                "text" => "📞 Para más información comunicate al 0800-123-EcoVolt o escribinos por WhatsApp al +54 9 3571-623139 o por mail a ecovolt@gmail.com.",
                "options" => ["Proyecto", "Consumo", "Ideas", "Servicio"]
            ];
        }
        elseif (strpos($message, 'hola') !== false || strpos($message, 'buen') !== false) {
            $response = [
                "text" => "🙌 ¡Hola! Soy el asistente virtual de EcoVolt. ¿Sobre qué tema querés consultar?",
                "options" => ["Proyecto", "Consumo", "Ideas", "Servicio", "Contacto"]
            ];
        }

        return $this->response->setJSON($response);
    }
}
