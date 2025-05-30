<?php namespace App\Models;

use CodeIgniter\Model;

class DispositivoModel extends Model
{
    protected $table = 'dispositivos';
    protected $primaryKey = 'id_dispositivo';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_usuario',
        'nombre',
        'mac_address',
        'stock',
        'precio',
        'descripcion',
        'estado',
        'fecha_actualizacion'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'id_usuario' => 'required|numeric',
        'nombre' => 'required|min_length[3]|max_length[100]',
        'mac_address' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[dispositivos.mac_address]',
        'stock' => 'required|numeric|greater_than_equal_to[0]',
        'precio' => 'required|numeric|greater_than_equal_to[0]',
        'estado' => 'required|in_list[pendiente,activo,inactivo]'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El ID del usuario es requerido',
            'numeric' => 'El ID del usuario debe ser un número'
        ],
        'nombre' => [
            'required' => 'El nombre del dispositivo es requerido',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede tener más de 100 caracteres'
        ],
        'mac_address' => [
            'required' => 'La dirección MAC es requerida',
            'regex_match' => 'La dirección MAC debe tener el formato XX:XX:XX:XX:XX:XX',
            'is_unique' => 'Esta dirección MAC ya está registrada'
        ],
        'stock' => [
            'required' => 'El stock es requerido',
            'numeric' => 'El stock debe ser un número',
            'greater_than_equal_to' => 'El stock no puede ser negativo'
        ],
        'precio' => [
            'required' => 'El precio es requerido',
            'numeric' => 'El precio debe ser un número',
            'greater_than_equal_to' => 'El precio no puede ser negativo'
        ],
        'estado' => [
            'required' => 'El estado es requerido',
            'in_list' => 'El estado debe ser pendiente, activo o inactivo'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function generarMacSimulada()
    {
        do {
            $mac = '';
            for ($i = 0; $i < 6; $i++) {
                $mac .= sprintf('%02x', rand(0, 255));
                if ($i < 5) {
                    $mac .= ':';
                }
            }
            $mac = strtoupper($mac);
        } while ($this->where('mac_address', $mac)->first() !== null);

        return $mac;
    }

    public function iniciarConfiguracion($idDispositivo)
    {
        return $this->update($idDispositivo, [
            'estado' => 'configurando'
        ]);
    }

    public function activarDispositivo($idDispositivo, $macReal, $ssid)
    {
        return $this->update($idDispositivo, [
            'estado' => 'activo',
            'mac_address' => $macReal,
            'ssid' => $ssid,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }

    public function obtenerDispositivosPendientes($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                    ->where('estado', 'pendiente')
                    ->findAll();
    }

    public function obtenerDispositivosActivos($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                    ->where('estado', 'activo')
                    ->findAll();
    }

    public function actualizarUltimaConexion($idDispositivo)
    {
        return $this->update($idDispositivo, [
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }

    public function obtenerDispositivosUsuario($idUsuario)
    {
        $db = \Config\Database::connect();
        
        // Obtener el rol del usuario
        $usuario = $db->table('usuario')
                     ->select('id_rol')
                     ->where('id_usuario', $idUsuario)
                     ->get()
                     ->getRow();
        
        if (!$usuario) {
            return [];
        }

        // Si es supervisor (id_rol = 3), puede ver todos los dispositivos
        if ($usuario->id_rol == 3) {
            return $this->findAll();
        }

        // Si es admin (id_rol = 1), puede ver sus dispositivos y los de los usuarios que invitó
        if ($usuario->id_rol == 1) {
            // Obtener usuarios invitados por este admin
            $invitacionModel = new \App\Models\InvitacionModel();
            $usuariosInvitados = $invitacionModel->where('invitado_por', $idUsuario)
                                               ->where('estado', 'aceptada')
                                               ->findAll();
            
            $idsUsuarios = array_column($usuariosInvitados, 'id_usuario');
            $idsUsuarios[] = $idUsuario; // Incluir también al admin
            
            return $this->whereIn('id_usuario', $idsUsuarios)->findAll();
        }

        // Si es usuario normal (id_rol = 2), solo puede ver sus dispositivos
        return $this->where('id_usuario', $idUsuario)->findAll();
    }

    public function vincularDispositivo($idUsuario, $macAddress, $nombre)
    {
        // Verificar si el dispositivo ya está vinculado
        $dispositivoExistente = $this->where('mac_address', $macAddress)->first();
        if ($dispositivoExistente) {
            return false;
        }

        // Asegurarnos de que el id_usuario sea un entero válido
        $idUsuario = (int)$idUsuario;
        if ($idUsuario <= 0) {
            log_message('error', 'ID de usuario inválido: ' . $idUsuario);
            return false;
        }

        $data = [
            'nombre' => $nombre,
            'id_usuario' => $idUsuario,
            'mac_address' => $macAddress,
            'estado' => 'activo'
        ];

        log_message('debug', 'Intentando insertar dispositivo: ' . print_r($data, true));

        try {
            $db = \Config\Database::connect();
            $builder = $db->table($this->table);
            
            $result = $builder->insert($data);
            log_message('debug', 'Resultado de la inserción: ' . ($result ? 'éxito' : 'fallo'));
            log_message('debug', 'Consulta SQL: ' . $db->getLastQuery());
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error al insertar dispositivo: ' . $e->getMessage());
            return false;
        }
    }

    public function desvincularDispositivo($idDispositivo, $idUsuario)
    {
        // Verificar si el usuario es el propietario del dispositivo
        $dispositivo = $this->where('id_dispositivo', $idDispositivo)
                           ->where('id_usuario', $idUsuario)
                           ->first();
                           
        if (!$dispositivo) {
            return false;
        }
        
        return $this->delete($idDispositivo);
    }

    public function findAll(?int $limit = null, int $offset = 0)
    {
        $id_usuario = session()->get('id_usuario');
        $builder = $this->builder();
        
        if ($id_usuario !== null) {
            // Obtener el rol del usuario
            $usuarioModel = new UsuarioModel();
            $usuario = $usuarioModel->find($id_usuario);
            
            if ($usuario && $usuario['id_rol'] == 1) {
                // Si es administrador, obtener todos los dispositivos
                if ($limit !== null) {
                    $builder->limit($limit, $offset);
                }
            } else {
                // Si es usuario normal, obtener sus dispositivos y los del administrador que lo invitó
                $invitacionModel = new InvitacionModel();
                $invitacion = $invitacionModel->where('email', $usuario['email'])
                                            ->where('estado', 'aceptada')
                                            ->first();
                
                if ($invitacion) {
                    $builder->where('id_usuario', $id_usuario)
                           ->orWhere('id_usuario', $invitacion['id_usuario']);
                } else {
                    $builder->where('id_usuario', $id_usuario);
                }
                
                if ($limit !== null) {
                    $builder->limit($limit, $offset);
                }
            }
        }
        
        return $builder->get()->getResultArray();
    }

    public function actualizarEstado($idDispositivo, $estado)
    {
        return $this->update($idDispositivo, [
            'estado' => $estado,
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ]);
    }

    public function obtenerDispositivo($idDispositivo)
    {
        return $this->find($idDispositivo);
    }

    public function getDispositivoByMac($macAddress)
    {
        return $this->where('mac_address', $macAddress)->first();
    }

    public function getDispositivosActivos()
    {
        return $this->where('estado', 'activo')->findAll();
    }

    public function getDispositivoConStock($idDispositivo)
    {
        return $this->where('id_dispositivo', $idDispositivo)
                    ->where('estado', 'activo')
                    ->where('stock >', 0)
                    ->first();
    }

    public function actualizarStock($idDispositivo, $cantidad)
    {
        $dispositivo = $this->find($idDispositivo);
        if ($dispositivo) {
            $nuevoStock = $dispositivo['stock'] - $cantidad;
            if ($nuevoStock >= 0) {
                return $this->update($idDispositivo, ['stock' => $nuevoStock]);
            }
        }
        return false;
    }
}