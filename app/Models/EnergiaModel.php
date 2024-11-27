<?php

namespace App\Models;

use CodeIgniter\Model;

class EnergiaModel extends Model
{
    protected $table = 'energia';
    protected $primaryKey = 'id';
    protected $allowedFields = ['voltaje', 'corriente', 'potencia', 'kwh', 'fecha'];
    protected $useTimestamps = false; // Cambia a true si necesitas timestamps

    // Método para obtener el límite de consumo (debes asegurarte de tener un campo o tabla para esto)
    public function getLimiteConsumo()
    {
        $resultado = $this->db->table($this->table)
            ->select('limite_consumo')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $resultado['limite_consumo'] ?? null; // Devuelve el valor o null si no existe
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
    public function getConsumoDiario()
    {
        $inicioHoy = date('Y-m-d 00:00:00');
        $finHoy = date('Y-m-d 23:59:59');

        $query = $this->db->table($this->table)
            ->selectSum('kwh')
            ->where('fecha >=', $inicioHoy)
            ->where('fecha <=', $finHoy)
            ->get();

        return $query->getRowArray()['kwh'] ?? 0;
    }

    public function getAllDataDesc()
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }

    public function getLatestData()
    {
        return $this->orderBy('id', 'DESC')->limit(1)->findAll();
    }

    public function insertData($data)
    {
        return $this->insert($data);
    }
}
