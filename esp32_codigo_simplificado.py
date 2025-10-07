# main.py - EcoVolt (MicroPython) - VERSIÓN SIMPLIFICADA
# Sistema simple: corta cuando alcanza límite, restablece automáticamente

import machine
from machine import ADC, Pin
import network
import socket
import ujson
import time
import math
import ubinascii
import os
import sys

try:
    import urequests
except Exception as e:
    print("WARNING: urequests no encontrado. HTTP POST no funcionará.", e)
    urequests = None

# ------------------- CONFIGURACIÓN SENSORES -------------------
VREF = 3.3
ADC_RESOLUTION = 4095.0

ACS712_PIN = 34
SENSIBILIDAD_ACS712 = 0.1
OFFSET_ACS712 = VREF / 2
SAMPLES_ACS712 = 500

ZMPT101B_PIN = 35
CALIBRACION_ZMPT = 345.0
SAMPLES_ZMPT = 1000

RELE_NO_ESENCIAL_PIN = 26
RELE_ESENCIAL_PIN = 25

# VALORES POR DEFECTO
INTERVALO_SEGUNDOS = 1
UMBRAL_VOLT_CRITICO = 160
UMBRAL_VOLT_MINIMO = 198
VOLT_AJUSTE_MIN = 215
VOLT_AJUSTE_MAX = 223

# ------------------- VARIABLES SIMPLIFICADAS -------------------
kwh_acumulado_total = 0.0           # kWh acumulado total
UMBRAL_CONSUMO = 0.0                # Límite de consumo
TIEMPO_RESTABLECIMIENTO = 60        # Tiempo para restablecer (segundos)

# Variables de medición
energia_wh_acumulada = 0.0
voltaje = 0.0
corriente = 0.0
potencia = 0.0

# Variables de control
linea_cortada = False
tiempo_corte = 0

# --- NUEVAS VARIABLES DE CONTROL ---
UMBRAL_CHECK_INTERVAL = 5        # cada cuantos segundos preguntar al servidor mientras está cortado
last_umbral_check = 0

# Configuración WiFi
CONFIG_FILE = "config.json"
AP_PASSWORD = "12345678"
AP_SSID_PREFIX = "EcoVolt-"

macAddress = ""
stored_ssid = ""
stored_password = ""
isConfigured = False

# URLs - CAMBIAR A TU IP ACTUAL
DATA_SERVER_URL = "http://192.168.0.138/Tesina/public/nuevos_datos"
UMBRAL_ENDPOINT_URL = "http://192.168.0.138/Tesina/public/energia/getlimite"
ULTIMO_KWH_ENDPOINT_URL = "http://192.168.0.138/Tesina/public/energia/getultimokwh"
ESTADO_COMPLETO_URL = "http://192.168.0.138/Tesina/public/energia/getestadocompleto"

sta_if = network.WLAN(network.STA_IF)
ap_if = network.WLAN(network.AP_IF)

# Inicializar relés en 1 (cortados)
RELE_NO_ESENCIAL = Pin(RELE_NO_ESENCIAL_PIN, Pin.OUT, value=1)
RELE_ESENCIAL = Pin(RELE_ESENCIAL_PIN, Pin.OUT, value=1)

adc_corriente = ADC(Pin(ACS712_PIN))
adc_corriente.atten(ADC.ATTN_11DB)
adc_corriente.width(ADC.WIDTH_12BIT)

adc_voltaje = ADC(Pin(ZMPT101B_PIN))
adc_voltaje.atten(ADC.ATTN_11DB)
adc_voltaje.width(ADC.WIDTH_12BIT)

# ------------------- FUNCIONES SIMPLIFICADAS -------------------
def get_mac_address():
    mac = ubinascii.hexlify(network.WLAN().config('mac')).decode()
    return mac.upper()

def load_config():
    global stored_ssid, stored_password, isConfigured
    try:
        if CONFIG_FILE in os.listdir():
            with open(CONFIG_FILE, "r") as f:
                data = ujson.load(f)
                stored_ssid = data.get("ssid", "")
                stored_password = data.get("password", "")
                isConfigured = bool(stored_ssid and stored_password)
                print("CONFIG loaded:", stored_ssid, "(configured)" if isConfigured else "(not configured)")
        else:
            isConfigured = False
    except Exception as e:
        print("Error loading config:", e)
        isConfigured = False

