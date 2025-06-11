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
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verificar contraseña actual
        $usuario = $this->usuarioModel->find($idUsuario);
        if (!password_verify($currentPassword, $usuario['password'])) {
            return redirect()->back()->withInput()->with('error', 'La contraseña actual es incorrecta');
        }

        try {
            $this->usuarioModel->update($idUsuario, [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT)
            ]);
            return redirect()->to('usuario/perfil')->with('success', 'Contraseña actualizada correctamente');
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar contraseña: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al cambiar la contraseña');
        }
    }
} 