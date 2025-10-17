# 📋 Documentación: Sistema de Alertas Superpuestas

## 🎯 **¿Qué son las Alertas Superpuestas?**

Las alertas superpuestas son **notificaciones visuales** que aparecen automáticamente en la pantalla cuando se detecta un **corte de línea** por consumo excesivo. Estas alertas están diseñadas para ser **imposibles de ignorar** y proporcionan información crítica al usuario.

---

## ⚡ **¿Cuándo se Activan las Alertas?**

### **Condiciones de Activación:**
1. **Consumo excesivo detectado**: Cuando `kwh_acumulado > limite_consumo`
2. **Dispositivo activo**: El dispositivo debe estar enviando datos
3. **Usuario autenticado**: Solo se muestran a usuarios logueados
4. **Control de spam**: Máximo 1 alerta cada 3 minutos por dispositivo

### **Ejemplo de Activación:**
```
Límite configurado: 10.0 kWh
Consumo actual: 12.5 kWh
Resultado: ✅ ALERTA ACTIVADA
```

---

## 🕐 **Control de Tiempo (3 Minutos)**

### **¿Por qué 3 minutos?**
- **Evitar spam**: Previene que la alerta aparezca constantemente
- **Tiempo de reacción**: Da tiempo al usuario para tomar acción
- **Experiencia de usuario**: No interrumpe excesivamente el trabajo

### **¿Cómo funciona el control?**
```javascript
// Control de spam con sessionStorage
const ultimaAlerta = sessionStorage.getItem('ultimaAlertaCorte');
const ahora = Date.now();
const tiempoTranscurrido = ahora - (ultimaAlerta || 0);

if (tiempoTranscurrido > 180000) { // 3 minutos = 180,000 ms
    // Mostrar alerta
    mostrarModalCorteEsencial();
    sessionStorage.setItem('ultimaAlertaCorte', ahora);
}
```

---

## 🎨 **Diseño Visual de las Alertas**

