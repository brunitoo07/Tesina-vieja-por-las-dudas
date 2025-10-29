# 📚 DOCUMENTACIÓN COMPLETA DEL SISTEMA ECOVOLT

## **🔌 ¿QUÉ ES ECOVOLT?**

EcoVolt es un sistema de monitoreo inteligente de consumo eléctrico que:
- **Mide** cuánta electricidad usas en tu casa
- **Avisa** cuando gastas demasiado
- **Corta** la luz automáticamente si es necesario
- **Te manda mensajes** por Telegram y email

---

## **🤖 COMPONENTES DEL SISTEMA**

### **1. ESP32 (Hardware)**
- **Ubicación:** `main.py` en Thonny
- **Función:** Cerebro del sistema que mide y envía datos
- **Sensores:** ACS712 (corriente), ZMPT101B (voltaje)
- **Actuadores:** Relés para controlar líneas eléctricas

### **2. Servidor Web (Backend)**
- **Ubicación:** `app/Controllers/Energia.php`
- **Función:** Recibe, procesa y almacena datos
- **Base de datos:** MySQL con tabla `energia`

### **3. Panel Web (Frontend)**
- **Ubicación:** `app/Views/energia/`
- **Función:** Muestra datos en tiempo real
- **Características:** Gráficos, alertas, configuración

---

## **📡 FLUJO COMPLETO DE DATOS**

### **PASO 1: MEDICIÓN EN ESP32**
```python
# La ESP32 mide cada 5 segundos:
voltaje_real = medir_voltaje_rms()      # Sensor ZMPT101B
corriente_real = medir_corriente_rms()  # Sensor ACS712
potencia_real = voltaje_real * corriente_real
kwh_acumulado = energia_wh_acumulada / 1000.0
```

**¿Qué hace?**
- Mide voltaje y corriente con sensores
- Calcula potencia instantánea
- Acumula energía en kWh
- Toma muestras cada 5 segundos

### **PASO 2: ENVÍO DE DATOS**
```python
def send_data_to_database(voltage, current, power, kWh_accumulated):
    payload = {
        "voltaje": round(voltage, 2),
        "corriente": round(current, 4),
        "potencia": round(power, 2),
        "kwh_acumulado": round(kWh_accumulated, 6),
        "ip_address": sta_if.ifconfig()[0],
        "mac_address": macAddress
    }
    
    # POST a: http://192.168.0.138/Tesina/public/nuevos_datos
    r = urequests.post(DATA_SERVER_URL, json=payload, headers=headers)
```

**¿Qué hace?**
- Prepara los datos en formato JSON
- Envía por HTTP POST al servidor
- Incluye MAC address para identificar el dispositivo
- Reintenta si falla la conexión

### **PASO 3: RECEPCIÓN EN EL SERVIDOR**
```php
public function recibirNuevosDatos()
{
    // 1. Obtener datos JSON del ESP32
    $data = $this->request->getJSON(true);
    
    // 2. Validar campos requeridos
    $requiredFields = ['voltaje', 'corriente', 'potencia', 'kwh_acumulado', 'mac_address'];
    
    // 3. Formatear MAC address
    $mac_formateada = implode(':', str_split($mac_sin_formato, 2));
    
    // 4. Buscar dispositivo por MAC
    $dispositivo = $this->dispositivoModel->where('mac_address', $mac_formateada)->first();
    
    // 5. Preparar datos para insertar
    $lectura = [
        'id_dispositivo' => $dispositivo['id_dispositivo'],
        'id_usuario' => $dispositivo['id_usuario'],
        'voltaje' => $data['voltaje'],
        'corriente' => $data['corriente'],
        'potencia' => $data['potencia'],
        'kwh_acumulado' => $data['kwh_acumulado'],
        'mac_address' => $mac_formateada,
        'fecha' => date('Y-m-d H:i:s'),
        'limite_superado' => 0
    ];
    
    // 6. Verificar límites y enviar notificaciones
    $this->verificarLimite($lectura, $dispositivo['id_dispositivo'], $dispositivo['id_usuario']);
    
    // 7. Insertar en base de datos
    $this->energiaModel->insert($lectura);
}
```

