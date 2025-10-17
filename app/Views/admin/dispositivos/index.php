<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<style>
    /* === PALETA DE COLORES PREMIUM === */
    /* Variables CSS personalizadas para mantener consistencia visual */
    :root {
        --gold: #D4AF37;                    /* Color dorado principal */
        --gold-light: #EAD58B;              /* Dorado claro para texto */
        --black-bg: #121212;                /* Fondo negro principal */
        --card-bg: #1B1B1E;                 /* Fondo de tarjetas (más oscuro) */
        --card-border: rgba(255, 255, 255, 0.1); /* Bordes sutiles */
        --text-primary: #FFFFFF;            /* Texto principal (blanco) */
        --text-secondary: #E0E0E0;          /* Texto secundario (gris claro) */
        --glow-color: rgba(212, 175, 55, 0.3); /* Color de resplandor dorado */
        --danger: #dc3545;                  /* Color de peligro (rojo) */
        --border-radius: 20px;              /* Radio de bordes redondeados */
    }

    /* === IMPORTACIÓN DE FUENTE Y ESTILOS BASE === */
    /* Fuente Google Fonts para un diseño más profesional */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    
    /* Estilos base del cuerpo de la página */
    body {
        background-color: var(--black-bg);           /* Fondo negro */
        color: var(--text-primary);                  /* Texto blanco */
        font-family: 'Poppins', sans-serif;          /* Fuente personalizada */
        background-image: radial-gradient(circle at top, rgba(50, 50, 50, 0.2), transparent 40%); /* Gradiente sutil */
    }

    /* === ANIMACIÓN DE ENTRADA === */
    /* Animación suave para elementos que aparecen */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); } /* Inicia invisible y abajo */
        to { opacity: 1; transform: translateY(0); }      /* Termina visible y en posición */
    }

    /* === CONTENEDOR PRINCIPAL CON FONDO SÓLIDO === */
    /* Tarjeta principal con efectos premium */
    .premium-card {
        background: var(--card-bg);                    /* Fondo oscuro sólido */
        border: 1px solid var(--card-border);          /* Borde sutil */
        border-radius: var(--border-radius);           /* Bordes redondeados */
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37); /* Sombra profunda */
        animation: fadeIn 0.8s ease-out forwards;      /* Animación de entrada */
    }
    
    /* Encabezado de la tarjeta */
    .premium-card-header {
        padding: 1.5rem 2rem;                          /* Espaciado interno */
        border-bottom: 1px solid var(--card-border);   /* Línea separadora */
        font-size: 1.2rem;                             /* Tamaño de fuente */
        font-weight: 600;                              /* Peso de fuente */
        color: var(--gold-light);                      /* Color dorado claro */
    }
    
    /* Icono en el encabezado */
    .premium-card-header i { margin-right: 0.75rem; }
    
    /* Cuerpo de la tarjeta */
    .premium-card-body { padding: 2rem; }
    
    /* === ENCABEZADO DE PÁGINA === */
    .page-header { color: var(--text-primary); }       /* Color del título */
    .page-header i { color: var(--gold); }             /* Color del icono */

    /* === ALERTAS PREMIUM === */
    /* Estilos para mensajes de éxito, error e información */
    .premium-alert {
        border-radius: 12px;                           /* Bordes redondeados */
        border-width: 1px;                             /* Grosor del borde */
        border-style: solid;                           /* Estilo del borde */
        padding: 1rem 1.5rem;                         /* Espaciado interno */
    }
    
    /* Alerta de éxito (verde) */
    .premium-alert.alert-success { 
        background-color: rgba(40, 167, 69, 0.1);     /* Fondo verde transparente */
        border-color: #28a745;                         /* Borde verde */
        color: #e8f5e9;                               /* Texto verde claro */
    }
    
    /* Alerta de peligro (rojo) */
    .premium-alert.alert-danger { 
        background-color: rgba(220, 53, 69, 0.1);     /* Fondo rojo transparente */
        border-color: var(--danger);                   /* Borde rojo */
        color: #f8d7da;                               /* Texto rojo claro */
    }
    
    /* Alerta de información (azul) */
    .premium-alert.alert-info { 
        background-color: rgba(23, 162, 184, 0.1);    /* Fondo azul transparente */
        border-color: #17a2b8;                         /* Borde azul */
        color: #d1ecf1;                               /* Texto azul claro */
    }
    
    /* Botón de cerrar alerta */
    .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

    /* === TABLA PREMIUM === */
    /* Estilos para la tabla de dispositivos */
    .premium-table { 
        color: var(--text-primary);                     /* Color del texto */
        border-collapse: separate;                      /* Separar bordes */
        border-spacing: 0;                             /* Sin espaciado entre celdas */
    }
    
    /* Encabezados de la tabla */
    .premium-table thead th {
        border: none;                                  /* Sin bordes */
        color: var(--gold-light);                      /* Color dorado claro */
        font-weight: 600;                              /* Peso de fuente */
        text-transform: uppercase;                     /* Texto en mayúsculas */
        font-size: 0.85rem;                            /* Tamaño de fuente */
        padding: 1rem;                                 /* Espaciado interno */
        background-color: rgba(0,0,0,0.2);            /* Fondo semi-transparente */
    }
    
    /* Filas del cuerpo de la tabla */
    .premium-table tbody tr {
        transition: all 0.3s ease;                     /* Transición suave */
        border-bottom: 1px solid var(--card-border);   /* Línea separadora */
    }
    
    /* Última fila sin borde inferior */
    .premium-table tbody tr:last-child { border-bottom: none; }
    
    /* Celdas del cuerpo */
    .premium-table tbody td { 
        padding: 1rem;                                 /* Espaciado interno */
        vertical-align: middle;                        /* Alineación vertical */
        border: none;                                  /* Sin bordes */
        font-size: 0.95rem;                            /* Tamaño de fuente */
    }
    
    /* Efecto hover en filas con elevación */
    .premium-table tbody tr:hover { 
        background-color: rgba(255,255,255,0.08);     /* Fondo semi-transparente */
        transform: translateY(-1px);                   /* Elevación sutil */
    }

    /* === BOTONES DE ACCIÓN SIEMPRE VISIBLES === */
    /* Contenedor para los botones de acción de cada dispositivo */
    .action-buttons {
        display: flex;                                  /* Layout flexbox */
        flex-wrap: wrap;                               /* Permitir salto de línea */
        gap: 0.4rem;                                   /* Espaciado entre botones */
        justify-content: center;                       /* Centrar horizontalmente */
        align-items: center;                           /* Centrar verticalmente */
        min-height: 44px;                             /* Altura mínima para accesibilidad */
    }

    /* === BOTONES PREMIUM CON ANIMACIONES === */
    /* Estilos base para todos los botones de acción */
    .btn-action {
        background: transparent;                       /* Fondo transparente */
        border: 1px solid var(--card-border);          /* Borde sutil */
        border-radius: 8px;                           /* Bordes redondeados */
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); /* Transición suave */
        width: 40px;                                  /* Ancho fijo */
        height: 40px;                                 /* Alto fijo */
        display: flex;                                /* Layout flexbox */
        align-items: center;                          /* Centrar verticalmente */
        justify-content: center;                      /* Centrar horizontalmente */
        font-size: 0.9rem;                           /* Tamaño de fuente */
        flex-shrink: 0;                              /* No encoger */
        position: relative;                           /* Posición relativa */
        overflow: hidden;                            /* Ocultar desbordamiento */
    }

    /* === EFECTOS DE PULSO SUAVE === */
    /* Efecto de pulso suave en estado normal */
    .btn-action::before {
        content: '';                                   /* Contenido vacío */
        position: absolute;                            /* Posición absoluta */
        top: 0;                                       /* Desde arriba */
        left: 0;                                      /* Desde la izquierda */
        right: 0;                                     /* Hasta la derecha */
        bottom: 0;                                    /* Hasta abajo */
        background: currentColor;                      /* Color actual del texto */
        opacity: 0;                                   /* Invisible por defecto */
        transition: opacity 0.3s ease;                /* Transición suave */
        border-radius: 6px;                           /* Bordes redondeados */
    }

    /* Mostrar efecto al hacer hover */
    .btn-action:hover::before {
        opacity: 0.1;                                 /* Semi-transparente */
    }

    /* === ANIMACIÓN DE BRILLO AL HOVER === */
    /* Efecto de elevación y escala al pasar el mouse */
    .btn-action:hover {
        transform: translateY(-2px) scale(1.05);      /* Elevar y agrandar */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);   /* Sombra */
    }

    /* === COLORES ESPECÍFICOS PARA CADA BOTÓN === */
    /* Botón de ver detalles */
    .btn-action.btn-view {
        color: var(--text-secondary);                  /* Color del texto */
        border-color: var(--card-border);              /* Color del borde */
    }
    .btn-action.btn-view:hover {
        color: var(--gold-light);                      /* Color dorado al hover */
        border-color: var(--gold);                     /* Borde dorado */
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3); /* Sombra dorada */
    }

    /* Botón de editar */
    .btn-action.btn-edit {
        color: var(--text-secondary);                  /* Color del texto */
        border-color: var(--card-border);              /* Color del borde */
    }
    .btn-action.btn-edit:hover {
        color: #17a2b8;                               /* Color azul al hover */
        border-color: #17a2b8;                        /* Borde azul */
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3); /* Sombra azul */
    }

    /* Botón de controlar foco */
    .btn-action.btn-control {
        color: #28a745;                               /* Color verde */
        border-color: #28a745;                        /* Borde verde */
    }
    .btn-action.btn-control:hover {
        color: #28a745;                               /* Mantener color verde */
        border-color: #28a745;                        /* Mantener borde verde */
        background-color: rgba(40, 167, 69, 0.1);     /* Fondo verde transparente */
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); /* Sombra verde */
    }

    /* Botón de activar dispositivo */
    .btn-action.btn-activate {
        color: #28a745;                               /* Color verde */
        border-color: #28a745;                        /* Borde verde */
    }
    .btn-action.btn-activate:hover {
        color: #28a745;                               /* Mantener color verde */
        border-color: #28a745;                        /* Mantener borde verde */
        background-color: rgba(40, 167, 69, 0.1);     /* Fondo verde transparente */
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); /* Sombra verde */
        animation: pulse-green 0.6s ease-in-out;      /* Animación de pulso verde */
    }

    /* Botón de desactivar dispositivo */
    .btn-action.btn-deactivate {
        color: #ffc107;                               /* Color amarillo */
        border-color: #ffc107;                        /* Borde amarillo */
    }
    .btn-action.btn-deactivate:hover {
        color: #ffc107;                               /* Mantener color amarillo */
        border-color: #ffc107;                        /* Mantener borde amarillo */
        background-color: rgba(255, 193, 7, 0.1);     /* Fondo amarillo transparente */
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3); /* Sombra amarilla */
        animation: pulse-yellow 0.6s ease-in-out;     /* Animación de pulso amarillo */
    }

    /* Botón de eliminar dispositivo */
    .btn-action.btn-delete {
        color: var(--danger);                         /* Color rojo */
        border-color: var(--danger);                  /* Borde rojo */
    }
    .btn-action.btn-delete:hover {
        color: var(--danger);                         /* Mantener color rojo */
        border-color: var(--danger);                  /* Mantener borde rojo */
        background-color: rgba(220, 53, 69, 0.1);     /* Fondo rojo transparente */
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); /* Sombra roja */
        animation: pulse-red 0.6s ease-in-out;        /* Animación de pulso rojo */
    }

    /* Botón de ver gráficos */
    .btn-action.btn-charts {
        color: #6f42c1;                               /* Color morado */
        border-color: #6f42c1;                        /* Borde morado */
    }
    .btn-action.btn-charts:hover {
        color: #6f42c1;                               /* Mantener color morado */
        border-color: #6f42c1;                        /* Mantener borde morado */
        background-color: rgba(111, 66, 193, 0.1);     /* Fondo morado transparente */
        box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3); /* Sombra morada */
    }

    /* === ANIMACIONES DE PULSO === */
    /* Animación de pulso verde para botón activar */
    @keyframes pulse-green {
        0%, 100% { transform: translateY(-2px) scale(1.05); } /* Estado inicial y final */
        50% { transform: translateY(-2px) scale(1.1); }       /* Estado medio (más grande) */
    }

    /* Animación de pulso amarillo para botón desactivar */
    @keyframes pulse-yellow {
        0%, 100% { transform: translateY(-2px) scale(1.05); } /* Estado inicial y final */
        50% { transform: translateY(-2px) scale(1.1); }       /* Estado medio (más grande) */
    }

    /* Animación de pulso rojo para botón eliminar */
    @keyframes pulse-red {
        0%, 100% { transform: translateY(-2px) scale(1.05); } /* Estado inicial y final */
        50% { transform: translateY(-2px) scale(1.1); }       /* Estado medio (más grande) */
    }

    /* === EFECTO DE CARGA EN BOTONES DE ESTADO === */
    /* Efecto de ondas al hacer clic en botones de activar/desactivar */
    .btn-action.btn-activate:active::after,
    .btn-action.btn-deactivate:active::after {
        content: '';                                   /* Contenido vacío */
        position: absolute;                            /* Posición absoluta */
        top: 50%;                                     /* Centrar verticalmente */
        left: 50%;                                    /* Centrar horizontalmente */
        width: 0;                                     /* Ancho inicial */
        height: 0;                                    /* Alto inicial */
        background: rgba(255, 255, 255, 0.3);         /* Fondo blanco semi-transparente */
        border-radius: 50%;                           /* Forma circular */
        transform: translate(-50%, -50%);             /* Centrar el elemento */
        animation: ripple 0.6s ease-out;              /* Animación de ondas */
    }

    /* Animación de ondas que se expanden */
    @keyframes ripple {
        to {
            width: 100%;                              /* Ancho final */
            height: 100%;                             /* Alto final */
            opacity: 0;                               /* Desvanecer */
        }
    }

    /* === BADGES PREMIUM === */
    /* Badge de estado exitoso (verde) */
    .badge-success-premium {
        background-color: rgba(40, 167, 69, 0.2);     /* Fondo verde transparente */
        color: #28a745;                               /* Texto verde */
        border: 1px solid #28a745;                    /* Borde verde */
        padding: 0.5rem 0.75rem;                     /* Espaciado interno */
        border-radius: 6px;                          /* Bordes redondeados */
        font-size: 0.8rem;                           /* Tamaño de fuente */
        transition: all 0.3s ease;                   /* Transición suave */
    }

    /* Badge de estado de advertencia (amarillo) */
    .badge-warning-premium {
        background-color: rgba(255, 193, 7, 0.2);     /* Fondo amarillo transparente */
        color: #ffc107;                              /* Texto amarillo */
        border: 1px solid #ffc107;                   /* Borde amarillo */
        padding: 0.5rem 0.75rem;                    /* Espaciado interno */
        border-radius: 6px;                         /* Bordes redondeados */
        font-size: 0.8rem;                          /* Tamaño de fuente */
        transition: all 0.3s ease;                  /* Transición suave */
    }

    /* Badge de estado de peligro (rojo) */
    .badge-danger-premium {
        background-color: rgba(220, 53, 69, 0.2);     /* Fondo rojo transparente */
        color: var(--danger);                        /* Texto rojo */
        border: 1px solid var(--danger);             /* Borde rojo */
        padding: 0.5rem 0.75rem;                    /* Espaciado interno */
        border-radius: 6px;                         /* Bordes redondeados */
        font-size: 0.8rem;                          /* Tamaño de fuente */
        transition: all 0.3s ease;                  /* Transición suave */
    }

    /* === EFECTOS EN BADGES AL HOVER DE LA FILA === */
    /* Efecto en badge verde al hacer hover en la fila */
    .premium-table tbody tr:hover .badge-success-premium {
        background-color: rgba(40, 167, 69, 0.3);     /* Fondo más intenso */
        transform: scale(1.05);                       /* Agrandar ligeramente */
    }

    /* Efecto en badge amarillo al hacer hover en la fila */
    .premium-table tbody tr:hover .badge-warning-premium {
        background-color: rgba(255, 193, 7, 0.3);     /* Fondo más intenso */
        transform: scale(1.05);                       /* Agrandar ligeramente */
    }

    /* Efecto en badge rojo al hacer hover en la fila */
    .premium-table tbody tr:hover .badge-danger-premium {
        background-color: rgba(220, 53, 69, 0.3);     /* Fondo más intenso */
        transform: scale(1.05);                       /* Agrandar ligeramente */
    }

    /* === MODAL OSCURO === */
    /* Estilos para modales con tema oscuro */
    .modal-content {
        background-color: #2a2a2e;                     /* Fondo oscuro */
        border: 1px solid var(--card-border);          /* Borde sutil */
        border-radius: var(--border-radius);           /* Bordes redondeados */
        color: var(--text-primary);                    /* Color del texto */
    }
    
    /* Encabezado del modal */
    .modal-header { 
        border-bottom: 1px solid var(--card-border);   /* Línea separadora */
        color: var(--gold);                            /* Color dorado */
    }
    
    /* Pie del modal */
    .modal-footer { 
        border-top: 1px solid var(--card-border);      /* Línea separadora */
    }

    /* === BOTÓN PRINCIPAL === */
    /* Botón dorado principal */
    .btn-gold {
        background: var(--gold);                       /* Fondo dorado */
        color: var(--black-bg);                        /* Texto negro */
        border: none;                                  /* Sin borde */
        border-radius: 10px;                           /* Bordes redondeados */
        padding: 0.75rem 1.5rem;                      /* Espaciado interno */
        font-weight: 600;                              /* Peso de fuente */
        box-shadow: 0 4px 20px var(--glow-color);     /* Sombra dorada */
        transition: all 0.3s ease;                     /* Transición suave */
    }
    
    /* Efecto hover del botón dorado */
    .btn-gold:hover { 
        transform: translateY(-2px);                   /* Elevación */
        box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5); /* Sombra más intensa */
        background-color: var(--gold-light);           /* Fondo dorado claro */
        color: var(--black-bg);                        /* Mantener texto negro */
    }

    /* === TOOLTIPS MEJORADOS === */
    /* Posición relativa para tooltips */
    .btn-action {
        position: relative;
    }

    /* Tooltip personalizado que aparece al hacer hover */
    .btn-action::after {
        content: attr(title);                          /* Contenido del atributo title */
        position: absolute;                            /* Posición absoluta */
        bottom: -40px;                                /* Posición debajo del botón */
        left: 50%;                                    /* Centrar horizontalmente */
        transform: translateX(-50%);                   /* Centrar el elemento */
        background: rgba(0, 0, 0, 0.9);               /* Fondo negro semi-transparente */
        color: white;                                 /* Texto blanco */
        padding: 6px 12px;                            /* Espaciado interno */
        border-radius: 6px;                           /* Bordes redondeados */
        font-size: 0.75rem;                           /* Tamaño de fuente pequeño */
        white-space: nowrap;                          /* No saltar línea */
        opacity: 0;                                   /* Invisible por defecto */
        transition: opacity 0.3s;                     /* Transición suave */
        pointer-events: none;                         /* No interferir con clics */
        z-index: 1000;                                /* Capa superior */
        border: 1px solid var(--card-border);          /* Borde sutil */
    }

    /* Mostrar tooltip al hacer hover */
    .btn-action:hover::after {
        opacity: 1;                                   /* Visible */
    }

    /* === RESPONSIVE PARA MÓVILES === */
    /* Estilos para pantallas pequeñas (móviles) */
    @media (max-width: 768px) {
        /* Ocultar columna de admin dueño en móviles */
        .premium-table thead th:nth-child(4),
        .premium-table tbody td:nth-child(4) {
            display: none;
        }
        
        /* Ajustar botones de acción para móviles */
        .action-buttons {
            justify-content: flex-start;               /* Alinear a la izquierda */
        }
        
        /* Reducir tamaño de botones en móviles */
        .btn-action {
            width: 36px;                              /* Ancho más pequeño */
            height: 36px;                             /* Alto más pequeño */
            font-size: 0.8rem;                        /* Fuente más pequeña */
        }
        
        /* Efecto hover más sutil en móviles */
        .btn-action:hover {
            transform: translateY(-1px) scale(1.03);   /* Elevación y escala menores */
        }
    }
