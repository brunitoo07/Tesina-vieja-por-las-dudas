<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nombre_rol', 'descripcion'];

    public function getRolAdmin()
    {
        return $this->where('nombre_rol', 'admin')->first();
    }

    public function getRolUsuario()
    {
        return $this->where('nombre_rol', 'usuario')->first();
    }
} 