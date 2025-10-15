<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<style>
/* === PALETA DE COLORES PREMIUM === */
/* Variables CSS personalizadas para mantener consistencia visual */
:root {
    --gold-primary: #D4AF37;                          /* Dorado principal */
    --gold-secondary: #B8860B;                        /* Dorado secundario */
    --gold-light: #F7E98E;                           /* Dorado claro */
    --gold-dark: #8B7355;                            /* Dorado oscuro */
    --silver-primary: #C0C0C0;                       /* Plata principal */
    --silver-secondary: #A8A8A8;                     /* Plata secundaria */
    --silver-light: #E8E8E8;                         /* Plata clara */
    --black-primary: #1a1a1a;                        /* Negro principal */
    --black-secondary: #2d2d2d;                      /* Negro secundario */
    --black-light: #404040;                          /* Negro claro */
    --white-primary: #ffffff;                        /* Blanco principal */
    --white-secondary: #f8f9fa;                      /* Blanco secundario */
    --white-dark: #e9ecef;                           /* Blanco oscuro */
    --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); /* Gradiente dorado */
    --gradient-silver: linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%); /* Gradiente plateado */
    --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); /* Gradiente oscuro */
    --shadow-premium: 0 10px 30px rgba(212, 175, 55, 0.3); /* Sombra premium */
    --shadow-dark: 0 10px 30px rgba(0, 0, 0, 0.3);  /* Sombra oscura */
    --border-radius: 15px;                           /* Radio de bordes */
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); /* Transición suave */
}

/* === ESTILOS GLOBALES PREMIUM === */
/* Estilos base del cuerpo de la página */
body {
    background: var(--gradient-dark);                 /* Fondo con gradiente oscuro */
    color: var(--white-primary);                     /* Texto blanco */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Fuente moderna */
    line-height: 1.6;                               /* Espaciado entre líneas */
}

/* Contenedor principal */
.container-fluid {
    background: transparent;                         /* Fondo transparente */
    padding: 20px;                                  /* Espaciado interno */
}

/* === HEADER PREMIUM === */
/* Encabezado principal con efectos premium */
.premium-header {
    background: var(--gradient-gold);                 /* Fondo con gradiente dorado */
    border-radius: var(--border-radius);              /* Bordes redondeados */
    padding: 30px;                                   /* Espaciado interno */
    margin-bottom: 30px;                             /* Margen inferior */
    box-shadow: var(--shadow-premium);               /* Sombra premium */
    position: relative;                              /* Posición relativa */
    overflow: hidden;                                /* Ocultar desbordamiento */
}

/* Patrón de textura sutil en el header */
.premium-header::before {
    content: '';                                     /* Contenido vacío */
    position: absolute;                              /* Posición absoluta */
    top: 0;                                         /* Desde arriba */
    left: 0;                                        /* Desde la izquierda */
    right: 0;                                       /* Hasta la derecha */
    bottom: 0;                                      /* Hasta abajo */
    /* Patrón SVG de puntos sutiles */
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;                                   /* Semi-transparente */
}

/* Título del header */
.premium-header h1 {
    color: var(--black-primary);                      /* Color negro */
    font-weight: 700;                                /* Peso de fuente bold */
    font-size: 2.5rem;                              /* Tamaño de fuente grande */
    margin: 0;                                      /* Sin margen */
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);      /* Sombra de texto */
    position: relative;                              /* Posición relativa */
    z-index: 1;                                     /* Capa superior */
}

/* Botones del header */
.premium-header .btn {
    background: var(--black-primary);                 /* Fondo negro */
    border: none;                                   /* Sin borde */
    color: var(--gold-primary);                     /* Texto dorado */
    padding: 12px 25px;                            /* Espaciado interno */
    border-radius: 25px;                           /* Bordes muy redondeados */
    font-weight: 600;                              /* Peso de fuente semi-bold */
    transition: var(--transition);                 /* Transición suave */
    position: relative;                            /* Posición relativa */
    z-index: 1;                                   /* Capa superior */
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);      /* Sombra */
}

/* Efecto hover del botón */
.premium-header .btn:hover {
    background: var(--gold-primary);                 /* Fondo dorado */
    color: var(--black-primary);                    /* Texto negro */
    transform: translateY(-2px);                    /* Elevación */
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4); /* Sombra dorada */
}

/* === CARDS PREMIUM === */
/* Tarjetas con efectos de vidrio esmerilado */
.premium-card {
    background: rgba(255, 255, 255, 0.05);           /* Fondo blanco semi-transparente */
    backdrop-filter: blur(20px);                     /* Efecto de desenfoque */
    border: 1px solid rgba(212, 175, 55, 0.2);      /* Borde dorado sutil */
    border-radius: var(--border-radius);             /* Bordes redondeados */
    box-shadow: var(--shadow-dark);                  /* Sombra oscura */
    transition: var(--transition);                   /* Transición suave */
    overflow: hidden;                               /* Ocultar desbordamiento */
    position: relative;                             /* Posición relativa */
}

/* Línea dorada en la parte superior de la tarjeta */
.premium-card::before {
    content: '';                                    /* Contenido vacío */
    position: absolute;                             /* Posición absoluta */
    top: 0;                                        /* Desde arriba */
    left: 0;                                       /* Desde la izquierda */
    right: 0;                                      /* Hasta la derecha */
    height: 3px;                                   /* Altura de 3px */
    background: var(--gradient-gold);              /* Fondo con gradiente dorado */
}

/* Efecto hover de la tarjeta */
.premium-card:hover {
    transform: translateY(-5px);                    /* Elevación */
    box-shadow: 0 20px 40px rgba(212, 175, 55, 0.2); /* Sombra dorada */
    border-color: var(--gold-primary);             /* Borde dorado */
}

/* Encabezado de la tarjeta */
.premium-card-header {
    background: rgba(212, 175, 55, 0.1);            /* Fondo dorado transparente */
    border-bottom: 1px solid rgba(212, 175, 55, 0.3); /* Línea separadora dorada */
    padding: 20px 25px;                            /* Espaciado interno */
    position: relative;                             /* Posición relativa */
}

/* Título del encabezado */
.premium-card-header h6 {
    color: var(--gold-primary);                     /* Color dorado */
    font-weight: 700;                              /* Peso de fuente bold */
    font-size: 1.1rem;                            /* Tamaño de fuente */
    margin: 0;                                    /* Sin margen */
    text-transform: uppercase;                     /* Texto en mayúsculas */
    letter-spacing: 1px;                          /* Espaciado entre letras */
}

/* Cuerpo de la tarjeta */
.premium-card-body {
    padding: 25px;                                /* Espaciado interno */
    background: rgba(255, 255, 255, 0.02);        /* Fondo blanco muy sutil */
}

/* === MÉTRICAS PREMIUM === */
/* Tarjetas para mostrar métricas de energía */
.metric-card {
    background: var(--gradient-dark);                /* Fondo con gradiente oscuro */
    border: 1px solid var(--gold-primary);          /* Borde dorado */
    border-radius: var(--border-radius);             /* Bordes redondeados */
    padding: 25px;                                 /* Espaciado interno */
    text-align: center;                            /* Texto centrado */
    transition: var(--transition);                   /* Transición suave */
    position: relative;                             /* Posición relativa */
    overflow: hidden;                              /* Ocultar desbordamiento */
}

/* Línea dorada en la parte superior de la métrica */
.metric-card::before {
    content: '';                                   /* Contenido vacío */
    position: absolute;                            /* Posición absoluta */
    top: 0;                                       /* Desde arriba */
    left: 0;                                      /* Desde la izquierda */
    right: 0;                                     /* Hasta la derecha */
    height: 4px;                                  /* Altura de 4px */
    background: var(--gradient-gold);             /* Fondo con gradiente dorado */
}

/* Efecto hover de la métrica */
.metric-card:hover {
    transform: translateY(-3px);                    /* Elevación sutil */
    box-shadow: var(--shadow-premium);             /* Sombra premium */
    border-color: var(--gold-light);              /* Borde dorado claro */
}

/* Etiqueta de la métrica */
.metric-card .metric-label {
    color: var(--silver-primary);                    /* Color plateado */
    font-size: 0.9rem;                             /* Tamaño de fuente */
    font-weight: 600;                              /* Peso de fuente semi-bold */
    text-transform: uppercase;                     /* Texto en mayúsculas */
    letter-spacing: 1px;                          /* Espaciado entre letras */
    margin-bottom: 10px;                          /* Margen inferior */
}

/* Valor de la métrica */
.metric-card .metric-value {
    color: var(--gold-primary);                     /* Color dorado */
    font-size: 2rem;                              /* Tamaño de fuente grande */
    font-weight: 700;                             /* Peso de fuente bold */
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);   /* Sombra de texto */
}

