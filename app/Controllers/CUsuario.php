<?php

namespace App\Controllers;

class CUsuario extends BaseController
{
    public function perfil()
    {
        // Verificar si el usuario ha iniciado sesión
        if (!session()->has('userData')) {
            // Redirigir al login si no hay usuario en la sesión
            return redirect()->to('autenticacion/login');
        }

        // Obtener los datos del usuario desde la sesión
        $userData = session()->get('userData');
        $data['nombre'] = $_SESSION['userData']['nombre'];
        $data['apellido'] = $_SESSION['userData']['apellido'];
        $data['email'] = $_SESSION['userData']['email'];
        

        // Cargar la vista del perfil y pasar los datos del usuario
        return view('perfil/perfil', $data);
    }
}