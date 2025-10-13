# 🎨 Documentación de Vistas - EcoVolt

## 📋 Estructura de Vistas

El sistema EcoVolt utiliza el patrón de vistas de CodeIgniter 4 con una estructura organizada por funcionalidades.

## 🏗️ Layout Principal

### `app/Views/layouts/main.php`
**Layout base para todas las páginas del sistema**

#### Características:
- **Responsive Design**: Compatible con dispositivos móviles
- **Modo Oscuro/Claro**: Toggle automático con persistencia en localStorage
- **Navegación Dinámica**: Menú que cambia según el rol del usuario
- **Internacionalización**: Soporte para múltiples idiomas

#### Estructura:
```html
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta tags, CSS, JavaScript -->
</head>
<body>
    <!-- Theme Switch -->
    <div class="theme-switch" id="themeSwitch">
        <i class="fas fa-moon"></i>
    </div>
    
    <!-- Selector de idioma -->
    <div class="language-selector">
        <img src="es.png" alt="Español">
        <img src="en.png" alt="English">
    </div>
    
    <!-- Navegación principal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Menú dinámico según rol -->
    </nav>
    
    <!-- Contenido principal -->
    <div class="container mt-4">
        <?= $this->renderSection('contenido') ?>
    </div>
</body>
</html>
```

#### Navegación por Roles:

**Administrador (Rol 1):**
- Dashboard
- Gestión de Usuarios
- Invitar Usuario

**Supervisor (Rol 3):**
- Dashboard
- Mis Usuarios
- Dispositivos Globales

**Usuario Normal (Rol 2):**
- Mis Dispositivos

## 🔐 Vistas de Autenticación

### `app/Views/autenticacion/login.php`
**Página de inicio de sesión con diseño avanzado**

#### Características Visuales:
- **Fondo Animado**: Gradientes con efectos aurora y partículas flotantes
- **Tarjeta de Login**: Diseño glassmorphism con efectos de brillo
- **Modo Oscuro**: Tema oscuro con colores dorados
- **Animaciones**: Efectos de hover y transiciones suaves

#### Elementos Principales:
```html
<div class="login-container">
    <h2 class="text-center mb-4">
        <i class="fas fa-bolt me-2"></i>EcoVolt
    </h2>
    
    <!-- Formulario de login -->
    <form action="<?= base_url('iniciarSesion') ?>" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="mb-3 password-input">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            <i class="fas fa-eye password-toggle" onclick="togglePassword('contrasena')"></i>
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
        </button>
    </form>
</div>
```

#### Funcionalidades JavaScript:
- **Toggle de Contraseña**: Mostrar/ocultar contraseña
- **Modo Oscuro**: Cambio de tema con persistencia
- **Validación**: Validación del lado del cliente

## 📊 Vistas de Energía

### `app/Views/energia/index.php`
**Dashboard principal de monitoreo de energía**

#### Características:
- **Gráficos en Tiempo Real**: Visualización de consumo con Chart.js
- **Datos Actualizados**: AJAX para actualización automática
- **Alertas Visuales**: Indicadores cuando se superan límites
- **Filtros**: Filtrado por fechas y dispositivos

#### Estructura del Dashboard:
```html
<div class="row">
    <!-- Tarjetas de resumen -->
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Consumo Actual</h5>
                <h2 id="consumo-actual">0.00 kWh</h2>
            </div>
        </div>
    </div>
    
    <!-- Gráfico principal -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Consumo en Tiempo Real</h5>
            </div>
            <div class="card-body">
                <canvas id="consumoChart"></canvas>
            </div>
        </div>
    </div>
</div>
```

## 👥 Vistas de Administración

### `app/Views/admin/dashboard.php`
**Panel de control para administradores**

#### Funcionalidades:
- **Estadísticas Generales**: Resumen de usuarios y dispositivos
- **Gestión de Usuarios**: Lista de usuarios con acciones
- **Invitaciones**: Sistema de invitación por email
- **Configuración**: Ajustes del sistema

