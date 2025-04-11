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
        $email = session()->get('emailValido');

        log_message('debug', 'Datos recibidos: ' . print_r([
            'codigo' => $codigo,
            'email' => $email,
            'nueva_contrasena' => $nuevaContrasena,
            'confirmar_contrasena' => $confirmarContrasena
        ], true));

        if (!$email) {
            session()->set('error', 'No se encontró el email del usuario.');
            return redirect()->to('autenticacion/login');
        }

        // Obtener el usuario por email
        $usuario = $usuarioModel->where('email', $email)->first();
        if (!$usuario) {
            session()->set('error', 'Usuario no encontrado.');
            return redirect()->to('autenticacion/login');
        }

        log_message('debug', 'Usuario encontrado: ' . print_r($usuario, true));

        // Verificar el código
        $codigoData = $codigoModel->where('id_usuario', $usuario['id_usuario'])
                                 ->where('codigo', $codigo)
                                 ->where('expiracion >', date('Y-m-d H:i:s'))
                                 ->first();

        log_message('debug', 'Código encontrado: ' . print_r($codigoData, true));

        if (!$codigoData) {
            session()->set('error', 'Código inválido o expirado.');
            session()->setFlashdata('codigo', $codigo);
            return redirect()->back();
        }

        // Validar que las contraseñas tengan al menos 6 caracteres, una mayúscula y un símbolo
        if (strlen($nuevaContrasena) < 6 || !preg_match('/[A-Z]/', $nuevaContrasena) || !preg_match('/[!@#$%]/', $nuevaContrasena)) {
            session()->set('error', 'La nueva contraseña debe tener al menos 6 caracteres, una letra mayúscula y un símbolo (!@#$%).');
            session()->setFlashdata('codigo', $codigo);
            return redirect()->back();
        }

        // Validar que las contraseñas coincidan
        if ($nuevaContrasena !== $confirmarContrasena) {
            session()->set('error', 'Las contraseñas no coinciden.');
            session()->setFlashdata('codigo', $codigo);
            return redirect()->back();
        }

        // Hashear la nueva contraseña
        $hashedContraseña = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

        // Actualizar la contraseña del usuario
        if ($usuarioModel->update($usuario['id_usuario'], ['contrasena' => $hashedContraseña])) {
            // Eliminar el código usado
            $codigoModel->delete($codigoData['id_codigo']);
            session()->set('exito', 'Contraseña actualizada correctamente.');
            session()->remove('emailValido'); // Limpiar el email de la sesión
            return redirect()->to('autenticacion/login');
        } else {
            session()->set('error', 'Error al actualizar la contraseña.');
            session()->setFlashdata('codigo', $codigo);
            return redirect()->back();
        }
    }
}
