<?php namespace App\Models;

use CodeIgniter\Model;

class DispositivoModel extends Model
{
    protected $table = 'dispositivos';
    protected $primaryKey = 'id_dispositivo';
    protected $allowedFields = ['nombre', 'id_usuario', 'mac_address', 'estado'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function obtenerDispositivosUsuario($idUsuario)
    {
        log_message('debug', 'Buscando dispositivos para usuario ID: ' . $idUsuario);
        
        // Obtener el rol del usuario
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            log_message('error', 'Usuario no encontrado: ' . $idUsuario);
            return [];
        }
        
        // Si es administrador, obtener todos los dispositivos
        if ($usuario['id_rol'] == 1) {
            $dispositivos = $this->findAll();
        } else {
            // Si es usuario normal, obtener sus dispositivos y los del administrador que lo invitó
            $invitacionModel = new InvitacionModel();
            $invitacion = $invitacionModel->where('email', $usuario['email'])
                                        ->where('estado', 'aceptada')
                                        ->first();
            
            if ($invitacion) {
                $dispositivos = $this->where('id_usuario', $idUsuario)
                                   ->orWhere('id_usuario', $invitacion['id_usuario'])
                                   ->findAll();
            } else {
                $dispositivos = $this->where('id_usuario', $idUsuario)
                                   ->findAll();
            }
        }
                           
        log_message('debug', 'Consulta SQL: ' . $this->getLastQuery());
        log_message('debug', 'Dispositivos encontrados: ' . print_r($dispositivos, true));
        
        return $dispositivos;
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
} 