**¿Qué hace?**
- Recibe los datos JSON del ESP32
- Valida que estén completos
- Busca el dispositivo por MAC address
- Verifica si se superó el límite
- Guarda en la base de datos

### **PASO 4: ALMACENAMIENTO EN BASE DE DATOS**
```sql
-- Tabla: energia
CREATE TABLE energia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_dispositivo INT,
    id_usuario INT,
    voltaje DECIMAL(10,2),
    corriente DECIMAL(10,4),
    potencia DECIMAL(10,2),
    kwh_acumulado DECIMAL(10,6),
    mac_address VARCHAR(17),
    fecha DATETIME,
    limite_superado TINYINT(1),
    created_at TIMESTAMP
);
```

**¿Qué se guarda?**
- Voltaje, corriente, potencia
- Energía acumulada en kWh
- Fecha y hora de la medición
- Si se superó el límite
- MAC address del dispositivo

---

## **🔄 CONSULTAS DE LA ESP32 AL SERVIDOR**

### **1. OBTENER UMBRAL DESDE LA WEB**
```python
def obtener_umbral_desde_web():
    r = urequests.get(UMBRAL_ENDPOINT_URL)  # http://192.168.0.138/Tesina/public/energia/getlimite
    if r.status_code == 200:
        data = r.json()
        nuevo_umbral = float(data["limite_consumo"])
        return nuevo_umbral
```

**¿Qué hace?**
- ESP32 pregunta: "¿Cuál es mi límite de consumo?"
- Servidor responde con el valor actualizado
- ESP32 actualiza su umbral interno

### **2. OBTENER ÚLTIMO KWH ACUMULADO**
```python
def obtener_ultimo_kwh_desde_web():
    params = "?mac=" + macAddress
    r = urequests.get(ULTIMO_KWH_URL + params)  # http://192.168.0.138/Tesina/public/energia/getUltimoKwh
    if r.status_code == 200:
        data = r.json()
        ultimo_kwh = float(data.get("ultimo_kwh", 0.0))
        energia_wh_acumulada = ultimo_kwh * 1000.0
        kwh_acumulado = ultimo_kwh
        return ultimo_kwh
```

**¿Qué hace?**
- ESP32 pregunta: "¿Cuál fue mi último kWh acumulado?"
- Servidor busca el último valor en la BD
- ESP32 continúa desde ese valor

---

## **🌐 CONFIGURACIÓN DE LÍMITES DESDE LA WEB**

### **1. USUARIO CONFIGURA LÍMITE**
```php
public function actualizarLimite()
{
    // Obtiene los datos del formulario web
    $limite = $this->request->getVar('limite_consumo');
    $email = $this->request->getVar('email');
    $id_dispositivo = $this->request->getVar('id_dispositivo');
    
    // Busca si ya existe un límite para este dispositivo
    $limiteExistente = $limiteModel->where('id_dispositivo', $id_dispositivo)->first();
    
    if ($limiteExistente) {
        // Actualiza el límite existente
        $limiteModel->update($limiteExistente['id'], [
            'limite_consumo' => $limite,
            'email_notificacion' => $email,
            'notificacion_enviada' => 0
        ]);
    } else {
        // Crea un nuevo límite
        $limiteModel->insert([
            'id_usuario' => session()->get('id_usuario'),
            'id_dispositivo' => $id_dispositivo,
            'limite_consumo' => $limite,
            'email_notificacion' => $email
        ]);
    }
    
    // Envía confirmación por email y Telegram
    $this->enviarNotificacionEmail($email, $limite);
    $this->alertaTelegram($mensajeTelegram);
}
```

**¿Qué hace?**
- Usuario configura límite en la web
- Servidor guarda en la base de datos
- Envía confirmación por email y Telegram

---

## **🚨 SISTEMA DE NOTIFICACIONES**

