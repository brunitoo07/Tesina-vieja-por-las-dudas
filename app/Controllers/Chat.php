<?php

namespace App\Controllers;

use App\Models\EnergiaModel;
use App\Models\DispositivoModel;
use App\Models\MacValidationModel;

class Chat extends BaseController
{
    protected $energiaModel;
    protected $dispositivoModel;
    protected $macValidationModel;

    public function __construct()
    {
        $this->energiaModel = new EnergiaModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->macValidationModel = new MacValidationModel();
    }

    private function setCORSHeaders()
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    public function index()
    {
        return view('chat_profesional');
    }
    
    public function estado()
    {
        try {
            $this->setCORSHeaders();
            $response = $this->mostrarEstado();
            return $this->response->setJSON(['response' => $response]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['response' => '❌ Error al obtener estado del sistema']);
        }
    }
    
    public function dispositivos()
    {
        try {
            $this->setCORSHeaders();
            $response = $this->mostrarDispositivos();
            return $this->response->setJSON(['response' => $response]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['response' => '❌ Error al obtener dispositivos']);
        }
    }
    
    public function consumo()
    {
        try {
            $this->setCORSHeaders();
            $response = $this->mostrarConsumo();
            return $this->response->setJSON(['response' => $response]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['response' => '❌ Error al obtener datos de consumo']);
        }
    }
    
    public function proyecto()
    {
        try {
            $this->setCORSHeaders();
            $response = $this->mostrarInfoProyecto();
            return $this->response->setJSON(['response' => $response]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['response' => '❌ Error al obtener información del proyecto']);
        }
    }
    
    public function ayuda()
    {
        try {
            $this->setCORSHeaders();
            $response = $this->mostrarAyuda();
            return $this->response->setJSON(['response' => $response]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['response' => '❌ Error al obtener ayuda']);
        }
    }

