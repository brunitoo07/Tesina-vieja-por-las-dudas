<?php

namespace App\Controllers;

class ViviendasController extends BaseController
{
    public function index()
    {
        // Lógica para la página de viviendas
        return view('viviendas'); // Cargar la vista 'viviendas.php' en 'app/Views'
    }
}
