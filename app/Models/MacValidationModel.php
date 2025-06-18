<?php

namespace App\Models;

use CodeIgniter\Model;

class MacValidationModel extends Model
{
    protected $table = 'mac_validation';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_usuario',
        'mac_address',
        'fabricante',
        'tipo_dispositivo',
        'es_valida',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_usuario' => 'required|numeric|is_not_unique[usuarios.id_usuario]',
        'mac_address' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[mac_validation.mac_address]',
        'fabricante' => 'permit_empty|max_length[100]',
        'tipo_dispositivo' => 'permit_empty|max_length[50]',
        'es_valida' => 'required|boolean'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El ID del usuario es requerido',
            'numeric' => 'El ID del usuario debe ser un número',
            'is_not_unique' => 'El usuario especificado no existe'
        ],
        'mac_address' => [
            'required' => 'La dirección MAC es requerida',
            'regex_match' => 'La dirección MAC debe tener el formato XX:XX:XX:XX:XX:XX',
            'is_unique' => 'Esta dirección MAC ya está registrada'
        ],
        'fabricante' => [
            'max_length' => 'El nombre del fabricante no puede exceder los 100 caracteres'
        ],
        'tipo_dispositivo' => [
            'max_length' => 'El tipo de dispositivo no puede exceder los 50 caracteres'
        ],
        'es_valida' => [
            'required' => 'El estado de validez es requerido',
            'boolean' => 'El estado de validez debe ser verdadero o falso'
        ]
    ];

    /**
     * Verifica si una dirección MAC es válida
     */
    public function esMacValida($macAddress)
    {
        $macAddress = strtoupper($macAddress);
        $mac = $this->where('mac_address', $macAddress)
                    ->where('es_valida', true)
                    ->first();
        
        return $mac !== null;
    }

    /**
     * Obtiene información sobre una dirección MAC
     */
    public function getMacInfo($macAddress)
    {
        return $this->select('mac_validation.*, usuario.nombre, usuario.apellido')
                    ->join('usuario', 'usuario.id_usuario = mac_validation.id_usuario')
                    ->where('mac_validation.mac_address', $macAddress)
                    ->first();
    }

    /**
     * Registra una nueva dirección MAC en la base de datos
     */
    public function registrarMac($macAddress, $idUsuario, $fabricante = null, $tipoDispositivo = null)
    {
        $macAddress = strtoupper($macAddress);
        $data = [
            'id_usuario' => $idUsuario,
            'mac_address' => $macAddress,
            'fabricante' => $fabricante,
            'tipo_dispositivo' => $tipoDispositivo,
            'es_valida' => true
        ];

        return $this->insert($data);
    }

    /**
     * Obtiene todas las MACs registradas por un usuario específico
     */
    public function getMacsPorUsuario($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)->findAll();
    }

    public function actualizarUsuarioMac($macAddress, $idUsuario)
    {
        return $this->where('mac_address', $macAddress)
                    ->set(['id_usuario' => $idUsuario])
                    ->update();
    }
} 