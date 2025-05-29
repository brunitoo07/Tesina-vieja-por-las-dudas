<?php namespace App\Models;

use CodeIgniter\Model;

class DispositivoModel extends Model
{
    protected $table = 'dispositivos';
    protected $primaryKey = 'id_dispositivo';
    protected $allowedFields = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'caracteristicas',
        'stock',
        'estado'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_creacion';
    protected $updatedField = 'fecha_actualizacion';

    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'descripcion' => 'required|min_length[10]',
        'precio' => 'required|numeric',
        'stock' => 'required|integer',
        'estado' => 'required|in_list[activo,inactivo]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre del dispositivo es requerido',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder los 100 caracteres'
        ],
        'descripcion' => [
            'required' => 'La descripción es requerida',
            'min_length' => 'La descripción debe tener al menos 10 caracteres'
        ],
        'precio' => [
            'required' => 'El precio es requerido',
            'numeric' => 'El precio debe ser un número válido'
        ],
        'stock' => [
            'required' => 'El stock es requerido',
            'integer' => 'El stock debe ser un número entero'
        ],
        'estado' => [
            'required' => 'El estado es requerido',
            'in_list' => 'El estado debe ser activo o inactivo'
        ]
    ];

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

    public function cambiarEstado($idDispositivo, $estado)
    {
        return $this->update($idDispositivo, ['estado' => $estado]);
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

    public function getDispositivoConStock($id)
    {
        return $this->where('id_dispositivo', $id)
                    ->where('estado', 'activo')
                    ->where('stock >', 0)
                    ->first();
    }

    public function actualizarStock($id, $cantidad)
    {
        $dispositivo = $this->find($id);
        if ($dispositivo) {
            $nuevoStock = $dispositivo['stock'] - $cantidad;
            if ($nuevoStock >= 0) {
                return $this->update($id, ['stock' => $nuevoStock]);
            }
        }
        return false;
    }
}