### `app/Views/admin/gestionarUsuarios.php`
**Gestión completa de usuarios**

#### Características:
- **Tabla Responsiva**: Lista de usuarios con paginación
- **Acciones Rápidas**: Cambiar rol, eliminar, editar
- **Filtros**: Búsqueda por nombre, email, rol
- **Validaciones**: Confirmaciones antes de acciones críticas

## 📱 Responsive Design

### Breakpoints:
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

### Adaptaciones Móviles:
```css
@media (max-width: 768px) {
    .login-container {
        margin: 50px auto;
        padding: 1rem;
    }
    
    .navbar-nav {
        text-align: center;
    }
    
    .card-columns {
        column-count: 1;
    }
}
```

## 🎨 Sistema de Temas

### Modo Claro:
- **Colores**: Blanco, grises claros, dorado como acento
- **Contraste**: Alto contraste para legibilidad
- **Efectos**: Sombras suaves y gradientes sutiles

### Modo Oscuro:
- **Colores**: Negro, grises oscuros, dorado brillante
- **Efectos**: Brillos y efectos de neón
- **Transiciones**: Animaciones suaves entre temas

### Implementación:
```javascript
// Cambio de tema
function toggleTheme() {
    const theme = localStorage.getItem('theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}
```

## 🌐 Internacionalización

### Idiomas Soportados:
- **Español (es)**: Idioma principal
- **Inglés (en)**: Idioma secundario

### Implementación:
```php
// En las vistas
<?= lang('App.login_title') ?>
<?= lang('App.email') ?>
<?= lang('App.password') ?>
```

### Archivos de Idioma:
- `app/Language/es/App.php`
- `app/Language/en/App.php`

## 🔧 Componentes Reutilizables

### Alertas:
```html
<?php if (session()->get('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= session()->get('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
```

### Formularios:
```html
<form action="<?= base_url('endpoint') ?>" method="post">
    <div class="mb-3">
        <label for="campo" class="form-label">Etiqueta</label>
        <input type="text" class="form-control" id="campo" name="campo" required>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>
```

### Tablas Responsivas:
```html
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Columna 1</th>
                <th>Columna 2</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Datos dinámicos -->
        </tbody>
    </table>
</div>
```

## 📊 Gráficos y Visualizaciones

### Chart.js Integration:
```javascript
// Gráfico de consumo en tiempo real
const ctx = document.getElementById('consumoChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Consumo (kWh)',
            data: [],
            borderColor: '#FFD700',
            backgroundColor: 'rgba(255, 215, 0, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
```

## 🚀 Optimizaciones

### Performance:
- **Lazy Loading**: Carga diferida de imágenes
- **Minificación**: CSS y JS minificados
- **CDN**: Bootstrap y Font Awesome desde CDN
- **Caching**: Headers de cache apropiados

### SEO:
- **Meta Tags**: Títulos y descripciones optimizadas
- **Semantic HTML**: Estructura semántica correcta
- **Alt Text**: Textos alternativos en imágenes

### Accesibilidad:
- **ARIA Labels**: Etiquetas para lectores de pantalla
- **Keyboard Navigation**: Navegación por teclado
- **Color Contrast**: Contraste adecuado en todos los temas

## 🔄 Actualizaciones en Tiempo Real

### AJAX Updates:
```javascript
// Actualización automática de datos
setInterval(function() {
    fetch('/energia/getLatestData')
        .then(response => response.json())
        .then(data => {
            updateChart(data);
            updateCards(data);
        });
}, 5000); // Cada 5 segundos
```

### WebSocket (Futuro):
- Implementación de WebSockets para actualizaciones instantáneas
- Notificaciones push en tiempo real
- Sincronización multi-dispositivo

---

**EcoVolt Views** - Interfaz moderna y funcional para monitoreo de energía ⚡
