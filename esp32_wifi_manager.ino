#include <WiFi.h>
#include <WebServer.h>
#include <DNSServer.h>
#include <EEPROM.h>
#include <HTTPClient.h>

// Configuración de la red AP
const char* AP_SSID = "EcoVolt_Setup";
const char* AP_PASSWORD = "12345678"; // Contraseña para la red de configuración

// Puerto para el servidor web
const int WEB_PORT = 80;
const int DNS_PORT = 53;

// Variables para almacenar la configuración
String ssid = "";
String password = "";
bool isConfigured = false;

// Crear instancias del servidor web y DNS
WebServer server(WEB_PORT);
DNSServer dnsServer;

// Dirección IP del AP
IPAddress apIP(192, 168, 4, 1);
IPAddress gateway(192, 168, 4, 1);
IPAddress subnet(255, 255, 255, 0);

void setup() {
  Serial.begin(115200);
  
  // Inicializar EEPROM
  EEPROM.begin(512);
  
  // Intentar cargar configuración guardada
  loadConfig();
  
  if (!isConfigured) {
    setupAP();
  } else {
    connectToWiFi();
  }
}

void loop() {
  if (!isConfigured) {
    dnsServer.processNextRequest();
    server.handleClient();
  }
  
  // Tu código principal aquí
  // ...
}

void setupAP() {
  String macAddress = getMacAddress();
  String apName = "EcoVolt-" + macAddress.substring(0, 5);
  
  // Configurar el modo AP
  WiFi.mode(WIFI_AP);
  WiFi.softAPConfig(apIP, gateway, subnet);
  WiFi.softAP(apName.c_str(), AP_PASSWORD);
  
  // Iniciar servidor DNS
  dnsServer.start(DNS_PORT, "*", apIP);
  
  // Configurar rutas del servidor web
  server.on("/", HTTP_GET, handleRoot);
  server.on("/scan", HTTP_GET, handleScan);
  server.on("/connect", HTTP_POST, handleConnect);
  server.on("/style.css", HTTP_GET, handleStyle);
  
  server.begin();
}

void handleRoot() {
  String macAddress = getMacAddress(); // Obtener la MAC
  String html = R"(
    <!DOCTYPE html>
    <html>
    <head>
      <title>EcoVolt WiFi Setup</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <style>
        * {
          box-sizing: border-box;
          margin: 0;
          padding: 0;
        }
        body {
          font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
          line-height: 1.6;
          padding: 20px;
          background-color: #f5f5f5;
          color: #333;
        }
        .container {
          max-width: 600px;
          margin: 0 auto;
          background: white;
          padding: 20px;
          border-radius: 12px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
          color: #2c3e50;
          text-align: center;
          margin-bottom: 20px;
          font-size: 24px;
        }
        .logo {
          text-align: center;
          margin-bottom: 20px;
        }
        .logo img {
          width: 80px;
          height: 80px;
        }
        .network {
          background: #f8f9fa;
          padding: 15px;
          margin: 10px 0;
          border-radius: 8px;
          display: flex;
          justify-content: space-between;
          align-items: center;
          cursor: pointer;
          transition: all 0.3s ease;
        }
        .network:hover {
          background: #e9ecef;
          transform: translateY(-2px);
        }
        .network .ssid {
          font-weight: 500;
        }
        .network .rssi {
          color: #6c757d;
          font-size: 0.9em;
        }
        .form-group {
          margin-bottom: 20px;
        }
        label {
          display: block;
          margin-bottom: 8px;
          font-weight: 500;
        }
        input[type="password"] {
          width: 100%;
          padding: 12px;
          border: 2px solid #ddd;
          border-radius: 8px;
          font-size: 16px;
          transition: border-color 0.3s ease;
        }
        input[type="password"]:focus {
          border-color: #007bff;
          outline: none;
        }
        button {
          width: 100%;
          padding: 12px;
          background: #007bff;
          color: white;
          border: none;
          border-radius: 8px;
          font-size: 16px;
          font-weight: 500;
          cursor: pointer;
          transition: background 0.3s ease;
        }
        button:hover {
          background: #0056b3;
        }
        .status {
          text-align: center;
          margin-top: 20px;
          padding: 10px;
          border-radius: 8px;
          display: none;
        }
        .status.success {
          background: #d4edda;
          color: #155724;
          display: block;
        }
        .status.error {
          background: #f8d7da;
          color: #721c24;
          display: block;
        }
        .loading {
          text-align: center;
          margin: 20px 0;
          display: none;
        }
        .loading::after {
          content: '';
          display: inline-block;
          width: 20px;
          height: 20px;
          border: 2px solid #f3f3f3;
          border-top: 2px solid #007bff;
          border-radius: 50%;
          animation: spin 1s linear infinite;
        }
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        .mac-address {
            background: #e3f2fd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            text-align: center;
            font-family: monospace;
            color: #1565C0;
        }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="logo">
          <h1>EcoVolt</h1>
        </div>
        <div class="mac-address">
          <strong>Dirección MAC:</strong><br>
          )" + macAddress + R"(
        </div>
        <div id="networks"></div>
        <div id="connect-form" style="display: none;">
          <h2>Conectar a Red WiFi</h2>
          <form action="/connect" method="post">
            <input type="hidden" id="ssid" name="ssid">
            <div class="form-group">
              <label for="password">Contraseña:</label>
              <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Conectar</button>
          </form>
        </div>
        <div id="loading" class="loading"></div>
        <div id="status" class="status"></div>
      </div>
      <script>
        function showConnectForm(ssid) {
          document.getElementById('ssid').value = ssid;
          document.getElementById('connect-form').style.display = 'block';
          document.getElementById('networks').style.display = 'none';
        }
        
        function showLoading() {
          document.getElementById('loading').style.display = 'block';
          document.getElementById('status').style.display = 'none';
        }
        
        function showStatus(message, isError = false) {
          const status = document.getElementById('status');
          status.textContent = message;
          status.className = 'status ' + (isError ? 'error' : 'success');
          document.getElementById('loading').style.display = 'none';
        }
        
        function scanNetworks() {
          showLoading();
          fetch('/scan')
            .then(response => response.json())
            .then(networks => {
              const networksDiv = document.getElementById('networks');
              networksDiv.innerHTML = '<h2>Redes Disponibles</h2>';
              networks.forEach(network => {
                networksDiv.innerHTML += `
                  <div class="network" onclick="showConnectForm('${network.ssid}')">
                    <span class="ssid">${network.ssid}</span>
                    <span class="rssi">${network.rssi} dBm</span>
                  </div>
                `;
              });
              document.getElementById('loading').style.display = 'none';
            })
            .catch(error => {
              showStatus('Error al escanear redes WiFi', true);
            });
        }
        
        // Escanear redes al cargar la página
        scanNetworks();
        
        // Manejar el envío del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
          e.preventDefault();
          showLoading();
          
          const formData = new FormData(this);
          fetch('/connect', {
            method: 'POST',
            body: formData
          })
          .then(response => response.text())
          .then(html => {
            showStatus('Conectando... El dispositivo se reiniciará en unos segundos.');
          })
          .catch(error => {
            showStatus('Error al conectar', true);
          });
        });
      </script>
    </body>
    </html>
  )";
  
  server.send(200, "text/html", html);
}

