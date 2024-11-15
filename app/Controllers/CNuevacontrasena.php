<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsuarioModel;
use App\Models\CodigoModel;

class CNuevacontrasena extends Controller
{
    public function index()
    {
        // Verifica si hay un email válido en la sesión
        if (!session()->get('emailValido')) {
            session()->set('error', 'Ingrese su email para restablecer la contraseña.');
            return redirect()->to('autenticacion/correo');
        }

        return view('autenticacion/nueva-contrasena'); // Asegúrate de que el nombre del archivo sea correcta
    }

    public function actualizar()
    {
        $usuarioModel = new UsuarioModel();
        $codigoModel = new CodigoModel();

        $codigo = $this->request->getPost('codigo');
        $nuevaContrasena = $this->request->getPost('nueva_contrasena');
        $confirmarContrasena = $this->request->getPost('confirmar_contrasena');

        // Verificar el código
        $codigoData = $codigoModel->obtenerUsuarioPorCodigo($codigo);
        if (!$codigoData) {
            session()->set('error', 'Código inválido o expirado.');
            return redirect()->back();
        }

        $idUsuario = $codigoData['id_usuario'];
        $usuario = $usuarioModel->find($idUsuario);

        // Verificar que la nueva contraseña no sea igual a la antigua
        if (password_verify($nuevaContrasena, $usuario['contrasena'])) {
            session()->set('error', 'La nueva contraseña no puede ser igual a la contraseña anterior.');
            return redirect()->back();
        }

        // Validar que las contraseñas tengan al menos 6 caracteres, una mayúscula y un símbolo
        if (strlen($nuevaContrasena) < 6 || !preg_match('/[A-Z]/', $nuevaContrasena) || !preg_match('/[!@#$%]/', $nuevaContrasena)) {
            session()->set('error', 'La nueva contraseña debe tener al menos 6 caracteres, una letra mayúscula y un símbolo (!@#$%).');
            return redirect()->back();
        }

        // Validar que las contraseñas coincidan
        if ($nuevaContrasena !== $confirmarContrasena) {
            session()->set('error', 'Las contraseñas no coinciden.');
            return redirect()->back();
        }

        // Hashear la nueva contraseña
        $hashedContraseña = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

        // Actualizar la contraseña del usuario
        if ($usuarioModel->actualizarcontrasena($hashedContraseña, $idUsuario)) {
            // Eliminar el código de recuperación
            $codigoModel->eliminarCodigoPorUsuario($idUsuario);
            session()->set('exito', 'Contraseña actualizada correctamente.');
            return redirect()->to('autenticacion/login');
        } else {
            session()->set('error', 'Error al actualizar la contraseña.');
            return redirect()->back();
        }
    }
}
