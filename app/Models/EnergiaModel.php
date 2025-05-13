<?php
namespace App\Models;

use CodeIgniter\Model;

class EnergiaModel extends Model
{
    protected $table = 'energia';
    protected $primaryKey = 'id';
    protected $allowedFields = ['mac_address', 'voltaje', 'corriente', 'potencia', 'kwh', 'fecha'];
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

    public function obtenerConsumo24Horas($idUsuario)
    {
        $db = \Config\Database::connect();
        
        // Obtener los IDs de los dispositivos del usuario
        $dispositivos = $db->table('dispositivos')
                          ->select('id_dispositivo')
                          ->where('id_usuario', $idUsuario)
                          ->get()
                          ->getResultArray();
        
        $idsDispositivos = array_column($dispositivos, 'id_dispositivo');
        
        if (empty($idsDispositivos)) {
            return 0;
        }

        // Calcular el consumo total de los últimos 24 horas
        $fechaInicio = date('Y-m-d H:i:s', strtotime('-24 hours'));
        
        $query = $db->table('energia')
                   ->select('SUM(kwh) as total_consumo')
                   ->whereIn('id_dispositivo', $idsDispositivos)
                   ->where('fecha >=', $fechaInicio)
                   ->get();
        
        $resultado = $query->getRow();
        return $resultado ? $resultado->total_consumo : 0;
    }

    public function obtenerConsumoPromedioDiario($idUsuario)
    {
        $db = \Config\Database::connect();
        
        // Obtener los IDs de los dispositivos del usuario
        $dispositivos = $db->table('dispositivos')
                          ->select('id_dispositivo')
                          ->where('id_usuario', $idUsuario)
                          ->get()
                          ->getResultArray();
        
        $idsDispositivos = array_column($dispositivos, 'id_dispositivo');
        
        if (empty($idsDispositivos)) {
            return 0;
        }

        // Calcular el consumo promedio diario de los últimos 30 días
        $fechaInicio = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        $query = $db->table('energia')
                   ->select('AVG(kwh) as promedio_diario')
                   ->whereIn('id_dispositivo', $idsDispositivos)
                   ->where('fecha >=', $fechaInicio)
                   ->get();
        
        $resultado = $query->getRow();
        return $resultado ? $resultado->promedio_diario : 0;
    }

    public function obtenerUltimosDatos($idDispositivo, $limite = 10)
    {
        return $this->where('id_dispositivo', $idDispositivo)
                   ->orderBy('fecha', 'DESC')
                   ->limit($limite)
                   ->findAll();
    }

    public function insertarDatos($datos)
    {
        return $this->insert($datos);
    }
}