### **1. VERIFICACIÓN DE LÍMITES**
```php
private function verificarLimite(&$lectura, $id_dispositivo, $idUsuario)
{
    $limite = $this->limiteModel->getLimiteByDispositivo($id_dispositivo);
    
    if ($limite && $lectura['kwh_acumulado'] > $limite['limite_consumo']) {
        // Marcar como superado
        $lectura['limite_superado'] = 1;
        
        // Registrar corte de línea
        $corteModel->registrarCorte($id_dispositivo, $idUsuario, $lectura['kwh_acumulado'], $limite['limite_consumo']);
        
        // Enviar notificaciones (anti-spam: 1 hora)
        if (!$limite['notificacion_enviada'] || (strtotime($limite['ultima_notificacion']) < strtotime('-1 hour'))) {
            // Email
            $this->enviarNotificacionEmail($idUsuario, $lectura['kwh_acumulado'], $limite['limite_consumo'], $id_dispositivo);
            
            // Telegram personalizado
            $this->alertaTelegram($mensaje, $idUsuario);
            
            // Actualizar BD
            $this->limiteModel->actualizarNotificacion($limite['id']);
        }
    }
}
```

**¿Qué hace?**
- Compara consumo actual con límite configurado
- Si supera el límite, marca como superado
- Registra corte de línea en la BD
- Envía notificaciones por email y Telegram
- Controla spam (máximo 1 notificación por hora)

### **2. NOTIFICACIONES POR TELEGRAM**
```php
public function alertaTelegram($mensaje, $idUsuario = null)
{
    $telegramController = new \App\Controllers\TelegramSimple();
    
    if ($idUsuario) {
        // Enviar a usuario específico
        return $telegramController->enviarNotificacionUsuario($idUsuario, $mensaje);
    } else {
        // Enviar a todos los usuarios activos
        return $telegramController->enviarAlerta($mensaje);
    }
}
```

**¿Qué hace?**
- Envía notificaciones personalizadas por usuario
- Cada usuario recibe solo sus alertas
- Sistema de comandos: `/start`, `/activar`, `/desactivar`

---

## **📊 ESTRUCTURA DE LA BASE DE DATOS**

### **Tabla: `energia`**
```sql
CREATE TABLE energia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_dispositivo INT,
    id_usuario INT,
    voltaje DECIMAL(10,2),
    corriente DECIMAL(10,4),
    potencia DECIMAL(10,2),
    kwh_acumulado DECIMAL(10,6),
    mac_address VARCHAR(17),
    fecha DATETIME,
    limite_superado TINYINT(1),
    created_at TIMESTAMP
);
```

### **Tabla: `limite_consumo`**
```sql
CREATE TABLE limite_consumo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_dispositivo INT,
    limite_consumo DECIMAL(10,6),
    email_notificacion VARCHAR(255),
    notificacion_enviada TINYINT(1),
    ultima_notificacion DATETIME,
    created_at TIMESTAMP
);
```

### **Tabla: `cortes_linea`**
```sql
CREATE TABLE cortes_linea (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('corte', 'prealerta'),
    id_dispositivo INT,
    id_usuario INT,
    consumo_actual DECIMAL(10,6),
    limite_configurado DECIMAL(10,6),
    fecha_corte DATETIME,
    vista_por_usuario TINYINT(1),
    fecha_vista DATETIME,
    resuelto TINYINT(1),
    fecha_resolucion DATETIME,
    created_at TIMESTAMP
);
```

---

## **🔧 FUNCIONES PRINCIPALES**

### **1. MEDIR ENERGÍA (ESP32)**
```python
# Mediciones cada 5 segundos
voltaje_real = medir_voltaje_rms()      # Sensor ZMPT101B
corriente_real = medir_corriente_rms()  # Sensor ACS712
potencia_real = voltaje_real * corriente_real
kwh_acumulado = energia_wh_acumulada / 1000.0
```

### **2. ENVIAR DATOS (ESP32)**
```python
def send_data_to_database(voltage, current, power, kWh_accumulated):
    payload = {
        "voltaje": round(voltage, 2),
        "corriente": round(current, 4),
        "potencia": round(power, 2),
        "kwh_acumulado": round(kWh_accumulated, 6),
        "mac_address": macAddress
    }
    r = urequests.post(DATA_SERVER_URL, json=payload, headers=headers)
```

### **3. VERIFICAR LÍMITES (SERVIDOR)**
```php
if ($limite && $lectura['kwh_acumulado'] > $limite['limite_consumo']) {
    // Marcar como superado
    $lectura['limite_superado'] = 1;
    
    // Enviar notificaciones
    $this->enviarNotificacionEmail($idUsuario, $lectura['kwh_acumulado'], $limite['limite_consumo']);
    $this->alertaTelegram($mensaje, $idUsuario);
}
```

