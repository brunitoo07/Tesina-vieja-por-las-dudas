<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Login extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function autenticar()
    {
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');

        log_message('debug', 'Intento de inicio de sesión para email: ' . $email);

        $usuario = $this->usuarioModel->where('email', $email)->first();
        
        if (!$usuario) {
            log_message('debug', 'Usuario no encontrado para email: ' . $email);
            return redirect()->back()->with('error', 'Credenciales inválidas.');
        }

        log_message('debug', 'Usuario encontrado: ' . json_encode([
            'id' => $usuario['id_usuario'],
            'email' => $usuario['email'],
            'estado' => $usuario['estado'],
            'rol' => $usuario['id_rol']
        ]));

        if ($usuario['estado'] !== 'activo') {
            log_message('debug', 'Usuario no activo: ' . $usuario['estado']);
            return redirect()->back()->with('error', 'Tu cuenta no está activa. Por favor, verifica tu email.');
        }

        if (!password_verify($contrasena, $usuario['contrasena'])) {
            log_message('debug', 'Contraseña incorrecta para usuario: ' . $email);
            return redirect()->back()->with('error', 'Credenciales inválidas.');
        }

        log_message('debug', 'Inicio de sesión exitoso para usuario: ' . $email);

        // Crear sesión
        $sesionData = [
            'id_usuario' => $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'id_rol' => $usuario['id_rol'],
            'logged_in' => true
        ];

        session()->set($sesionData);

        // Redirigir según el rol
        if ($usuario['id_rol'] == 1) { // admin
            return redirect()->to('admin/dashboard');
        } else {
            return redirect()->to('dashboard');
        }
    }
} 