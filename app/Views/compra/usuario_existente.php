<!-- DONDE APARECE SI LA DIRECCION DE ENVIO ES CORRECTA.. -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Dispositivo Adicional - EcoVolt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* === PALETA DE COLORES PREMIUM === */
        :root {
            --gold-primary: #D4AF37;
            --gold-secondary: #B8860B;
            --gold-light: #F7E98E;
            --gold-dark: #8B7355;
            --silver-primary: #C0C0C0;
            --silver-secondary: #A8A8A8;
            --silver-light: #E8E8E8;
            --black-primary: #1a1a1a;
            --black-secondary: #2d2d2d;
            --black-light: #404040;
            --white-primary: #ffffff;
            --white-secondary: #f8f9fa;
            --white-dark: #e9ecef;
            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            --gradient-silver: linear-gradient(135deg, #C0C0C0 0%, #A8A8A8 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            --shadow-premium: 0 10px 30px rgba(212, 175, 55, 0.3);
            --shadow-dark: 0 10px 30px rgba(0, 0, 0, 0.3);
            --border-radius: 15px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* === ESTILOS GLOBALES PREMIUM === */
        body {
            background: var(--gradient-dark);
            color: var(--white-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* === HEADER PREMIUM === */
        .premium-header {
            background: var(--gradient-gold);
            color: var(--black-primary);
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-premium);
        }

        .premium-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .premium-header p {
            font-size: 1.2rem;
            margin: 0.5rem 0 0 0;
            font-weight: 600;
        }

        /* === TARJETAS PREMIUM === */
        .premium-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-premium);
            backdrop-filter: blur(10px);
            transition: var(--transition);
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.4);
        }

        .premium-title {
            color: var(--gold-primary);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* === INFORMACIÓN DEL USUARIO === */
        .user-info {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .user-info h5 {
            color: var(--gold-primary);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .user-info p {
            margin: 0.5rem 0;
            color: var(--white-primary);
        }

        .user-info strong {
            color: var(--gold-primary);
        }

        /* === BOTÓN PREMIUM === */
        .btn-premium {
            background: var(--gradient-gold);
            color: var(--black-primary);
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 2rem;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
            box-shadow: var(--shadow-premium);
        }

        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.4);
            color: var(--black-primary);
        }

        /* === SECCIÓN DE DIRECCIÓN === */
        .address-section {
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 2rem 0;
            transition: var(--transition);
        }

        .address-section.confirmed {
            background: rgba(34, 197, 94, 0.1);
            border-color: #22c55e;
        }

        .address-confirmation {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(212, 175, 55, 0.3);
        }

        .btn-update-address {
            background: var(--gradient-silver);
            color: var(--black-primary);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-update-address:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(192, 192, 192, 0.4);
        }

        .form-check-input:checked {
            background-color: var(--gold-primary);
            border-color: var(--gold-primary);
        }

        /* === MODAL PREMIUM === */
        .premium-modal .modal-content {
            background: var(--gradient-dark);
            border: 2px solid var(--gold-primary);
            border-radius: var(--border-radius);
            color: var(--white-primary);
        }

        .premium-modal .modal-header {
            border-bottom: 1px solid var(--gold-primary);
            background: rgba(212, 175, 55, 0.1);
        }

        .premium-modal .modal-title {
            color: var(--gold-primary);
            font-weight: 700;
        }

        .premium-modal .btn-close {
            filter: invert(1);
        }

        .premium-modal .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--gold-primary);
            color: var(--white-primary);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
        }

        .premium-modal .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--gold-light);
            color: var(--white-primary);
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }

        .premium-modal .form-label {
            color: var(--gold-primary);
            font-weight: 600;
        }

        .premium-modal .btn-modal-primary {
            background: var(--gradient-gold);
            color: var(--black-primary);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            transition: var(--transition);
        }

        .premium-modal .btn-modal-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        .premium-modal .btn-modal-secondary {
            background: var(--gradient-silver);
            color: var(--black-primary);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            transition: var(--transition);
        }

        /* === ALERTAS === */
        .alert-premium {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--gold-primary);
            border-radius: var(--border-radius);
            color: var(--white-primary);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .premium-header h1 {
                font-size: 2rem;
            }
            
            .premium-card {
                padding: 1.5rem;
            }

            .address-confirmation {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    
    <!-- Header Premium -->
    <div class="premium-header">
        <div class="container">
            <h1><i class="fas fa-plus-circle me-3"></i>Dispositivo Adicional</h1>
            <p>Agrega un segundo medidor a tu cuenta premium</p>
        </div>
    </div>

    <div class="container mb-4">
        <a class="nav-link text-danger" href="<?= base_url('cerrarSesion') ?>">
            <i class="fas fa-sign-out-alt"></i> Volver al login principal
        </a>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <!-- Alertas -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-premium alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Información del Usuario -->
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-user me-2"></i>Tu Cuenta Premium
                    </h3>
                    
                    <div class="user-info">
                        <h5><i class="fas fa-user-circle me-2"></i>Información de la Cuenta</h5>
                        <p><strong>Nombre:</strong> <?= esc($usuario['nombre']) ?> <?= esc($usuario['apellido']) ?></p>
                        <p><strong>Email:</strong> <?= esc($usuario['email']) ?></p>
                        <p><strong>Estado:</strong> <span style="color: var(--gold-primary); font-weight: 700;">Premium Activo</span></p>
                        <p><strong>Dispositivos actuales:</strong> <?= $dispositivos_count ?> dispositivo(s)</p>
                    </div>

                    <!-- Sección de Dirección de Envío -->
                    <div class="address-section" id="addressSection">
                        <h5><i class="fas fa-map-marker-alt me-2"></i>Dirección de Envío</h5>
                        
                        <?php if ($direccion): ?>
                            <div class="address-details">
                                <p><strong>Calle:</strong> <?= esc($direccion['calle']) ?> <?= esc($direccion['numero']) ?></p>
                                <p><strong>Ciudad:</strong> <?= esc($direccion['ciudad']) ?></p>
                                <p><strong>Código Postal:</strong> <?= esc($direccion['codigo_postal']) ?></p>
                                <p><strong>País:</strong> <?= esc($direccion['pais']) ?></p>
                            </div>
                            <div class="address-confirmation">
    <div class="form-check">
        <!-- QUITA el name y value del checkbox -->
        <input class="form-check-input" type="checkbox" id="confirmAddress">
        <label class="form-check-label" for="confirmAddress">
            <strong>Confirmo que esta es mi dirección de envío correcta</strong>
        </label>
    </div>
    <button type="button" class="btn-update-address" data-bs-toggle="modal" data-bs-target="#addressModal">
        <i class="fas fa-edit me-1"></i>Actualizar Dirección
    </button>
</div>
                        <?php else: ?>
                            <div class="alert alert-premium">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No tienes una dirección de envío registrada. Por favor, agrega una dirección para continuar.
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn-premium" data-bs-toggle="modal" data-bs-target="#addressModal">
                                    <i class="fas fa-plus me-2"></i>Agregar Dirección de Envío
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-center">
                        <p class="lead" style="color: var(--silver-primary); margin-bottom: 2rem;">
                            ¡Perfecto! Ya tienes una cuenta premium activa. Puedes agregar dispositivos adicionales usando la misma cuenta.
                        </p>
                        
                        <?php if ($direccion): ?>
                            <form action="<?= base_url('compra-existente/procesar') ?>" method="post" id="compraForm">
                                <input type="hidden" name="id_dispositivo" value="1">
                                <button type="submit" class="btn-premium" id="btnComprar" disabled>
                                    <i class="fas fa-shopping-cart me-2"></i>Continuar con la Compra
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-premium">
                                <i class="fas fa-info-circle me-2"></i>
                                Debes agregar una dirección de envío antes de continuar con la compra.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Información del Dispositivo -->
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-microchip me-2"></i>EcoVolt Pro Premium
                    </h3>
                    
                    <div class="text-center">
                        <i class="fas fa-microchip" style="font-size: 4rem; color: var(--gold-primary); margin-bottom: 1rem;"></i>
                        <h4 style="color: var(--gold-primary); margin-bottom: 1rem;">Dispositivo Adicional</h4>
                        <p style="color: var(--silver-primary); margin-bottom: 2rem;">
                            Agrega un segundo medidor de energía a tu cuenta premium existente.
                        </p>
                        
                        <div style="background: var(--gradient-gold); color: var(--black-primary); padding: 1.5rem; border-radius: var(--border-radius); margin: 2rem 0;">
                            <h4 style="margin: 0; font-weight: 800;">$150 USD</h4>
                            <p style="margin: 0.5rem 0 0 0; font-weight: 600;">Dispositivo + Soporte Premium</p>
                        </div>
                    </div>
                </div>

                <!-- Beneficios -->
                <div class="premium-card">
                    <h3 class="premium-title">
                        <i class="fas fa-star me-2"></i>Beneficios Incluidos
                    </h3>
                    
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Se agregará a tu cuenta existente
                        </li>
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Mismo soporte premium 24/7
                        </li>
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Configuración automática
                        </li>
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.2); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Garantía extendida de 2 años
                        </li>
                        <li style="padding: 0.8rem 0; display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: var(--gold-primary); margin-right: 1rem;"></i>
                            Actualizaciones premium gratuitas
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Actualizar Dirección -->
    <div class="modal fade premium-modal" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <?= $direccion ? 'Actualizar Dirección de Envío' : 'Agregar Dirección de Envío' ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addressForm" action="<?= base_url('compra-existente/guardar-direccion') ?>" method="post">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="calle" class="form-label">Calle</label>
                                    <input type="text" class="form-control" id="calle" name="calle" 
                                           value="<?= old('calle', $direccion['calle'] ?? '') ?>" 
                                           placeholder="Ingresa el nombre de la calle" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <input type="text" class="form-control" id="numero" name="numero" 
                                           value="<?= old('numero', $direccion['numero'] ?? '') ?>" 
                                           placeholder="Nº" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                           value="<?= old('ciudad', $direccion['ciudad'] ?? '') ?>" 
                                           placeholder="Ingresa tu ciudad" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo_postal" class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                           value="<?= old('codigo_postal', $direccion['codigo_postal'] ?? '') ?>" 
                                           placeholder="Código postal" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pais" class="form-label">País</label>
                            <input type="text" class="form-control" id="pais" name="pais" 
                                   value="<?= old('pais', $direccion['pais'] ?? '') ?>" 
                                   placeholder="Ingresa tu país" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" form="addressForm" class="btn-modal-primary">
                        <i class="fas fa-save me-2"></i>Guardar Dirección
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmCheckbox = document.getElementById('confirmAddress');
        const comprarBtn = document.getElementById('btnComprar');
        const addressSection = document.getElementById('addressSection');
        const addressForm = document.getElementById('addressForm');
        const compraForm = document.getElementById('compraForm');

        // === CORRECCIÓN: Agregar campo hidden para la confirmación ===
        if (compraForm) {
            const hiddenConfirm = document.createElement('input');
            hiddenConfirm.type = 'hidden';
            hiddenConfirm.name = 'confirmar_direccion';
            hiddenConfirm.value = 'no';
            hiddenConfirm.id = 'hiddenConfirm';
            compraForm.appendChild(hiddenConfirm);
        }

        // Manejar la confirmación de dirección - CORREGIDO
        if (confirmCheckbox && comprarBtn) {
            confirmCheckbox.addEventListener('change', function() {
                const hiddenConfirmField = document.getElementById('hiddenConfirm');
                if (this.checked) {
                    comprarBtn.disabled = false;
                    addressSection.classList.add('confirmed');
                    if (hiddenConfirmField) {
                        hiddenConfirmField.value = 'si';
                    }
                } else {
                    comprarBtn.disabled = true;
                    addressSection.classList.remove('confirmed');
                    if (hiddenConfirmField) {
                        hiddenConfirmField.value = 'no';
                    }
                }
                console.log('Confirmación actualizada:', hiddenConfirmField ? hiddenConfirmField.value : 'no encontrado');
            });
        }

        // Debug: ver qué se envía en el formulario de compra
        if (compraForm) {
            compraForm.addEventListener('submit', function(e) {
                const formData = new FormData(this);
                console.log('Datos del formulario de compra:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }
            });
        }

        // Manejar el envío del formulario de dirección (modal)
        if (addressForm) {
            addressForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cerrar modal y recargar la página para mostrar los cambios
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
                        modal.hide();
                        
                        // Mostrar mensaje de éxito
                        showAlert('Dirección guardada correctamente', 'success');
                        
                        // Recargar después de un breve delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Mostrar errores
                        if (data.errors) {
                            let errorMessage = 'Por favor corrige los siguientes errores:\n';
                            for (const field in data.errors) {
                                errorMessage += `• ${data.errors[field]}\n`;
                            }
                            showAlert(errorMessage, 'error');
                        } else {
                            showAlert(data.message || 'Error al guardar la dirección', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error de conexión. Intenta nuevamente.', 'error');
                });
            });
        }

        function showAlert(message, type) {
            // Crear alerta
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'success' ? 'premium' : 'danger'} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insertar al inicio del contenedor
            const container = document.querySelector('.container .row .col-md-8');
            container.insertBefore(alertDiv, container.firstChild);
            
            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Limpiar el formulario cuando se cierra el modal sin guardar
        const modalElement = document.getElementById('addressModal');
        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', function () {
                // No limpiar el formulario para mantener los datos ingresados
                // Solo resetear la validación
                const inputs = modalElement.querySelectorAll('.is-invalid');
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                });
            });
        }
    });
</script>
</body>
</html>