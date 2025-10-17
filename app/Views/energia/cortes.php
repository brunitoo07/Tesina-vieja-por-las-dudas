<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<style>
/* === ESTILOS PREMIUM PARA CORTES === */
:root {
    --gold-primary: #D4AF37;
    --gold-secondary: #B8860B;
    --gold-light: #F7E98E;
    --silver-primary: #C0C0C0;
    --black-primary: #1a1a1a;
    --white-primary: #ffffff;
    --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    --shadow-premium: 0 10px 30px rgba(212, 175, 55, 0.3);
    --border-radius: 15px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    background: var(--gradient-dark);
    color: var(--white-primary);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.premium-header {
    background: var(--gradient-gold);
    border-radius: var(--border-radius);
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: var(--shadow-premium);
    position: relative;
    overflow: hidden;
}

.premium-header h1 {
    color: var(--black-primary);
    font-weight: 700;
    font-size: 2.5rem;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.premium-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: var(--border-radius);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transition: var(--transition);
    overflow: hidden;
    position: relative;
}

.premium-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-gold);
}

.premium-card-header {
    background: rgba(212, 175, 55, 0.1);
    border-bottom: 1px solid rgba(212, 175, 55, 0.3);
    padding: 20px 25px;
}

.premium-card-header h6 {
    color: var(--gold-primary);
    font-weight: 700;
    font-size: 1.1rem;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.premium-card-body {
    padding: 25px;
    background: rgba(255, 255, 255, 0.02);
}

/* === ESTADÍSTICAS === */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--gradient-dark);
    border: 1px solid var(--gold-primary);
    border-radius: var(--border-radius);
    padding: 25px;
    text-align: center;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-gold);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-premium);
    border-color: var(--gold-light);
}

.stat-icon {
    color: var(--gold-primary);
    font-size: 2.5rem;
    margin-bottom: 15px;
    opacity: 0.8;
}