void handleStyle() {
  String css = R"(
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f0f0f0;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    h1 {
      color: #333;
      text-align: center;
    }
    .network {
      padding: 10px;
      margin: 5px 0;
      background-color: #f8f9fa;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
    }
    .network:hover {
      background-color: #e9ecef;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    input[type="password"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    button {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
  )";
  
  server.send(200, "text/css", css);
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
    ssid = server.arg("ssid");
    password = server.arg("password");
    String macAddress = getMacAddress();
    
    // Guardar configuración en EEPROM
    saveConfig();
    
    // Enviar la MAC al servidor para registro
    HTTPClient http;
    String serverURL = "http://192.168.2.176/Tesina/public/registrar_dispositivo";
    
    http.begin(serverURL);
    http.addHeader("Content-Type", "application/json");
    
    String postData = "{\"mac_address\":\"" + macAddress + "\",\"estado\":\"activo\"}";
    
    int httpResponseCode = http.POST(postData);
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Dispositivo registrado: " + response);
    } else {
      Serial.println("Error al registrar dispositivo");
    }
    
    http.end();
    
    // Enviar respuesta al cliente
    server.send(200, "text/html", "<h1>Conectando...</h1><p>El dispositivo se reiniciará para conectarse a la red seleccionada.</p>");
    
    // Reiniciar el ESP32
    delay(2000);
    ESP.restart();
  } else {
    server.send(400, "text/plain", "Faltan parámetros");
  }
}

void loadConfig() {
  // Leer configuración de EEPROM
  ssid = EEPROM.readString(0);
  password = EEPROM.readString(100);
  
  isConfigured = (ssid.length() > 0 && password.length() > 0);
}

void saveConfig() {
  // Guardar configuración en EEPROM
  EEPROM.writeString(0, ssid);
  EEPROM.writeString(100, password);
  EEPROM.commit();
}

void connectToWiFi() {
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid.c_str(), password.c_str());
  
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nConectado a WiFi");
    Serial.print("Dirección IP: ");
    Serial.println(WiFi.localIP());
    
    // Comenzar a enviar datos inmediatamente
    previousMillis = 0; // Reiniciar el contador para enviar datos inmediatamente
  } else {
    Serial.println("\nError al conectar a WiFi");
    isConfigured = false;
    setupAP();
  }
}

String getMacAddress() {
    uint8_t mac[6];
    WiFi.macAddress(mac);
    char macStr[18];
    snprintf(macStr, sizeof(macStr), "%02X:%02X:%02X:%02X:%02X:%02X",
             mac[0], mac[1], mac[2], mac[3], mac[4], mac[5]);
    return String(macStr);
}

void sendDataToDatabase(float voltage, float current, float power, float kWh) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("⚠️ Reconectando a WiFi...");
        WiFi.reconnect();
        delay(1000);
        return;
    }

    HTTPClient http;
    http.begin(serverURL);
    http.addHeader("Content-Type", "application/json");
    http.setTimeout(5000);

    String macAddress = getMacAddress();
    String postData = "{\"voltaje\":" + String(voltage, 2) +
                     ",\"corriente\":" + String(current, 4) +
                     ",\"potencia\":" + String(power, 2) +
                     ",\"kwh\":" + String(kWh, 4) +
                     ",\"mac_address\":\"" + macAddress + "\"" +
                     ",\"estado\":\"activo\"" +
                     ",\"ultima_actualizacion\":\"" + String(millis()) + "\"}";

    Serial.println("\n📤 Enviando datos al servidor...");
    Serial.println("📝 Datos: " + postData);

    int httpResponseCode = http.POST(postData);
    
    if (httpResponseCode > 0) {
        Serial.print("✅ HTTP Response code: ");
        Serial.println(httpResponseCode);
        String response = http.getString();
        Serial.print(" Respuesta del servidor: ");
        Serial.println(response);
    } else {
        Serial.print("❌ Error en HTTP POST: ");
        Serial.println(http.errorToString(httpResponseCode).c_str());
    }
    
    http.end();
} 