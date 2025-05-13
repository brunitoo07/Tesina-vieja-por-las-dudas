<?php
namespace App\Models;

use CodeIgniter\Model;

class MedicionesModel extends Model
{
    protected $table = 'mediciones';
    protected $primaryKey = 'id_medicion';
    protected $allowedFields = ['id_dispositivo', 'valor', 'unidad', 'fecha_medicion'];
    protected $useTimestamps = false;

    public function getMedicionesByDispositivo($idDispositivo)
    {
        return $this->where('id_dispositivo', $idDispositivo)->orderBy('fecha_medicion', 'DESC')->findAll();
    }
}