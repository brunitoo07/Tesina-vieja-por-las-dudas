<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('home/bienvenida');
        }
        return view('home/index');
    }

    public function bienvenida()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('autenticacion/login');
        }
        return view('home/bienvenida');
    }

    public function manual()
    {
        return view('home/manual');
    }
}
