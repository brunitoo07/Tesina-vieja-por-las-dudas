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
        return view('autenticacion/register');
    }

    public function registrarse()
    {
        $usuarioModel = new UsuarioModel();
    
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');
    
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

        if ($usuarioModel->existenteEmail($email)) {
            session()->set('error', 'El correo electrónico ya está registrado.');
            return redirect()->to('autenticacion/register');
        }

        // Validar que la contraseña tenga al menos 6 caracteres, una mayúscula y un símbolo
        if (strlen($contrasena) < 6 || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[!@#$%]/', $contrasena)) {
            session()->set('password_error', 'La contraseña debe tener al menos 6 caracteres, una letra mayúscula y un símbolo (!@#$%).');
            return redirect()->to('autenticacion/register'); // Corregido
        }
        
        $array = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contrasena' => password_hash($contrasena, PASSWORD_BCRYPT),
        ];
    
        if ($usuarioModel->insertarUsuario($array)) {
            session()->set('exito', 'Usuario registrado.');     
            return redirect()->to('autenticacion/login');   
        } else {
            session()->set('error', 'Error al registrar el usuario. Inténtalo de nuevo.');
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
    
        // Establecer la sesión del usuario y redirigir
        session()->set('userData', $informacionUsuario);
        session()->set('Tipo', 'usuario');
        
        log_message('debug', 'Inicio de sesión exitoso para el usuario: ' . $email);
        return redirect()->to('home/bienvenida');
    }
    
    public function cerrarSesion()
    {
        session()->destroy();
        return redirect()->to('autenticacion/login');
    }
}
