<?php
namespace App\Models;

use CodeIgniter\Model;

class EnergiaModel extends Model
{
    protected $table = 'energia';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_dispositivo', 'id_usuario', 'voltaje', 'corriente', 'potencia', 'kwh', 'fecha'];
    protected $useTimestamps = false;
   

    public function insertData($data)
    {
        return $this->insert($data);
    }

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

    // ✅ Obtenemos el límite de consumo de la tabla limites_consumo
    public function getLimiteConsumo($id_usuario)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('limites_consumo');
        $registro = $builder->where('id_usuario', $id_usuario)
                            ->orderBy('id', 'DESC')
                            ->limit(1)
                            ->get()
                            ->getRow();

        return $registro ? $registro->limite_consumo : 10; // valor por defecto
    }

    // ✅ Actualizar el límite en la tabla limites_consumo
    public function updateLimiteConsumo($id_usuario, $nuevoLimite)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('limites_consumo');

        // Verificamos si ya existe un registro para ese usuario
        $existe = $builder->where('id_usuario', $id_usuario)->countAllResults();

        if ($existe) {
            // Si ya existe, actualizamos
            return $builder->where('id_usuario', $id_usuario)
                           ->set('limite_consumo', $nuevoLimite)
                           ->update();
        } else {
            // Si no existe, insertamos uno nuevo
            return $builder->insert([
                'id_usuario' => $id_usuario,
                'limite_consumo' => $nuevoLimite
            ]);
        }
    }
}
