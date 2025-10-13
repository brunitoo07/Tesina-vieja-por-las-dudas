# 📊 EcoVolt - Sistema de Monitoreo de Energía Eléctrica

## 🎯 Descripción del Proyecto

**EcoVolt** es un sistema completo de monitoreo de consumo de energía eléctrica desarrollado con **CodeIgniter 4** y dispositivos IoT (ESP32). El sistema permite a los usuarios monitorear en tiempo real el consumo eléctrico de sus hogares o negocios, configurar límites de consumo y recibir notificaciones cuando se superan estos límites.

## 🏗️ Arquitectura del Sistema

### Patrón MVC (Model-View-Controller)

El proyecto sigue el patrón MVC de CodeIgniter 4:

- **Modelos**: Manejan la lógica de datos y operaciones de base de datos
- **Vistas**: Contienen la interfaz de usuario (HTML, CSS, JavaScript)
- **Controladores**: Procesan las peticiones HTTP y coordinan entre modelos y vistas

## 📁 Estructura del Proyecto

```
app/
├── Controllers/          # Controladores de la aplicación
│   ├── Admin.php         # Gestión de administradores
│   ├── Login.php         # Autenticación de usuarios
│   ├── Energia.php       # Monitoreo de energía
│   ├── Registro.php      # Registro de usuarios
│   └── ...
├── Models/               # Modelos de datos
│   ├── UsuarioModel.php  # Gestión de usuarios
│   ├── DispositivoModel.php # Gestión de dispositivos IoT
│   ├── EnergiaModel.php  # Lecturas de energía
│   └── ...
├── Views/                # Vistas de la aplicación
│   ├── admin/           # Vistas de administración
│   ├── energia/         # Vistas de monitoreo
│   ├── autenticacion/   # Vistas de login/registro
│   └── ...
└── Config/              # Configuraciones
```

## 🔐 Sistema de Roles

El sistema implementa un sistema de roles jerárquico:

### 1. **Administrador (Rol 1)**
- **Permisos**: Acceso completo al sistema
- **Funciones**:
  - Gestionar todos los usuarios
  - Ver todos los dispositivos
  - Configurar límites de consumo
  - Enviar invitaciones
  - Acceso al panel de administración

### 2. **Usuario Normal (Rol 2)**
- **Permisos**: Acceso limitado
- **Funciones**:
  - Ver sus propios dispositivos
  - Ver dispositivos del admin que lo invitó
  - Monitorear consumo de energía
  - Recibir notificaciones

### 3. **Supervisor (Rol 3)**
- **Permisos**: Acceso intermedio
- **Funciones**:
  - Ver dispositivos de múltiples usuarios
  - Configurar límites
  - Monitoreo avanzado

## 🔌 Dispositivos IoT (ESP32)

### Tipos de MAC Address
- **mac_address**: MAC simulada para identificación en el sistema
- **mac_real_esp32**: MAC física real del dispositivo ESP32

### Estados de Dispositivos
- **activo**: Dispositivo funcionando y enviando datos
- **inactivo**: Dispositivo deshabilitado o sin conexión

### Datos que Envían los Dispositivos
```json
{
  "voltaje": 220.5,      // Voltaje en voltios
  "corriente": 2.3,      // Corriente en amperios
  "potencia": 507.15,    // Potencia en watts
  "kwh": 0.507,          // Consumo en kWh
  "mac_address": "AA:BB:CC:DD:EE:FF"
}
```

## 📊 Funcionalidades Principales

### 1. **Monitoreo en Tiempo Real**
- Dashboard con gráficos de consumo
- Actualización automática de datos
- Alertas visuales cuando se superan límites

### 2. **Gestión de Límites**
- Configuración de límites de consumo por dispositivo
- Notificaciones automáticas por email y Telegram
- Control de frecuencia para evitar spam

### 3. **Reportes y Análisis**
- Generación de reportes PDF
- Análisis de consumo por períodos
- Cálculo de costos basado en tarifas

### 4. **Notificaciones**
- **Email**: Alertas cuando se supera el límite
- **Telegram**: Notificaciones en tiempo real
- **Control de frecuencia**: Máximo 1 notificación por hora

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 8.x** con **CodeIgniter 4**
- **MySQL** para base de datos
- **Dompdf** para generación de PDFs

### Frontend
- **HTML5/CSS3** con **Bootstrap**
- **JavaScript** para interactividad
- **Chart.js** para gráficos

### IoT
- **ESP32** microcontroladores
- **Sensores de corriente** para medición
- **WiFi** para comunicación

## 📋 Modelos Principales

### UsuarioModel
```php
// Funciones principales:
- insertarUsuario($array)     // Crear nuevo usuario
- verificarCredenciales()     // Autenticación
- actualizarRol()            // Cambiar rol de usuario
- eliminarUsuario()          // Eliminar usuario
- validarRol()               // Validar rol existente
```

