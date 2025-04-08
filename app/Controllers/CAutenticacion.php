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

        $purchase = $this->request->getGet('purchase') === 'true';
        // Pasar el valor a la vista y usarlo en un campo oculto
        return view('autenticacion/register', [
            'purchase' => $purchase,
            'message' => $purchase ? '¡Complete su registro como ADMINISTRADOR!' : null
        ]);
    }

    public function registrarse()
{
    $usuarioModel = new UsuarioModel();
    
    $nombre = $this->request->getPost('nombre');
    $apellido = $this->request->getPost('apellido');
    $email = $this->request->getPost('email');
    $contrasena = $this->request->getPost('contrasena');
    $purchase = $this->request->getPost('purchase') === 'true' || 
                $this->request->getGet('purchase') === 'true'; // Captura GET y POST

    // Validación de nombre (solo letras y espacios)
    if (!preg_match('/^[a-zA-Z\s]+$/', $nombre)) {
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'El nombre solo puede contener letras y espacios.');
    }
    
    // Validación de apellido (solo letras y espacios)
    if (!preg_match('/^[a-zA-Z\s]+$/', $apellido)) {
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'El apellido solo puede contener letras y espacios.');
    }

    // Verificar si el email ya existe
    if ($usuarioModel->existenteEmail($email)) {
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'El correo electrónico ya está registrado.');
    }

    // Validación de contraseña
    if (strlen($contrasena) < 6 || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[!@#$%]/', $contrasena)) {
        return redirect()->back()
                       ->withInput()
                       ->with('password_error', 'La contraseña debe tener: 6+ caracteres, 1 mayúscula y 1 símbolo (!@#$%).');
    }
    
    $dataUsuario = [
        'nombre' => $nombre,
        'apellido' => $apellido,
        'email' => $email,
        'contrasena' => $contrasena,
        'id_rol' => $purchase ? 1 : 2, // 1=admin, 2=usuario
        'estado' => 'activo'
    ];

    try {
        if (!$usuarioModel->insertarUsuario($dataUsuario)) {
            throw new \RuntimeException('Error al insertar usuario en la base de datos');
        }

        $mensaje = $purchase 
            ? '¡Registro como administrador exitoso! Ahora puedes iniciar sesión.' 
            : 'Registro exitoso. Bienvenido/a.';
            
        return redirect()->to('autenticacion/login')
                       ->with('success', $mensaje);

    } catch (\Exception $e) {
        log_message('error', 'Error en registro: ' . $e->getMessage());
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error durante el registro. Por favor intente nuevamente.');
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