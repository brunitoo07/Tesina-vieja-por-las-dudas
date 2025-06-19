<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<h2 class="mb-4 text-primary"><?= lang('App.invited_users') ?></h2>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?php if (empty($usuarios)) : ?>
    <div class="alert alert-info"><?= lang('App.no_invited_users') ?></div>
<?php else : ?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th><?= lang('App.name') ?></th>
            <th><?= lang('App.email') ?></th>
            <th><?= lang('App.current_role') ?></th>
            <th><?= lang('App.change_role') ?></th>
            <th><?= lang('App.delete') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= esc($usuario['id_usuario']) ?></td>
                <td><?= esc($usuario['nombre']) ?></td>
                <td><?= esc($usuario['email']) ?></td>
                <td><?= esc($usuario['rol']) ?></td>

                <td>
                    <form action="<?= base_url('admin/cambiarRol') ?>" method="post">
                        <input type="hidden" name="usuario_id" value="<?= $usuario['id_usuario'] ?>">
                        <select name="id_rol" class="form-control" onchange="this.form.submit()">
                            <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>><?= lang('App.admin') ?></option>
                            <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>><?= lang('App.user') ?></option>
                        </select>
                    </form>
                </td>

                <td>
                    <form action="<?= base_url('admin/eliminarUsuario') ?>" method="post" class="d-inline">
                        <input type="hidden" name="usuario_id" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('<?= lang('App.confirm_delete_user') ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?= $this->endSection() ?>