def save_config(ssid, password):
    try:
        with open(CONFIG_FILE, "w") as f:
            ujson.dump({"ssid": ssid, "password": password}, f)
        print("Configuración guardada en", CONFIG_FILE)
    except Exception as e:
        print("Error saving config:", e)

def clear_config():
    global stored_ssid, stored_password, isConfigured
    try:
        if CONFIG_FILE in os.listdir():
            os.remove(CONFIG_FILE)
        stored_ssid = ""
        stored_password = ""
        isConfigured = False
        print("Configuración borrada.")
    except Exception as e:
        print("Error clearing config:", e)

def rand_int(min_v, max_v):
    try:
        r = machine.rng()
        return (r % (max_v - min_v + 1)) + min_v
    except:
        return (int(time.time() * 1000) % (max_v - min_v + 1)) + min_v

# ------------------- FUNCIÓN SIMPLE PARA OBTENER ÚLTIMO kWh -------------------
def obtener_ultimo_kwh():
    global kwh_acumulado_total, energia_wh_acumulada
    if urequests is None:
        print("❌ urequests no disponible")
        return 0.0
    
    try:
        url = ULTIMO_KWH_ENDPOINT_URL + "?mac_address=" + macAddress
        print("🔍 Obteniendo último kWh desde:", url)
        r = urequests.get(url, timeout=10)
        
        if r.status_code == 200:
            data = r.json()
            print("📊 Respuesta:", data)
            
            if "ultimo_kwh" in data:
                ultimo_kwh = float(data["ultimo_kwh"])
                kwh_acumulado_total = ultimo_kwh
                energia_wh_acumulada = ultimo_kwh * 1000.0
                print("✅ Último kWh recuperado:", ultimo_kwh)
                return ultimo_kwh
            else:
                print("❌ No se encontró 'ultimo_kwh' en la respuesta")
                return 0.0
        else:
            print("❌ Error HTTP:", r.status_code)
            return 0.0
            
        r.close()
        
    except Exception as e:
        print("❌ Error obteniendo último kWh:", e)
        return 0.0

# ------------------- FUNCIÓN SIMPLE PARA OBTENER UMBRAL -------------------
def obtener_umbral():
    global UMBRAL_CONSUMO
    if urequests is None:
        print("❌ urequests no disponible")
        return UMBRAL_CONSUMO
    
    try:
        print("🔍 Obteniendo umbral desde:", UMBRAL_ENDPOINT_URL)
        r = urequests.get(UMBRAL_ENDPOINT_URL, timeout=10)
        
        if r.status_code == 200:
            data = r.json()
            print("📊 Respuesta umbral:", data)
            
            if "limite_consumo" in data:
                nuevo_umbral = float(data["limite_consumo"])
                UMBRAL_CONSUMO = nuevo_umbral
                print("✅ Umbral actualizado:", UMBRAL_CONSUMO)
                return nuevo_umbral
            else:
                print("❌ No se encontró 'limite_consumo' en la respuesta")
                return UMBRAL_CONSUMO
        else:
            print("❌ Error HTTP umbral:", r.status_code)
            return UMBRAL_CONSUMO
            
        r.close()
        
    except Exception as e:
        print("❌ Error obteniendo umbral:", e)
        return UMBRAL_CONSUMO

