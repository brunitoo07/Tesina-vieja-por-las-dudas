# 🔧 Configuración de PayPal Sandbox

## 📋 **Pasos para Configurar PayPal Sandbox**

### **1. Crear Cuenta de Desarrollador**
1. Ve a [https://developer.paypal.com/](https://developer.paypal.com/)
2. Inicia sesión con tu cuenta de PayPal (o créala si no tienes)
3. Ve a "My Apps & Credentials"

### **2. Crear una Nueva App**
1. Haz clic en "Create App"
2. Nombre: `EcoVolt Test`
3. Merchant: Selecciona tu cuenta de prueba
4. Features: Selecciona "Accept payments"
5. Haz clic en "Create App"

### **3. Obtener las Credenciales**
Después de crear la app, verás:
- **Client ID** (público)
- **Client Secret** (privado)

### **4. Actualizar el Código**
Reemplaza en `app/Views/compra/index.php` línea 11:

```javascript
// REEMPLAZA ESTA LÍNEA:
<script src="https://www.paypal.com/sdk/js?client-id=AVc8Jj68sTx6Jv9nb46eoXNfoSgFcAr6C0ZQuogzyFuQ7dDwBPPSnqET1LM3vr1yi0c9tHp4mVuPxZlB&currency=ARS&intent=capture"></script>

// CON TU CLIENT ID DE SANDBOX:
<script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID_AQUI&currency=ARS&intent=capture"></script>
```

### **5. Cuentas de Prueba**
PayPal Sandbox te dará cuentas de prueba:
- **Buyer Account** (comprador): Para probar compras
- **Seller Account** (vendedor): Para recibir pagos

### **6. Probar el Flujo**
1. Usa las credenciales de Sandbox
2. En el checkout de PayPal, usa las credenciales del Buyer Account
3. Verifica que el pago se procese correctamente

## 🎯 **Credenciales de Ejemplo (NO USAR EN PRODUCCIÓN)**

```
Client ID: sb-1234567890abcdef...
Client Secret: sb-abcdef1234567890...
```

## ⚠️ **Importante**
- **NUNCA** uses credenciales de Sandbox en producción
- **SIEMPRE** usa credenciales de producción para el sitio real
- Las transacciones de Sandbox **NO** son reales

## 🔍 **Verificar que Funciona**
1. El botón de PayPal debe aparecer
2. Al hacer clic, debe abrir el popup de PayPal
3. Debe permitir completar el pago con credenciales de prueba
4. Debe redirigir correctamente después del pago
