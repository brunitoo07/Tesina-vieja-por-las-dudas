<?php

namespace App\Models;

use CodeIgniter\Model;

class EnergyDataModel extends Model
{
    protected $table = 'energy_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['voltaje', 'corriente', 'potencia', 'kwh', 'mac_address', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    protected $validationRules = [
        'voltaje' => 'required|numeric',
        'corriente' => 'required|numeric',
        'potencia' => 'required|numeric',
        'kwh' => 'required|numeric',
        'mac_address' => 'required|valid_mac_address'
    ];

    protected $validationMessages = [
        'voltaje' => [
            'required' => 'El voltaje es requerido',
            'numeric' => 'El voltaje debe ser un número'
        ],
        'corriente' => [
            'required' => 'La corriente es requerida',
            'numeric' => 'La corriente debe ser un número'
        ],
        'potencia' => [
            'required' => 'La potencia es requerida',
            'numeric' => 'La potencia debe ser un número'
        ],
        'kwh' => [
            'required' => 'El kWh es requerido',
            'numeric' => 'El kWh debe ser un número'
        ],
        'mac_address' => [
            'required' => 'La dirección MAC es requerida',
            'valid_mac_address' => 'La dirección MAC no es válida'
        ]
    ];
} 