<?php

namespace App\Models;

use CodeIgniter\Model;

class EnergiaModel extends Model
{
    protected $table = 'energia';
    protected $primaryKey = 'id';
    protected $allowedFields = ['voltaje', 'corriente', 'potencia', 'kwh', 'fecha', 'id_usuario'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Método para obtener el límite de consumo (debes asegurarte de tener un campo o tabla para esto)
    public function getLimiteConsumo($id_usuario = null)
    {
        $builder = $this->builder();
        if ($id_usuario) {
            $builder->where('id_usuario', $id_usuario);
        }
        $ultimoRegistro = $builder->orderBy('id', 'DESC')
                                 ->limit(1)
                                 ->get()
                                 ->getRow();
        return $ultimoRegistro ? $ultimoRegistro->limite_consumo : 10;
    }

    public function updateLimiteConsumo($nuevoLimite)
    {
        // Aquí deberías tener un campo "limite_consumo" en la tabla "energia"
        return $this->db->table($this->table)
            ->set('limite_consumo', $nuevoLimite)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->update();
    }

    // Otros métodos de tu modelo
    public function getConsumoDiario($id_usuario = null)
    {
        $builder = $this->builder();
        if ($id_usuario) {
            $builder->where('id_usuario', $id_usuario);
        }
        $hoy = date('Y-m-d');
        $consumo = $builder->select('SUM(kwh) as total')
                          ->where('DATE(fecha)', $hoy)
                          ->get()
                          ->getRow();
        return $consumo ? $consumo->total : 0;
    }

    public function getAllDataDesc()
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }

    public function getLatestData($id_usuario = null)
    {
        $builder = $this->builder();
        if ($id_usuario) {
            $builder->where('id_usuario', $id_usuario);
        }
        return $builder->orderBy('id', 'DESC')
                      ->limit(1)
                      ->get()
                      ->getResultArray();
    }

    public function insertData($data)
    {
        return $this->insert($data);
    }
}