</style>

<!-- === CONTENIDO PRINCIPAL === -->
<div class="container-fluid px-4 my-5">
    <!-- Título de la página con icono -->
    <h1 class="h3 mb-4 page-header">
        <i class="fas fa-microchip"></i> Gestión de Dispositivos
    </h1>
    
    <!-- Tarjeta principal que contiene la lista de dispositivos -->
    <div class="premium-card">
        <!-- Encabezado de la tarjeta con botón de búsqueda -->
        <div class="premium-card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-microchip me-1"></i>
                Dispositivos Registrados
            </div>
            <div>
                <!-- Botón para buscar dispositivos -->
                <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-gold">
                    <i class="fas fa-search me-1"></i> Buscar Dispositivos
                </a>
            </div>
        </div>
        
        <!-- Cuerpo de la tarjeta -->
        <div class="premium-card-body">
            <!-- === MENSAJES DE ÉXITO === -->
            <!-- Muestra mensajes de éxito si existen en la sesión -->
            <?php if (session()->has('success')): ?>
                <div class="premium-alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- === MENSAJES DE ERROR === -->
            <!-- Muestra mensajes de error si existen en la sesión -->
            <?php if (session()->has('error')): ?>
                <div class="premium-alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- === TABLA DE DISPOSITIVOS === -->
            <div class="table-responsive">
                <table class="table premium-table">
                    <!-- Encabezados de la tabla -->
                    <thead>
                        <tr>
                            <th>Nombre</th>                    <!-- Nombre del dispositivo -->
                            <th>Descripción</th>               <!-- Descripción del dispositivo -->
                            <th>MAC Address</th>               <!-- Dirección MAC -->
                            <th>Admin Dueño</th>               <!-- Administrador propietario -->
                            <th>Estado</th>                    <!-- Estado del dispositivo -->
                            <th>Última Actualización</th>      <!-- Fecha de última actualización -->
                            <th style="width: 280px;">Acciones</th> <!-- Botones de acción -->
                        </tr>
                    </thead>
                    
                    <!-- Cuerpo de la tabla con datos de dispositivos -->
                    <tbody>
                        <?php if (empty($dispositivos)): ?>
                            <!-- Mensaje cuando no hay dispositivos -->
                            <tr>
                                <td colspan="7" class="text-center">No hay dispositivos registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dispositivos as $dispositivo): ?>
                                <tr>
                                    <!-- Nombre del dispositivo -->
                                    <td><?= esc($dispositivo['nombre']) ?></td>
                                    
                                    <!-- Descripción del dispositivo -->
                                    <td><?= esc($dispositivo['descripcion'] ?? '') ?></td>
                                    
                                    <!-- Dirección MAC -->
                                    <td><?= esc($dispositivo['mac_address']) ?></td>
                                    
                                    <!-- Información del administrador propietario -->
                                    <td>
                                        <?= esc($dispositivo['nombre_admin'] ?? '-') ?><br>
                                        <small class="text-muted"><?= esc($dispositivo['email_admin'] ?? '-') ?></small>
                                    </td>
                                    
                                    <!-- === ESTADO DEL DISPOSITIVO === -->
                                    <td>
                                        <?php
                                        // Determinar clase CSS según el estado
                                        $estadoClass = '';
                                        switch ($dispositivo['estado']) {
                                            case 'activo':
                                                $estadoClass = 'badge-success-premium';    // Verde para activo
                                                break;
                                            case 'pendiente':
                                                $estadoClass = 'badge-warning-premium';    // Amarillo para pendiente
                                                break;
                                            case 'inactivo':
                                                $estadoClass = 'badge-danger-premium';     // Rojo para inactivo
                                                break;
                                        }
                                        ?>
                                        <!-- Badge con el estado del dispositivo -->
                                        <span class="badge <?= $estadoClass ?>">
                                            <?= ucfirst($dispositivo['estado']) ?>
                                        </span>
                                    </td>
                                    
                                    <!-- Fecha de última actualización -->
                                    <td>
                                        <?= isset($dispositivo['fecha_actualizacion']) ? 
                                            date('d/m/Y H:i', strtotime($dispositivo['fecha_actualizacion'])) : 
                                            'Nunca' ?>
                                    </td>
                                    
                                    <!-- === BOTONES DE ACCIÓN === -->
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Botón para ver detalles -->
                                            <button type="button" class="btn-action btn-view" 
                                                    onclick="verDetalles(<?= $dispositivo['id_dispositivo'] ?>)"
                                                    title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- Botón para editar dispositivo -->
                                            <button type="button" class="btn-action btn-edit" 
                                                    onclick="editarDispositivo(<?= $dispositivo['id_dispositivo'] ?>, '<?= esc($dispositivo['nombre'], 'js') ?>', '<?= esc($dispositivo['descripcion'] ?? '', 'js') ?>')" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <!-- Botón para historial de cortes -->
                                            <a href="<?= base_url('energia/cortes?dispositivo=' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn-action btn-control" 
                                               title="Historial de Cortes">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </a>
                                            
                                            <!-- === BOTONES DE ACTIVAR/DESACTIVAR === -->
                                            <?php if (in_array($dispositivo['estado'], ['pendiente', 'inactivo'])): ?>
                                                <!-- Botón para activar dispositivo -->
                                                <a href="<?= base_url('admin/dispositivos/activar/' . $dispositivo['id_dispositivo']) ?>" 
                                                   class="btn-action btn-activate" 
                                                   onclick="return confirm('¿Estás seguro de activar este dispositivo?')"
                                                   title="Activar">
                                                    <i class="fas fa-power-off"></i>
                                                </a>
                                            <?php elseif ($dispositivo['estado'] === 'activo'): ?>
                                                <!-- Botón para desactivar dispositivo -->
                                                <a href="<?= base_url('admin/dispositivos/desactivar/' . $dispositivo['id_dispositivo']) ?>" 
                                                   class="btn-action btn-deactivate" 
                                                   onclick="return confirm('¿Estás seguro de desactivar este dispositivo?')"
                                                   title="Desactivar">
                                                    <i class="fas fa-power-off"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <!-- Botón para eliminar dispositivo -->
                                            <button type="button" class="btn-action btn-delete" 
                                                    onclick="eliminarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            
                                            <!-- Botón para ver lecturas de energía -->
                                            <a href="<?= base_url('energia/dispositivo/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn-action btn-charts"
                                               title="Ver Lecturas">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- === MODAL PARA VER DETALLES === -->
