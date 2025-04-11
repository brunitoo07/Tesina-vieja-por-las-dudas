<?php

namespace App\Models;

use CodeIgniter\Model;

class LimiteConsumoModel extends Model
{
    protected $table = 'limites_consumo';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_usuario', 'limite_consumo'];

    public function getLimite($id_usuario)
    {
        return $this->where('id_usuario', $id_usuario)->first();
    }

    public function setLimite($id_usuario, $limite)
    {
        $data = ['limite_consumo' => $limite];

        if ($this->where('id_usuario', $id_usuario)->first()) {
            return $this->where('id_usuario', $id_usuario)->set($data)->update();
        } else {
            return $this->insert(['id_usuario' => $id_usuario, 'limite_consumo' => $limite]);
        }
    }
}
