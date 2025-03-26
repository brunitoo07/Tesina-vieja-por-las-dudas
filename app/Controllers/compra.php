<?php

namespace App\Controllers;

class Compra extends BaseController
{
    public function index()
    {
        return view('compra/index');
    }

    public function completada()
    {
        return view('compra/completada');
    }
}
