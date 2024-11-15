<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Asegúrate de que solo los usuarios autenticados puedan ver esta página
        if (!session()->get('userData')) {
            return redirect()->to('autenticacion/login');
        }

        return view('home/bienvenida'); // Vista de bienvenida
    }
}
