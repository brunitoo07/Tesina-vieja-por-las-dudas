<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nombre', 'apellido', 'email', 'contrasena', 'direccion_id', 'id_rol', 'invitado_por', 'updated_at', 'estado'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[50]',
        'apellido' => 'required|min_length[3]|max_length[50]',
        'email' => 'required|valid_email|is_unique[usuario.email,id_usuario,{id_usuario}]',
        'contrasena' => 'permit_empty|min_length[8]',
        'id_rol' => 'required|numeric'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Este email ya está registrado por otro usuario'
        ],
        'contrasena' => [
            'min_length' => 'La contraseña debe tener al menos 8 caracteres'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Inserta un nuevo usuario en la base de datos.
     */
    public function insertarUsuario($array)
    {
        log_message('debug', 'Datos a insertar: ' . print_r($array, true));
        
        if (!isset($array['id_rol']) || !$this->validarRol($array['id_rol'])) {
            log_message('error', 'Rol inválido: ' . $array['id_rol']);
            return false;
        }
        
        $result = $this->insert($array);
        
        if ($result === false) {
            log_message('error', 'Error al insertar usuario: ' . print_r($this->errors(), true));
        } else {
            log_message('debug', 'Usuario insertado con ID: ' . $result);
        }

        return $result;
    }

    /**
     * Verifica si un email ya está registrado.
     */
    public function existenteEmail($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    /**
     * Obtiene la información del usuario basado en el email.
     */
    public function obtenerUsuarioEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Actualiza la contraseña del usuario.
     */
    public function actualizarContrasena($hashedContrasena, $idUsuario)
    {
        return $this->set('contrasena', $hashedContrasena)
                    ->where('id_usuario', $idUsuario)
                    ->update();
    }

    /**
     * Inserta un código de verificación.
     */
    public function insertarCodigo($data)
    {
        return $this->db->table('codigo')->insert($data);
    }

    protected function hashPassword(array $data)
    {
        log_message('debug', 'Iniciando hashPassword con datos: ' . json_encode($data));
        
        if (!isset($data['data']['contrasena']) || empty($data['data']['contrasena'])) {
            log_message('debug', 'No se encontró contraseña para hashear');
            return $data;
        }

        $contrasenaOriginal = $data['data']['contrasena'];
        log_message('debug', 'Contraseña original: ' . $contrasenaOriginal);
        log_message('debug', 'Longitud de la contraseña original: ' . strlen($contrasenaOriginal));

        // Si la contraseña ya está hasheada, no la hasheamos de nuevo
        if (strpos($contrasenaOriginal, '$2y$') === 0) {
            log_message('debug', 'La contraseña ya está hasheada, no se modificará');
            return $data;
        }

        $data['data']['contrasena'] = password_hash($contrasenaOriginal, PASSWORD_DEFAULT);
        log_message('debug', 'Hash generado: ' . $data['data']['contrasena']);

        return $data;
    }

    public function verificarCredenciales($email, $password)
    {
        $usuario = $this->where('email', $email)->first();
        
        if ($usuario && password_verify($password, $usuario['contrasena'])) {
            return $usuario;
        }
        
        return false;
    }

    public function crearUsuarioAdmin($data)
    {
        $data['id_rol'] = 1;
        return $this->insert($data);
    }

    public function crearUsuarioNormal($data)
    {
        $data['id_rol'] = 2;
        return $this->insert($data);
    }

    public function obtenerUsuarios()
    {
        try {
            $usuarios = $this->select('usuario.*, roles.nombre_rol')
                            ->join('roles', 'roles.id_rol = usuario.id_rol')
                            ->findAll();
            log_message('debug', 'Usuarios obtenidos: ' . count($usuarios));
            return $usuarios;
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener usuarios: ' . $e->getMessage());
            return [];
        }
    }

    public function actualizarRol($id_usuario, $id_rol)
    {
        try {
            if (!$this->validarRol($id_rol)) {
                log_message('error', 'Intento de actualizar a un rol inválido: ' . $id_rol);
                return false;
            }

            $usuario = $this->find($id_usuario);
            if (!$usuario) {
                log_message('error', 'Usuario no encontrado: ' . $id_usuario);
                return false;
            }

            $data = ['id_rol' => $id_rol];
            $result = $this->update($id_usuario, $data);
            
            log_message('debug', 'Resultado de actualizar rol: ' . ($result ? 'true' : 'false'));
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar rol: ' . $e->getMessage());
            return false;
        }
    }

    public function eliminarUsuario($id_usuario)
    {
        try {
            $usuario = $this->find($id_usuario);
            if (!$usuario) {
                log_message('error', 'Usuario no encontrado: ' . $id_usuario);
                return false;
            }

            if ($usuario['id_rol'] == 1) {
                $adminCount = $this->where('id_rol', 1)->countAllResults();
                if ($adminCount <= 1) {
                    log_message('error', 'Intento de eliminar el último administrador');
                    return false;
                }
            }

            $result = $this->delete($id_usuario);
            log_message('debug', 'Resultado de eliminar usuario: ' . ($result ? 'true' : 'false'));
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar usuario: ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerRolUsuario($id_usuario)
    {
        return $this->db->table('roles')
                       ->join('usuario', 'usuario.id_rol = roles.id_rol')
                       ->where('usuario.id_usuario', $id_usuario)
                       ->get()
                       ->getRowArray();
    }

    public function validarRol($id_rol)
    {
        try {
            log_message('debug', 'Validando rol: ' . $id_rol);
            $result = $this->db->table('roles')
                           ->where('id_rol', $id_rol)
                           ->countAllResults() > 0;
            log_message('debug', 'Resultado de validación de rol: ' . ($result ? 'true' : 'false'));
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error al validar rol: ' . $e->getMessage());
            return false;
        }
    }

    public function update($id = null, $data = null): bool
    {
        log_message('debug', '=== INICIO ACTUALIZACIÓN USUARIO ===');
        log_message('debug', 'ID Usuario: ' . $id);
        log_message('debug', 'Datos recibidos: ' . print_r($data, true));

        if ($id === null || $data === null) {
            log_message('error', 'ID o datos nulos en la actualización');
            return false;
        }

        try {
            // Verificar si el usuario existe
            $usuario = $this->find($id);
            if (!$usuario) {
                log_message('error', 'Usuario no encontrado para actualización');
                return false;
            }

            // Si se está actualizando el email, verificar que no esté en uso
            if (isset($data['email']) && $data['email'] !== $usuario['email']) {
                $existe = $this->where('email', $data['email'])
                              ->where('id_usuario !=', $id)
                              ->countAllResults();
                
                if ($existe > 0) {
                    log_message('error', 'Email ya está en uso por otro usuario');
                    return false;
                }
            }

            // Actualizar el timestamp
            $data['updated_at'] = date('Y-m-d H:i:s');

            // Si no se está actualizando la contraseña, removerla de los datos
            if (!isset($data['contrasena']) || empty($data['contrasena'])) {
                unset($data['contrasena']);
            }

            log_message('debug', 'Datos finales para actualización: ' . print_r($data, true));

            // Realizar la actualización usando el Query Builder
            $builder = $this->db->table($this->table);
            $builder->where($this->primaryKey, $id);
            $result = $builder->update($data);

            log_message('debug', 'Resultado de la actualización: ' . ($result ? 'true' : 'false'));
            
            if (!$result) {
                $error = $this->db->error();
                log_message('error', 'Error en la consulta SQL: ' . print_r($error, true));
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Excepción al actualizar usuario: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function find($id = null)
    {
        log_message('debug', 'Buscando usuario ID: ' . $id);
        $result = parent::find($id);
        log_message('debug', 'Resultado de la búsqueda: ' . print_r($result, true));
        return $result;
    }
}