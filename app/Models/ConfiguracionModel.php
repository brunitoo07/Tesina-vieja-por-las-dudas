<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table = 'configuraciones';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_usuario', 'limite_consumo'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_usuario' => 'required|numeric',
        'limite_consumo' => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El ID de usuario es requerido',
            'numeric' => 'El ID de usuario debe ser numérico'
        ],
        'limite_consumo' => [
            'required' => 'El límite de consumo es requerido',
            'numeric' => 'El límite de consumo debe ser numérico',
            'greater_than' => 'El límite de consumo debe ser mayor que 0'
        ]
    ];
} 