# ------------------- FUNCIÓN OPTIMIZADA PARA OBTENER ESTADO COMPLETO -------------------
def obtener_estado_completo():
    global UMBRAL_CONSUMO, kwh_acumulado_total
    if urequests is None:
        print("❌ urequests no disponible")
        return False
    
    try:
        url = ESTADO_COMPLETO_URL + "?mac_address=" + macAddress
        print("🔍 Obteniendo estado completo desde:", url)
        r = urequests.get(url, timeout=10)
        
        if r.status_code == 200:
            data = r.json()
            print("📊 Respuesta estado completo:", data)
            
            if data.get('success'):
                # Actualizar umbral
                if "limite_consumo" in data:
                    UMBRAL_CONSUMO = float(data["limite_consumo"])
                
                # Opcional: sincronizar kWh desde servidor si hay diferencia
                if "ultimo_kwh" in data:
                    kwh_servidor = float(data["ultimo_kwh"])
                    if abs(kwh_servidor - kwh_acumulado_total) > 0.001:  # Si hay diferencia significativa
                        print(f"🔄 Sincronizando kWh: local {kwh_acumulado_total:.4f} → servidor {kwh_servidor:.4f}")
                        kwh_acumulado_total = kwh_servidor
                        energia_wh_acumulada = kwh_servidor * 1000.0
                
                print("✅ Estado completo actualizado - Umbral:", UMBRAL_CONSUMO, "kWh:", kwh_acumulado_total)
                return True
            else:
                print("❌ Error en respuesta estado completo:", data.get('error', 'Sin error'))
                return False
        else:
            print("❌ Error HTTP estado completo:", r.status_code)
            return False
            
        r.close()
        
    except Exception as e:
        print("❌ Error obteniendo estado completo:", e)
        return False

# ------------------- FUNCIÓN MODIFICADA DE RESTABLECIMIENTO -------------------
def verificar_restablecimiento():
    """
    - Si linea_cortada == True: preguntar el umbral al servidor periódicamente.
    - Si el nuevo umbral es mayor que el kwh acumulado actual, RESTAURAR la línea.
    - No tocar kwh_acumulado_total (se mantiene).
    - Devuelve True si se restableció en esta llamada, False si no.
    """
    global linea_cortada, tiempo_corte, last_umbral_check, UMBRAL_CONSUMO, kwh_acumulado_total

    if not linea_cortada:
        return False

    tiempo_transcurrido = time.time() - tiempo_corte

    # Mostrar countdown cada 10 segundos (mantengo tu idea original)
    if int(tiempo_transcurrido) % 10 == 0 and tiempo_transcurrido > 0:
        tiempo_restante = TIEMPO_RESTABLECIMIENTO - tiempo_transcurrido
        if tiempo_restante > 0:
            print(f"⏰ Restablecimiento automático en {int(tiempo_restante)} s (backup) ...")

    # 1) Si pasó el tiempo de restablecimiento clásico, restablecer (comportamiento anterior)
    if TIEMPO_RESTABLECIMIENTO and (tiempo_transcurrido >= TIEMPO_RESTABLECIMIENTO):
        RELE_NO_ESENCIAL.value(0)
        RELE_ESENCIAL.value(0)
        linea_cortada = False
        tiempo_corte = 0
        print("✅ Línea restablecida AUTOMÁTICAMENTE (por timeout)")
        print(f"📊 Consumo actual: {kwh_acumulado_total:.2f} kWh, Umbral: {UMBRAL_CONSUMO:.2f} kWh")
        return True

    # 2) Mientras está cortada, chequear el estado completo en el servidor periódicamente
    #    (esto permite que el usuario cambie el umbral desde la web y la línea vuelva)
    now = time.time()
    if now - last_umbral_check >= UMBRAL_CHECK_INTERVAL:
        last_umbral_check = now
        print("🔎 (CORTADA) Consultando estado completo en servidor...")
        obtener_estado_completo()  # actualizará UMBRAL_CONSUMO y sincronizará kWh

        print(f"📌 Umbral actual (servidor): {UMBRAL_CONSUMO:.2f} kWh  |  kWh local: {kwh_acumulado_total:.2f}")

        # Si el nuevo umbral es mayor que el consumo acumulado actual → RESTAURAR
        if UMBRAL_CONSUMO > kwh_acumulado_total:
            print("⚡ Nuevo umbral permite restaurar la línea. Restaurando relés...")
            RELE_NO_ESENCIAL.value(0)  # 0 = ENCENDIDO
            RELE_ESENCIAL.value(0)     # 0 = ENCENDIDO (si quieres que esencial vuelva también)
            linea_cortada = False
            tiempo_corte = 0
            # NOTA: no cambiamos energia_wh_acumulada ni kwh_acumulado_total: preservados
            print(f"✅ Línea restaurada por cambio de umbral. kWh: {kwh_acumulado_total:.2f}, Umbral: {UMBRAL_CONSUMO:.2f}")
            return True

    return False


