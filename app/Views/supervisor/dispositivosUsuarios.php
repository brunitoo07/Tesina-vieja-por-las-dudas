<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Todos los Dispositivos del Sistema</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>MAC Address</th>
                            <th>Estado</th>
                            <th>Admin dueño</th>
                            <th>Usuarios invitados</th>
                            <th>Última Lectura</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dispositivos as $dispositivo): ?>
                        <tr>
                            <td><?= esc($dispositivo['id_dispositivo']) ?></td>
                            <td><?= esc($dispositivo['nombre']) ?></td>
                            <td><?= esc($dispositivo['mac_address']) ?></td>
                            <td>
                                <span class="badge <?= $dispositivo['estado'] == 'activo' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ucfirst($dispositivo['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <?= esc(($dispositivo['nombre_admin'] ?? '') . ' ' . ($dispositivo['apellido_admin'] ?? '')) ?><br>
                                <small><?= esc($dispositivo['email_admin'] ?? '') ?></small>
                            </td>
                            <td>
                                <?php if (!empty($usuariosInvitadosPorDispositivo[$dispositivo['id_dispositivo']])): ?>
                                    <ul class="mb-0">
                                    <?php foreach ($usuariosInvitadosPorDispositivo[$dispositivo['id_dispositivo']] as $usuario): ?>
                                        <li><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?><br><small><?= esc($usuario['email']) ?></small></li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= isset($dispositivo['ultima_lectura']) && $dispositivo['ultima_lectura'] ? date('d/m/Y H:i', strtotime($dispositivo['ultima_lectura'])) : 'Sin lecturas' ?></td>
                            <td>
                                <a href="#" class="btn btn-secondary btn-sm mb-1" title="Ver Detalles" onclick="verDetalles(this)" data-nombre="<?= esc($dispositivo['nombre']) ?>" data-mac="<?= esc($dispositivo['mac_address']) ?>" data-estado="<?= esc($dispositivo['estado']) ?>" data-admin="<?= esc(($dispositivo['nombre_admin'] ?? '') . ' ' . ($dispositivo['apellido_admin'] ?? '')) ?>" data-email-admin="<?= esc($dispositivo['email_admin'] ?? '') ?>" data-lectura="<?= isset($dispositivo['ultima_lectura']) && $dispositivo['ultima_lectura'] ? date('d/m/Y H:i', strtotime($dispositivo['ultima_lectura'])) : 'Sin lecturas' ?>">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-danger btn-sm mb-1" onclick="eliminarDispositivo(<?= $dispositivo['id_dispositivo'] ?>)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                                <a href="<?= base_url('consumo/ver/' . $dispositivo['id_dispositivo']) ?>" class="btn btn-info btn-sm mb-1">
                                    <i class="fas fa-chart-line"></i> Ver Consumo
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="alerta-exito" class="alert alert-success d-none" role="alert">
    ¡Dispositivo eliminado correctamente!
</div>

<!-- Modal Detalles Dispositivo -->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetallesLabel">Detalles del Dispositivo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item"><strong>Nombre:</strong> <span id="detalle-nombre"></span></li>
          <li class="list-group-item"><strong>MAC Address:</strong> <span id="detalle-mac"></span></li>
          <li class="list-group-item"><strong>Estado:</strong> <span id="detalle-estado"></span></li>
          <li class="list-group-item"><strong>Admin dueño:</strong> <span id="detalle-admin"></span></li>
          <li class="list-group-item"><strong>Email admin:</strong> <span id="detalle-email-admin"></span></li>
          <li class="list-group-item"><strong>Última Lectura:</strong> <span id="detalle-lectura"></span></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
function eliminarDispositivo(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este dispositivo?')) {
        fetch('<?= base_url('supervisor/eliminarDispositivo/') ?>' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('alerta-exito').classList.remove('d-none');
                setTimeout(() => { location.reload(); }, 1200);
            } else {
                alert('Error al eliminar el dispositivo: ' + data.message);
            }
        });
    }
}

function verDetalles(btn) {
    document.getElementById('detalle-nombre').textContent = btn.getAttribute('data-nombre');
    document.getElementById('detalle-mac').textContent = btn.getAttribute('data-mac');
    document.getElementById('detalle-estado').textContent = btn.getAttribute('data-estado');
    document.getElementById('detalle-admin').textContent = btn.getAttribute('data-admin');
    document.getElementById('detalle-email-admin').textContent = btn.getAttribute('data-email-admin');
    document.getElementById('detalle-lectura').textContent = btn.getAttribute('data-lectura');
    new bootstrap.Modal(document.getElementById('modalDetalles')).show();
}
</script>

<?= $this->endSection() ?> 