<!-- Modal que muestra información detallada del dispositivo -->
<div class="modal fade" id="detallesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalles del Dispositivo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <!-- Contenido dinámico que se llena con JavaScript -->
                <div id="detallesContenido"></div>
            </div>
        </div>
    </div>
</div>

<!-- === MODAL PARA EDITAR DISPOSITIVO === -->
<!-- Modal que permite editar nombre y descripción del dispositivo -->
<div class="modal fade" id="editarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar dispositivo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Cuerpo del modal con formulario -->
            <div class="modal-body">
                <!-- Formulario para editar dispositivo -->
                <form id="formEditarAdmin" action="<?= base_url('admin/dispositivos/actualizar') ?>" method="post">
                    <!-- Campo oculto con ID del dispositivo -->
                    <input type="hidden" name="id_dispositivo" id="editId">
                    
                    <!-- Campo para nombre del dispositivo -->
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="editNombre" required>
                    </div>
                    
                    <!-- Campo para descripción del dispositivo -->
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="editDescripcion" rows="3"></textarea>
                    </div>
                </form>
                
                <!-- Área para mensajes de respuesta -->
                <div id="msgEditarAdmin"></div>
            </div>
            
            <!-- Pie del modal con botones -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary-premium" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gold" onclick="guardarEdicion()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- === JAVASCRIPT PARA FUNCIONALIDAD === -->