# ------------------- MEDICIONES -------------------
def medir_corriente_rms(samples=SAMPLES_ACS712):
    suma = 0.0
    for _ in range(samples):
        raw = adc_corriente.read()
        voltaje_sensor = (raw / ADC_RESOLUTION) * VREF
        diferencia = voltaje_sensor - OFFSET_ACS712
        suma += (diferencia ** 2)
        time.sleep_us(100)
    rms = math.sqrt(suma / samples)
    return rms / SENSIBILIDAD_ACS712

def medir_voltaje_rms(samples=SAMPLES_ZMPT):
    suma = 0.0
    for _ in range(samples):
        raw = adc_voltaje.read()
        voltaje_sensor = (raw / ADC_RESOLUTION) * VREF
        voltaje_centrado = voltaje_sensor - (VREF / 2)
        suma += voltaje_centrado ** 2
        time.sleep_us(100)
    voltaje_rms_sensor = math.sqrt(suma / samples)
    return voltaje_rms_sensor * CALIBRACION_ZMPT

# ------------------- WIFI STA -------------------
def connect_sta(ssid, password, timeout_s=15):
    sta_if.active(True)
    if sta_if.isconnected():
        return True
    print("Conectando a WiFi:", ssid)
    sta_if.connect(ssid, password)
    t0 = time.time()
    while time.time() - t0 < timeout_s:
        if sta_if.isconnected():
            print("✅ Conectado a", ssid)
            print("IP:", sta_if.ifconfig())
            return True
        time.sleep(0.5)
    print("❌ No se pudo conectar a", ssid)
    return False

# ------------------- ENVÍO DE DATOS -------------------
def send_data_to_database(voltage, current, power, kWh):
    if urequests is None:
        print("urequests no disponible — no se puede enviar datos")
        return False
    if not sta_if.isconnected():
        print("⚠️ WiFi no conectado, no envio datos")
        return False
    
    payload = {
        "voltaje": round(voltage, 2),
        "corriente": round(current, 4),
        "potencia": round(power, 2),
        "kwh": round(kWh, 4),
        "ip_address": sta_if.ifconfig()[0],
        "mac_address": macAddress,
        "linea_cortada": linea_cortada
    }
    headers = {"Content-Type": "application/json"}
    try:
        r = urequests.post(DATA_SERVER_URL, json=payload, headers=headers, timeout=10)
        status = r.status_code
        r.close()
        return status == 200
    except Exception as e:
        print("❌ Error HTTP POST:", e)
        return False

# ------------------- MODO AP SIMPLIFICADO -------------------
def start_ap_mode():
    ap_if.active(True)
    ap_ssid = AP_SSID_PREFIX + macAddress[-6:]
    try:
        ap_if.config(essid=ap_ssid, password=AP_PASSWORD, authmode=3)
    except Exception:
        ap_if.config(essid=ap_ssid, password=AP_PASSWORD)
    ap_if.ifconfig(('192.168.4.1', '255.255.255.0', '192.168.4.1', '8.8.8.8'))
    print("=== Modo AP activo ===")
    print("SSID:", ap_ssid, "Password:", AP_PASSWORD)
    print("Conectar a http://192.168.4.1")
    return ap_ssid

