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
    }
    
    /* Alerta de peligro (rojo) */
    .premium-alert.alert-danger { 
        background-color: rgba(220, 53, 69, 0.1);     /* Fondo rojo transparente */
        border-color: var(--danger);                   /* Borde rojo */
    }
    
    /* Alerta de información (azul) */
    .premium-alert.alert-info { 
        background-color: rgba(23, 162, 184, 0.1);    /* Fondo azul transparente */
        border-color: #17a2b8;                         /* Borde azul */
    }
    
    /* Botón de cerrar alerta */
    .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

    /* === TABLA PREMIUM === */
    /* Estilos para la tabla de usuarios */
    .premium-table { color: var(--text-primary); }     /* Color del texto */
    
    /* Encabezados de la tabla */
    .premium-table thead th {
        border: none;                                  /* Sin bordes */
        color: var(--gold-light);                      /* Color dorado claro */
        font-weight: 600;                              /* Peso de fuente */
        text-transform: uppercase;                     /* Texto en mayúsculas */
        font-size: 0.85rem;                            /* Tamaño de fuente */
    }
    
    /* Filas del cuerpo de la tabla */
    .premium-table tbody tr {
        transition: background-color 0.2s;             /* Transición suave */
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
    
    /* Efecto hover en filas */
    .premium-table tbody tr:hover { background-color: rgba(255,255,255,0.05); }

    /* === FORMULARIOS PREMIUM === */
    /* Estilos para elementos de formulario */
    .form-select {
        background-color: rgba(0,0,0,0.2);             /* Fondo semi-transparente */
        border: 1px solid var(--card-border);          /* Borde sutil */
        color: var(--text-primary);                    /* Color del texto */
        border-radius: 10px;                           /* Bordes redondeados */
        padding: 0.5rem 1rem;                         /* Espaciado interno */
        width: 150px;                                  /* Ancho fijo */
        appearance: none;                              /* Quitar apariencia nativa */
        /* Flecha personalizada */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23D4AF37' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); 
        background-repeat: no-repeat;                  /* No repetir imagen */
        background-position: right .75rem center;      /* Posición de la flecha */
        background-size: 16px 12px;                    /* Tamaño de la flecha */
        transition: all 0.2s ease;                     /* Transición suave */
    }
    
    /* Estado de foco del select */
    .form-select:focus {
        background-color: rgba(0,0,0,0.3);             /* Fondo más oscuro */
        border-color: var(--gold);                     /* Borde dorado */
        box-shadow: 0 0 10px var(--glow-color);       /* Sombra dorada */
    }
    
    /* === BOTÓN ELIMINAR === */
    .btn-delete {
        background: transparent;                       /* Fondo transparente */
        color: var(--text-secondary);                  /* Color del texto */
        border: 1px solid var(--card-border);          /* Borde sutil */
        transition: all 0.3s ease;                     /* Transición suave */
    }
    
    /* Efecto hover del botón eliminar */
    .btn-delete:hover {
        color: var(--danger);                          /* Color rojo */
        border-color: var(--danger);                   /* Borde rojo */
        background-color: rgba(220, 53, 69, 0.1);     /* Fondo rojo transparente */
    }
    
    /* === MODAL OSCURO === */
    /* Estilos para modales con tema oscuro */
    .modal-content {
        background-color: #2a2a2e;                     /* Fondo oscuro */
        border: 1px solid var(--card-border);          /* Borde sutil */
        border-radius: var(--border-radius);           /* Bordes redondeados */
    }
    
    /* Encabezado del modal */
    .modal-header { 
        border-bottom: 1px solid var(--card-border);   /* Línea separadora */
        color: var(--gold);                            /* Color dorado */
    }
    
    /* Botón secundario */
    .btn-secondary { 
        background-color: var(--card-border);          /* Fondo del borde */
        border: none;                                  /* Sin borde */
    }
    
    /* Botón dorado principal */
    .btn-gold {
        background: var(--gold);                       /* Fondo dorado */
        color: var(--black-bg);                        /* Texto negro */
        border: none;                                  /* Sin borde */
        box-shadow: 0 4px 20px var(--glow-color);     /* Sombra dorada */
        transition: all 0.3s ease;                     /* Transición suave */
    }
    
    /* Efecto hover del botón dorado */
    .btn-gold:hover { 
        transform: translateY(-2px);                   /* Elevación */
        box-shadow: 0 6px 25px rgba(212, 175, 55, 0.5); /* Sombra más intensa */
    }
</style>

<!-- === CONTENIDO PRINCIPAL === -->
<div class="container my-5">
    <!-- Título de la página con icono -->
    <h1 class="h3 mb-4 page-header">
        <i class="fas fa-users-cog"></i> Usuarios Invitados
    </h1>

    <!-- Tarjeta principal que contiene la lista de usuarios -->
    <div class="premium-card">
        <!-- Encabezado de la tarjeta -->
        <div class="premium-card-header">
            <i class="fas fa-list-ul"></i> Lista de Usuarios
        </div>
        
        <!-- Cuerpo de la tarjeta -->
        <div class="premium-card-body">
            <!-- === MENSAJES DE ÉXITO === -->
            <!-- Muestra mensajes de éxito si existen en la sesión -->
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="premium-alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- === MENSAJES DE ERROR === -->
            <!-- Muestra mensajes de error si existen en la sesión -->
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="premium-alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- === VERIFICACIÓN DE USUARIOS === -->
            <!-- Si no hay usuarios, muestra mensaje informativo -->
            <?php if (empty($usuarios)) : ?>
                <div class="premium-alert alert-info">No hay usuarios invitados actualmente.</div>
            <?php else : ?>
                <!-- === TABLA DE USUARIOS === -->
                <div class="table-responsive">
                    <table class="table premium-table">
                        <!-- Encabezados de la tabla -->
                        <thead>
                            <tr>
                                <th>ID</th>                    <!-- Identificador único -->
                                <th>Nombre</th>                <!-- Nombre del usuario -->
                                <th>Email</th>                 <!-- Correo electrónico -->
                                <th>Rol actual</th>            <!-- Rol actual del usuario -->
                                <th>Cambiar rol</th>           <!-- Selector para cambiar rol -->
                                <th>Eliminar</th>              <!-- Botón para eliminar -->
                            </tr>
                        </thead>
                        
                        <!-- Cuerpo de la tabla con datos de usuarios -->
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <!-- ID del usuario -->
                                    <td><?= esc($usuario['id_usuario']) ?></td>
                                    
                                    <!-- Nombre del usuario -->
                                    <td><?= esc($usuario['nombre']) ?></td>
                                    
                                    <!-- Email del usuario -->
                                    <td><?= esc($usuario['email']) ?></td>
                                    
                                    <!-- Rol actual del usuario -->
                                    <td><?= esc($usuario['rol']) ?></td>
                                    
                                    <!-- === FORMULARIO PARA CAMBIAR ROL === -->
                                    <td>
                                        <form action="<?= base_url('admin/cambiarRol') ?>" method="post">
                                            <?= csrf_field() ?> <!-- Token de seguridad CSRF -->
                                            <input type="hidden" name="usuario_id" value="<?= $usuario['id_usuario'] ?>">
                                            
                                            <!-- Selector de rol que se envía automáticamente al cambiar -->
                                            <select name="id_rol" class="form-select" onchange="this.form.submit()">
                                                <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
                                                <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
                                            </select>
                                        </form>
                                    </td>
                                    
                                    <!-- === BOTÓN DE ELIMINAR === -->
                                    <td class="text-center">
                                        <!-- Botón que abre modal de confirmación -->
                                        <button type="button" class="btn btn-delete btn-sm" 
                                                onclick="confirmarEliminacion(<?= $usuario['id_usuario'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Formulario oculto para eliminar (se envía desde JavaScript) -->
                                        <form id="form-eliminar-<?= $usuario['id_usuario'] ?>" 
                                              action="<?= base_url('admin/eliminarUsuario') ?>" 
                                              method="post" class="d-none">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="usuario_id" value="<?= $usuario['id_usuario'] ?>">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- === MODAL DE CONFIRMACIÓN === -->
<!-- Modal que aparece antes de eliminar un usuario -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalTitle">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirmar Acción
                </h5>
            </div>
            
            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <p id="confirmationModalMessage">
                    ¿Estás seguro de que quieres eliminar este usuario? Esta acción es irreversible.
                </p>
            </div>
            
            <!-- Pie del modal con botones -->
            <div class="modal-footer" style="border-top: 1px solid var(--card-border);">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gold" id="confirmActionBtn">Sí, Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- === JAVASCRIPT PARA FUNCIONALIDAD === -->
<script>
    // Variables globales para el modal
    let confirmationModal = null;        // Instancia del modal
    let confirmActionCallback = null;    // Función a ejecutar al confirmar

    // === INICIALIZACIÓN AL CARGAR LA PÁGINA === -->
    document.addEventListener("DOMContentLoaded", function() {
        // Verificar si el modal existe
        if (document.getElementById('confirmationModal')) {
            // Crear instancia del modal de Bootstrap
            confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            
            // Agregar evento al botón de confirmar
            document.getElementById('confirmActionBtn').addEventListener('click', function() {
                // Ejecutar la función de confirmación si existe
                if (typeof confirmActionCallback === 'function') {
                    confirmActionCallback();
                }
                // Cerrar el modal
                confirmationModal.hide();
            });
        }
    });

    // === FUNCIÓN PARA CONFIRMAR ELIMINACIÓN === -->
    function confirmarEliminacion(usuarioId) {
        // Definir qué hacer al confirmar
        confirmActionCallback = function() {
            // Buscar el formulario de eliminación
            const form = document.getElementById('form-eliminar-' + usuarioId);
            if (form) {
                // Enviar el formulario
                form.submit();
            }
        };
        
        // Mostrar el modal si existe
        if (confirmationModal) {
            confirmationModal.show();
        }
    }
</script>

<?= $this->endSection() ?>