/* Icono de la métrica */
.metric-card .metric-icon {
    color: var(--gold-primary);                     /* Color dorado */
    font-size: 2.5rem;                            /* Tamaño de fuente muy grande */
    margin-bottom: 15px;                          /* Margen inferior */
    opacity: 0.8;                                /* Semi-transparente */
}

/* === FORMULARIOS PREMIUM === */
/* Campos de formulario con estilo premium */
.premium-form .form-control {
    background: rgba(255, 255, 255, 0.1);           /* Fondo blanco semi-transparente */
    border: 1px solid rgba(212, 175, 55, 0.3);      /* Borde dorado sutil */
    border-radius: 10px;                           /* Bordes redondeados */
    color: var(--white-primary);                   /* Texto blanco */
    padding: 12px 15px;                           /* Espaciado interno */
    transition: var(--transition);                  /* Transición suave */
}

/* Estado de foco del campo */
.premium-form .form-control:focus {
    background: rgba(255, 255, 255, 0.15);         /* Fondo más intenso */
    border-color: var(--gold-primary);             /* Borde dorado */
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2); /* Sombra dorada */
    color: var(--white-primary);                   /* Mantener texto blanco */
}

/* Texto placeholder */
.premium-form .form-control::placeholder {
    color: var(--silver-secondary);                 /* Color plateado secundario */
}

/* Etiquetas del formulario */
.premium-form label {
    color: var(--gold-primary);                     /* Color dorado */
    font-weight: 600;                              /* Peso de fuente semi-bold */
    margin-bottom: 8px;                           /* Margen inferior */
}

/* === BOTONES PREMIUM === */
/* Botón principal con efectos premium */
.btn-premium {
    background: var(--gradient-gold);                /* Fondo con gradiente dorado */
    border: none;                                  /* Sin borde */
    color: var(--black-primary);                   /* Texto negro */
    padding: 12px 30px;                           /* Espaciado interno */
    border-radius: 25px;                          /* Bordes muy redondeados */
    font-weight: 700;                             /* Peso de fuente bold */
    text-transform: uppercase;                     /* Texto en mayúsculas */
    letter-spacing: 1px;                          /* Espaciado entre letras */
    transition: var(--transition);                  /* Transición suave */
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); /* Sombra dorada */
    position: relative;                            /* Posición relativa */
    overflow: hidden;                             /* Ocultar desbordamiento */
    display: flex;                                /* Layout flexbox */
    align-items: center;                          /* Centrar verticalmente */
    justify-content: center;                      /* Centrar horizontalmente */
    text-align: center;                           /* Texto centrado */
}

/* Efecto de brillo que se desliza */
.btn-premium::before {
    content: '';                                  /* Contenido vacío */
    position: absolute;                            /* Posición absoluta */
    top: 0;                                      /* Desde arriba */
    left: -100%;                                 /* Inicia fuera del botón */
    width: 100%;                                 /* Ancho completo */
    height: 100%;                                /* Alto completo */
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); /* Gradiente de brillo */
    transition: left 0.5s;                       /* Transición de posición */
}

/* Efecto de brillo al hacer hover */
.btn-premium:hover::before {
    left: 100%;                                  /* Termina fuera del botón */
}

/* Efecto hover del botón */
.btn-premium:hover {
    transform: translateY(-2px);                   /* Elevación */
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5); /* Sombra más intensa */
    color: var(--black-primary);                  /* Mantener texto negro */
}

/* Botón secundario con estilo plateado */
.btn-premium-secondary {
    background: var(--gradient-silver);              /* Fondo con gradiente plateado */
    color: var(--black-primary);                   /* Texto negro */
    border: none;                                  /* Sin borde */
    padding: 15px 25px;                           /* Espaciado interno */
    border-radius: 20px;                          /* Bordes redondeados */
    font-weight: 600;                             /* Peso de fuente semi-bold */
    transition: var(--transition);                  /* Transición suave */
    display: flex;                                /* Layout flexbox */
    align-items: center;                          /* Centrar verticalmente */
    justify-content: center;                      /* Centrar horizontalmente */
    text-align: center;                           /* Texto centrado */
}

/* Efecto hover del botón secundario */
.btn-premium-secondary:hover {
    background: var(--gold-primary);                /* Fondo dorado */
    transform: translateY(-2px);                    /* Elevación */
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); /* Sombra dorada */
    color: var(--black-primary);                   /* Mantener texto negro */
}

/* === MEJORAS EN SELECT Y OPTIONS === */
/* Campos de formulario y selects con estilo premium */
.form-control, .form-select {
    background: rgba(255, 255, 255, 0.1);           /* Fondo blanco semi-transparente */
    border: 1px solid var(--gold-primary);          /* Borde dorado */
    color: var(--white-primary);                   /* Texto blanco */
    border-radius: 10px;                          /* Bordes redondeados */
    padding: 12px 15px;                          /* Espaciado interno */
    transition: var(--transition);                  /* Transición suave */
}

/* Estado de foco de campos y selects */
.form-control:focus, .form-select:focus {
    background: rgba(255, 255, 255, 0.15);         /* Fondo más intenso */
    border-color: var(--gold-light);               /* Borde dorado claro */
    box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25); /* Sombra dorada */
    color: var(--white-primary);                   /* Mantener texto blanco */
}

/* Texto placeholder */
.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);              /* Color blanco semi-transparente */
}

/* === OPCIONES DEL SELECT CON MEJOR CONTRASTE === */
/* Opciones del select con fondo oscuro */
.form-select option {
    background: var(--black-secondary);             /* Fondo negro secundario */
    color: var(--white-primary);                   /* Texto blanco */
    padding: 10px;                                /* Espaciado interno */
}

/* Efecto hover en opciones */
.form-select option:hover {
    background: var(--gold-primary);                /* Fondo dorado */
    color: var(--black-primary);                   /* Texto negro */
}

/* Opción seleccionada */
.form-select option:checked {
    background: var(--gold-primary);                /* Fondo dorado */
    color: var(--black-primary);                   /* Texto negro */
    font-weight: bold;                            /* Texto en negrita */
}

/* === TABLAS PREMIUM === */
/* Tabla con estilo premium */
.premium-table {
    background: rgba(255, 255, 255, 0.05);           /* Fondo blanco semi-transparente */
    border-radius: var(--border-radius);             /* Bordes redondeados */
    overflow: hidden;                               /* Ocultar desbordamiento */
    box-shadow: var(--shadow-dark);                  /* Sombra oscura */
}

/* Tabla interna */
.premium-table table {
    margin: 0;                                     /* Sin margen */
    background: transparent;                        /* Fondo transparente */
}

/* Encabezados de la tabla */
.premium-table thead th {
    background: var(--gradient-gold);                /* Fondo con gradiente dorado */
    color: var(--black-primary);                   /* Texto negro */
    font-weight: 700;                             /* Peso de fuente bold */
    text-transform: uppercase;                     /* Texto en mayúsculas */
    letter-spacing: 1px;                          /* Espaciado entre letras */
    padding: 20px 15px;                          /* Espaciado interno */
    border: none;                                 /* Sin bordes */
    font-size: 0.9rem;                           /* Tamaño de fuente */
}

/* Celdas del cuerpo de la tabla */
.premium-table tbody td {
    background: rgba(255, 255, 255, 0.02);         /* Fondo blanco muy sutil */
    color: var(--white-primary);                   /* Texto blanco */
    padding: 15px;                                /* Espaciado interno */
    border-bottom: 1px solid rgba(212, 175, 55, 0.1); /* Línea separadora dorada */
    transition: var(--transition);                  /* Transición suave */
}

/* Efecto hover en filas */
.premium-table tbody tr:hover td {
    background: rgba(212, 175, 55, 0.1);           /* Fondo dorado transparente */
    color: var(--gold-light);                      /* Texto dorado claro */
}

/* === ALERTAS PREMIUM === */
/* Alerta base con estilo premium */
.alert-premium {
    background: rgba(212, 175, 55, 0.1);            /* Fondo dorado transparente */
    border: 1px solid var(--gold-primary);          /* Borde dorado */
    border-radius: var(--border-radius);             /* Bordes redondeados */
    color: var(--gold-light);                      /* Texto dorado claro */
    padding: 20px;                                /* Espaciado interno */
    margin: 20px 0;                              /* Margen vertical */
    backdrop-filter: blur(10px);                   /* Efecto de desenfoque */
}

/* Alerta de éxito */
.alert-premium-success {
    background: rgba(40, 167, 69, 0.1);            /* Fondo verde transparente */
    border-color: #28a745;                         /* Borde verde */
    color: #90EE90;                               /* Texto verde claro */
}

/* Alerta de peligro */
.alert-premium-danger {
    background: rgba(220, 53, 69, 0.1);            /* Fondo rojo transparente */
    border-color: #dc3545;                         /* Borde rojo */
    color: #ff6b6b;                               /* Texto rojo claro */
}