### DispositivoModel
```php
// Funciones principales:
- obtenerDispositivosUsuario() // Obtener dispositivos por usuario
- obtenerPorMac()             // Buscar por MAC address
- actualizarEstado()          // Cambiar estado del dispositivo
- dispositivoExiste()         // Verificar si existe
```

### EnergiaModel
```php
// Funciones principales:
- insert()                    // Insertar nueva lectura
- obtenerLecturasPorRango()   // Lecturas por período
- calcularConsumoTotal()      // Total de consumo
```

## 🎮 Controladores Principales

### Login Controller
- **autenticar()**: Procesa login de usuarios
- Validación de credenciales
- Creación de sesión
- Redirección según rol

### Energia Controller
- **index()**: Dashboard principal
- **recibirNuevosDatos()**: API para ESP32
- **getLatestData()**: Datos en tiempo real
- **actualizarLimite()**: Configurar límites
- **generarPDF()**: Reportes PDF

### Admin Controller
- **gestionarUsuarios()**: Gestión de usuarios
- **enviarInvitacion()**: Invitar usuarios
- **cambiarRol()**: Cambiar roles
- **eliminarUsuario()**: Eliminar usuarios

## 🔧 Configuración y Instalación

### Requisitos del Sistema
- PHP 8.0 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Composer para dependencias

### Instalación
1. Clonar el repositorio
2. Instalar dependencias: `composer install`
3. Configurar base de datos en `app/Config/Database.php`
4. Ejecutar migraciones
5. Configurar servidor web

### Variables de Entorno
```php
// app/Config/Database.php
public $default = [
    'hostname' => 'localhost',
    'username' => 'tu_usuario',
    'password' => 'tu_password',
    'database' => 'ecovolt_db',
    // ...
];
```

## 📱 APIs y Endpoints

### API para Dispositivos ESP32
```
POST /energia/recibirNuevosDatos
- Recibe datos de consumo de dispositivos
- Valida MAC address
- Verifica límites
- Envía notificaciones

GET /energia/getlimite?mac=AA:BB:CC:DD:EE:FF
- Obtiene límite de consumo para dispositivo
- Endpoint público (sin autenticación)
```

### APIs para Frontend
```
GET /energia/getLatestData
- Última lectura en tiempo real
- Requiere autenticación

POST /energia/actualizarLimite
- Configurar límite de consumo
- Solo admin/supervisor

GET /energia/generarPDF/{id_dispositivo}
- Generar reporte PDF
- Requiere autenticación
```

## 🔒 Seguridad

### Autenticación
- Hash de contraseñas con `password_hash()`
- Verificación con `password_verify()`
- Sesiones seguras

### Validación
- Validación de entrada en modelos
- Sanitización de datos
- Protección contra SQL injection

### Autorización
- Verificación de roles en controladores
- Filtros de acceso
- Protección de rutas sensibles

## 📈 Monitoreo y Logs

### Sistema de Logging
```php
// Ejemplos de logging:
log_message('debug', 'Usuario encontrado: ' . $email);
log_message('error', 'Error al insertar usuario: ' . $error);
log_message('info', 'Dispositivo ID: ' . $id);
```

### Niveles de Log
- **debug**: Información detallada para desarrollo
- **info**: Información general del sistema
- **error**: Errores que requieren atención
- **critical**: Errores críticos del sistema

## 🚀 Características Avanzadas

### 1. **Notificaciones Inteligentes**
- Control de frecuencia (máximo 1 por hora)
- Múltiples canales (email + Telegram)
- Personalización de mensajes

### 2. **Reportes Avanzados**
- Análisis de tendencias
- Comparativas mensuales
- Recomendaciones de ahorro

### 3. **Escalabilidad**
- Arquitectura modular
- Separación de responsabilidades
- Fácil mantenimiento

## 🔄 Flujo de Datos

1. **ESP32** mide consumo eléctrico
2. **Envía datos** vía WiFi al servidor
3. **API recibe** y valida datos
4. **Verifica límites** de consumo
5. **Envía notificaciones** si es necesario
6. **Almacena** en base de datos
7. **Frontend** muestra datos en tiempo real

## 📝 Próximas Mejoras

- [ ] Dashboard móvil responsive
- [ ] Integración con más sensores
- [ ] Análisis predictivo con IA
- [ ] Integración con sistemas de facturación
- [ ] API REST completa
- [ ] Aplicación móvil nativa

## 🤝 Contribución

Para contribuir al proyecto:
1. Fork del repositorio
2. Crear rama de feature
3. Realizar cambios
4. Crear Pull Request

## 📞 Soporte

Para soporte técnico o consultas:
- Email: soporte@ecovolt.com
- Documentación: [Wiki del proyecto]
- Issues: [GitHub Issues]

---

**EcoVolt** - Monitoreo inteligente de energía eléctrica ⚡