    public function process()
    {
        try {
            $this->setCORSHeaders();
            
            // Verificar si es una petición AJAX
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'response' => '❌ Error: Esta función solo está disponible vía AJAX'
                ]);
            }
            
            $input = $this->request->getJSON();
            $message = $input->message ?? '';
            
            if (empty($message)) {
                return $this->response->setJSON([
                    'response' => '❌ Por favor, escribe un mensaje válido.'
                ]);
            }
            
            $response = $this->procesarMensaje($message);
            
            return $this->response->setJSON([
                'response' => $response
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en Chat::process: ' . $e->getMessage());
            return $this->response->setJSON([
                'response' => '❌ Error interno del servidor. Intenta de nuevo.'
            ]);
        }
    }

    private function procesarMensaje($text)
    {
        try {
            $texto = strtolower(trim($text));
            $textoOriginal = trim($text);
            
            // Saludos
            if (strpos($texto, 'hola') !== false || strpos($texto, 'hi') !== false || 
                strpos($texto, 'buenos') !== false || strpos($texto, 'buenas') !== false ||
                strpos($texto, 'saludos') !== false || strpos($texto, 'hey') !== false) {
                return "👋 ¡Hola! Soy tu asistente virtual de EcoVolt.\n\n¿En qué puedo ayudarte? Puedo:\n• Mostrar datos de dispositivos\n• Buscar por MAC\n• Consultar consumo de energía\n• Ver estado del sistema\n• Información del proyecto\n\n💡 *Usa los botones rápidos o escribe tu consulta*";
            }
            
            // Búsqueda por MAC
            if (preg_match('/mac[:\s]*([0-9a-f]{2}[:-][0-9a-f]{2}[:-][0-9a-f]{2}[:-][0-9a-f]{2}[:-][0-9a-f]{2}[:-][0-9a-f]{2})/i', $textoOriginal, $matches)) {
                $mac = strtoupper($matches[1]);
                return $this->buscarDispositivoPorMac($mac);
            }
            
            // Mostrar dispositivos
            if (strpos($texto, 'dispositivo') !== false || strpos($texto, 'device') !== false ||
                strpos($texto, 'mostrar dispositivos') !== false) {
                return $this->mostrarDispositivos();
            }
            
            // Mostrar consumo/energía
            if (strpos($texto, 'consumo') !== false || strpos($texto, 'energía') !== false || 
                strpos($texto, 'energia') !== false || strpos($texto, 'ver consumo') !== false ||
                strpos($texto, 'lectura') !== false || strpos($texto, 'lecturas') !== false) {
                return $this->mostrarConsumo();
            }
            
            // Estado del sistema
            if (strpos($texto, 'estado') !== false || strpos($texto, 'status') !== false ||
                strpos($texto, 'sistema') !== false) {
                return $this->mostrarEstado();
            }
            
            // Información del proyecto
            if (strpos($texto, 'proyecto') !== false || strpos($texto, 'ecovolt') !== false ||
                strpos($texto, 'como funciona') !== false || strpos($texto, 'que hace') !== false ||
                strpos($texto, 'funciona') !== false) {
                $info = $this->mostrarInfoProyecto();
                $info .= "\n\n🔗 **Para información completa:**\n";
                $info .= "• Página detallada: " . base_url('info-proyecto') . "\n";
                $info .= "• Panel principal: " . base_url('energia') . "\n";
                return $info;
            }
            
            // Ayuda
            if (strpos($texto, 'ayuda') !== false || strpos($texto, 'help') !== false) {
                return $this->mostrarAyuda();
            }
            
            // Respuesta por defecto
            return "🤔 No estoy seguro de lo que necesitas.\n\nPuedo ayudarte con:\n• **Estado del sistema** - Ver estado general\n• **Dispositivos** - Ver todos los dispositivos\n• **Consumo** - Ver lecturas de energía\n• **MAC: XX:XX:XX:XX:XX:XX** - Buscar dispositivo específico\n• **Proyecto** - Información sobre EcoVolt\n• **Ayuda** - Ver esta ayuda\n\n💡 *Usa los botones rápidos para accesos directos*";
            
        } catch (\Exception $e) {
            log_message('error', 'Error en procesarMensaje: ' . $e->getMessage());
            return "❌ Error al procesar tu mensaje. Intenta de nuevo.";
        }
    }

    private function buscarDispositivoPorMac($mac)
    {
        try {
            // Buscar en mac_validation
            $macInfo = $this->macValidationModel->where('mac_address', $mac)
                                              ->where('es_valida', 1)
                                              ->first();
            
            if (!$macInfo) {
                // Buscar MACs similares
                $todasLasMacs = $this->macValidationModel->where('es_valida', 1)->findAll();
                
                $macsDisponibles = [];
                foreach ($todasLasMacs as $macDisp) {
                    $macsDisponibles[] = $macDisp['mac_address'] . " - " . $macDisp['fabricante'];
                }
                
                $texto = "❌ **MAC no encontrada en mac_validation**\n\n";
                $texto .= "MAC buscada: `{$mac}`\n\n";
                
                $texto .= "💡 **MACs disponibles:**\n";
                foreach ($macsDisponibles as $macDisp) {
                    $texto .= "• `{$macDisp}`\n";
                }
                
                return $texto;
            }
            
            // Obtener lecturas de energía
            $ultimaLectura = $this->energiaModel->where('mac_address', $mac)
                                               ->orderBy('fecha', 'DESC')
                                               ->first();
            
            $texto = "🔌 **Dispositivo Encontrado**\n\n";
            $texto .= "📡 **MAC:** " . $macInfo['mac_address'] . "\n";
            $texto .= "🏭 **Fabricante:** " . ($macInfo['fabricante'] ?: 'No especificado') . "\n";
            $texto .= "📱 **Tipo:** " . ($macInfo['tipo_dispositivo'] ?: 'No especificado') . "\n";
            $texto .= "✅ **Estado:** Válida\n\n";
            
            if ($ultimaLectura) {
                $texto .= "⚡ **Última Lectura:**\n";
                $texto .= "📅 Fecha: " . date('d/m/Y H:i:s', strtotime($ultimaLectura['fecha'])) . "\n";
                $texto .= "⚡ Voltaje: " . number_format($ultimaLectura['voltaje'], 2) . " V\n";
                $texto .= "🔋 Corriente: " . number_format($ultimaLectura['corriente'], 2) . " A\n";
                $texto .= "⚡ Potencia: " . number_format($ultimaLectura['potencia'], 2) . " W\n";
                $texto .= "🔋 Energía: " . number_format($ultimaLectura['kwh'], 4) . " kWh\n";
            } else {
                $texto .= "⚡ **Sin lecturas de energía disponibles**\n";
            }
            
            return $texto;
            
        } catch (\Exception $e) {
            return "❌ **Error al buscar dispositivo**\n\n" . $e->getMessage();
        }
    }

    private function mostrarDispositivos()
    {
        try {
            $macs = $this->macValidationModel->where('es_valida', 1)->findAll();
            
            if (empty($macs)) {
                return "❌ **No hay dispositivos registrados**\n\nContacta al administrador para configurar dispositivos.";
            }
            
            $texto = "📋 **Dispositivos Registrados**\n\n";
            
            foreach ($macs as $index => $mac) {
                $texto .= "🔌 **" . ($index + 1) . ". " . $mac['mac_address'] . "**\n";
                $texto .= "   🏭 Fabricante: " . ($mac['fabricante'] ?: 'No especificado') . "\n";
                $texto .= "   📱 Tipo: " . ($mac['tipo_dispositivo'] ?: 'No especificado') . "\n\n";
            }
            
            $texto .= "💡 **Para ver datos específicos:**\n";
            $texto .= "• Escribe 'MAC: XX:XX:XX:XX:XX:XX'\n";
            $texto .= "• O usa el nombre del fabricante\n";
            
            return $texto;
            
        } catch (\Exception $e) {
            return "❌ **Error al obtener dispositivos**\n\n" . $e->getMessage();
        }
    }

    private function mostrarConsumo()
    {
        try {
            $lecturas = $this->energiaModel->orderBy('fecha', 'DESC')->limit(10)->findAll();
            
            if (empty($lecturas)) {
                return "❌ **No hay lecturas de energía disponibles**\n\nEl sistema aún no ha registrado lecturas.";
            }
            
            $texto = "⚡ **Últimas Lecturas de Energía**\n\n";
            
            foreach ($lecturas as $index => $lectura) {
                $texto .= "🔌 **" . ($index + 1) . ". " . $lectura['mac_address'] . "**\n";
                $texto .= "   ⚡ Voltaje: " . number_format($lectura['voltaje'], 2) . " V\n";
                $texto .= "   🔋 Corriente: " . number_format($lectura['corriente'], 2) . " A\n";
                $texto .= "   ⚡ Potencia: " . number_format($lectura['potencia'], 2) . " W\n";
                $texto .= "   🔋 Energía: " . number_format($lectura['kwh'], 4) . " kWh\n";
                $texto .= "   📅 Fecha: " . date('d/m H:i', strtotime($lectura['fecha'])) . "\n\n";
            }
            
            $texto .= "💡 **Para ver datos específicos:**\n";
            $texto .= "• Escribe 'MAC: XX:XX:XX:XX:XX:XX'\n";
            $texto .= "• O escribe 'dispositivos' para ver todos\n";
            
            return $texto;
            
        } catch (\Exception $e) {
            return "❌ **Error al obtener lecturas**\n\n" . $e->getMessage();
        }
    }

    private function mostrarEstado()
    {
        try {
            $totalMacs = $this->macValidationModel->where('es_valida', 1)->countAllResults();
            $totalLecturas = $this->energiaModel->countAllResults();
            
            $texto = "📊 **Estado del Sistema EcoVolt**\n\n";
            $texto .= "✅ **Sistema Operativo**\n";
            $texto .= "🔌 MACs validadas: {$totalMacs}\n";
            $texto .= "⚡ Lecturas de energía: {$totalLecturas}\n";
            $texto .= "🕐 Última actualización: " . date('d/m/Y H:i:s') . "\n\n";
            
            if ($totalMacs > 0) {
                $texto .= "✅ **Sistema activo** - {$totalMacs} MAC(s) validadas\n\n";
            } else {
                $texto .= "⚠️ **Sin MACs validadas** - Contacta al administrador\n\n";
            }
            
            $texto .= "💡 **Acciones disponibles:**\n";
            $texto .= "• Ver dispositivos\n";
            $texto .= "• Consultar consumo\n";
            $texto .= "• Buscar por MAC\n";
            
            return $texto;
            
        } catch (\Exception $e) {
            return "❌ **Error al obtener estado**\n\n" . $e->getMessage();
        }
    }

    private function mostrarInfoProyecto()
    {
        return "🚀 **EcoVolt - Sistema de Monitoreo de Energía**\n\n" .
               "**¿Qué es EcoVolt?**\n" .
               "EcoVolt es un sistema inteligente de monitoreo de consumo eléctrico en tiempo real que te permite:\n\n" .
               "⚡ **Monitoreo en Tiempo Real:**\n" .
               "• Medición de voltaje, corriente y potencia\n" .
               "• Cálculo automático de consumo en kWh\n" .
               "• Alertas cuando se superan límites configurados\n\n" .
               "🔌 **Gestión de Dispositivos:**\n" .
               "• Registro de dispositivos por dirección MAC\n" .
               "• Validación automática de dispositivos\n" .
               "• Control individual de cada medidor\n\n" .
               "📊 **Análisis y Reportes:**\n" .
               "• Gráficos de consumo en tiempo real\n" .
               "• Historial de lecturas detallado\n" .
               "• Estadísticas de uso por dispositivo\n\n" .
               "🤖 **Asistencia Inteligente:**\n" .
               "• Chat virtual integrado (como este)\n" .
               "• Notificaciones automáticas por Telegram\n" .
               "• Respuestas inteligentes a consultas\n\n" .
               "🔧 **Tecnologías Utilizadas:**\n" .
               "• **Hardware:** ESP32 con sensores de energía\n" .
               "• **Backend:** PHP con CodeIgniter 4\n" .
               "• **Base de Datos:** MySQL\n" .
               "• **Frontend:** HTML5, CSS3, JavaScript\n" .
               "• **Notificaciones:** Telegram Bot API\n\n" .
               "💡 **¿Cómo Funciona?**\n" .
               "1. Los dispositivos ESP32 miden la energía\n" .
               "2. Envían datos al servidor web\n" .
               "3. Se almacenan en la base de datos\n" .
               "4. Se muestran en tiempo real en el panel\n" .
               "5. Se envían alertas si es necesario\n\n" .
               "🎯 **Beneficios:**\n" .
               "• Ahorro de energía\n" .
               "• Control total del consumo\n" .
               "• Detección temprana de problemas\n" .
               "• Gestión eficiente de recursos\n\n" .
               "🔗 **Acceso:**\n" .
               "• Panel principal: Dashboard de energía\n" .
               "• Asistente virtual: Este chat\n" .
               "• Notificaciones: Telegram\n" .
               "• Configuración: Panel de administración";
    }

    private function mostrarAyuda()
    {
        return "❓ **Ayuda - Asistente Virtual EcoVolt**\n\n🤖 **¿Qué puedo hacer por ti?**\n\n📊 **Consultas:**\n• 'Estado' - Ver estado del sistema\n• 'Dispositivos' - Ver todos los dispositivos\n• 'Consumo' - Ver lecturas de energía\n• 'MAC: XX:XX:XX:XX:XX:XX' - Buscar dispositivo específico\n• 'Proyecto' - Información sobre EcoVolt\n\n💡 **Comandos disponibles:**\n• Usa los botones rápidos para accesos directos\n• Escribe tu consulta en lenguaje natural\n• Busca por MAC para datos específicos\n\n🔗 **Panel completo:**\nPuedes acceder a todas las funciones desde el panel principal de energía.";
    }
}
