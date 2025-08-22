#include <Arduino.h>
#include <math.h>
#include <WiFi.h>
#include <WebServer.h>
#include <DNSServer.h>
#include <EEPROM.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// ------------------- CONFIGURACIÓN SENSORES -------------------
#define VREF 3.3
#define ADC_RESOLUTION 4095.0

// ACS712 (Corriente)
#define ACS712_PIN 34
#define SENSIBILIDAD_ACS712 0.1   // 100mV/A para ACS712-20A
#define OFFSET_ACS712 (VREF / 2)
#define SAMPLES_ACS712 500

// ZMPT101B (Voltaje)
#define ZMPT101B_PIN 35
#define CALIBRACION_ZMPT 345.0   // Ajustar con multímetro
#define SAMPLES_ZMPT 1000

// Relés (25 y 26)
#define RELE_NO_ESENCIAL 25   // Línea no esencial (luces)
#define RELE_ESENCIAL    26   // Línea esencial (electrodomésticos)

// Umbrales
#define UMBRAL_CONSUMO_KWH 0.003
#define INTERVALO_SEGUNDOS 1
#define UMBRAL_VOLT_CRITICO 135
#define UMBRAL_VOLT_MINIMO 200

// Variables energía
float energia_wh_acumulada = 0.0;

// ------------------- CONFIGURACIÓN WIFI -------------------
#define EEPROM_SIZE 512
#define EEPROM_SSID_ADDR 0
#define EEPROM_PASS_ADDR 100
const char* AP_PASSWORD = "12345678";
const char* AP_SSID_PREFIX = "EcoVolt-";

String macAddress;   // <- guardamos la MAC

String stored_ssid = "";
String stored_password = "";
bool isConfigured = false;

WebServer server(80);
DNSServer dnsServer;

IPAddress apIP(192, 168, 4, 1);
IPAddress gateway(192, 168, 4, 1);
IPAddress subnet(255, 255, 255, 0);

// Estados de relés para API
bool estado_focos[3] = {false, false, false};

// URL servidor datos
const String DATA_SERVER_URL = "http://192.168.2.178/Tesina/public/nuevos_datos";

// ------------------- FUNCIONES MEDICIÓN -------------------
float medir_corriente_rms(int samples = SAMPLES_ACS712) {
  double suma = 0;
  for (int i = 0; i < samples; i++) {
    int valor_adc = analogRead(ACS712_PIN);
    float voltaje = (valor_adc / ADC_RESOLUTION) * VREF;
    float diferencia = voltaje - OFFSET_ACS712;
    suma += pow(diferencia, 2);
    delayMicroseconds(100);
  }
  float rms = sqrt(suma / samples);
  return rms / SENSIBILIDAD_ACS712;
}

float medir_voltaje_rms(int samples = SAMPLES_ZMPT) {
  double suma_cuadrados = 0;
  for (int i = 0; i < samples; i++) {
    int raw = analogRead(ZMPT101B_PIN);
    float voltaje_sensor = (raw / ADC_RESOLUTION) * VREF;
    float voltaje_centrado = voltaje_sensor - (VREF / 2);
    suma_cuadrados += pow(voltaje_centrado, 2);
    delayMicroseconds(100);
  }
  float voltaje_rms_sensor = sqrt(suma_cuadrados / samples);
  return voltaje_rms_sensor * CALIBRACION_ZMPT;
}

// ------------------- FUNCIONES EEPROM -------------------
String readStringFromEEPROM(int addr) {
  char data[100];
  int len = 0;
  for (int i = 0; i < 100; i++) {
    data[i] = EEPROM.read(addr + i);
    if(data[i] == 0) break;
    len++;
  }
  return String(data).substring(0, len);
}

void writeStringToEEPROM(int addr, const String &strToWrite) {
  int len = strToWrite.length();
  for (int i = 0; i < 100; i++) {
    if (i < len) EEPROM.write(addr + i, strToWrite[i]);
    else EEPROM.write(addr + i, 0);
  }
  EEPROM.commit();
}

void loadConfig() {
  EEPROM.begin(EEPROM_SIZE);
  stored_ssid = readStringFromEEPROM(EEPROM_SSID_ADDR);
  stored_password = readStringFromEEPROM(EEPROM_PASS_ADDR);
  isConfigured = (stored_ssid.length() > 0 && stored_password.length() > 0);
  Serial.print("SSID cargado: "); Serial.println(stored_ssid);
  Serial.print("Password cargada: "); Serial.println(stored_password);
  Serial.print("Configurado: "); Serial.println(isConfigured ? "SI" : "NO");
}

void saveConfig() {
  writeStringToEEPROM(EEPROM_SSID_ADDR, stored_ssid);
  writeStringToEEPROM(EEPROM_PASS_ADDR, stored_password);
  Serial.println("Configuración guardada en EEPROM");
}