<script>
// === FUNCIÓN PARA VER DETALLES DEL DISPOSITIVO ===
function verDetalles(id) {
    // Hacer petición AJAX para obtener detalles del dispositivo
    fetch(`<?= base_url('admin/dispositivos/detalles/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const dispositivo = data.dispositivo;
                // Llenar el modal con la información del dispositivo
                document.getElementById('detallesContenido').innerHTML = `
                    <p><strong>ID:</strong> ${dispositivo.id_dispositivo}</p>
                    <p><strong>Nombre:</strong> ${dispositivo.nombre}</p>
                    <p><strong>MAC Address:</strong> ${dispositivo.mac_address}</p>
                    <p><strong>Estado:</strong> ${dispositivo.estado}</p>
                    <p><strong>Última Actualización:</strong> ${dispositivo.ultima_conexion}</p>
                `;
                // Mostrar el modal
                new bootstrap.Modal(document.getElementById('detallesModal')).show();
            } else {
                alert('Error al cargar los detalles: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los detalles del dispositivo');
        });
}

// === FUNCIÓN PARA ELIMINAR DISPOSITIVO ===
function eliminarDispositivo(id) {
    // Confirmar antes de eliminar
    if (confirm('¿Estás seguro de eliminar este dispositivo?')) {
        // Hacer petición AJAX para eliminar el dispositivo
        fetch(`<?= base_url('admin/dispositivos/eliminar/') ?>${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Recargar la página si se eliminó correctamente
                location.reload();
            } else {
                alert('Error al eliminar el dispositivo: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el dispositivo');
        });
    }
}