.stat-label {
    color: var(--silver-primary);
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.stat-value {
    color: var(--gold-primary);
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

/* === TABLA DE CORTES === */
.cortes-table {
    background: rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.cortes-table table {
    margin: 0;
    background: transparent;
    width: 100%;
}

.cortes-table thead th {
    background: var(--gradient-gold);
    color: var(--black-primary);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 20px 15px;
    border: none;
    font-size: 0.9rem;
}

.cortes-table tbody td {
    background: rgba(255, 255, 255, 0.02);
    color: var(--white-primary);
    padding: 15px;
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    transition: var(--transition);
}

.cortes-table tbody tr:hover td {
    background: rgba(212, 175, 55, 0.1);
    color: var(--gold-light);
}

/* === ESTADOS DE CORTE === */
.status-badge {
    padding: 6px 12px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.status-activo {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: var(--white-primary);
    box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
}

.status-resuelto {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: var(--white-primary);
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
}

.status-visto {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: var(--white-primary);
    box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
}

/* === FILTROS === */
.filters-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: var(--border-radius);
    padding: 20px;
    margin-bottom: 20px;
}

.form-control, .form-select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid var(--gold-primary);
    color: var(--white-primary);
    border-radius: 10px;
    padding: 12px 15px;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: var(--gold-light);
    box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
    color: var(--white-primary);
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.form-select option {
    background: var(--black-primary);
    color: var(--white-primary);
    padding: 10px;
}

.btn-premium {
    background: var(--gradient-gold);
    border: none;
    color: var(--black-primary);
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: var(--transition);
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5);
    color: var(--black-primary);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .premium-header h1 {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .cortes-table {
        overflow-x: auto;
    }
    
    .cortes-table table {
        min-width: 600px;
    }
}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="premium-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>
                <i class="fas fa-exclamation-triangle me-2"></i>
                Historial de Cortes de Línea
            </h1>
            <a href="http://192.168.2.173/Tesina/public/energia/dispositivo/2" class="btn" style="background: var(--black-primary); color: var(--gold-primary); border: none; padding: 12px 25px; border-radius: 25px; font-weight: 600;">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid" id="estadisticasContainer">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-label">Total de Cortes</div>
            <div class="stat-value" id="totalCortes">-</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <div class="stat-label">Cortes Activos</div>
            <div class="stat-value" id="cortesActivos">-</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-label">Cortes Resueltos</div>
            <div class="stat-value" id="cortesResueltos">-</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-label">Cortes Vistos</div>
            <div class="stat-value" id="cortesVistos">-</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters-card">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="filtroDispositivo" class="form-label">Dispositivo</label>
                <select class="form-select" id="filtroDispositivo">
                    <option value="">Todos los dispositivos</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroEstado" class="form-label">Estado</label>
                <select class="form-select" id="filtroEstado">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activos</option>
                    <option value="resuelto">Resueltos</option>
                    <option value="visto">Vistos</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="filtroFechaDesde" class="form-label">Desde</label>
                <input type="date" class="form-control" id="filtroFechaDesde">
            </div>
            <div class="col-md-2">
                <label for="filtroFechaHasta" class="form-label">Hasta</label>
                <input type="date" class="form-control" id="filtroFechaHasta">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn-premium w-100" onclick="aplicarFiltros()">
                    <i class="fas fa-search me-1"></i>Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Cortes -->
    <div class="premium-card">
        <div class="premium-card-header d-flex justify-content-between align-items-center">
            <h6><i class="fas fa-history me-2"></i>Historial de Cortes</h6>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-outline-success me-2" onclick="exportarCortes()" title="Exportar a Excel">
                    <i class="fas fa-file-excel"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-info" onclick="actualizarDatos()" title="Actualizar">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="premium-card-body">
            <div id="loadingCortes" class="text-center" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-2">Cargando cortes...</p>
            </div>
            
            <div class="cortes-table" id="tablaCortesContainer">
                <table id="tablaCortes">
                    <thead>
                        <tr>
                            <th>Dispositivo</th>
                            <th>Consumo (kWh)</th>
                            <th>Límite (kWh)</th>
                            <th>Fecha del Corte</th>
                            <th>Estado</th>
                            <th>Visto</th>
                            <th>Fecha Vista</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaCortes">
                        <!-- Los datos se cargarán aquí -->
                    </tbody>
                </table>
            </div>
            
            <div class="text-center mt-3" id="paginacionCortes">
                <!-- Paginación se generará aquí -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarEstadisticas();
    cargarDispositivos();
    cargarCortes();
});

// Cargar estadísticas
function cargarEstadisticas() {
    fetch('<?= base_url('energia/getEstadisticasCortes') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.estadisticas;
                document.getElementById('totalCortes').textContent = stats.total_cortes;
                document.getElementById('cortesActivos').textContent = stats.cortes_activos;
                document.getElementById('cortesResueltos').textContent = stats.cortes_resueltos;
                document.getElementById('cortesVistos').textContent = stats.cortes_vistos;
            }
        })
        .catch(error => {
            console.error('Error cargando estadísticas:', error);
        });
}

