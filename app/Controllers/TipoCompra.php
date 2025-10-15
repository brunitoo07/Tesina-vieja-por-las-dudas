<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TipoCompra extends BaseController
{
    public function index()
    {
        return view('compra/tipo_compra');
    }
}
