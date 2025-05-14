<?php

namespace App\Models;

use CodeIgniter\Model;

class EnergiaModel extends Model
{
    protected $table = 'energia';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id_dispositivo', 'id_usuario', 'voltaje', 'corriente', 'potencia', 'kwh', 'fecha', 'mac_address'];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getLatestData()
    {
        return $this->orderBy('fecha', 'DESC')->findAll(1);
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
                            ->getRow()->kwh ?? 0;
        return $consumo ? $consumo->total : 0;
    }

    public function getAllDataDesc()
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }

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

    public function updateLimiteConsumo($id_usuario, $nuevoLimite)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('limites_consumo');

        $existe = $builder->where('id_usuario', $id_usuario)->countAllResults();

        if ($existe) {
            return $builder->where('id_usuario', $id_usuario)
                           ->set('limite_consumo', $nuevoLimite)
                           ->update();
        } else {
            return $builder->insert([
                'id_usuario' => $id_usuario,
                'limite_consumo' => $nuevoLimite
            ]);
        }
    }

    public function obtenerConsumo24Horas($idUsuario)
    {
        $db = \Config\Database::connect();

        $dispositivos = $db->table('dispositivos')
                            ->select('id_dispositivo')
                            ->where('id_usuario', $idUsuario)
                            ->get()
                            ->getResultArray();

        $idsDispositivos = array_column($dispositivos, 'id_dispositivo');

        if (empty($idsDispositivos)) {
            return 0;
        }

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

        $dispositivos = $db->table('dispositivos')
                            ->select('id_dispositivo')
                            ->where('id_usuario', $idUsuario)
                            ->get()
                            ->getResultArray();

        $idsDispositivos = array_column($dispositivos, 'id_dispositivo');

        if (empty($idsDispositivos)) {
            return 0;
        }

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

    public function getLecturasByUsuario($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)->orderBy('fecha', 'DESC')->findAll();
    }

    // *** LÓGICA PARA FORZAR A ENTERO ANTES DE INSERTAR ***
    public function insert($data = null, bool $returnID = true, bool $overwrite = false)
    {
        if (is_array($data)) {
            if (isset($data['id_dispositivo'])) {
                $data['id_dispositivo'] = (int)$data['id_dispositivo'];
            }
            if (isset($data['id_usuario'])) {
                $data['id_usuario'] = (int)$data['id_usuario'];
            }
        }
        return parent::insert($data, $returnID, $overwrite);
    }
    // *** FIN DE LA LÓGICA ***
}