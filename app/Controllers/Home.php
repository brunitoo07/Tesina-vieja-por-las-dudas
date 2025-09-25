<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            $rol = session()->get('rol');
            if ($rol === 'admin') {
                return redirect()->to('admin');
            } elseif ($rol === 'supervisor') {
                return redirect()->to('supervisor');
            } else {
                return redirect()->to('usuario');
            }
        }
        return view('home/index');
    }

    // Eliminamos la ruta de bienvenida del flujo
    public function bienvenida()
    {
        return redirect()->to('/');
    }

    public function manual()
    {
        return view('home/manual');
    }

    public function cambiar_idioma($lang)
    {
        $session = session();
        $lang = in_array($lang, ['es', 'en']) ? $lang : 'es';
        $session->set('locale', $lang);
        return redirect()->to('/');

    }
}
