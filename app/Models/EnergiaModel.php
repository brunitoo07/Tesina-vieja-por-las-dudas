<?php

namespace App\Models;

use CodeIgniter\Model;

class EnergiaModel extends Model
{
    protected $table = 'energia';
    protected $primaryKey = 'id';
    protected $allowedFields = ['voltaje', 'corriente', 'potencia', 'kwh', 'fecha'];
    protected $useTimestamps = false; // Si deseas usar timestamps, cámbialo a true

    public function getLimiteConsumo()
    {
        return $this->db->table($this->table)->select('limite_consumo')->orderBy('id', 'DESC')->get()->getRowArray()['limite_consumo'];
    }

    public function updateLimiteConsumo($nuevoLimite)
    {
        $this->db->table($this->table)->update(['limite_consumo' => $nuevoLimite]);
    }

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


    // Método para obtener todos los datos en orden descendente por id
    public function getAllDataDesc()
    {
        return $this->orderBy('id', 'DESC')->findAll();
    }

    // Método para obtener el último dato registrado
    public function getLatestData()
    {
        return $this->orderBy('id', 'DESC')->limit(1)->findAll();
    }

    // Método para insertar datos en la base de datos
    public function insertData($data)
    {
        return $this->insert($data);
    }

  
  

    // Método para actualizar el límite de consumo
    public function actualizarLimiteConsumo($nuevo_limite)
    {
        // Este método se puede ajustar si deseas almacenar el límite en la base de datos
        // Aquí simplemente estamos actualizando el límite en una variable, pero puedes implementarlo en tu base de datos
        // Por ejemplo, si tuvieras una tabla de configuración que almacene el límite de consumo

        // Para ahora, actualizamos un campo de configuración (esto debe ser ajustado según tu base de datos)
        // Por ejemplo, insertar o actualizar el límite de consumo
        // Si tienes un campo fijo, deberías actualizarlo en la tabla

        // Para este ejemplo, dejaremos este método simple y devolveremos el nuevo límite
        return $nuevo_limite;
    }
}