/* Alerta de advertencia */
.alert-premium-warning {
    background: rgba(255, 193, 7, 0.1);            /* Fondo amarillo transparente */
    border-color: #ffc107;                         /* Borde amarillo */
    color: #ffd700;                               /* Texto amarillo dorado */
}

/* === GRÁFICO PREMIUM === */
/* Contenedor del gráfico con estilo premium */
.premium-chart {
    background: rgba(255, 255, 255, 0.05);           /* Fondo blanco semi-transparente */
    border-radius: var(--border-radius);             /* Bordes redondeados */
    padding: 20px;                                 /* Espaciado interno */
    backdrop-filter: blur(10px);                    /* Efecto de desenfoque */
}

/* (Se eliminó sección mensual) */

/* === ESTADO DE CONEXIÓN PREMIUM === */
/* Badge base para estados */
.status-badge {
    padding: 8px 15px;                             /* Espaciado interno */
    border-radius: 20px;                          /* Bordes muy redondeados */
    font-weight: 600;                             /* Peso de fuente semi-bold */
    font-size: 0.85rem;                          /* Tamaño de fuente */
    text-transform: uppercase;                     /* Texto en mayúsculas */
    letter-spacing: 1px;                         /* Espaciado entre letras */
}

/* Estado conectado */
.status-connected {
    background: var(--gradient-gold);               /* Fondo con gradiente dorado */
    color: var(--black-primary);                  /* Texto negro */
    box-shadow: 0 3px 10px rgba(212, 175, 55, 0.3); /* Sombra dorada */
}

/* Estado de error */
.status-error {
    background: linear-gradient(135deg, #dc3545, #c82333); /* Fondo con gradiente rojo */
    color: var(--white-primary);                   /* Texto blanco */
    box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3); /* Sombra roja */
}

/* Estado actualizando */
.status-updating {
    background: var(--gradient-silver);             /* Fondo con gradiente plateado */
    color: var(--black-primary);                  /* Texto negro */
    box-shadow: 0 3px 10px rgba(192, 192, 192, 0.3); /* Sombra plateada */
}

/* === ANIMACIONES PREMIUM === */
/* Animación de brillo que se desliza */
@keyframes shimmer {
    0% { transform: translateX(-100%); }            /* Inicia fuera del elemento */
    100% { transform: translateX(100%); }           /* Termina fuera del elemento */
}

/* Elemento con efecto shimmer */
.shimmer {
    position: relative;                             /* Posición relativa */
    overflow: hidden;                              /* Ocultar desbordamiento */
}

/* Efecto de brillo que se desliza */
.shimmer::after {
    content: '';                                   /* Contenido vacío */
    position: absolute;                            /* Posición absoluta */
    top: 0;                                      /* Desde arriba */
    left: 0;                                     /* Desde la izquierda */
    width: 100%;                                 /* Ancho completo */
    height: 100%;                                /* Alto completo */
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); /* Gradiente de brillo */
    animation: shimmer 2s infinite;               /* Animación infinita */
}

/* === RESPONSIVE PREMIUM === */
@media (max-width: 768px) {
    .premium-header h1 {
        font-size: 2rem;
    }
    
    .metric-card .metric-value {
        font-size: 1.5rem;
    }
    
    .premium-card-body {
        padding: 20px;
    }
}

/* === EFECTOS ESPECIALES === */
.glow-effect {
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    from { box-shadow: 0 0 20px rgba(212, 175, 55, 0.5); }
    to { box-shadow: 0 0 30px rgba(212, 175, 55, 0.8); }
}