### **4. GUARDAR EN BASE DE DATOS (SERVIDOR)**
```php
$lectura = [
    'id_dispositivo' => $dispositivo['id_dispositivo'],
    'voltaje' => $data['voltaje'],
    'corriente' => $data['corriente'],
    'potencia' => $data['potencia'],
    'kwh_acumulado' => $data['kwh_acumulado'],
    'fecha' => date('Y-m-d H:i:s')
];
$this->energiaModel->insert($lectura);
```

---

## **🌐 URLs Y ENDPOINTS**

### **ESP32 → Servidor**
- **Enviar datos:** `POST /nuevos_datos`
- **Obtener límite:** `GET /energia/getlimite`
- **Obtener último kWh:** `GET /energia/getUltimoKwh`

### **Web → Servidor**
- **Dashboard:** `/energia`
- **Dispositivo específico:** `/energia/dispositivo/{id}`
- **Cortes de línea:** `/energia/cortes`
- **Configurar límite:** `POST /energia/actualizarLimite`

### **Telegram Bot**
- **Webhook:** `/telegram/webhook`
- **Configuración:** `/telegram/configuracion`

---

## **🎯 FLUJO COMPLETO RESUMIDO**

```
ESP32 (Thonny) → Medición → POST /nuevos_datos → Energia::recibirNuevosDatos()
                                                           ↓
Base de datos ← Insertar lectura ← Verificar límites ← Buscar dispositivo por MAC
     ↓
Si supera límite → Enviar email + Telegram → Registrar corte de línea
     ↓
ESP32 consulta → GET /getlimite → Obtener umbral actualizado
ESP32 consulta → GET /getUltimoKwh → Continuar desde último valor
```

---

## **📱 EXPLICACIÓN PARA PREGUNTAS COMUNES**

### **"¿Cómo sabe cuánta energía gasto?"**
- La ESP32 mide voltaje y corriente cada 5 segundos
- Multiplica: Potencia = Voltaje × Corriente
- Suma todo el tiempo: Energía = Potencia × Tiempo

### **"¿Dónde se guardan los datos?"**
- En una base de datos MySQL en tu servidor
- Cada medición se guarda con fecha y hora
- Puedes ver el historial completo en la web

### **"¿Cómo me avisa si gasto mucho?"**
- Compara tu consumo con el límite que configuraste
- Si lo superas, manda email y Telegram
- Solo te avisa una vez por hora para no molestar

### **"¿Cómo funciona el corte automático?"**
- La ESP32 tiene relés (interruptores automáticos)
- Si gastas más del límite, corta la luz no esencial
- Mantiene encendido lo esencial (nevera, etc.)

### **"¿Cómo veo mis datos?"**
- Entras a tu panel web de EcoVolt
- Ves gráficos en tiempo real
- Puedes descargar reportes PDF
- Recibes alertas por Telegram

---

## **🔗 RESUMEN TÉCNICO SIMPLE**

**En palabras simples:** EcoVolt es como tener un contador de luz inteligente que habla por internet, te avisa cuando gastas mucho, y puede cortar la luz automáticamente si es necesario.

**Flujo técnico:**
```
ESP32 (Hardware) → Mide energía → Envía por WiFi → Servidor web → Base de datos
                                                                    ↓
Panel web ← Muestra datos ← Consulta base de datos ← Verifica límites ← Envía alertas
```

---

## **📚 ARCHIVOS CLAVE DEL SISTEMA**

- **ESP32:** `main.py` (Thonny)
- **Recepción:** `app/Controllers/Energia.php`
- **Almacenamiento:** `app/Models/EnergiaModel.php`
- **Límites:** `app/Models/LimiteConsumoModel.php`
- **Notificaciones:** `app/Controllers/TelegramSimple.php`
- **Base de datos:** Tabla `energia`
- **Vistas:** `app/Views/energia/`

---

*Documentación generada para el sistema EcoVolt - Sistema de monitoreo inteligente de consumo eléctrico*
