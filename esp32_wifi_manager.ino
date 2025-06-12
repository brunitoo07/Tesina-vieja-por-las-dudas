#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <WiFi.h>
#include <WebServer.h>
#include <DNSServer.h>
#include <EEPROM.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <esp_wifi.h>

// ====================================================================
// --- Configuración Global ---
// ====================================================================

// LCD 16x2 I2C
LiquidCrystal_I2C lcd(0x27, 16, 2);

// Pines sensores
const int adc_current_pin = 34;
const int adc_voltage_pin = 35;

// Calibraciones
float currCalibration = 69.0;
float voltCalibration = 220.0;

// Energía acumulada
float energyConsumed = 0.0;
unsigned long previousMillis = 0;
const unsigned long interval = 10000;

// Direcciones del servidor
const String DATA_SERVER_URL = "http://192.168.2.176/Tesina/public/nuevos_datos";

// EEPROM
const int EEPROM_SIZE = 512;
const int EEPROM_SSID_ADDR = 0;
const int EEPROM_PASS_ADDR = 100;

// ====================================================================
// --- Configuración AP ---
// ====================================================================

const char* AP_SSID_PREFIX = "EcoVolt-";
const char* AP_PASSWORD = "12345678";

// Puerto para el servidor web
const int WEB_PORT = 80;
const int DNS_PORT = 53;

// Variables para almacenar la configuración
String stored_ssid = "";
String stored_password = "";
bool isConfigured = false;

// Crear instancias del servidor web y DNS
WebServer server(WEB_PORT);
DNSServer dnsServer;

// Dirección IP del AP
IPAddress apIP(192, 168, 4, 1);
IPAddress gateway(192, 168, 4, 1);
IPAddress subnet(255, 255, 255, 0);

// ====================================================================
// --- Funciones Auxiliares ---
// ====================================================================

String getMacAddress() {
    uint8_t mac[6];
    WiFi.macAddress(mac);
    char macStr[18];
    snprintf(macStr, sizeof(macStr), "%02X%02X%02X%02X%02X%02X",
             mac[0], mac[1], mac[2], mac[3], mac[4], mac[5]);
    return String(macStr);
}

void loadConfig() {
    Serial.println("Cargando configuración de EEPROM...");
    EEPROM.begin(EEPROM_SIZE);
    stored_ssid = EEPROM.readString(EEPROM_SSID_ADDR);
    stored_password = EEPROM.readString(EEPROM_PASS_ADDR);

    isConfigured = (stored_ssid.length() > 0 && stored_password.length() > 0);
    Serial.print("SSID cargado: "); Serial.println(stored_ssid);
    Serial.print("Estado configurado: "); Serial.println(isConfigured ? "Sí" : "No");
}

void saveConfig() {
    Serial.println("Guardando configuración en EEPROM...");
    EEPROM.writeString(EEPROM_SSID_ADDR, stored_ssid);
    EEPROM.writeString(EEPROM_PASS_ADDR, stored_password);
    EEPROM.commit();
    Serial.println("Configuración guardada.");
}

void clearConfig() {
    Serial.println("Borrando configuración de EEPROM...");
    for (int i = 0; i < EEPROM_SIZE; i++) {
        EEPROM.write(i, 0);
    }
    EEPROM.commit();
    isConfigured = false;
    stored_ssid = "";
    stored_password = "";
    Serial.println("Configuración borrada.");
}

// ====================================================================
// --- Funciones de Configuración de Red ---
// ====================================================================

void setupAPMode() {
    String macAddress = getMacAddress();
    String apName = String(AP_SSID_PREFIX) + macAddress.substring(8);
    
  WiFi.mode(WIFI_AP);
  WiFi.softAPConfig(apIP, gateway, subnet);
    WiFi.softAP(apName.c_str(), AP_PASSWORD);
    
    Serial.print("AP iniciado: "); Serial.print(apName); Serial.print(" / "); Serial.println(AP_PASSWORD);
    Serial.print("IP del AP: "); Serial.println(WiFi.softAPIP());
    Serial.print("MAC Real: "); Serial.println(macAddress);

    // Configurar DNS para redireccionar todas las peticiones a la IP del AP
  dnsServer.start(DNS_PORT, "*", apIP);
  
  server.on("/", HTTP_GET, handleRoot);
  server.on("/scan", HTTP_GET, handleScan);
  server.on("/connect", HTTP_POST, handleConnect);
    server.begin();
    
    Serial.println("Servidor web AP iniciado.");
  
    lcd.clear();
    lcd.print("Configurar WiFi");
    lcd.setCursor(0, 1);
    lcd.print(apName);
}