void clearConfig() {
  for (int i = 0; i < EEPROM_SIZE; i++) EEPROM.write(i, 0);
  EEPROM.commit();
  stored_ssid = "";
  stored_password = "";
  isConfigured = false;
  Serial.println("Configuración borrada.");
}

// ------------------- FUNCIONES ENVÍO DE DATOS -------------------
void sendDataToDatabase(float voltage, float current, float power, float kWh) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("⚠️ WiFi no conectado, intentando reconectar...");
    WiFi.reconnect();
    delay(1000);
    return;
  }

  HTTPClient http;
  http.begin(DATA_SERVER_URL);
  http.addHeader("Content-Type", "application/json");

  // Usar la MAC address global que se inicializa en setup()
  String postData = "{\"voltaje\":" + String(voltage, 2) +
                    ",\"corriente\":" + String(current, 4) +
                    ",\"potencia\":" + String(power, 2) +
                    ",\"kwh\":" + String(kWh, 4) +
                    ",\"mac_address\":\"" + macAddress + "\"}";

  Serial.println("📤 Enviando datos al servidor...");
  Serial.println("📋 JSON: " + postData);

  int httpResponseCode = http.POST(postData);

  if (httpResponseCode > 0) {
    Serial.printf("✅ HTTP Response code: %d\n", httpResponseCode);
    String response = http.getString();
    Serial.println("📩 Respuesta del servidor: " + response);
    
    // Verificar si la respuesta es exitosa
    if (httpResponseCode == 200) {
      Serial.println("🎉 Datos enviados correctamente!");
    } else {
      Serial.printf("⚠️ Respuesta inesperada: %d\n", httpResponseCode);
    }
  } else {
    Serial.printf("❌ Error en HTTP POST: %s\n", http.errorToString(httpResponseCode).c_str());
  }

  http.end();
}

void sendDataWithRetry(float voltage, float current, float power, float kWh) {
  const int maxRetries = 3;
  int retryCount = 0;

  while (retryCount < maxRetries) {
    sendDataToDatabase(voltage, current, power, kWh);
    
    // Si la respuesta fue exitosa, salir del bucle
    if (WiFi.status() == WL_CONNECTED) {
      break;
    }

    retryCount++;
    if (retryCount < maxRetries) {
      Serial.printf("🔄 Reintento %d/%d en 5 segundos...\n", retryCount, maxRetries);
      delay(5000);
    }
  }

  if (retryCount >= maxRetries) {
    Serial.println("❌ Fallo después de 3 intentos de envío");
  }
}

// ------------------- WIFI + SERVIDOR -------------------
String getMacAddress() {
  // Inicializar WiFi en modo STA para obtener la MAC real
  WiFi.mode(WIFI_STA);
  WiFi.begin(); // Iniciar WiFi sin credenciales para obtener MAC
  
  uint8_t mac[6]; 
  WiFi.macAddress(mac);
  char macStr[18];
  snprintf(macStr, sizeof(macStr), "%02X%02X%02X%02X%02X%02X",
           mac[0], mac[1], mac[2], mac[3], mac[4], mac[5]);
  
  // Apagar WiFi después de obtener la MAC
  WiFi.disconnect();
  WiFi.mode(WIFI_OFF);
  
  return String(macStr);
}

void setupAPMode() {
  String apName = String(AP_SSID_PREFIX) + macAddress.substring(8);
  WiFi.mode(WIFI_AP);
  WiFi.softAPConfig(apIP, gateway, subnet);
  WiFi.softAP(apName.c_str(), AP_PASSWORD);
  dnsServer.start(53, "*", apIP);

  // --- IMPRIMIR EN SERIAL ---
  Serial.println("=== Modo AP activo ===");   
  Serial.print("📱 MAC mostrada en la página web AP: ");
  Serial.println(macAddress);

  server.on("/", HTTP_GET, []() {
    String html = "<h3>MAC de la ESP32: " + macAddress + "</h3>"; // usa la global
    html += "<form method='POST' action='/connect'>";
    html += "SSID:<br><select name='ssid'>";
    int n = WiFi.scanNetworks();
    for (int i = 0; i < n; ++i) {
      html += "<option value='" + WiFi.SSID(i) + "'>" + WiFi.SSID(i) + "</option>";
    }
    html += "</select><br>Password:<br><input type='password' name='password'><br>";
    html += "<button>Conectar</button></form>";
    server.send(200, "text/html", html);
  }); 

  server.on("/connect", HTTP_POST, []() {
    if (server.hasArg("ssid") && server.hasArg("password")) {
      stored_ssid = server.arg("ssid");
      stored_password = server.arg("password");
      
      Serial.println("Configuración recibida:");
      Serial.print("  SSID: ");
      Serial.println(stored_ssid);
      Serial.print("  MAC: ");
      Serial.println(macAddress);
      
      WiFi.mode(WIFI_STA);
      WiFi.begin(stored_ssid.c_str(), stored_password.c_str());
      int attempts = 0;
      while (WiFi.status() != WL_CONNECTED && attempts < 20) {
        delay(500); attempts++;
      }
      if (WiFi.status() == WL_CONNECTED) {
        isConfigured = true; saveConfig(); ESP.restart();
      } else {
        server.send(200, "text/html", "❌ Error al conectar.");
      }
    }
  });

  server.on("/rele", HTTP_GET, []() {
    if (server.hasArg("foco") && server.hasArg("estado")) {
      int foco = server.arg("foco").toInt();
      bool encender = (server.arg("estado") == "on");
      if (foco == 1) digitalWrite(RELE_NO_ESENCIAL, encender ? HIGH : LOW);
      if (foco == 2) digitalWrite(RELE_ESENCIAL, encender ? HIGH : LOW);
      estado_focos[foco] = encender;
      server.send(200, "text/plain", "OK");
    }
  });

  server.on("/estado", HTTP_GET, []() {
    String json = "{\"relay1\":" + String(estado_focos[1] ? "true" : "false") +
                  ",\"relay2\":" + String(estado_focos[2] ? "true" : "false") + "}";
    server.send(200, "application/json", json);
  });

  server.begin();
}

