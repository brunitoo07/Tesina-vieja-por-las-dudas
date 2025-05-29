<?= session()->getFlashdata('error') ? '<div class="alert alert-danger">'.session()->getFlashdata('error').'</div>' : '' ?>
<form action="<?= base_url('registro-compra/procesar') ?>" method="post">
    <input type="text" name="nombre" placeholder="Nombre" class="form-control mb-2" required>
    <input type="text" name="apellido" placeholder="Apellido" class="form-control mb-2" required>
    <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
    <input type="password" name="contrasena" placeholder="Contraseña" class="form-control mb-2" required>
    <hr>
    <input type="text" name="calle" placeholder="Calle" class="form-control mb-2" required>
    <input type="text" name="numero" placeholder="Número" class="form-control mb-2" required>
    <input type="text" name="ciudad" placeholder="Ciudad" class="form-control mb-2" required>
    <input type="text" name="codigo_postal" placeholder="Código Postal" class="form-control mb-2" required>
    <input type="text" name="pais" placeholder="País" class="form-control mb-2" required>
    <button type="submit" class="btn btn-success">Comprar y continuar al pago</button>
</form> 