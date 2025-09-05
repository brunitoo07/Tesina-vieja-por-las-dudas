<?php

namespace App\Models;

use CodeIgniter\Model;

class LimiteConsumoModel extends Model
{
    protected $table      = 'limites_consumo';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_usuario', 'id_dispositivo', 'limite_consumo', 'email_notificacion', 'notificacion_enviada', 'ultima_notificacion'];
    protected $useTimestamps = true;

    // Obtener límite de un dispositivo específico
    public function getLimiteByDispositivo($id_dispositivo)
    {
        return $this->where('id_dispositivo', $id_dispositivo)
                    ->orderBy('id', 'DESC')
                    ->first();
    }

    public function actualizarNotificacion($id)
    {
        return $this->update($id, [
            'notificacion_enviada' => 1,
            'ultima_notificacion' => date('Y-m-d H:i:s')
        ]);
    }

    
}