### **Características del Modal:**
- **Fondo oscuro semi-transparente**: Bloquea la interacción con el resto de la página
- **Bordes dorados brillantes**: Color premium (#D4AF37) para destacar
- **Animación de entrada**: Efecto suave de aparición
- **Iconos grandes**: Fácil identificación visual
- **Texto claro y conciso**: Información esencial sin saturar

### **Colores y Significado:**
- 🔴 **Rojo**: Consumo actual (peligro)
- 🟢 **Verde**: Límite configurado (seguro)
- 🟡 **Amarillo**: Advertencia y atención
- ⚪ **Blanco**: Texto principal
- 🟫 **Dorado**: Elementos premium y bordes

---

## 📱 **Información Mostrada en la Alerta**

### **Datos Principales:**
1. **Dispositivo afectado**: Nombre del dispositivo
2. **Consumo actual**: kWh exactos consumidos
3. **Límite configurado**: kWh máximo permitido
4. **Fecha y hora**: Momento exacto del corte
5. **Porcentaje de exceso**: Cuánto se superó el límite

### **Ejemplo de Alerta:**
```
🚨 CORTE DE LÍNEA DETECTADO

Dispositivo: Cocina Principal
Consumo actual: 12.50 kWh
Límite configurado: 10.00 kWh
Exceso: 25.0% (2.50 kWh)

Fecha: 16/10/2024 21:30:15

[Entendido] [Ver Detalles]
```

---

## 🔧 **¿Cómo Cerrar la Alerta?**

### **Opciones Disponibles:**
1. **Botón "Entendido"**: Cierra la alerta y la marca como vista
2. **Botón "Ver Detalles"**: Abre la vista completa de cortes
3. **Clic fuera del modal**: Cierra la alerta (opcional)
4. **Tecla ESC**: Cierra la alerta (opcional)

### **¿Qué pasa al cerrar?**
- La alerta se marca como **"vista por usuario"** en la base de datos
- Se registra la **fecha y hora** de visualización
- Se actualiza el **estado** en el sistema de cortes
- **No se puede volver a mostrar** hasta que haya un nuevo corte

---

## 🎛️ **Configuración y Personalización**

### **Parámetros Ajustables:**
```javascript
// Tiempo de control de spam (milisegundos)
const TIEMPO_CONTROL_SPAM = 180000; // 3 minutos

// Duración de la animación (milisegundos)
const DURACION_ANIMACION = 500;

// Opacidad del fondo
const OPACIDAD_FONDO = 0.8;
```

### **Personalización Visual:**
- **Colores**: Modificar variables CSS en el tema
- **Tamaño**: Ajustar dimensiones del modal
- **Posición**: Centrado, esquina, etc.
- **Animaciones**: Efectos de entrada/salida

---

## 🔄 **Flujo Completo del Sistema**

### **1. Detección:**
```
ESP32 → Envía datos → Controlador → Verifica límite → ¿Superado? → SÍ
```

### **2. Registro:**
```
Sistema → Registra corte en BD → Envía notificaciones → Activa alerta visual
```

### **3. Visualización:**
```
Usuario → Ve alerta → Lee información → Toma acción → Cierra alerta
```

### **4. Seguimiento:**
```
Sistema → Marca como vista → Actualiza estadísticas → Prepara próxima alerta
```

---

## 🚨 **Estados de las Alertas**

### **Estados Posibles:**
1. **🟢 Activa**: Alerta mostrándose al usuario
2. **🟡 Vista**: Usuario vio la alerta pero no resolvió
3. **🔴 Resuelta**: Consumo volvió a niveles normales
4. **⚫ Archivada**: Alerta antigua, no relevante

### **Transiciones:**
```
Activa → Vista → Resuelta → Archivada
  ↓        ↓        ↓
Nueva   Nueva    Nueva
Alerta  Alerta   Alerta
```

---

## 🛠️ **Solución de Problemas**

### **❌ La alerta no aparece:**
1. **Verificar límites**: ¿Está configurado correctamente?
2. **Revisar datos**: ¿Llegan datos del ESP32?
3. **Control de spam**: ¿Pasaron 3 minutos desde la última?
4. **Sesión activa**: ¿El usuario está logueado?

### **❌ La alerta aparece demasiado:**
1. **Ajustar límite**: ¿Es muy bajo el límite configurado?
2. **Verificar datos**: ¿Hay lecturas erróneas?
3. **Revisar ESP32**: ¿Está enviando datos correctos?

### **❌ La alerta no se cierra:**
1. **JavaScript activo**: ¿Están habilitados los scripts?
2. **Bootstrap cargado**: ¿Está la librería disponible?
3. **Errores en consola**: ¿Hay errores JavaScript?

---

## 📊 **Estadísticas y Monitoreo**

### **Métricas Disponibles:**
- **Total de alertas**: Cuántas se han mostrado
- **Tiempo de respuesta**: Cuánto tarda el usuario en cerrar
- **Efectividad**: ¿Se resuelven los cortes después de la alerta?
- **Frecuencia**: ¿Con qué frecuencia aparecen?

### **Datos en Base de Datos:**
```sql
-- Tabla cortes_linea
SELECT 
    COUNT(*) as total_alertas,
    AVG(TIMESTAMPDIFF(MINUTE, fecha_corte, fecha_vista)) as tiempo_respuesta_promedio,
    SUM(CASE WHEN resuelto = 1 THEN 1 ELSE 0 END) as alertas_resueltas
FROM cortes_linea 
WHERE id_usuario = ?;
```

---

## 🎯 **Mejores Prácticas**

### **Para Usuarios:**
1. **Revisar alertas inmediatamente**: No ignorar las notificaciones
2. **Verificar dispositivos**: Desconectar equipos no necesarios
3. **Ajustar límites**: Configurar límites realistas
4. **Monitorear tendencias**: Revisar patrones de consumo

### **Para Administradores:**
1. **Configurar límites apropiados**: Basados en uso real
2. **Monitorear sistema**: Revisar logs y estadísticas
3. **Educar usuarios**: Explicar el funcionamiento del sistema
4. **Mantener actualizado**: Revisar y ajustar configuraciones

---

## 🔮 **Futuras Mejoras**

### **Funcionalidades Planificadas:**
- **Alertas por email**: Notificaciones adicionales
- **Alertas por SMS**: Para casos críticos
- **Alertas personalizables**: Diferentes tipos por dispositivo
- **Historial de alertas**: Vista completa de todas las notificaciones
- **Configuración avanzada**: Límites por horarios, días, etc.

### **Integraciones Posibles:**
- **Telegram Bot**: Notificaciones en tiempo real
- **WhatsApp API**: Alertas por mensaje
- **Sistemas de domótica**: Integración con otros dispositivos
- **APIs externas**: Servicios de notificación profesionales

---

## 📞 **Soporte y Contacto**

### **Si tienes problemas:**
1. **Revisar esta documentación**: Buscar la solución aquí
2. **Verificar logs**: Revisar archivos de log del sistema
3. **Contactar soporte**: Para problemas técnicos complejos
4. **Reportar bugs**: Ayudar a mejorar el sistema

### **Información Útil:**
- **Versión del sistema**: EcoVolt v2.0
- **Última actualización**: Octubre 2024
- **Compatibilidad**: Navegadores modernos (Chrome, Firefox, Safari, Edge)
- **Responsive**: Funciona en móviles y tablets

---

## ✅ **Resumen Ejecutivo**

El sistema de alertas superpuestas es una **herramienta crítica** para el monitoreo energético que:

- **Detecta automáticamente** consumos excesivos
- **Notifica inmediatamente** al usuario con información clara
- **Controla el spam** con un sistema de 3 minutos
- **Registra todo** para análisis y seguimiento
- **Se integra perfectamente** con el resto del sistema EcoVolt

**🎯 Objetivo principal**: Mantener al usuario **informado y en control** de su consumo energético, evitando cortes inesperados y optimizando el uso de la energía.

---

*Documentación generada automáticamente por EcoVolt - Sistema de Monitoreo Energético*
*Fecha: 16 de Octubre de 2024*