void handleRoot() {
    String macAddress = getMacAddress();
    
    String html = "<!DOCTYPE html>"
                 "<html>"
                 "<head>"
                 "<title>EcoVolt WiFi Setup</title>"
                 "<meta name='viewport' content='width=device-width, initial-scale=1'>"
                 "<style>"
                 "body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f0f2f5; }"
                 ".container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }"
                 "h1 { color: #1a73e8; text-align: center; margin-bottom: 20px; }"
                 ".mac-address { background: #e8f0fe; padding: 10px; margin: 10px 0; border-radius: 5px; text-align: center; font-family: monospace; }"
                 ".network { background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; cursor: pointer; }"
                 ".network:hover { background: #e9ecef; }"
                 "input { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; }"
                 "button { width: 100%; padding: 10px; background: #1a73e8; color: white; border: none; border-radius: 5px; cursor: pointer; }"
                 "button:hover { background: #1557b0; }"
                 "</style>"
                 "</head>"
                 "<body>"
                 "<div class='container'>"
                 "<h1>EcoVolt WiFi Setup</h1>"
                 "<div class='mac-address'>"
                 "<strong>MAC:</strong><br>" + macAddress +
                 "</div>"
                 "<div id='networks'></div>"
                 "<div id='connect-form' style='display:none;'>"
                 "<h2>Conectar a Red WiFi</h2>"
                 "<form action='/connect' method='post'>"
                 "<input type='hidden' id='ssid' name='ssid'>"
                 "<input type='password' name='password' placeholder='Contraseña' required>"
                 "<button type='submit'>Conectar</button>"
                 "</form>"
                 "</div>"
                 "</div>"
                 "<script>"
                 "function showConnectForm(ssid) {"
                 "    document.getElementById('ssid').value = ssid;"
                 "    document.getElementById('connect-form').style.display = 'block';"
                 "    document.getElementById('networks').style.display = 'none';"
                 "}"
                 "function scanNetworks() {"
                 "    fetch('/scan')"
                 "        .then(response => response.json())"
                 "        .then(networks => {"
                 "            const networksDiv = document.getElementById('networks');"
                 "            networksDiv.innerHTML = '<h2>Redes Disponibles</h2>';"
                 "            networks.forEach(network => {"
                 "                networksDiv.innerHTML += '<div class=\"network\" onclick=\"showConnectForm(\\'' + network.ssid + '\\')\">' +"
                 "                    network.ssid + ' (' + network.rssi + ' dBm)</div>';"
                 "            });"
                 "        });"
                 "}"
                 "scanNetworks();"
                 "</script>"
                 "</body>"
                 "</html>";
  
  server.send(200, "text/html", html);
}

void handleScan() {
  int n = WiFi.scanNetworks();
  String json = "[";
  
  for (int i = 0; i < n; ++i) {
    if (i > 0) json += ",";
    json += "{";
    json += "\"ssid\":\"" + WiFi.SSID(i) + "\",";
    json += "\"rssi\":" + String(WiFi.RSSI(i));
    json += "}";
  }
  
  json += "]";
  server.send(200, "application/json", json);
}

void handleConnect() {
  if (server.hasArg("ssid") && server.hasArg("password")) {
        stored_ssid = server.arg("ssid");
        stored_password = server.arg("password");
        String macAddress = getMacAddress();
        
        Serial.println("Configuración recibida:");
        Serial.print("  SSID: "); Serial.println(stored_ssid);
        Serial.print("  MAC: "); Serial.println(macAddress);

        WiFi.mode(WIFI_STA);
        WiFi.begin(stored_ssid.c_str(), stored_password.c_str());

        lcd.clear();
        lcd.print("Conectando...");
        lcd.setCursor(0,1);
        lcd.print(stored_ssid);

        int attempts = 0;
        while (WiFi.status() != WL_CONNECTED && attempts < 30) {
            delay(500);
            Serial.print(".");
            attempts++;
        }
        Serial.println();

        if (WiFi.status() == WL_CONNECTED) {
            Serial.println("Conectado a la red del usuario.");
            Serial.print("IP: "); Serial.println(WiFi.localIP());
            isConfigured = true;
            saveConfig();
            server.send(200, "text/plain", "OK. Dispositivo conectado. Reiniciando...");
    delay(2000);
    ESP.restart();
  } else {
            Serial.println("No se pudo conectar a la red del usuario.");
            server.send(500, "text/plain", "Error al conectar a su red WiFi. Verifique la contraseña.");
        }
    } else {
        server.send(400, "text/plain", "Faltan parámetros (SSID o Password).");
    }
}

// ====================================================================
// --- Funciones de Medición y Envío de Datos ---
// ====================================================================

float read_current() {
    int value = analogRead(adc_current_pin);
    float Irms = (value / 4095.0) * (3.3 / currCalibration);
    return Irms;
}

float read_voltage() {
    float Vrms = 215.0 + ((float)rand() / RAND_MAX) * 2.0;
    return Vrms;
}

void displayLCD(float current, float voltage) {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("I: ");
    lcd.print(current, 4);
    lcd.print(" A");
    
    lcd.setCursor(0, 1);
    lcd.print("V: ");
    lcd.print(voltage, 2);
    lcd.print(" V");
}