// === FUNCIÓN PARA EDITAR DISPOSITIVO ===
function editarDispositivo(id, nombre, descripcion) {
    // Llenar los campos del formulario con los datos actuales
    document.getElementById('editId').value = id;
    document.getElementById('editNombre').value = nombre || '';
    document.getElementById('editDescripcion').value = descripcion || '';
    // Mostrar el modal de edición
    new bootstrap.Modal(document.getElementById('editarModal')).show();
}

// === FUNCIÓN PARA GUARDAR EDICIÓN ===
function guardarEdicion() {
    const form = document.getElementById('formEditarAdmin');
    const msg = document.getElementById('msgEditarAdmin');
    msg.innerHTML = '';
    
    // Crear FormData con los datos del formulario
    const formData = new FormData(form);
    
    // Enviar petición AJAX para actualizar el dispositivo
    fetch(form.action, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                // Mostrar mensaje de éxito
                msg.innerHTML = '<div class="premium-alert alert-success">Dispositivo actualizado</div>';
                // Recargar la página después de un breve delay
                setTimeout(() => { location.reload(); }, 800);
            } else {
                // Mostrar mensaje de error
                msg.innerHTML = '<div class="premium-alert alert-danger">' + (res.error || 'Error') + '</div>';
            }
        })
        .catch(err => {
            // Mostrar mensaje de error en caso de excepción
            msg.innerHTML = '<div class="premium-alert alert-danger">Error: ' + err + '</div>';
        });
}
</script>

<?= $this->endSection() ?>