def handle_ap_request(client_socket):
    try:
        client_socket.settimeout(10.0)
        request = client_socket.recv(1024).decode('utf-8')
        print("📨 Request recibido")
        
        lines = request.split('\n')
        if not lines:
            return
        
        first_line = lines[0]
        if 'GET' not in first_line:
            return
            
        parts = first_line.split(' ')
        if len(parts) < 2:
            return
            
        path = parts[1]
        print("📁 Path:", path)
        
        if path == '/' or path.startswith('/?'):
            if '?' in path:
                query_string = path.split('?', 1)[1]
                params = parse_query(query_string)
                
                if 'ssid' in params and 'password' in params:
                    ssid = url_decode(params['ssid'])
                    password = url_decode(params['password'])
                    
                    if ssid and password:
                        save_config(ssid, password)
                        response_html = """
                            <html>
                                <head><title>EcoVolt Configurado</title></head>
                                <body>
                                    <h1>✅ Configuración Guardada</h1>
                                    <p>SSID: """ + ssid + """</p>
                                    <p>El dispositivo se reiniciará y conectará a la red.</p>
                                    <script>
                                        setTimeout(function() {
                                            window.location.href = '/';
                                        }, 3000);
                                    </script>
                                </body>
                            </html>
                        """
                        client_socket.send('HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n')
                        client_socket.send(response_html)
                        client_socket.close()
                        time.sleep(2)
                        machine.reset()
                        return
            
            html = """
            <!DOCTYPE html>
            <html>
            <head>
                <title>EcoVolt - Configuración WiFi</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
                    .container { max-width: 400px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                    h1 { color: #2c3e50; text-align: center; }
                    .form-group { margin-bottom: 15px; }
                    label { display: block; margin-bottom: 5px; font-weight: bold; }
                    input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
                    button { width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
                    button:hover { background: #2980b9; }
                    .info { background: #e8f4fd; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
                    .mac { font-family: monospace; background: #f8f9fa; padding: 5px; border-radius: 3px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>🌐 EcoVolt WiFi</h1>
                    <div class="info">
                        <p><strong>MAC:</strong> <span class="mac">""" + macAddress + """</span></p>
                        <p>Configure su red WiFi para continuar</p>
                    </div>
                    <form method="GET">
                        <div class="form-group">
                            <label for="ssid">SSID (Nombre de red):</label>
                            <input type="text" id="ssid" name="ssid" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit">💾 Guardar Configuración</button>
                    </form>
                    <p style="text-align: center; margin-top: 15px; font-size: 12px; color: #666;">
                        El dispositivo se reiniciará automáticamente
                    </p>
                </div>
            </body>
            </html>
            """
            
            client_socket.send('HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n')
            client_socket.send(html)
            
        else:
            client_socket.send('HTTP/1.1 404 Not Found\r\nContent-Type: text/html\r\n\r\n')
            client_socket.send('<html><body><h1>404 - Página no encontrada</h1></body></html>')
            
    except Exception as e:
        print("❌ Error handling request:", e)
    finally:
        try:
            client_socket.close()
        except:
            pass

def parse_query(query):
    params = {}
    if not query:
        return params
    parts = query.split('&')
    for p in parts:
        if '=' in p:
            k, v = p.split('=', 1)
            params[k] = url_decode(v)
    return params

def url_decode(s):
    s = s.replace('+', ' ')
    i = 0
    out = ''
    while i < len(s):
        if s[i] == '%' and i + 2 < len(s):
            try:
                hexv = s[i+1:i+3]
                out += chr(int(hexv, 16))
                i += 3
            except:
                out += s[i]
                i += 1
        else:
            out += s[i]
            i += 1
    return out

def ap_mode_loop():
    ap_ssid = start_ap_mode()
    server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    server_socket.bind(('0.0.0.0', 80))
    server_socket.listen(5)
    server_socket.settimeout(1.0)
    
    print("🔵 Modo AP ACTIVO - Esperando configuración...")
    
    last_status_print = time.time()
    
    while True:
        try:
            current_time = time.time()
            if current_time - last_status_print > 10:
                print("🔵 Modo AP activo - Conectar a:", ap_ssid, "- http://192.168.4.1")
                last_status_print = current_time
            
            client_socket, addr = server_socket.accept()
            client_socket.settimeout(5.0)
            
            print("📱 Cliente conectado:", addr[0])
            handle_ap_request(client_socket)
            
        except OSError as e:
            if e.args[0] == 110 or e.args[0] == 116:
                continue
            print("❌ Error socket:", e)
            break
        except Exception as e:
            print("❌ Error en AP loop:", e)
            time.sleep(1)
    
    server_socket.close()

# ------------------- INICIALIZACIÓN SIMPLIFICADA -------------------
print("🚀 INICIANDO ECOVOLT SIMPLIFICADO...")
macAddress = get_mac_address()
print("🔑 MAC Address:", macAddress)

load_config()

