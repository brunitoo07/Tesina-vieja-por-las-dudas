<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class SeleccionPostLogin extends BaseController
{
    public function index()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('autenticacion/login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        return view('autenticacion/seleccion_post_login');
    }
}