void checkWiFiConnection() {
  static unsigned long lastCheck = 0;
  if (millis() - lastCheck > 30000) {
    lastCheck = millis();
    if (WiFi.status() != WL_CONNECTED) {
      Serial.println("🔌 WiFi desconectado, reiniciando...");
      clearConfig(); 
      ESP.restart();
    }
  }
}

void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("🚀 Iniciando ESP32 EcoVolt...");
  
  // ⚠️ IMPORTANTE: Obtener la MAC address ANTES de cualquier configuración WiFi
  macAddress = getMacAddress();
  Serial.print("📱 MAC Address: ");
  Serial.println(macAddress);

  pinMode(RELE_NO_ESENCIAL, OUTPUT);
  pinMode(RELE_ESENCIAL, OUTPUT);
  digitalWrite(RELE_NO_ESENCIAL, HIGH); // Inicialmente apagado
  digitalWrite(RELE_ESENCIAL, HIGH);    // Inicialmente apagado
  
  loadConfig();

  if (!isConfigured) {
    Serial.println("⚙️ Configuración no encontrada, iniciando modo AP...");
    setupAPMode();
  } else {
    Serial.println("🔗 Conectando a WiFi guardado...");
    WiFi.mode(WIFI_STA);
    WiFi.begin(stored_ssid.c_str(), stored_password.c_str());
    int t = 0;
    while (WiFi.status() != WL_CONNECTED && t < 30) { 
      delay(500); 
      t++; 
      Serial.print(".");
    }
    Serial.println();
    
    if (WiFi.status() == WL_CONNECTED) {
      Serial.println("✅ Conectado a WiFi: " + stored_ssid);
      Serial.print("🌐 IP: ");
      Serial.println(WiFi.localIP());
    } else {
      Serial.println("❌ Fallo al conectar, iniciando modo AP...");
      setupAPMode();
    }
  }
}

// ------------------- LOOP -------------------
void loop() {
  if (!isConfigured) { 
    server.handleClient(); 
    dnsServer.processNextRequest(); 
    return; 
  }

  checkWiFiConnection();

  // Medición de sensores
  float voltaje = medir_voltaje_rms();
  float corriente = medir_corriente_rms();
  float potencia = voltaje * corriente;
  energia_wh_acumulada += potencia * (INTERVALO_SEGUNDOS / 3600.0);
  float kwh_acumulado = energia_wh_acumulada / 1000.0;

  // Mostrar datos en Serial
  Serial.printf("📊 V: %.2fV | I: %.4fA | P: %.2fW | kWh: %.6f\n",
                voltaje, corriente, potencia, kwh_acumulado);

  // --- Envía datos al servidor usando la función que funcionaba ---
  sendDataWithRetry(voltaje, corriente, potencia, kwh_acumulado);

  // --- Control relés por voltaje ---
  if (voltaje < UMBRAL_VOLT_MINIMO) {
    digitalWrite(RELE_NO_ESENCIAL, LOW);
    Serial.println("🔌 Voltaje bajo, apagando línea no esencial");
  } else {
    digitalWrite(RELE_NO_ESENCIAL, HIGH);
  }

  if (voltaje < UMBRAL_VOLT_CRITICO) {
    digitalWrite(RELE_ESENCIAL, LOW);
    Serial.println("🚨 Voltaje crítico, apagando línea esencial");
  } else {
    digitalWrite(RELE_ESENCIAL, HIGH);
  }

  delay(INTERVALO_SEGUNDOS * 1000);
}
