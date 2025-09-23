<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
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
            color: var(--white);
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
        .badge.bg-success, .badge.bg-warning, .badge.bg-danger {
            color: var(--white);
        }
    </style>

    <h1 class="mt-4 mb-4 theme-header">
        <i class="fas fa-crown me-2" style="color:var(--gold)"></i>
        <?= lang('App.admin_panel') ?>
    </h1>
    
    <div class="row">
        <!-- Tarjetas de Estadísticas -->
        <?php 
            $stats = [
                ['title' => lang('App.devices'), 'subtitle' => lang('App.manage_esp32'), 'icon' => 'fa-microchip', 'link' => 'admin/dispositivos'],
                ['title' => lang('App.users'), 'subtitle' => lang('App.manage_users'), 'icon' => 'fa-users', 'link' => 'admin/gestionarUsuarios'],
                ['title' => lang('App.invitations'), 'subtitle' => lang('App.invite_new_users'), 'icon' => 'fa-envelope', 'link' => 'admin/invitar'],
                ['title' => 'Energía', 'subtitle' => 'Monitorea el consumo', 'icon' => 'fa-bolt', 'link' => 'admin/dispositivos'],
            ];
        ?>
        <?php foreach($stats as $stat): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card theme stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><?= $stat['title'] ?></h4>
                            <p class="mb-0"><?= $stat['subtitle'] ?></p>
                        </div>
                        <div class="icon-container">
                            <i class="fas <?= $stat['icon'] ?> fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a class="stretched-link" href="<?= base_url($stat['link']) ?>">
                        Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <!-- Acciones Rápidas -->
        <div class="col-xl-6 mb-4">
            <div class="card theme h-100">
                <div class="card-header">
                    <i class="fas fa-bolt me-1"></i>
                    <?= lang('App.quick_actions') ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <a href="http://192.168.2.178/Tesina/public/dispositivo/agregar" class="btn btn-gold w-100">
                                <i class="fas fa-plus me-2"></i> Registrar dispositivo
                            </a>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <a href="<?= base_url('admin/invitar') ?>" class="btn btn-outline-gold w-100">
                                <i class="fas fa-user-plus me-2"></i> Invitar usuario
                            </a>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <a href="<?= base_url('admin/dispositivos/buscar') ?>" class="btn btn-outline-gold w-100">
                                <i class="fas fa-search me-2"></i> Buscar dispositivos
                            </a>
                        </div>
                        <div class="col-sm-6 mb-3">
                             <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-outline-gold w-100">
                                <i class="fas fa-chart-line me-2"></i> Ver dispositivos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimos Dispositivos -->
        <div class="col-xl-6 mb-4">
            <div class="card theme h-100">
                <div class="card-header">
                    <i class="fas fa-microchip me-1"></i>
                    <?= lang('App.latest_devices') ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th><?= lang('App.name') ?></th>
                                    <th><?= lang('App.status') ?></th>
                                    <th class="text-end"><?= lang('App.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($ultimosDispositivos)): ?>
                                    <?php foreach ($ultimosDispositivos as $dispositivo): ?>
                                        <tr>
                                            <td><strong><?= esc($dispositivo['nombre']) ?></strong></td>
                                            <td>
                                                <span class="badge bg-<?= $dispositivo['estado'] === 'activo' ? 'success' : ($dispositivo['estado'] === 'pendiente' ? 'warning' : 'danger') ?>">
                                                    <?= ucfirst(esc($dispositivo['estado'])) ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= base_url('admin/dispositivos') ?>" class="btn btn-sm btn-outline-gold">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center p-4">
                                            <div class="alert alert-dark mb-0 border-warning">
                                                <i class="fas fa-info-circle me-2 text-warning"></i>
                                                <?= lang('App.no_devices') ?>
                                                <a href="<?= base_url('admin/dispositivos/registrar') ?>" class="alert-link text-warning">
                                                    <?= lang('App.register_first_device') ?>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('chat_profesional') ?>
<?= $this->endSection() ?>