if not isConfigured:
    print("🔵 Iniciando MODO AP (sin configuración)")
    ap_mode_loop()
else:
    print("🟢 Intentando conexión WiFi con configuración guardada...")
    connected = connect_sta(stored_ssid, stored_password, timeout_s=20)
    
    if not connected:
        print("❌ Falló conexión WiFi. Iniciando modo AP...")
        clear_config()
        time.sleep(2)
        ap_mode_loop()
    else:
        print("✅ WiFi conectado. Inicializando sistema...")
        
        # Obtener último kWh acumulado
        print("🔄 Obteniendo último kWh acumulado...")
        obtener_ultimo_kwh()
        
        # Obtener umbral de consumo
        print("🔄 Obteniendo umbral de consumo...")
        obtener_umbral()
        
        print(f"📊 Sistema iniciado - kWh actual: {kwh_acumulado_total:.2f}, Umbral: {UMBRAL_CONSUMO:.2f}")

# --- LOOP PRINCIPAL SIMPLIFICADO ---
print("🔄 Iniciando loop principal...")
last_config_update = 0
CONFIG_UPDATE_INTERVAL = 30000  # 30 segundos

while True:
    try:
        # Chequeo WiFi periódico
        if not sta_if.isconnected():
            print("❌ WiFi desconectado. Reconectando...")
            if not connect_sta(stored_ssid, stored_password, timeout_s=10):
                print("❌ No se pudo reconectar. Reiniciando...")
                machine.reset()
        
        # Actualizar configuración cada 30 segundos
        current_time = time.ticks_ms()
        if time.ticks_diff(current_time, last_config_update) > CONFIG_UPDATE_INTERVAL:
            print("🔄 Actualizando configuración...")
            obtener_umbral()
            last_config_update = current_time
        
        # Verificar restablecimiento (ahora comprueba umbral remoto mientras está cortada)
        verificar_restablecimiento()
        
        # Mediciones
        voltaje = medir_voltaje_rms()
        corriente = medir_corriente_rms()
        
        # Filtrado
        if voltaje < UMBRAL_VOLT_CRITICO:
            voltaje = 0.0
            corriente = 0.0
            potencia = 0.0
            print("🚫 SIN ENERGÍA: voltaje crítico → todo apagado")
            RELE_NO_ESENCIAL.value(1)  # 1 = CORTADO
            RELE_ESENCIAL.value(1)     # 1 = CORTADO
        else:
            if voltaje < UMBRAL_VOLT_MINIMO:
                voltaje = rand_int(VOLT_AJUSTE_MIN, VOLT_AJUSTE_MAX)
                print(f"⚠️ Voltaje bajo, ajustado a {voltaje} V")
            
            # Cálculo potencia y energía
            potencia = voltaje * corriente
            energia_intervalo = potencia * (INTERVALO_SEGUNDOS / 3600.0)
            
            # ACTUALIZAR kWh ACUMULADO
            energia_wh_acumulada += energia_intervalo
            kwh_acumulado_total = energia_wh_acumulada / 1000.0
            
            # CONTROL SIMPLE: CORTAR cuando se alcanza el umbral
            if kwh_acumulado_total >= UMBRAL_CONSUMO and not linea_cortada:
                RELE_NO_ESENCIAL.value(1)  # 1 = CORTADO
                linea_cortada = True
                tiempo_corte = time.time()
                
                print(f"⚠️ CORTE: Consumo {kwh_acumulado_total:.2f} kWh >= Umbral {UMBRAL_CONSUMO:.2f} kWh")
                print(f"⏰ Restablecimiento automático en {TIEMPO_RESTABLECIMIENTO} segundos")
            
            # Línea esencial siempre encendida (solo se corta si no hay energía)
            RELE_ESENCIAL.value(0)  # 0 = ENCENDIDO
                           
        # Envío de datos
        send_data_to_database(voltaje, corriente, potencia, kwh_acumulado_total)
        
        time.sleep(INTERVALO_SEGUNDOS)
        
    except Exception as e:
        print("❌ Exception en main loop:", e)
        time.sleep(5)
        try:
            machine.reset()
        except:
            sys.exit()