.pulse-effect {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<div class="container-fluid">
    <div class="premium-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
            <i class="fas fa-chart-line me-2"></i>
            Lecturas de Energía - <?= esc($dispositivo['nombre']) ?>
        </h1>
        <?php 
            $rol = session()->get('rol');
            $volverUrl = base_url('energia');
            if ($rol === 'admin') {
                $volverUrl = base_url('admin/dispositivos');
            } elseif ($rol === 'supervisor') {
                $volverUrl = base_url('supervisor/dispositivosGlobal');
            } else {
                $volverUrl = base_url('perfil/perfil');
            }
        ?>
            <a href="<?= $volverUrl ?>" class="btn">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
        </div>
    </div>

    <!-- Gráfico general de consumo -->
    <div class="premium-card mb-4">
        <div class="premium-card-header d-flex justify-content-between align-items-center">
            <h6><i class="fas fa-chart-area me-2"></i>Gráfico de Consumo</h6>
            <div class="d-flex align-items-center">
                <div class="me-2 d-none d-md-block">
                    <select id="seleccionMetrica" class="form-select form-select-sm" style="background:rgba(255,255,255,0.1);color:#F7E98E;border:1px solid rgba(212,175,55,0.3);">
                        <option value="potencia" selected>Potencia (W)</option>
                        <option value="kwh_acumulado">Energía Acumulada (kWh)</option>
                    </select>
                </div>
                <div class="me-3 d-none d-md-block">
                    <select id="seleccionRango" class="form-select form-select-sm" style="background:rgba(255,255,255,0.1);color:#F7E98E;border:1px solid rgba(212,175,55,0.3);">
                        <option value="2">Últimas 2 h</option>
                        <option value="6">Últimas 6 h</option>
                        <option value="12">Últimas 12 h</option>
                        <option value="24" selected>Últimas 24 h</option>
                        <option value="all">Todo</option>
                    </select>
                </div>
                <div id="estadoActualizacion" class="status-badge status-connected">
                    <i class="fas fa-check-circle"></i> Conectado
                </div>
                <small class="text-muted ms-2">Actualización automática cada 5s</small>
                <small id="infoActualizacion" class="text-info ms-2" style="font-size: 0.8rem;"></small>
                <button type="button" class="btn btn-sm btn-outline-success ms-2" onclick="forzarActualizacion()" title="Forzar actualización">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-info ms-1" onclick="debugTiempoReal()" title="Debug tiempo real">
                    <i class="fas fa-bug"></i>
                </button>
            </div>
        </div>
        <div class="premium-card-body">
            <!-- Alerta de sin energía -->
            <div id="alertaSinEnergia" class="alert-premium alert-premium-danger text-center mb-3" style="display: none;">
                <h4 class="alert-heading">
                    <i class="fas fa-power-off fa-2x mb-2"></i><br>
                    ¡SIN ENERGÍA!
                </h4>
                <p class="mb-0" style="font-size: 1.2em; font-weight: bold;">
                    No hay consumo en el sistema. Verifique la conexión eléctrica.
                </p>
            </div>
           
            <div class="premium-chart">
            <canvas id="graficoConsumo" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Valores actuales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-bolt"></i>
                        </div>
                <div class="metric-label">Voltaje</div>
                <div class="metric-value" id="valorVoltaje">0 V</div>
                        </div>
                    </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-wave-square"></i>
                </div>
                <div class="metric-label">Corriente</div>
                <div class="metric-value" id="valorCorriente">0 A</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card">
                <div class="metric-icon">
                    <i class="fas fa-tachometer-alt"></i>
                        </div>
                <div class="metric-label">Potencia</div>
                <div class="metric-value" id="valorPotencia">0 W</div>
                        </div>
                    </div>
        <div class="col-md-3 mb-3">
            <div class="metric-card glow-effect">
                <div class="metric-icon">
                    <i class="fas fa-battery-half"></i>
        </div>
    </div>


    <!-- Mensajes de estado en tiempo real -->
    <div id="logsEstado" class="mb-3"></div>
  

    <!-- Estado del Límite en Tiempo Real -->
    <div class="premium-card mb-4">
        <div class="premium-card-header d-flex justify-content-between align-items-center">
            <h6><i class="fas fa-shield-alt me-2"></i>Estado del Límite de Consumo</h6>
            <div class="d-flex align-items-center">
                <span class="status-badge status-updating" id="estadoLimite">
                    <i class="fas fa-sync-alt fa-spin"></i> Verificando...
                </span>
                <small class="text-muted ms-2">Actualización automática</small>
            </div>
        </div>
        <div class="premium-card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="alert-premium">
                        <h6><i class="fas fa-tachometer-alt me-2"></i>Límite Actual</h6>
                        <p class="mb-0">
                            <strong id="limiteActual" class="text-warning"><?= esc($limite_consumo) ?></strong> kWh
                        </p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="alert-premium" id="alertaEstadoLimite">
                        <h6><i class="fas fa-chart-bar me-2"></i>Estado del Consumo</h6>
                        <p class="mb-0 fw-bold" id="textoEstadoLimite">
                            Verificando consumo actual...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->get('rol') === 'admin' || session()->get('rol') === 'supervisor'): ?>
    <div class="premium-card mb-4" id="configurar-limites">
    <div class="premium-card-header">
        <h6><i class="fas fa-cog me-2"></i>Configuración de Límite de Consumo</h6>
    </div>
    <div class="premium-card-body">
    <form id="formLimite" action="<?= base_url('energia/actualizarLimite') ?>" method="post" class="premium-form">
        <div class="form-group mb-3">
            <label for="limite_consumo">Límite de Consumo (kWh)</label>
            <input type="number" step="0.001" min="0.001" class="form-control" 
                   id="limite_consumo" name="limite_consumo" 
                   value="<?= esc($limite_consumo) ?>" required>
            <small class="form-text text-muted">
                Este límite se aplicará automáticamente al dispositivo ESP32 basado en el kWh acumulado.
            </small>
        </div>
        <div class="form-group mb-3">
            <label for="email">Email de notificación (opcional)</label>
            <input type="email" class="form-control" 
                   id="email" name="email" 
                   value="<?= esc(session()->get('email')) ?>">
        </div>
        <button type="submit" class="btn-premium">
            <i class="fas fa-save me-2"></i> Guardar Configuración
        </button>
    </form>
    <div id="msgLimite" class="mt-3"></div>
    
    <!-- Información técnica oculta (solo para desarrolladores) -->
    <?php if (session()->get('rol') === 'admin'): ?>
    <div class="mt-4" id="infoTecnica" style="display: none;">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleInfoTecnica()">
            <i class="fas fa-code me-1"></i> Ver Info Técnica
        </button>
        <div id="contenidoTecnico" class="mt-3" style="display: none;">
            <div class="alert alert-light">
                <p><strong>URL del Endpoint:</strong> <code><?= base_url('energia/getlimite') ?></code></p>
                <p><strong>Método:</strong> GET</p>
                <p><strong>Respuesta JSON:</strong></p>
                <pre class="bg-dark text-light p-2 rounded"><code>{
  "success": true,
  "limite_consumo": <?= esc($limite_consumo) ?>,
  "timestamp": "2024-01-01 12:00:00"
}</code></pre>
                <button type="button" class="btn btn-sm btn-outline-info mt-2" id="btnProbarEndpoint">
                    <i class="fas fa-wifi me-1"></i> Probar Endpoint
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
    <?php endif; ?>


    <?php if (session()->get('rol') === 'admin' || session()->get('rol') === 'supervisor'): ?>
    <!-- Editar nombre y descripción del dispositivo (solo admin/supervisor) -->
    <div class="premium-card mb-4">
        <div class="premium-card-header">
            <h6><i class="fas fa-edit me-2"></i>Editar dispositivo</h6>
        </div>
        <div class="premium-card-body">
            <form id="formEditarDispositivo" action="<?= base_url('energia/actualizarDispositivo') ?>" method="post" class="premium-form row g-3">
                <input type="hidden" name="id_dispositivo" value="<?= esc($dispositivo['id_dispositivo']) ?>">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?= esc($dispositivo['nombre']) ?>" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción del dispositivo..."><?= esc($dispositivo['descripcion'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn-premium" id="btnGuardarDispositivo"><i class="fas fa-save me-2"></i>Guardar</button>
                    <span id="msgEditarDispositivo" class="ms-2"></span>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
<!-- Formulario para comparar valor de kWh -->
<div class="premium-card mb-4">
    <div class="premium-card-header">
        <h6><i class="fas fa-calculator me-2"></i>Calcular Costo de Energía</h6>
    </div>
    <div class="premium-card-body">
        <form id="formKwh" class="premium-form row g-3">
            <div class="col-md-4">
                <label for="valorKwh" class="form-label">Valor de kWh ($)</label>
                <input type="number" step="0.01" class="form-control" id="inputKwh" placeholder="Ej: 150.50" required>
                </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn-premium">
                    <i class="fas fa-calculator me-2"></i> Calcular
                </button>
            </div>
        </form>

        <div id="resultadoCosto" class="mt-3" style="display:none;">
            <div class="alert-premium alert-premium-success">
            <h6 class="fw-bold">Resultado:</h6>
                <p>Total de Energía Acumulada: <span id="totalKwh" class="text-warning fw-bold"></span> kWh</p>
                <p>Costo estimado: <span id="costoTotal" class="text-warning fw-bold"></span> $</p>
                <button id="btnPdf" onclick="descargarPDF()" class="btn-premium mt-2">
                    <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                </button>
            </div>
        </div>
    </div>
</div>
    


    <!-- Tabla de Lecturas con Filtros -->
    <div class="premium-card mb-4">
        <div class="premium-card-header d-flex justify-content-between align-items-center">
            <h6><i class="fas fa-history me-2"></i>Historial de Lecturas</h6>
            <div class="d-flex align-items-center">
                <button type="button" class="btn-premium-secondary" id="btnMostrarFiltros">
                    <i class="fas fa-filter me-1"></i> Filtros
                </button>
            </div>
        </div>
        
        <!-- Panel de Filtros (oculto por defecto) -->
        <div class="premium-card-body border-bottom" id="panelFiltros" style="display: none;">
            <form id="formFiltros" class="premium-form row g-3">
                <div class="col-md-3">
                    <label for="filtroFechaDesde" class="form-label">Desde:</label>
                    <input type="date" class="form-control" id="filtroFechaDesde" name="fecha_desde">
                </div>
                <div class="col-md-3">
                    <label for="filtroFechaHasta" class="form-label">Hasta:</label>
                    <input type="date" class="form-control" id="filtroFechaHasta" name="fecha_hasta">
                </div>
                <div class="col-md-2">
                    <label for="filtroLimite" class="form-label">Mostrar:</label>
                    <select class="form-control" id="filtroLimite" name="limite">
                        <option value="10">Últimas 10</option>
                        <option value="25" selected>Últimas 25</option>
                        <option value="50">Últimas 50</option>
                        <option value="100">Últimas 100</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filtroOrden" class="form-label">Orden:</label>
                    <select class="form-control" id="filtroOrden" name="orden">
                        <option value="DESC" selected>Más recientes</option>
                        <option value="ASC">Más antiguos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end justify-content-center w-100">
                    <button type="submit" class="btn-premium me-2 px-4 py-2">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <button type="button" class="btn-premium-secondary px-4 py-2" id="btnLimpiarFiltros">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
        
        <div class="premium-card-body">
            <div id="loadingLecturas" class="text-center" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-2">Cargando lecturas...</p>
            </div>
            
            <div id="contenidoLecturas">
                <div class="alert-premium">
                    Usa los <strong>Filtros</strong> para consultar el historial de lecturas sin cargar toda la página.
                </div>
                <div class="premium-table" style="display:none;" id="wrapperTablaLecturas">
                    <table id="tablaLecturas">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Voltaje (V)</th>
                                <th>Corriente (A)</th>
                                <th>Potencia (W)</th>
                                <th>Energía Acumulada (kWh)</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="text-center mt-3" id="contadorLecturas" style="display:none;">
                    <small class="text-muted"></small>
                </div>
                <div class="d-flex justify-content-center gap-2" id="paginacionLecturas" style="display:none;">
                    <button class="btn-premium-secondary" id="btnPrevPage"><i class="fas fa-chevron-left"></i></button>
                    <span class="mx-2" id="paginaActual" style="align-self:center;color:#C0C0C0;">1 / 1</span>
                    <button class="btn-premium-secondary" id="btnNextPage"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($lecturas)): ?>
    
    let lecturas = <?= json_encode($lecturas) ?>;

    function ultimoValor(variable) {
        return lecturas.length ? lecturas[0][variable] : 0;
    }

    function mostrarMensaje(tipo, texto) {
        const mensajesEstado = document.getElementById('logsEstado');
        let clase = 'alert-premium';

        switch (tipo) {
            case 'error': clase = 'alert-premium alert-premium-danger'; break;
            case 'alerta': clase = 'alert-premium alert-premium-warning'; break;
            case 'ok': clase = 'alert-premium alert-premium-success'; break;
            case 'info': clase = 'alert-premium'; break;
        }

        mensajesEstado.innerHTML = `
            <div class="${clase} text-center">
                ${texto}
            </div>
        `;
        console.log(`Mensaje tipo "${tipo}": ${texto}`);
    }

    function verificarEnergia() {
        const alerta = document.getElementById('alertaSinEnergia');
        const graficoCanvas = document.getElementById('graficoConsumo');
        const volt = ultimoValor('voltaje');
        const corr = ultimoValor('corriente');
        const pot = ultimoValor('potencia');
        const kwh = ultimoValor('kwh_acumulado');

        const sinEnergia = volt < 0.1 && corr < 0.1 && pot < 0.1 && kwh < 0.1;

        if (sinEnergia) {
            alerta.style.display = 'block';
            graficoCanvas.style.display = 'none';
        } else {
            alerta.style.display = 'none';
            graficoCanvas.style.display = 'block';
        }
    }

    function actualizarValoresActuales() {
        document.getElementById('valorVoltaje').textContent = Number(ultimoValor('voltaje')).toFixed(2) + ' V';
        document.getElementById('valorCorriente').textContent = Number(ultimoValor('corriente')).toFixed(2) + ' A';
        document.getElementById('valorPotencia').textContent = Number(ultimoValor('potencia')).toFixed(2) + ' W';
        document.getElementById('valorKwh').textContent = Number(ultimoValor('kwh_acumulado')).toFixed(2) + ' kWh';
        verificarEnergia();
    }

    // Crear gráfico de consumo (tema premium)
    const ctx = document.getElementById('graficoConsumo').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 240);
    gradient.addColorStop(0, 'rgba(212, 175, 55, 0.35)');
    gradient.addColorStop(1, 'rgba(212, 175, 55, 0.05)');

    let graficoConsumo = new Chart(ctx, {
        type: 'line',
        data: {
            labels: lecturas.map(l => new Date(l.fecha).toLocaleString()),
            datasets: [{
                label: 'Potencia (W)',
                data: lecturas.map(l => l.potencia),
                borderColor: '#D4AF37',
                backgroundColor: gradient,
                tension: 0.25,
                fill: true,
                pointRadius: 2,
                pointHoverRadius: 4,
                pointBackgroundColor: '#D4AF37',
                pointBorderColor: '#1a1a1a',
                pointBorderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: {
                    labels: { color: '#F7E98E', boxWidth: 12, usePointStyle: true, pointStyle: 'circle' }
                },
                tooltip: {
                    backgroundColor: 'rgba(26,26,26,0.9)',
                    borderColor: '#D4AF37',
                    borderWidth: 1,
                    titleColor: '#F7E98E',
                    bodyColor: '#ffffff',
                    callbacks: {
                        label: (ctx) => ` ${Number(ctx.parsed.y).toFixed(2)} W`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(212,175,55,0.15)' },
                    ticks: { color: '#E8E8E8' },
                    title: { display: true, text: 'Potencia (W)', color: '#C0C0C0' }
                },
                x: {
                    grid: { color: 'rgba(192,192,192,0.08)' },
                    ticks: { color: '#E8E8E8', maxRotation: 0, autoSkip: true, maxTicksLimit: 6 },
                    title: { display: true, text: 'Fecha y Hora', color: '#C0C0C0' }
                }
            },
            elements: { line: { borderWidth: 2 } },
            animations: {
                tension: { duration: 600, easing: 'easeOutCubic', from: 0.5, to: 0.25, loop: false }
            }
        }
    });

    function filtrarPorRango(horas) {
        if (horas === 'all') return lecturas;
        const limite = Date.now() - (Number(horas) * 60 * 60 * 1000);
        return lecturas.filter(l => new Date(l.fecha).getTime() >= limite);
    }

    function actualizarGrafico() {
        const metrica = document.getElementById('seleccionMetrica')?.value || 'potencia';
        const rango = document.getElementById('seleccionRango')?.value || '24';
        const datos = filtrarPorRango(rango);

        console.log('Actualizando gráfico:', {
            metrica: metrica,
            rango: rango,
            totalLecturas: lecturas.length,
            datosFiltrados: datos.length,
            ultimaLectura: lecturas[0]
        });

        const labels = datos.map(l => new Date(l.fecha).toLocaleString());
        const valores = datos.map(l => metrica === 'kwh_acumulado' ? l.kwh_acumulado : l.potencia);

        console.log('Datos del gráfico:', {
            labels: labels.slice(0, 3), // Primeros 3 labels
            valores: valores.slice(0, 3) // Primeros 3 valores
        });

        graficoConsumo.data.labels = labels;
        graficoConsumo.data.datasets[0].label = metrica === 'kwh_acumulado' ? 'Energía Acumulada (kWh)' : 'Potencia (W)';
        graficoConsumo.data.datasets[0].data = valores;
        graficoConsumo.options.scales.y.title.text = metrica === 'kwh_acumulado' ? 'Energía Acumulada (kWh)' : 'Potencia (W)';
        graficoConsumo.update();
    }

    function actualizarTabla() { /* rendering deshabilitado por defecto; usar filtros */ }

    // Función para obtener datos en tiempo real
    function obtenerDatosTiempoReal() {
        contadorActualizaciones++;
        ultimaActualizacion = new Date();
        
        // Mostrar estado de actualización
        const estadoElement = document.getElementById('estadoActualizacion');
        estadoElement.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> Actualizando...';
        estadoElement.className = 'status-badge status-updating';
        
        console.log(`🔄 Actualización #${contadorActualizaciones} - ${ultimaActualizacion.toLocaleTimeString()}`);
        
        fetch(`<?= base_url('energia/getLatestDataByDevice/' . $dispositivo['id_dispositivo']) ?>`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    // Obtener la nueva lectura
                    const nuevaLectura = data.data;

                    // Verificar si es una lectura nueva (comparar fecha)
                    const ultimaLecturaExistente = lecturas[0];
                    
                    // Mejorar la comparación de fechas
                    let esNuevaLectura = false;
                    
                    if (!ultimaLecturaExistente) {
                        esNuevaLectura = true;
                        console.log('Primera lectura detectada');
                    } else {
                        const fechaNueva = new Date(nuevaLectura.fecha);
                        const fechaExistente = new Date(ultimaLecturaExistente.fecha);
                        const diferenciaTiempo = fechaNueva.getTime() - fechaExistente.getTime();
                        
                        // Considerar nueva si la diferencia es mayor a 1 segundo
                        esNuevaLectura = diferenciaTiempo > 1000;
                        
                        console.log('Comparando fechas:', {
                            nuevaLectura: nuevaLectura.fecha,
                            ultimaExistente: ultimaLecturaExistente.fecha,
                            diferenciaSegundos: Math.round(diferenciaTiempo / 1000),
                            esNueva: esNuevaLectura
                        });
                    }

                    if (esNuevaLectura) {
                        
                        console.log('Agregando nueva lectura:', nuevaLectura);
                        
                        // Agregar nueva lectura al inicio
                        lecturas.unshift(nuevaLectura);
                        
                        // Mantener solo las últimas 50 lecturas
                        if (lecturas.length > 50) {
                            lecturas = lecturas.slice(0, 50);
                        }
                        
                        console.log('Total de lecturas después de agregar:', lecturas.length);
                        
                        // Actualizar todo
                        actualizarValoresActuales();
                        actualizarGrafico();
                        actualizarTabla();
                        
                        // VERIFICAR CORTE DE LÍNEA NO ESENCIAL
                        verificarCorteLineaNoEsencial(nuevaLectura);
                        
                        // Mostrar estado exitoso
                        estadoElement.innerHTML = `<i class="fas fa-check-circle"></i> Conectado (${contadorActualizaciones})`;
                        estadoElement.className = 'status-badge status-connected';
                        
                        // Actualizar información de actualización
                        const infoElement = document.getElementById('infoActualizacion');
                        if (infoElement) {
                            infoElement.textContent = `Última: ${ultimaActualizacion.toLocaleTimeString()}`;
                        }
                        
                        console.log('✅ Datos actualizados correctamente:', nuevaLectura);
                    } else {
                        // Mostrar estado conectado (sin cambios)
                        estadoElement.innerHTML = `<i class="fas fa-check-circle"></i> Conectado (${contadorActualizaciones})`;
                        estadoElement.className = 'status-badge status-connected';
                        console.log('ℹ️ No hay nuevas lecturas disponibles - fecha no es más reciente');
                    }

                    // Lógica para mensajes de estado
                    const limiteConsumo = (typeof data.limite_consumo !== 'undefined' && data.limite_consumo !== null)
                        ? Number(data.limite_consumo)
                        : (Number(document.getElementById('limite_consumo')?.value) || 10);

                    if (nuevaLectura.voltaje < 1 && nuevaLectura.corriente < 0.1) {
                        mostrarMensaje('error', '<i class="fas fa-ban me-2"></i>SIN ENERGÍA EN EL SISTEMA → Voltaje crítico, no hay consumo.');
                    } else if (nuevaLectura.potencia < 1) {
                        mostrarMensaje('info', '<i class="fas fa-times-circle me-2"></i>NO HAY CONSUMO EN EL SISTEMA (0V, 0A, 0W, 0kWh).');
                    } else if (nuevaLectura.voltaje < 200) {
                        mostrarMensaje('alerta', '<i class="fas fa-exclamation-triangle me-2"></i>Voltaje bajo detectado, verificar conexión eléctrica.');
                    } else if (Number(nuevaLectura.kwh_acumulado) > limiteConsumo) {
                        mostrarMensaje('alerta', `<i class="fas fa-exclamation-triangle me-2"></i>Límite de consumo superado (${Number(nuevaLectura.kwh_acumulado).toFixed(2)} kWh > ${limiteConsumo} kWh). Línea NO esencial desconectada.`);
                    } else {
                        mostrarMensaje('ok', '<i class="fas fa-check-circle me-2"></i>Consumo dentro del límite.');
                    }
 
                } else {
                    throw new Error('Respuesta del servidor no exitosa');
                }
            })
            .catch(error => {
                console.error('Error al obtener datos en tiempo real:', error);
                // Mostrar estado de error
                estadoElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error';
                estadoElement.className = 'status-badge status-error';
                mostrarMensaje('error', '<i class="fas fa-wifi me-2"></i>Error al conectar con el servidor. Verificando conexión...');
            });
    }

    // Variables para tracking de actualizaciones
    let contadorActualizaciones = 0;
    let ultimaActualizacion = new Date();
    
    // Inicializar todo
    actualizarValoresActuales();
    actualizarTabla();
    
    // Mostrar mensaje inicial
    mostrarMensaje('info', '<i class="fas fa-sync-alt fa-spin me-2"></i>Sistema iniciado. Conectando con el dispositivo...');
    
    // Configurar actualización automática
    console.log('Iniciando actualización automática cada 5 segundos...');
    let intervaloActualizacion = setInterval(obtenerDatosTiempoReal, 5000);
    
    // Primera actualización después de 1 segundo
    setTimeout(obtenerDatosTiempoReal, 1000);
    
    // Función para reiniciar el intervalo si se detiene
    function reiniciarActualizacion() {
        if (intervaloActualizacion) {
            clearInterval(intervaloActualizacion);
        }
        console.log('🔄 Reiniciando actualización automática...');
        intervaloActualizacion = setInterval(obtenerDatosTiempoReal, 5000);
    }
    
    // Verificar que el intervalo esté funcionando cada 30 segundos
    setInterval(() => {
        if (!intervaloActualizacion) {
            console.log('⚠️ Intervalo de actualización perdido, reiniciando...');
            reiniciarActualizacion();
        }
    }, 30000);

    // Listeners de controles del gráfico
    const selMetrica = document.getElementById('seleccionMetrica');
    const selRango = document.getElementById('seleccionRango');
    if (selMetrica) selMetrica.addEventListener('change', actualizarGrafico);
    if (selRango) selRango.addEventListener('change', actualizarGrafico);

    // (Se eliminó carga de totales mensuales)
    
    // Limpiar intervalo al cerrar la página
    window.addEventListener('beforeunload', function() {
        clearInterval(intervaloActualizacion);
    });
    
    <?php endif; ?>

    // Guardado AJAX de dispositivo
    const formEditar = document.getElementById('formEditarDispositivo');
    if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            const msg = document.getElementById('msgEditarDispositivo');
            const btn = document.getElementById('btnGuardarDispositivo');
            msg.innerHTML = '';
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';

            const formData = new FormData(formEditar);
            fetch(formEditar.action, {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    msg.innerHTML = '<span class="text-success">Dispositivo actualizado</span>';
                } else {
                    msg.innerHTML = '<span class="text-danger">' + (res.error || 'Error al actualizar') + '</span>';
                }
            })
            .catch(err => {
                msg.innerHTML = '<span class="text-danger">Error: ' + err + '</span>';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar';
            });
        });
    }
});

