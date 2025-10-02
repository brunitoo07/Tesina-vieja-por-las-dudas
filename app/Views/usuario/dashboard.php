<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4">
    <style>
        /* --- Paleta de Colores Elegante Oscura --- */
        :root {
            --gold: #D4AF37;
            --gold-light: #EAD58B;
            --gold-dark: #B89B2E;
            --black-bg: #121212; /* Fondo principal profundo */
            --black-card: #1E1E1E; /* Fondo para elementos elevados como tarjetas */
            --gray-border: #2F2F2F; /* Bordes sutiles */
            --text-primary: #EAEAEA; /* Texto principal claro */
            --text-secondary: #A0A0A0; /* Texto secundario o descriptivo */
            --white: #FFFFFF;

            --shadow-gold: 0 0 15px rgba(212, 175, 55, 0.2);
            --border-radius: 12px;
        }

        /* --- Estilos Base --- */
        body {
            background-color: var(--black-bg);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* --- Encabezado Principal --- */
        .theme-header {
            color: var(--black-bg);
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* --- Estilo General de Tarjetas --- */
        .card.theme {
            background: var(--black-card);
            border: 1px solid var(--gray-border);
            border-top: 4px solid var(--gold);
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
        }

        .card.theme:hover {
            transform: translateY(-5px);
            border-top-color: var(--gold-light);
            box-shadow: var(--shadow-gold);
        }

        .card.theme .card-header,
        .card.theme .card-footer {
            background-color: transparent;
            border-color: var(--gray-border);
            padding: 1.25rem;
        }

        .card.theme .card-header {
            color: var(--gold);
            font-weight: 600;
            border-bottom: 1px solid var(--gray-border);
            padding-bottom: 1rem;
        }
        
        .card.theme .card-header i {
            color: var(--gold);
        }

        .card.theme .card-footer a {
            color: var(--gold-light);
            text-decoration: none;
            font-weight: 500;
        }
        
        .card.theme .card-footer a:hover {
            color: var(--white);
        }
        
        /* --- Tarjetas de Estadísticas (Fila Superior) --- */
        .stat-card h4 {
            font-weight: 700;
            color: var(--white);
        }

        .stat-card p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .stat-card .icon-container {
            background-color: rgba(212, 175, 55, 0.1); /* Fondo dorado transparente */
            color: var(--gold-light);
            height: 50px;
            width: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* --- Botones --- */
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .btn-gold {
            background: linear-gradient(145deg, var(--gold-light), var(--gold));
            border: none;
            color: var(--black-bg);
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.2);
        }

        .btn-gold:hover {
            background: linear-gradient(145deg, var(--gold), var(--gold-dark));
            color: var(--black-bg);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-outline-gold {
            border: 2px solid var(--gold);
            color: var(--gold);
            background-color: transparent;
        }
        
        .btn-outline-gold:hover {
            background-color: var(--gold);
            color: var(--black-bg);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }
        
        /* --- Tabla --- */
        .table {
            color: var(--text-primary);
        }
        
        .table thead th {
            border: none;
            color: var(--gold-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
        }
        
        .table tbody tr {
            transition: background-color 0.2s;
            border-bottom: 1px solid var(--gray-border);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border: none;
        }

        .table tbody tr:hover {
            background-color: #2a2a2a; /* Un gris un poco más claro para el hover */
        }

        .badge {
            padding: 0.5em 0.8em;
            border-radius: 6px;
            font-weight: 600;
            color: var(--black-bg);
        }
        .badge.bg-success { background-color: #34c759 !important; }
        .badge.bg-warning { background-color: #ff9500 !important; }
        .badge.bg-danger { background-color: #ff3b30 !important; }
        .badge.bg-info { background-color: #007AFF !important; }
        .badge.bg-success, .badge.bg-warning, .badge.bg-danger, .badge.bg-info {
            color: var(--white);
        }

        /* --- Bienvenida --- */
        .welcome-card {
            background: linear-gradient(135deg, var(--black-card) 0%, #2a2a2a 100%);
            border: 1px solid var(--gray-border);
            border-top: 4px solid var(--gold);
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .welcome-card h1 {
            color: var(--gold);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-card p {
            color: var(--text-secondary);
            margin-bottom: 0;
        }
    </style>

    <!-- Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card">
                <div class="card-body">
                    <h1 class="h3">
                        <i class="fas fa-user-circle me-2" style="color: var(--gold)"></i>
                        Bienvenido, <?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                    </h1>
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i>
                        Email: <?= esc($usuario['email']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card theme stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><?= number_format($consumo24h ?? 0, 2) ?> kWh</h4>
                            <p class="mb-0">Consumo 24h</p>
                        </div>
                        <div class="icon-container">
                            <i class="fas fa-bolt fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card theme stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><?= number_format($consumoPromedio ?? 0, 2) ?> kWh</h4>
                            <p class="mb-0">Consumo Promedio Diario</p>
                        </div>
                        <div class="icon-container">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card theme stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><?= (int)($activosPropios ?? 0) ?></h4>
                            <p class="mb-0">Dispositivos Activos</p>
                        </div>
                        <div class="icon-container">
                            <i class="fas fa-plug fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card theme stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><?= count($dispositivos_propios ?? []) ?></h4>
                            <p class="mb-0">Total Dispositivos</p>
                        </div>
                        <div class="icon-container">
                            <i class="fas fa-microchip fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card theme">
                <div class="card-header">
                    <i class="fas fa-bolt me-1"></i>
                    Acciones Rápidas
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('perfil/perfil') ?>" class="btn btn-gold w-100">
                                <i class="fas fa-user me-2"></i> Ver Perfil
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('usuario/cambiarContrasena') ?>" class="btn btn-outline-gold w-100">
                                <i class="fas fa-key me-2"></i> Cambiar Contraseña
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('home/manual') ?>" class="btn btn-outline-gold w-100">
                                <i class="fas fa-book me-2"></i> Manual de Usuario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de dispositivos -->
    <div class="row">
        <div class="col-12">
            <div class="card theme">
                <div class="card-header">
                    <i class="fas fa-microchip me-1"></i>
                    Mis Dispositivos
                    <?php if (!empty($dispositivos_compartidos)): ?>
                        <span class="badge bg-info ms-2">Incluye dispositivos compartidos</span>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($dispositivos_propios) && empty($dispositivos_compartidos)): ?>
                        <div class="alert alert-dark mb-0 border-warning m-4">
                            <i class="fas fa-info-circle me-2 text-warning"></i>
                            No tienes dispositivos registrados.
                            <a href="<?= base_url('dispositivo/buscar') ?>" class="alert-link text-warning">
                                Buscar dispositivos disponibles
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table mb-0" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Propietario</th>
                                        <th>Estado</th>
                                        <th>Consumo Actual</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Combinar dispositivos propios y compartidos
                                    $todosDispositivos = array_merge($dispositivos_propios ?? [], $dispositivos_compartidos ?? []);
                                    foreach ($todosDispositivos as $dispositivo): 
                                        $esPropio = isset($dispositivo['id_usuario']) && (int)$dispositivo['id_usuario'] === (int)session()->get('id_usuario');
                                        $consumoActual = $dispositivo['ultima_lectura']['consumo_total'] ?? 0;
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($dispositivo['nombre']) ?></strong>
                                            <?php if (!$esPropio): ?>
                                                <i class="fas fa-share-alt ms-1 text-info" title="Dispositivo compartido"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($esPropio): ?>
                                                <span class="badge bg-success">Propio</span>
                                            <?php else: ?>
                                                <span class="badge bg-info"><?= esc($dispositivo['nombre_usuario'] ?? 'Admin') ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $dispositivo['estado'] === 'activo' ? 'success' : 'danger' ?>">
                                                <?= ucfirst(esc($dispositivo['estado'])) ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($consumoActual, 2) ?> kWh</td>
                                        <td class="text-end">
                                            <a href="<?= base_url('energia/dispositivo/' . $dispositivo['id_dispositivo']) ?>" 
                                               class="btn btn-sm btn-outline-gold">
                                                <i class="fas fa-chart-bar"></i> Ver Consumo
                                            </a>
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
    </div>
</div>

<!-- Script para inicializar DataTables -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "pageLength": 10,
            "responsive": true,
            "order": [[ 0, "asc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 4 } // Deshabilitar ordenamiento en la columna de acciones
            ]
        });
    });
</script>
<?= $this->endSection() ?> 