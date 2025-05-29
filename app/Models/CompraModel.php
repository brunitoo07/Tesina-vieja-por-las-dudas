<?php
namespace App\Models;

use CodeIgniter\Model;

class CompraModel extends Model
{
    protected $table = 'compra';
    protected $primaryKey = 'id_compra';
    protected $allowedFields = ['id_usuario', 'direccion_envio', 'fecha_compra', 'estado', 'id_dispositivo'];
    protected $useTimestamps = false;
} 