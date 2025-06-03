#include <WiFi.h>
#include <WebServer.h>
#include <DNSServer.h>
#include <EEPROM.h>

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
  // Configurar el modo AP
  WiFi.mode(WIFI_AP);
  WiFi.softAPConfig(apIP, gateway, subnet);
  WiFi.softAP(AP_SSID, AP_PASSWORD);
  
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
  String html = R"(
    <!DOCTYPE html>
    <html>
    <head>
      <title>EcoVolt WiFi Setup</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="/style.css">
    </head>
    <body>
      <div class="container">
        <h1>Configuración de EcoVolt</h1>
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
      </div>
      <script>
        function showConnectForm(ssid) {
          document.getElementById('ssid').value = ssid;
          document.getElementById('connect-form').style.display = 'block';
        }
        
        function scanNetworks() {
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
            });
        }
        
        // Escanear redes al cargar la página
        scanNetworks();
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
    
    // Guardar configuración en EEPROM
    saveConfig();
    
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
  } else {
    Serial.println("\nError al conectar a WiFi");
    // Si falla la conexión, volver al modo AP
    isConfigured = false;
    setupAP();
  }
} 