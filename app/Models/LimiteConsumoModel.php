<?php

namespace App\Models;

use CodeIgniter\Model;

class LimiteConsumoModel extends Model
{
    protected $table = 'limites_consumo';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_usuario',
        'id_dispositivo',
        'limite_consumo',
        'email_notificacion',
        'notificacion_enviada',
        'ultima_notificacion'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_usuario' => 'required|numeric',
        'id_dispositivo' => 'required|numeric',
        'limite_consumo' => 'required|numeric|greater_than[0]',
        'email_notificacion' => 'permit_empty|valid_email'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El ID de usuario es requerido',
            'numeric' => 'El ID de usuario debe ser numérico'
        ],
        'id_dispositivo' => [
            'required' => 'El ID de dispositivo es requerido',
            'numeric' => 'El ID de dispositivo debe ser numérico'
        ],
        'limite_consumo' => [
            'required' => 'El límite de consumo es requerido',
            'numeric' => 'El límite de consumo debe ser numérico',
            'greater_than' => 'El límite de consumo debe ser mayor que 0'
        ],
        'email_notificacion' => [
            'valid_email' => 'El email de notificación debe ser válido'
        ]
    ];

    public function getLimiteByUsuario($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)->first();
    }

    public function getLimiteByDispositivo($idDispositivo)
    {
        return $this->where('id_dispositivo', $idDispositivo)->first();
    }

    public function actualizarNotificacion($id, $enviada = true)
    {
        return $this->update($id, [
            'notificacion_enviada' => $enviada ? 1 : 0,
            'ultima_notificacion' => date('Y-m-d H:i:s')
        ]);
    }
}