// Cargar dispositivos para el filtro
function cargarDispositivos() {
    fetch('<?= base_url('energia/getDispositivosUsuario') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('filtroDispositivo');
                select.innerHTML = '<option value="">Todos los dispositivos</option>';
                
                data.dispositivos.forEach(dispositivo => {
                    const option = document.createElement('option');
                    option.value = dispositivo.id_dispositivo;
                    option.textContent = dispositivo.nombre;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error cargando dispositivos:', error);
        });
}

// Cargar cortes
function cargarCortes() {
    const loading = document.getElementById('loadingCortes');
    const container = document.getElementById('tablaCortesContainer');
    
    loading.style.display = 'block';
    container.style.display = 'none';
    
    fetch('<?= base_url('energia/getCortesPendientes') ?>')
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            container.style.display = 'block';
            
            if (data.success) {
                mostrarCortes(data.cortes);
            } else {
                mostrarError('Error al cargar cortes: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            container.style.display = 'block';
            mostrarError('Error de conexión: ' + error.message);
        });
}

// Mostrar cortes en la tabla
function mostrarCortes(cortes) {
    const tbody = document.getElementById('cuerpoTablaCortes');
    tbody.innerHTML = '';
    
    if (cortes.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    No se encontraron cortes con los filtros aplicados
                </td>
            </tr>
        `;
        return;
    }
    
    cortes.forEach(corte => {
        const row = document.createElement('tr');
        
        // Determinar estado
        let estadoBadge = '';
        if (corte.resuelto == 1) {
            estadoBadge = '<span class="status-badge status-resuelto">Resuelto</span>';
        } else {
            estadoBadge = '<span class="status-badge status-activo">Activo</span>';
        }
        
        // Determinar si fue visto
        let vistoBadge = '';
        if (corte.vista_por_usuario == 1) {
            vistoBadge = '<span class="status-badge status-visto">Visto</span>';
        } else {
            vistoBadge = '<span class="text-muted">No visto</span>';
        }
        
        // Fecha vista
        const fechaVista = corte.fecha_vista ? 
            new Date(corte.fecha_vista).toLocaleString() : 
            '<span class="text-muted">-</span>';
        
        row.innerHTML = `
            <td>
                <strong>${corte.nombre_dispositivo || 'Dispositivo ' + corte.id_dispositivo}</strong>
            </td>
            <td>
                <span class="text-warning fw-bold">${parseFloat(corte.consumo_actual).toFixed(2)}</span>
            </td>
            <td>
                <span class="text-info">${parseFloat(corte.limite_configurado).toFixed(2)}</span>
            </td>
            <td>${new Date(corte.fecha_corte).toLocaleString()}</td>
            <td>${estadoBadge}</td>
            <td>${vistoBadge}</td>
            <td>${fechaVista}</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="verDetalleCorte(${corte.id})" title="Ver detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    ${corte.vista_por_usuario == 0 ? `
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="marcarComoVisto(${corte.id})" title="Marcar como visto">
                            <i class="fas fa-check"></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Aplicar filtros
function aplicarFiltros() {
    const filtros = {
        dispositivo: document.getElementById('filtroDispositivo').value,
        estado: document.getElementById('filtroEstado').value,
        fecha_desde: document.getElementById('filtroFechaDesde').value,
        fecha_hasta: document.getElementById('filtroFechaHasta').value
    };
    
    const params = new URLSearchParams();
    Object.keys(filtros).forEach(key => {
        if (filtros[key]) {
            params.append(key, filtros[key]);
        }
    });
    
    const loading = document.getElementById('loadingCortes');
    const container = document.getElementById('tablaCortesContainer');
    
    loading.style.display = 'block';
    container.style.display = 'none';
    
    fetch(`<?= base_url('energia/getCortesFiltrados') ?>?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            container.style.display = 'block';
            
            if (data.success) {
                mostrarCortes(data.cortes);
                actualizarEstadisticas(data.estadisticas);
            } else {
                mostrarError('Error al aplicar filtros: ' + (data.error || 'Error desconocido'));
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            container.style.display = 'block';
            mostrarError('Error de conexión: ' + error.message);
        });
}

// Marcar corte como visto
function marcarComoVisto(idCorte) {
    fetch(`<?= base_url('energia/marcarCorteVisto') ?>/${idCorte}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Corte marcado como visto');
            cargarCortes();
            cargarEstadisticas();
        } else {
            alert('❌ Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error al marcar como visto');
    });
}

// Ver detalle del corte
function verDetalleCorte(idCorte) {
    fetch(`<?= base_url('energia/getDetalleCorte') ?>/${idCorte}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarModalDetalle(data.corte);
            } else {
                alert('Error al cargar detalles: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar detalles del corte');
        });
}

// Mostrar modal de detalle
function mostrarModalDetalle(corte) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'modalDetalleCorte';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border: 2px solid #D4AF37; border-radius: 20px;">
                <div class="modal-header" style="border-bottom: 2px solid #D4AF37; background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); border-radius: 18px 18px 0 0;">
                    <h5 class="modal-title text-dark fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Detalle del Corte de Línea
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-white">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-warning" style="background: rgba(255, 193, 7, 0.1);">
                                <div class="card-body">
                                    <h6 class="text-warning fw-bold">
                                        <i class="fas fa-microchip me-2"></i>Dispositivo
                                    </h6>
                                    <p class="mb-0">${corte.nombre_dispositivo || 'Dispositivo ' + corte.id_dispositivo}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info" style="background: rgba(13, 202, 240, 0.1);">
                                <div class="card-body">
                                    <h6 class="text-info fw-bold">
                                        <i class="fas fa-calendar me-2"></i>Fecha del Corte
                                    </h6>
                                    <p class="mb-0">${new Date(corte.fecha_corte).toLocaleString()}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-danger" style="background: rgba(220, 53, 69, 0.1);">
                                <div class="card-body">
                                    <h6 class="text-danger fw-bold">
                                        <i class="fas fa-bolt me-2"></i>Consumo Actual
                                    </h6>
                                    <p class="mb-0 fs-4">${parseFloat(corte.consumo_actual).toFixed(2)} kWh</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success" style="background: rgba(40, 167, 69, 0.1);">
                                <div class="card-body">
                                    <h6 class="text-success fw-bold">
                                        <i class="fas fa-chart-line me-2"></i>Límite Configurado
                                    </h6>
                                    <p class="mb-0 fs-4">${parseFloat(corte.limite_configurado).toFixed(2)} kWh</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-primary" style="background: rgba(13, 110, 253, 0.1);">
                                <div class="card-body">
                                    <h6 class="text-primary fw-bold">
                                        <i class="fas fa-calculator me-2"></i>Análisis del Corte
                                    </h6>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="fw-bold text-warning">${((parseFloat(corte.consumo_actual) / parseFloat(corte.limite_configurado)) * 100).toFixed(1)}%</div>
                                            <small class="text-muted">Porcentaje del Límite</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-danger">${(parseFloat(corte.consumo_actual) - parseFloat(corte.limite_configurado)).toFixed(2)} kWh</div>
                                            <small class="text-muted">Exceso de Consumo</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-info">${corte.resuelto == 1 ? 'Resuelto' : 'Activo'}</div>
                                            <small class="text-muted">Estado Actual</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ${corte.fecha_vista ? `
                        <div class="col-md-6">
                            <div class="card border-info" style="background: rgba(23, 162, 184, 0.1);">
                                <div class="card-body">
                                    <h6 class="text-info fw-bold">
                                        <i class="fas fa-eye me-2"></i>Fecha Vista
                                    </h6>
                                    <p class="mb-0">${new Date(corte.fecha_vista).toLocaleString()}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                        ${corte.fecha_resolucion ? `
                        <div class="col-md-6">
                            <div class="card border-success" style="background: rgba(40, 167, 69, 0.1);">
                                <div class="card-body">
                                    <h6 class="text-success fw-bold">
                                        <i class="fas fa-check-circle me-2"></i>Fecha Resolución
                                    </h6>
                                    <p class="mb-0">${new Date(corte.fecha_resolucion).toLocaleString()}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #D4AF37;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cerrar
                    </button>
                    ${corte.vista_por_usuario == 0 ? `
                        <button type="button" class="btn btn-success" onclick="marcarComoVisto(${corte.id}); bootstrap.Modal.getInstance(document.getElementById('modalDetalleCorte')).hide();">
                            <i class="fas fa-check me-2"></i>Marcar como Visto
                        </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Limpiar modal cuando se cierre
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

// Exportar cortes
function exportarCortes() {
    window.location.href = '<?= base_url('energia/exportarCortesExcel') ?>';
}

// Actualizar datos
function actualizarDatos() {
    cargarEstadisticas();
    cargarCortes();
}

// Actualizar estadísticas
function actualizarEstadisticas(estadisticas) {
    if (estadisticas) {
        document.getElementById('totalCortes').textContent = estadisticas.total_cortes;
        document.getElementById('cortesActivos').textContent = estadisticas.cortes_activos;
        document.getElementById('cortesResueltos').textContent = estadisticas.cortes_resueltos;
        document.getElementById('cortesVistos').textContent = estadisticas.cortes_vistos;
    }
}

// Mostrar error
function mostrarError(mensaje) {
    const tbody = document.getElementById('cuerpoTablaCortes');
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="text-center text-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${mensaje}
            </td>
        </tr>
    `;
}
</script>

<?= $this->endSection() ?>
