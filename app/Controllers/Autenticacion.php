<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Autenticacion extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Depuración
            log_message('debug', 'Intento de login con email: ' . $email);

            $usuario = $this->usuarioModel->where('email', $email)->first();

            if ($usuario) {
                log_message('debug', 'Usuario encontrado: ' . print_r($usuario, true));
                
                if (password_verify($password, $usuario['password'])) {
                    // Guardar datos en la sesión
                    $sessionData = [
                        'user_id' => (int)$usuario['id_usuario'],  // Asegurarnos de que es un entero
                        'email' => $usuario['email'],
                        'rol' => $usuario['id_rol'] == 1 ? 'admin' : 'usuario',
                        'logged_in' => true
                    ];
                    
                    log_message('debug', 'Datos a guardar en sesión: ' . print_r($sessionData, true));
                    
                    session()->set($sessionData);
                    
                    // Verificar que se guardó correctamente
                    log_message('debug', 'Datos guardados en sesión: ' . print_r(session()->get(), true));

                    // Redirigir según el rol
                    if ($usuario['id_rol'] == 1) {
                        return redirect()->to('/admin');
                    } else {
                        return redirect()->to('/home/bienvenida');
                    }
                } else {
                    log_message('error', 'Contraseña incorrecta para el usuario: ' . $email);
                }
            } else {
                log_message('error', 'Usuario no encontrado: ' . $email);
            }
            
            return redirect()->back()->with('error', 'Credenciales inválidas');
        }

        return view('autenticacion/login');
    }
} 