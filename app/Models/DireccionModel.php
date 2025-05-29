<?php
namespace App\Models;

use CodeIgniter\Model;

class DireccionModel extends Model
{
    protected $table = 'direcciones';
    protected $primaryKey = 'direccion_id';
    protected $allowedFields = ['calle', 'numero', 'ciudad', 'codigo_postal', 'pais', 'id_usuario'];
    protected $useTimestamps = false;
} 