<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\DispositivoModel;

class CAutenticacion extends BaseController
{
    protected $usuarioModel;
    protected $dispositivoModel; // <--- Declara el modelo

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->dispositivoModel = new DispositivoModel(); // <--- Inicializa el modelo
    }
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
        $nuevoAdminId = $usuarioModel->getInsertID(); // Obtener el ID del usuario recién insertado

        if ($purchase) {
            // Simular la generación de una MAC address aleatoria solo para administradores
            $macSimulada = $this->generarMacAleatoria();

            // Guardar el dispositivo simulado y asociarlo al administrador
            $this->dispositivoModel->save([
                'id_usuario' => $nuevoAdminId,
                'nombre' => 'Dispositivo Admin ' . $nuevoAdminId, // Nombre por defecto
                'mac_address' => $macSimulada,
                'estado' => 'activo' // Estado inicial
            ]);
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

private function generarMacAleatoria() {
    $hexDigits = '0123456789abcdef';
    $mac = '';
    for ($i = 0; $i < 6; $i++) {
        $mac .= str_repeat($hexDigits[rand(0, 15)], 2);
        if ($i < 5) {
            $mac .= ':';
        }
    }
    return strtoupper($mac);
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
        $email = $this->request->getPost('email');
        $contrasena = $this->request->getPost('contrasena');

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('email', $email)->first();

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
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
            }

            $userData = [
                'id_usuario' => $usuario['id_usuario'],
                'email' => $usuario['email'],
                'rol' => $rol,
                'logged_in' => true
            ];

            // Escribir logs directamente en un archivo
            $logFile = WRITEPATH . 'logs/auth_debug.log';
            $logMessage = date('Y-m-d H:i:s') . " - Usuario encontrado: " . print_r($usuario, true) . "\n";
            $logMessage .= date('Y-m-d H:i:s') . " - ID Rol del usuario: " . $usuario['id_rol'] . "\n";
            $logMessage .= date('Y-m-d H:i:s') . " - Rol asignado: " . $rol . "\n";
            $logMessage .= date('Y-m-d H:i:s') . " - Datos de usuario guardados en sesión: " . print_r($userData, true) . "\n";
            
            file_put_contents($logFile, $logMessage, FILE_APPEND);

            session()->set($userData);
            
            // Verificar si los datos se guardaron correctamente
            $sessionData = session()->get();
            $logMessage = date('Y-m-d H:i:s') . " - Datos en sesión después de guardar: " . print_r($sessionData, true) . "\n";
            file_put_contents($logFile, $logMessage, FILE_APPEND);

            return redirect()->to('/' . $rol);
        }

        return redirect()->back()->with('error', 'Email o contraseña incorrectos');
    }
    
    
    public function cerrarSesion()
    {
        session()->destroy();
        return redirect()->to('autenticacion/login');
    }
}