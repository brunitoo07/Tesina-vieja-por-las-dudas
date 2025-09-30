# 🔌 Integración ESP32 - Sistema de Límites de Consumo

## 📋 Resumen de Funcionalidades Implementadas

### ✅ Endpoint Creado
- **URL**: `http://192.168.0.138/Tesina/public/energia/getlimite`
- **Método**: GET
- **Acceso**: Público (sin autenticación)
- **Propósito**: Permitir que el ESP32 obtenga el límite de consumo actualizado

### ✅ Vista Mejorada
- Panel de estado del límite en tiempo real
- Configuración visual del límite de consumo
- Botón para probar el endpoint
- Feedback visual del estado del consumo

## 🚀 Cómo Usar con tu Código de MicroPython

### 1. URL del Endpoint
Tu código ya está configurado correctamente:
```python
UMBRAL_ENDPOINT_URL = "http://192.168.0.138/Tesina/public/energia/getlimite"
```

### 2. Función Mejorada para Obtener Límite
Tu función `obtener_umbral_desde_web()` ya está bien implementada. Aquí tienes una versión mejorada con más logging:

```python
def obtener_umbral_desde_web():
    global UMBRAL_CONSUMO_KWH
    if urequests is None:
        print("urequests no disponible, no se puede obtener umbral")
        return UMBRAL_CONSUMO_KWH
    
    try:
        # Agregar MAC address como parámetro opcional
        mac_param = f"?mac={macAddress}" if macAddress else ""
        url_completa = UMBRAL_ENDPOINT_URL + mac_param
        
        print(f"🌐 Consultando límite desde: {url_completa}")
        r = urequests.get(url_completa)
        
        if r.status_code == 200:
            data = r.json()
            if data.get("success") and "limite_consumo" in data:
                nuevo_limite = float(data["limite_consumo"])
                if nuevo_limite != UMBRAL_CONSUMO_KWH:
                    print(f"🔄 Límite actualizado: {UMBRAL_CONSUMO_KWH} → {nuevo_limite} kWh")
                    UMBRAL_CONSUMO_KWH = nuevo_limite
                else:
                    print(f"✅ Límite sin cambios: {UMBRAL_CONSUMO_KWH} kWh")
                
                # Log adicional si hay información del dispositivo
                if "dispositivo_id" in data:
                    print(f"📱 Dispositivo ID: {data['dispositivo_id']}")
                if "timestamp" in data:
                    print(f"⏰ Timestamp: {data['timestamp']}")
            else:
                print("⚠️ Respuesta del servidor no válida")
        else:
            print(f"❌ Error HTTP: {r.status_code}")
        
        r.close()
    except Exception as e:
        print(f"❌ Error obteniendo umbral: {e}")
    
    return UMBRAL_CONSUMO_KWH
```

### 3. Integración en el Loop Principal
Tu código ya tiene la integración correcta. Aquí está la parte relevante:

```python
# En tu loop principal, cada 30 segundos:
if time.ticks_diff(time.ticks_ms(), last_wifi_check) > 30000:
    last_wifi_check = time.ticks_ms()
    if sta_if.isconnected():
        UMBRAL_CONSUMO_KWH = obtener_umbral_desde_web()
    else:
        clear_config()
        time.sleep(1)
        machine.reset()
```

## 🎯 Funcionalidades de la Vista Web

### Panel de Estado del Límite
- **Límite Actual**: Muestra el valor configurado
- **Estado del Consumo**: 
  - ✅ Verde: Dentro del límite
  - ⚠️ Amarillo: Cerca del límite (80%+)
  - ❌ Rojo: Límite superado

### Configuración del Límite
- Campo numérico con validación
- Botón "Probar Endpoint ESP32" para verificar conectividad
- Botón "Actualizar Ahora" para forzar actualización
- Información del endpoint en tiempo real

### Respuesta del Endpoint
```json
{
  "success": true,
  "limite_consumo": 0.004,
  "dispositivo_id": 123,
  "mac_address": "AA:BB:CC:DD:EE:FF",
  "timestamp": "2024-01-01 12:00:00",
  "ip_address": "192.168.1.100"
}
```

## 🔧 Configuración Adicional

### Parámetros Opcionales del Endpoint
- `?mac=AA:BB:CC:DD:EE:FF` - Para obtener límite específico del dispositivo
- Sin parámetros - Obtiene el límite más reciente configurado

### Logs del Sistema
El sistema registra todas las consultas al endpoint en los logs de CodeIgniter:
- Consultas exitosas
- Errores de conexión
- Cambios de límite

## 🚨 Consideraciones de Seguridad

1. **Endpoint Público**: No requiere autenticación para facilitar el acceso del ESP32
2. **Logging**: Todas las consultas se registran con IP y timestamp
3. **Validación**: El endpoint valida los datos antes de devolverlos
4. **Fallback**: Siempre devuelve un valor por defecto en caso de error

## 📱 Pruebas Recomendadas

1. **Probar Endpoint**: Usar el botón "Probar Endpoint ESP32" en la vista
2. **Cambiar Límite**: Modificar el valor y verificar que se actualiza en el ESP32
3. **Verificar Logs**: Revisar los logs del servidor para confirmar las consultas
4. **Simular Fallo**: Desconectar WiFi del ESP32 y verificar el comportamiento

## 🔄 Flujo de Trabajo Completo

1. **Admin/Supervisor** configura el límite en la vista web
2. **ESP32** consulta el límite cada 30 segundos
3. **Sistema** compara consumo actual vs límite
4. **ESP32** desconecta línea no esencial si se supera el límite
5. **Vista web** muestra estado en tiempo real

¡Tu sistema está listo para funcionar con límites dinámicos! 🎉