void checkWiFiConnection() {
    static unsigned long lastCheck = 0;
    const unsigned long checkInterval = 30000;
    
    if (millis() - lastCheck > checkInterval) {
        lastCheck = millis();
        if (WiFi.status() != WL_CONNECTED) {
            Serial.println("⚠️ WiFi desconectado, intentando reconectar...");
            lcd.clear();
            lcd.print("WiFi Perdido!");
            lcd.setCursor(0,1);
            lcd.print("Reconectando...");
            WiFi.reconnect();
            delay(1000);
            
            if (WiFi.status() == WL_CONNECTED) {
                Serial.println("✅ WiFi reconectado");
                lcd.clear();
                lcd.print("WiFi OK!");
                lcd.setCursor(0,1);
                lcd.print(WiFi.localIP().toString());
            } else {
                Serial.println("❌ Falló la reconexión WiFi");
                clearConfig();
                ESP.restart();
            }
        }
    }
}

void sendDataToDatabase(float voltage, float current, float power, float kWh) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("⚠️ Reconectando a WiFi...");
        WiFi.reconnect();
        delay(1000);
        return;
    }

    HTTPClient http;
    http.begin(DATA_SERVER_URL);
    http.addHeader("Content-Type", "application/json");
    http.setTimeout(5000);

    // Formato exacto que funcionaba antes
    String postData = "{\"voltaje\":" + String(voltage, 2) +
                     ",\"corriente\":" + String(current, 4) +
                     ",\"potencia\":" + String(power, 2) +
                     ",\"kwh\":" + String(kWh, 4) +
                     ",\"mac_address\":\"" + getMacAddress() + "\"}";

    Serial.println("\n📤 Enviando datos al servidor...");
    Serial.println("📝 Datos: " + postData);

    int httpResponseCode = http.POST(postData);
    
    if (httpResponseCode > 0) {
        Serial.print("✅ HTTP Response code: ");
        Serial.println(httpResponseCode);
        String response = http.getString();
        Serial.print("📨 Respuesta del servidor: ");
        Serial.println(response);
    } else {
        Serial.print("❌ Error en HTTP POST: ");
        Serial.println(http.errorToString(httpResponseCode).c_str());
    }
    
    http.end();
}

void sendDataWithRetry(float voltage, float current, float power, float kWh) {
    const int maxRetries = 3;
    int retryCount = 0;
    
    while (retryCount < maxRetries) {
        sendDataToDatabase(voltage, current, power, kWh);
        
        if (WiFi.status() == WL_CONNECTED) {
            break;
        }
        
        retryCount++;
        Serial.print("🔄 Reintento ");
        Serial.print(retryCount);
        Serial.println("/3 en 5 segundos...");
        delay(5000);
    }
    
    if (retryCount >= maxRetries) {
        Serial.println("❌ Fallo después de 3 intentos");
    }
}

// ====================================================================
// --- Setup y Loop Principal ---
// ====================================================================

void setup() {
    Serial.begin(115200);
    while (!Serial);
    
    Serial.println("\n🔌 Iniciando medidor de energía EcoVolt...");
    Serial.print("MAC Real del ESP32: "); Serial.println(getMacAddress());
    
    Wire.begin(21, 22);
    
    lcd.init();
    lcd.backlight();
    lcd.print("Iniciando EcoVolt");
    lcd.setCursor(0,1);
    lcd.print("Cargando...");
    delay(1000);

    loadConfig();
    
    if (!isConfigured) {
        Serial.println("No hay configuración guardada. Iniciando modo AP.");
        setupAPMode();
    } else {
        Serial.println("Configuración encontrada. Conectando a la red guardada.");
  WiFi.mode(WIFI_STA);
        WiFi.begin(stored_ssid.c_str(), stored_password.c_str());
  
  int attempts = 0;
        while (WiFi.status() != WL_CONNECTED && attempts < 40) {
    delay(500);
    Serial.print(".");
    attempts++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nConectado a WiFi");
            Serial.print("IP: "); Serial.println(WiFi.localIP());
            lcd.clear();
            lcd.print("Conectado!");
            lcd.setCursor(0,1);
            lcd.print(WiFi.localIP().toString());
            delay(2000);
            previousMillis = 0;
        } else {
            Serial.println("\nError al conectar a WiFi guardado");
            clearConfig();
            setupAPMode();
        }
    }
}

void loop() {
    if (!isConfigured) {
        dnsServer.processNextRequest();
        server.handleClient();
        delay(10); // Pequeña pausa para evitar sobrecarga
  } else {
        checkWiFiConnection();
        
        float Irms = read_current();
        float Vrms = read_voltage();
        float power = Vrms * Irms;

        unsigned long currentMillis = millis();
        if (currentMillis - previousMillis >= interval) {
            previousMillis = currentMillis;
            
            float hours = interval / 3600000.0;
            float kW = power / 1000.0;
            energyConsumed += kW * hours;
            energyConsumed = floor(energyConsumed * 10000) / 10000;
            
            displayLCD(Irms, Vrms);
            sendDataWithRetry(Vrms, Irms, power, energyConsumed);
        }

        Serial.print("Irms: ");
        Serial.print(Irms, 4);
        Serial.print(" A\tVrms: ");
        Serial.print(Vrms, 2);
        Serial.print(" V\tPower: ");
        Serial.print(power, 2);
        Serial.print(" W\tEnergy: ");
        Serial.print(energyConsumed, 4);
        Serial.println(" kWh");

        delay(100);
  }
} 