</script>
<!-- 🚀 SCRIPT PARA CALCULAR COSTO DE ENERGÍA -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formKwh = document.getElementById('formKwh');
    const resultado = document.getElementById('resultadoCosto');
    const inputValorKwh = document.getElementById('inputKwh'); // ahora apunta al input correcto

    if (formKwh) {
        // Actualiza tarifa y link del PDF al escribir, sin necesidad de enviar el form
        inputValorKwh.addEventListener('input', async function() {
            const valor = parseFloat(inputValorKwh.value);
            if (isNaN(valor)) return;
            try {
                await fetch('<?= base_url('energia/setTarifa') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ tarifa_kwh: valor })
                });
            } catch(e) { /* noop */ }
            const btnPdf = document.getElementById('btnPdf');
            if (btnPdf) {
                const url = new URL(btnPdf.href, window.location.origin);
                url.searchParams.set('tarifa', valor.toString());
                btnPdf.href = url.toString();
            }
        });

        formKwh.addEventListener('submit', async function(e) {
            e.preventDefault();

            const valorKwhUnitario = parseFloat(inputValorKwh.value);
            if (isNaN(valorKwhUnitario)) {
                alert('Ingrese un valor válido para el kWh');
                return;
            }

            // Calcular total de kWh acumulados (última lectura)
            let totalKwh = 0;
            <?php if (!empty($lecturas)): ?>
                totalKwh = <?= end($lecturas)['kwh_acumulado'] ?? 0 ?>;
            <?php endif; ?>

            const costoTotal = (totalKwh * valorKwhUnitario).toFixed(2);

            document.getElementById('totalKwh').textContent = totalKwh.toFixed(2);
            document.getElementById('costoTotal').textContent = costoTotal;

            resultado.style.display = 'block';

            try {
                // Guardar tarifa en sesión para que el PDF la use
                await fetch('<?= base_url('energia/setTarifa') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ tarifa_kwh: valorKwhUnitario })
                });
                // Actualizar link del PDF para incluir tarifa explícita por si falla la sesión
                const btnPdf = document.getElementById('btnPdf');
                if (btnPdf) {
                    const url = new URL(btnPdf.href, window.location.origin);
                    url.searchParams.set('tarifa', valorKwhUnitario.toString());
                    btnPdf.href = url.toString();
                }
            } catch(err) {
                console.error('No se pudo guardar la tarifa en sesión', err);
            }
        });
    }
});
</script>
<!--  SCRIPT PARA GUARDAR LÍMITE DE CONSUMO Y FUNCIONALIDADES EN TIEMPO REAL -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formLimite');
    const msg = document.getElementById('msgLimite');
    const btnProbarEndpoint = document.getElementById('btnProbarEndpoint');
    const btnActualizarTiempoReal = document.getElementById('btnActualizarTiempoReal');
    const estadoLimite = document.getElementById('estadoLimite');
    const limiteActual = document.getElementById('limiteActual');
    const alertaEstadoLimite = document.getElementById('alertaEstadoLimite');
    const textoEstadoLimite = document.getElementById('textoEstadoLimite');

    // Función para actualizar el estado del límite
    function actualizarEstadoLimite() {
        const limiteConsumo = parseFloat(document.getElementById('limite_consumo').value) || 0;
        const consumoActual = parseFloat(document.getElementById('valorKwh').textContent) || 0;
        
        // Actualizar límite actual
        limiteActual.textContent = limiteConsumo.toFixed(3);
        
        // Determinar estado
        if (consumoActual > limiteConsumo) {
            alertaEstadoLimite.className = 'alert alert-danger';
            textoEstadoLimite.innerHTML = `<strong><i class="fas fa-exclamation-triangle me-2"></i>LÍMITE SUPERADO</strong><br>Consumo: ${consumoActual.toFixed(3)} kWh > Límite: ${limiteConsumo.toFixed(3)} kWh`;
            estadoLimite.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Límite Superado';
            estadoLimite.className = 'badge badge-danger mr-2';
        } else if (consumoActual > limiteConsumo * 0.8) {
            alertaEstadoLimite.className = 'alert alert-warning';
            textoEstadoLimite.innerHTML = `<strong><i class="fas fa-exclamation-circle me-2"></i>CERCANO AL LÍMITE</strong><br>Consumo: ${consumoActual.toFixed(3)} kWh (${((consumoActual/limiteConsumo)*100).toFixed(1)}% del límite)`;
            estadoLimite.innerHTML = '<i class="fas fa-exclamation-circle"></i> Cerca del Límite';
            estadoLimite.className = 'badge badge-warning mr-2';
        } else {
            alertaEstadoLimite.className = 'alert alert-success';
            textoEstadoLimite.innerHTML = `<strong><i class="fas fa-check-circle me-2"></i>DENTRO DEL LÍMITE</strong><br>Consumo: ${consumoActual.toFixed(3)} kWh (${((consumoActual/limiteConsumo)*100).toFixed(1)}% del límite)`;
            estadoLimite.innerHTML = '<i class="fas fa-check-circle"></i> Dentro del Límite';
            estadoLimite.className = 'badge badge-success mr-2';
        }
    }

    // Función para probar el endpoint
    function probarEndpoint() {
        if (btnProbarEndpoint) {
            btnProbarEndpoint.disabled = true;
            btnProbarEndpoint.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Probando...';
            
            fetch('<?= base_url('energia/getlimite') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        msg.innerHTML = `<div class="alert alert-success">
                            <strong><i class="fas fa-check-circle me-2"></i>Endpoint funcionando correctamente</strong><br>
                            Límite actual: ${data.limite_consumo} kWh<br>
                            Timestamp: ${data.timestamp}
                        </div>`;
                    } else {
                        msg.innerHTML = `<div class="alert alert-warning">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>Endpoint respondió con error</strong><br>
                            Error: ${data.error || 'Desconocido'}
                        </div>`;
                    }
                })
                .catch(error => {
                    msg.innerHTML = `<div class="alert alert-danger">
                        <strong><i class="fas fa-times-circle me-2"></i>Error al probar endpoint</strong><br>
                        Error: ${error.message}
                    </div>`;
                })
                .finally(() => {
                    btnProbarEndpoint.disabled = false;
                    btnProbarEndpoint.innerHTML = '<i class="fas fa-wifi me-1"></i> Probar Endpoint ESP32';
                });
        }
    }

    // Event listeners
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const data = {
    limite_consumo: document.getElementById('limite_consumo').value,
    email: document.getElementById('email').value,
    id_dispositivo: <?= $dispositivo['id_dispositivo'] ?>
};

           fetch("<?= base_url('energia/actualizarLimite') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    msg.innerHTML = `<div class="alert alert-success">${response.message}</div>`;
                    // Actualizar el estado después de guardar
                    setTimeout(actualizarEstadoLimite, 500);
                } else {
                    msg.innerHTML = `<div class="alert alert-danger">${response.error || 'Error desconocido'}</div>`;
                }
            })
            .catch(err => {
                msg.innerHTML = `<div class="alert alert-danger">Error al guardar configuración: ${err}</div>`;
            });
        });
    }

    if (btnProbarEndpoint) {
        btnProbarEndpoint.addEventListener('click', probarEndpoint);
    }

    if (btnActualizarTiempoReal) {
        btnActualizarTiempoReal.addEventListener('click', function() {
            // Simular actualización de datos en tiempo real
            if (typeof obtenerDatosTiempoReal === 'function') {
                obtenerDatosTiempoReal();
            }
            actualizarEstadoLimite();
        });
    }

    // Actualizar estado del límite cada vez que cambien los valores
    const inputLimite = document.getElementById('limite_consumo');
    if (inputLimite) {
        inputLimite.addEventListener('input', actualizarEstadoLimite);
    }

    // Actualizar estado inicial
    actualizarEstadoLimite();
    
    // Actualizar estado cada 10 segundos
    setInterval(actualizarEstadoLimite, 10000);
});

// Función para mostrar/ocultar información técnica
function toggleInfoTecnica() {
    const contenido = document.getElementById('contenidoTecnico');
    const boton = event.target;
    
    if (contenido.style.display === 'none') {
        contenido.style.display = 'block';
        boton.innerHTML = '<i class="fas fa-code me-1"></i> Ocultar Info Técnica';
    } else {
        contenido.style.display = 'none';
        boton.innerHTML = '<i class="fas fa-code me-1"></i> Ver Info Técnica';
    }
}

// Funcionalidad de filtros para lecturas
document.addEventListener('DOMContentLoaded', function() {
    const btnMostrarFiltros = document.getElementById('btnMostrarFiltros');
    const panelFiltros = document.getElementById('panelFiltros');
    const formFiltros = document.getElementById('formFiltros');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const loadingLecturas = document.getElementById('loadingLecturas');
    const contenidoLecturas = document.getElementById('contenidoLecturas');
    const paginacionLecturas = document.getElementById('paginacionLecturas');
    const btnPrevPage = document.getElementById('btnPrevPage');
    const btnNextPage = document.getElementById('btnNextPage');
    const paginaActual = document.getElementById('paginaActual');
    let currentPage = 1;

    // Mostrar/ocultar filtros
    if (btnMostrarFiltros) {
        btnMostrarFiltros.addEventListener('click', function() {
            if (panelFiltros.style.display === 'none') {
                panelFiltros.style.display = 'block';
                btnMostrarFiltros.innerHTML = '<i class="fas fa-filter me-1"></i> Ocultar Filtros';
            } else {
                panelFiltros.style.display = 'none';
                btnMostrarFiltros.innerHTML = '<i class="fas fa-filter me-1"></i> Filtros';
            }
        });
    }

    // Aplicar filtros
    if (formFiltros) {
        formFiltros.addEventListener('submit', function(e) {
            e.preventDefault();
            aplicarFiltros();
        });
    }

    // Limpiar filtros
    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', function() {
            // Reset UI
            formFiltros.reset();
            document.getElementById('filtroLimite').value = '25';
            document.getElementById('filtroOrden').value = 'DESC';
            // Reset tabla y mensajes
            const tbody = document.querySelector('#tablaLecturas tbody');
            if (tbody) tbody.innerHTML = '';
            document.getElementById('wrapperTablaLecturas').style.display = 'none';
            document.getElementById('contadorLecturas').style.display = 'none';
            const existentes = contenidoLecturas.querySelectorAll('.alert');
            existentes.forEach(n => n.remove());
            // No mostrar mensaje al limpiar - dejar vacío
        });
    }

    function aplicarFiltros() {
        const formData = new FormData(formFiltros);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }
        params.set('page', String(currentPage));
        
        // Mostrar loading
        loadingLecturas.style.display = 'block';
        contenidoLecturas.style.display = 'none';
        
        // Llamada AJAX real al endpoint
        fetch(`<?= base_url('energia/filtrarLecturas/' . $dispositivo['id_dispositivo']) ?>?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                loadingLecturas.style.display = 'none';
                contenidoLecturas.style.display = 'block';
                
                if (data.success) {
                    actualizarTablaLecturas(data.lecturas, data.total);
                    document.getElementById('wrapperTablaLecturas').style.display = '';
                    const cont = document.querySelector('#contadorLecturas small.text-muted');
                    if (cont) {
                        cont.textContent = `Mostrando ${data.total} lecturas`;
                        document.getElementById('contadorLecturas').style.display = '';
                    }
                    // paginación
                    if (paginaActual) {
                        paginaActual.textContent = `${data.page} / ${data.pages}`;
                        paginacionLecturas.style.display = (data.pages > 1) ? '' : 'none';
                        if (btnPrevPage) btnPrevPage.disabled = data.page <= 1;
                        if (btnNextPage) btnNextPage.disabled = data.page >= data.pages;
                    }
                } else {
                    throw new Error(data.error || 'Error al filtrar lecturas');
                }
            })
            .catch(error => {
                loadingLecturas.style.display = 'none';
                contenidoLecturas.style.display = 'block';
                
                const mensaje = document.createElement('div');
                mensaje.className = 'alert alert-danger';
                mensaje.innerHTML = `Error al aplicar filtros: ${error.message}`;
                contenidoLecturas.insertBefore(mensaje, contenidoLecturas.firstChild);
                
                setTimeout(() => {
                    if (mensaje.parentNode) {
                        mensaje.parentNode.removeChild(mensaje);
                    }
                }, 5000);
            });
    }

    // Eventos paginación
    if (btnPrevPage) btnPrevPage.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage -= 1;
            aplicarFiltros();
        }
    });
    if (btnNextPage) btnNextPage.addEventListener('click', function() {
        currentPage += 1;
        aplicarFiltros();
    });

    function actualizarTablaLecturas(lecturas, total) {
        const tbody = document.querySelector('#tablaLecturas tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        lecturas.forEach(lectura => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${lectura.fecha}</td>
                <td>${lectura.voltaje}</td>
                <td>${lectura.corriente}</td>
                <td>${lectura.potencia}</td>
                <td>${lectura.kwh_acumulado}</td>
            `;
            tbody.appendChild(row);
        });
        
        // Actualizar contador
        const contador = document.querySelector('.text-muted');
        if (contador) {
            contador.textContent = `Mostrando ${total} lecturas`;
        }
    }
});

// Función para descargar PDF
function descargarPDF() {
    const url = '<?= base_url('energia/generarPDF/'.$dispositivo['id_dispositivo']) ?>';
    
    // Crear un enlace temporal para forzar la descarga
    const link = document.createElement('a');
    link.href = url;
    link.download = 'Informe_EcoVolt_' + new Date().toISOString().slice(0,10) + '.pdf';
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Función para forzar actualización manual
function forzarActualizacion() {
    console.log('🔄 Forzando actualización manual...');
    
    const estadoElement = document.getElementById('estadoActualizacion');
    estadoElement.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> Actualizando...';
    estadoElement.className = 'status-badge status-updating';
    
    // Llamar directamente a la función de actualización
    obtenerDatosTiempoReal();
}

// Función de debugging para tiempo real
function debugTiempoReal() {
    console.log('🔍 INICIANDO DEBUG DE TIEMPO REAL');
    console.log('================================');
    
    console.log('📊 Estado actual:');
    console.log('- Total de lecturas:', lecturas.length);
    console.log('- Última lectura:', lecturas[0]);
    console.log('- Gráfico definido:', typeof graficoConsumo !== 'undefined');
    
    console.log('🔄 Probando endpoint...');
    fetch(`<?= base_url('energia/getLatestDataByDevice/' . $dispositivo['id_dispositivo']) ?>`)
        .then(response => {
            console.log('📡 Respuesta HTTP:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('📦 Datos recibidos:', data);
            
            if (data.success && data.data) {
                const nuevaLectura = data.data;
                console.log('📈 Nueva lectura:', nuevaLectura);
                
                // Verificar si es realmente nueva
                const ultimaExistente = lecturas[0];
                const esNueva = !ultimaExistente || new Date(nuevaLectura.fecha) > new Date(ultimaExistente.fecha);
                console.log('🆕 ¿Es nueva lectura?', esNueva);
                
                if (esNueva) {
                    console.log('✅ Agregando nueva lectura al gráfico...');
                    lecturas.unshift(nuevaLectura);
                    if (lecturas.length > 50) {
                        lecturas = lecturas.slice(0, 50);
                    }
                    
                    actualizarValoresActuales();
                    actualizarGrafico();
                    
                    console.log('📊 Gráfico actualizado. Total lecturas:', lecturas.length);
                } else {
                    console.log('ℹ️ No hay lecturas nuevas');
                }
            } else {
                console.error('❌ Error en la respuesta:', data);
            }
        })
        .catch(error => {
            console.error('❌ Error en la petición:', error);
        });
    
    // Mostrar información del gráfico
    if (typeof graficoConsumo !== 'undefined') {
        console.log('📊 Estado del gráfico:');
        console.log('- Labels:', graficoConsumo.data.labels.length);
        console.log('- Datos:', graficoConsumo.data.datasets[0].data.length);
        console.log('- Últimos 3 labels:', graficoConsumo.data.labels.slice(-3));
        console.log('- Últimos 3 valores:', graficoConsumo.data.datasets[0].data.slice(-3));
    }
    
    alert('Debug completado. Revisa la consola (F12) para ver los detalles.');
}
</script>

<!-- Se eliminó la suscripción a notificaciones push -->

<!-- MODAL DE ALERTA DE CORTE DE LÍNEA ESENCIAL -->
<div class="modal fade" id="modalCorteEsencial" tabindex="-1" aria-labelledby="modalCorteEsencialLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border: 2px solid #dc3545; border-radius: 20px; box-shadow: 0 20px 60px rgba(220, 53, 69, 0.4);">
            <div class="modal-header" style="border-bottom: 2px solid #dc3545; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 18px 18px 0 0;">
                <div class="d-flex align-items-center w-100">
                    <div class="me-3" style="font-size: 3rem; animation: pulse 1.5s infinite;">
                        ⚡🚨
                    </div>
                    <div>
                        <h4 class="modal-title text-white fw-bold mb-0" id="modalCorteEsencialLabel" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                            ¡LÍNEA NO ESENCIAL CORTADA!
                        </h4>
                        <small class="text-white-50">Alerta de consumo excesivo</small>
                    </div>
                </div>
            </div>
            <div class="modal-body text-center py-4" style="background: rgba(255, 255, 255, 0.05);">
                <div class="alert alert-danger border-0 mb-4" style="background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(200, 35, 51, 0.2) 100%); border-radius: 15px; border-left: 5px solid #dc3545;">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-exclamation-triangle text-danger me-3" style="font-size: 2.5rem; animation: shake 0.5s infinite alternate;"></i>
                        <h5 class="text-danger mb-0 fw-bold">LÍMITE DE CONSUMO SUPERADO</h5>
                    </div>
                    <p class="text-white mb-0 fs-5">
                        El sistema ha detectado que el consumo de energía ha superado el límite configurado y se ha cortado la línea NO esencial por seguridad. La línea esencial permanece activa.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-warning" style="background: rgba(255, 193, 7, 0.1); border-radius: 15px;">
                            <div class="card-body text-center">
                                <i class="fas fa-bolt text-warning mb-2" style="font-size: 2rem;"></i>
                                <h6 class="text-warning fw-bold">Consumo Actual</h6>
                                <h4 class="text-white mb-0" id="consumoActualModal">-- kWh</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-info" style="background: rgba(13, 202, 240, 0.1); border-radius: 15px;">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line text-info mb-2" style="font-size: 2rem;"></i>
                                <h6 class="text-info fw-bold">Límite Configurado</h6>
                                <h4 class="text-white mb-0" id="limiteConfiguradoModal">-- kWh</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0" style="background: linear-gradient(135deg, rgba(13, 202, 240, 0.2) 0%, rgba(6, 182, 212, 0.2) 100%); border-radius: 15px; border-left: 5px solid #0dcaf0;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle text-info me-3 mt-1" style="font-size: 1.5rem;"></i>
                        <div class="text-start">
                            <h6 class="text-info fw-bold mb-2">¿Cómo restablecer la línea NO esencial?</h6>
                            <ol class="text-white mb-0" style="padding-left: 1.2rem;">
                                <li class="mb-2">Ve a la sección <strong>"Configurar Límites"</strong> en el panel de control</li>
                                <li class="mb-2">Establece un límite de consumo <strong>más alto</strong> que el consumo actual</li>
                                <li class="mb-0">El sistema restablecerá automáticamente la línea NO esencial</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-primary btn-lg me-3" onclick="irAConfigurarLimites()" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); border: none; border-radius: 25px; padding: 12px 30px; font-weight: 600; box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);">
                        <i class="fas fa-cog me-2"></i>Ir a Configurar Límites
                    </button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); border: none; border-radius: 25px; padding: 12px 30px; font-weight: 600;">
                        <i class="fas fa-times me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes shake {
    0% { transform: translateX(0); }
    100% { transform: translateX(5px); }
}

.modal.show .modal-dialog {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-content {
    animation: modalGlow 2s infinite alternate;
}

@keyframes modalGlow {
    from {
        box-shadow: 0 20px 60px rgba(220, 53, 69, 0.4);
    }
    to {
        box-shadow: 0 20px 60px rgba(220, 53, 69, 0.6);
    }
}
</style>

<script>
// Función para mostrar el modal de corte de línea esencial
function mostrarModalCorteEsencial(consumoActual, limiteConfigurado) {
    // Actualizar los valores en el modal
    document.getElementById('consumoActualModal').textContent = consumoActual + ' kWh';
    document.getElementById('limiteConfiguradoModal').textContent = limiteConfigurado + ' kWh';
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalCorteEsencial'));
    modal.show();
    
    // Reproducir sonido de alerta (opcional)
    try {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
        audio.volume = 0.3;
        audio.play().catch(() => {}); // Ignorar errores si no se puede reproducir
    } catch (e) {
        // Ignorar errores de audio
    }
}

// Función para ir a configurar límites
function irAConfigurarLimites() {
    // Cerrar el modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCorteEsencial'));
    modal.hide();
    
    // Redirigir a la sección de configuración de límites
    window.location.href = '#configurar-limites';
    
    // Scroll suave a la sección
    setTimeout(() => {
        const seccion = document.querySelector('#configurar-limites');
        if (seccion) {
            seccion.scrollIntoView({ behavior: 'smooth' });
        }
    }, 300);
}

// Función para verificar si se debe mostrar el modal (se llamará desde el polling de datos)
function verificarCorteLineaNoEsencial(ultimaLectura) {
    if (ultimaLectura && ultimaLectura.limite_superado == 1) {
        // Obtener el límite de consumo desde la respuesta del servidor
        fetch(`<?= base_url('energia/getLatestDataByDevice/' . $dispositivo['id_dispositivo']) ?>`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const limiteConsumo = data.limite_consumo || 10;
                    const consumoActual = parseFloat(ultimaLectura.kwh_acumulado);
                    
                    // Verificar si el consumo supera el límite (corte de línea NO esencial)
                    if (consumoActual > limiteConsumo) {
                        // Verificar si ya se mostró el modal para evitar spam
                        const modalYaMostrado = sessionStorage.getItem('modalCorteLineaNoEsencialMostrado');
                        const timestampActual = new Date().getTime();
                        
                        if (!modalYaMostrado || (timestampActual - parseInt(modalYaMostrado)) > 300000) { // 5 minutos
                            mostrarModalCorteEsencial(
                                consumoActual.toFixed(2),
                                parseFloat(limiteConsumo).toFixed(2)
                            );
                            
                            // Marcar que se mostró el modal
                            sessionStorage.setItem('modalCorteLineaNoEsencialMostrado', timestampActual.toString());
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error obteniendo límite de consumo:', error);
            });
    }
}
</script>

<?= $this->endSection() ?>
