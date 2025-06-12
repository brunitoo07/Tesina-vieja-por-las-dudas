<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Usuario extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function perfil()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);

        // Determinar el rol basado en id_rol
        $rol = '';
        switch ($usuario['id_rol']) {
            case 1:
                $rol = 'admin';
                break;
            case 2:
                $rol = 'usuario';
                break;
            case 3:
                $rol = 'supervisor';
                break;
            default:
                $rol = 'usuario'; // Rol por defecto
                break;
        }

        // Agregar el rol al array de usuario
        $usuario['rol'] = $rol;

        $data = [
            'usuario' => $usuario,
            'titulo' => 'Mi Perfil'
        ];

        return view('dashboard/perfil', $data);
    }

    public function actualizarPerfil()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        $rules = [
            'nombre' => 'required|min_length[3]',
            'apellido' => 'required|min_length[3]',
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email' => $this->request->getPost('email')
        ];

        try {
            $this->usuarioModel->update($idUsuario, $data);
            return redirect()->to('usuario/perfil')->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar perfil: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el perfil');
        }
    }

    public function cambiarContrasena()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $current_password = $this->request->getPost('current_password');
        $new_password = $this->request->getPost('new_password');
        $confirm_password = $this->request->getPost('confirm_password');

        // Validar campos vacíos
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            return redirect()->to('/dashboard/perfil')->with('error', 'Todos los campos son obligatorios');
        }

        // Validar que la nueva contraseña cumpla con los requisitos
        if (strlen($new_password) < 6 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[!@#$%]/', $new_password)) {
            return redirect()->to('/dashboard/perfil')->with('error', 'La contraseña debe tener al menos 6 caracteres, una mayúscula y un símbolo (!@#$%)');
        }

        // Validar que las contraseñas coincidan
        if ($new_password !== $confirm_password) {
            return redirect()->to('/dashboard/perfil')->with('error', 'Las contraseñas no coinciden');
        }

        // Obtener el usuario actual
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);

        // Verificar la contraseña actual
        if (!password_verify($current_password, $usuario['contrasena'])) {
            return redirect()->to('/dashboard/perfil')->with('error', 'La contraseña actual es incorrecta');
        }

        // Actualizar la contraseña
        $this->usuarioModel->update($idUsuario, [
            'contrasena' => password_hash($new_password, PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/dashboard/perfil')->with('success', 'Contraseña actualizada correctamente');
    }
} 