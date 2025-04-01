<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class CAutenticacion extends BaseController
{
    public function login()
    {
        if (session()->get('userData')) {
            return redirect()->to('home/bienvenida');
        }

        return view('autenticacion/login');
    }

    public function register()
    {
        if (session()->get('userData')) {
            return redirect()->to('autenticacion/login');
        }

        // Verificar si viene de una compra exitosa
        $purchase = $this->request->getGet('purchase') === 'true';
        return view('autenticacion/register', ['purchase' => $purchase]);
    }

    public function registrarse()
    {
        $usuarioModel = new UsuarioModel();
    
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');
        $purchase = $this->request->getPost('purchase') === 'true' || $this->request->getGet('purchase') === 'true';
    
        // Validación de nombre completo (solo letras)
        if (!preg_match('/^[a-zA-Z\s]+$/', $nombre)) {
            session()->set('error', 'El nombre solo puede contener letras.');
            return redirect()->to('autenticacion/register');
        }
        
        // Validación de apellido (solo letras)
        if (!preg_match('/^[a-zA-Z\s]+$/', $apellido)) {
            session()->set('error', 'El apellido solo puede contener letras.');
            return redirect()->to('autenticacion/register');
        }

        // Verificar si el email ya existe
        if ($usuarioModel->existenteEmail($email)) {
            session()->set('error', 'El correo electrónico ya está registrado.');
            return redirect()->to('autenticacion/register');
        }

        // Validar que la contraseña tenga al menos 6 caracteres, una mayúscula y un símbolo
        if (strlen($contrasena) < 6 || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[!@#$%]/', $contrasena)) {
            session()->set('password_error', 'La contraseña debe tener al menos 6 caracteres, una letra mayúscula y un símbolo (!@#$%).');
            return redirect()->to('autenticacion/register'); 
        }
        
        $array = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contrasena' => $contrasena,
            'id_rol' => $purchase ? 1 : 2, // 1 para admin, 2 para usuario normal
            'estado' => 'activo'
        ];
    
        try {
            if ($usuarioModel->insertarUsuario($array)) {
                if ($purchase) {
                    session()->set('exito', '¡Usuario registrado como administrador! Por favor, inicia sesión para configurar tu cuenta.');     
                } else {
                    session()->set('exito', 'Usuario registrado exitosamente. Por favor, inicia sesión.');     
                }
                return redirect()->to('autenticacion/login');   
            } else {
                session()->set('error', 'Error al registrar el usuario. Inténtalo de nuevo.');
                return redirect()->to('autenticacion/register');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al registrar usuario: ' . $e->getMessage());
            session()->set('error', 'Error al registrar el usuario. Por favor, inténtalo de nuevo.');
            return redirect()->to('autenticacion/register');
        }
    }

    public function iniciarSesion()
    {
        $usuarioModel = new UsuarioModel();
    
        // Obtener los datos del formulario
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');
    
        // Verificar que los campos no estén vacíos
        if (empty($email) || empty($contrasena)) {
            session()->set('error', 'Por favor, completa todos los campos.');
            return redirect()->to('autenticacion/login');
        }
    
        // Obtener la información del usuario basado en el email
        $informacionUsuario = $usuarioModel->obtenerUsuarioEmail($email);
    
        // Verificar si el usuario existe y si la contraseña es correcta
        if ($informacionUsuario === null || !password_verify($contrasena, $informacionUsuario['contrasena'])) {
            session()->set('error', 'Correo electrónico o contraseña incorrecto.');
            log_message('error', 'Intento de inicio de sesión fallido. Email: ' . $email);
            return redirect()->to('autenticacion/login');
        }
    
        // Obtener el rol del usuario
        $rol = $usuarioModel->obtenerRolUsuario($informacionUsuario['id_usuario']);
        
        // Verificar si se encontró el rol
        if ($rol === null) {
            log_message('error', 'No se encontró el rol para el usuario: ' . $email);
            session()->set('error', 'Error al obtener el rol del usuario. Por favor, contacta al administrador.');
            return redirect()->to('autenticacion/login');
        }
    
        // Establecer la sesión del usuario y redirigir
        session()->set('userData', $informacionUsuario);
        session()->set('Tipo', 'usuario');
        session()->set('logged_in', true);
        session()->set('rol', $rol['nombre_rol']);

        log_message('debug', 'Inicio de sesión exitoso para el usuario: ' . $email);
        log_message('debug', 'Rol del usuario: ' . $rol['nombre_rol']);
    
        // Verificar la sesión antes de redirigir
        log_message('debug', 'Session userData: ' . json_encode(session()->get('userData')));
        return redirect()->to('home/bienvenida');
    }
    
    
    public function cerrarSesion()
    {
        session()->destroy();
        return redirect()->to('autenticacion/login');
    }
}