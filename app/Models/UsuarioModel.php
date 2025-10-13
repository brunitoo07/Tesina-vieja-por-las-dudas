<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * MODELO DE USUARIO - EcoVolt
 * 
 * Este modelo maneja todas las operaciones relacionadas con los usuarios del sistema.
 * Incluye autenticación, registro, gestión de roles y validaciones de seguridad.
 * 
 * FUNCIONALIDADES PRINCIPALES:
 * - Registro y autenticación de usuarios
 * - Gestión de roles (admin, usuario normal, supervisor)
 * - Validación de credenciales y seguridad
 * - Hash automático de contraseñas
 * - Verificación de emails únicos
 * - Gestión de invitaciones entre usuarios
 * 
 * ROLES DEL SISTEMA:
 * - 1: Administrador (puede gestionar todos los usuarios y dispositivos)
 * - 2: Usuario normal (puede ver solo sus dispositivos y los del admin que lo invitó)
 * - 3: Supervisor (puede ver dispositivos de múltiples usuarios)
 */
class UsuarioModel extends Model
{
    // ==================== CONFIGURACIÓN DE LA TABLA ====================
    
    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'usuario';
    
    /** @var string Clave primaria de la tabla */
    protected $primaryKey = 'id_usuario';
    
    /** @var bool Usar auto incremento para la clave primaria */
    protected $useAutoIncrement = true;
    
    /** @var string Tipo de datos que retorna (array, object, etc.) */
    protected $returnType = 'array';
    
    /** @var bool Usar soft deletes (eliminación lógica) */
    protected $useSoftDeletes = false;
    
    /** @var bool Proteger campos automáticamente */
    protected $protectFields = true;
    
    /** @var array Campos permitidos para inserción/actualización */
    protected $allowedFields = ['nombre', 'apellido', 'email', 'contrasena', 'direccion_id', 'id_rol', 'invitado_por', 'updated_at', 'estado'];

    // ==================== CONFIGURACIÓN DE TIMESTAMPS ====================
    
    /** @var bool Usar timestamps automáticos (created_at, updated_at) */
    protected $useTimestamps = true;
    
    /** @var string Formato de fecha para timestamps */
    protected $dateFormat = 'datetime';
    
    /** @var string Nombre del campo de fecha de creación */
    protected $createdField = 'created_at';
    
    /** @var string Nombre del campo de fecha de actualización */
    protected $updatedField = 'updated_at';
    
    /** @var string Nombre del campo de fecha de eliminación (soft delete) */
    protected $deletedField = 'deleted_at';

    // ==================== REGLAS DE VALIDACIÓN ====================
    
    /** @var array Reglas de validación para los campos del usuario */
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[50]',           // Nombre obligatorio, 3-50 caracteres
        'apellido' => 'required|min_length[3]|max_length[50]',         // Apellido obligatorio, 3-50 caracteres
        'email' => 'required|valid_email|is_unique[usuario.email,id_usuario,{id_usuario}]', // Email único y válido
        'contrasena' => 'permit_empty|min_length[8]',                  // Contraseña opcional, mínimo 8 caracteres
        'id_rol' => 'required|numeric'                                 // Rol obligatorio y numérico
    ];

    /** @var array Mensajes personalizados para las validaciones */
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Este email ya está registrado por otro usuario'
        ],
        'contrasena' => [
            'min_length' => 'La contraseña debe tener al menos 8 caracteres'
        ]
    ];

    /** @var bool Saltar validación automática */
    protected $skipValidation = false;
    
    /** @var bool Limpiar reglas de validación automáticamente */
    protected $cleanValidationRules = true;

    // ==================== CALLBACKS AUTOMÁTICOS ====================
    
    /** @var bool Permitir callbacks automáticos */
    protected $allowCallbacks = true;
    
    /** @var array Callbacks que se ejecutan antes de insertar */
    protected $beforeInsert = ['hashPassword'];
    
    /** @var array Callbacks que se ejecutan antes de actualizar */
    protected $beforeUpdate = ['hashPassword'];

    // ==================== MÉTODOS PÚBLICOS PRINCIPALES ====================
    
    /**
     * Inserta un nuevo usuario en la base de datos
     * 
     * @param array $array Datos del usuario a insertar
     * @return int|false ID del usuario insertado o false si hay error
     */
    public function insertarUsuario($array)
    {
        log_message('debug', 'Datos a insertar: ' . print_r($array, true));
        
        // Validar que el rol sea válido antes de insertar
        if (!isset($array['id_rol']) || !$this->validarRol($array['id_rol'])) {
            log_message('error', 'Rol inválido: ' . $array['id_rol']);
            return false;
        }
        
        // Insertar el usuario (el hash de contraseña se hace automáticamente por el callback)
        $result = $this->insert($array);
        
        if ($result === false) {
            log_message('error', 'Error al insertar usuario: ' . print_r($this->errors(), true));
        } else {
            log_message('debug', 'Usuario insertado con ID: ' . $result);
        }

        return $result;
    }

    /**
     * Verifica si un email ya está registrado en el sistema
     * 
     * @param string $email Email a verificar
     * @return bool True si el email ya existe, false si no
     */
    public function existenteEmail($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    /**
     * Obtiene la información completa del usuario basado en su email
     * 
     * @param string $email Email del usuario
     * @return array|null Datos del usuario o null si no existe
     */
    public function obtenerUsuarioEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Actualiza la contraseña de un usuario específico
     * 
     * @param string $hashedContrasena Contraseña ya hasheada
     * @param int $idUsuario ID del usuario
     * @return bool True si se actualizó correctamente
     */
    public function actualizarContrasena($hashedContrasena, $idUsuario)
    {
        return $this->set('contrasena', $hashedContrasena)
                    ->where('id_usuario', $idUsuario)
                    ->update();
    }

    /**
     * Inserta un código de verificación en la tabla de códigos
     * 
     * @param array $data Datos del código de verificación
     * @return bool True si se insertó correctamente
     */
    public function insertarCodigo($data)
    {
        return $this->db->table('codigo')->insert($data);
    }

    // ==================== CALLBACKS AUTOMÁTICOS ====================
    
    /**
     * Callback que se ejecuta automáticamente antes de insertar o actualizar
     * Hashea la contraseña del usuario usando password_hash()
     * 
     * @param array $data Datos que se van a insertar/actualizar
     * @return array Datos modificados con la contraseña hasheada
     */
    protected function hashPassword(array $data)
    {
        log_message('debug', 'Iniciando hashPassword con datos: ' . json_encode($data));
        
        // Verificar si hay contraseña para hashear
        if (!isset($data['data']['contrasena']) || empty($data['data']['contrasena'])) {
            log_message('debug', 'No se encontró contraseña para hashear');
            return $data;
        }

        $contrasenaOriginal = $data['data']['contrasena'];
        log_message('debug', 'Contraseña original: ' . $contrasenaOriginal);
        log_message('debug', 'Longitud de la contraseña original: ' . strlen($contrasenaOriginal));

        // Si la contraseña ya está hasheada (empieza con $2y$), no la hasheamos de nuevo
        if (strpos($contrasenaOriginal, '$2y$') === 0) {
            log_message('debug', 'La contraseña ya está hasheada, no se modificará');
            return $data;
        }

        // Hashear la contraseña usando el algoritmo por defecto de PHP
        $data['data']['contrasena'] = password_hash($contrasenaOriginal, PASSWORD_DEFAULT);
        log_message('debug', 'Hash generado: ' . $data['data']['contrasena']);

        return $data;
    }

    // ==================== MÉTODOS DE AUTENTICACIÓN ====================
    
    /**
     * Verifica las credenciales de un usuario (email y contraseña)
     * 
     * @param string $email Email del usuario
     * @param string $password Contraseña en texto plano
     * @return array|false Datos del usuario si las credenciales son correctas, false si no
     */
    public function verificarCredenciales($email, $password)
    {
        $usuario = $this->where('email', $email)->first();
        
        // Verificar si el usuario existe y la contraseña es correcta
        if ($usuario && password_verify($password, $usuario['contrasena'])) {
            return $usuario;
        }
        
        return false;
    }

    // ==================== MÉTODOS DE CREACIÓN POR ROL ====================
    
    /**
     * Crea un nuevo usuario con rol de administrador
     * 
     * @param array $data Datos del usuario
     * @return int|false ID del usuario creado o false si hay error
     */
    public function crearUsuarioAdmin($data)
    {
        $data['id_rol'] = 1; // Rol de administrador
        return $this->insert($data);
    }

    /**
     * Crea un nuevo usuario con rol normal
     * 
     * @param array $data Datos del usuario
     * @return int|false ID del usuario creado o false si hay error
     */
    public function crearUsuarioNormal($data)
    {
        $data['id_rol'] = 2; // Rol de usuario normal
        return $this->insert($data);
    }

    // ==================== MÉTODOS DE CONSULTA ====================
    
    /**
     * Obtiene todos los usuarios con información de sus roles
     * 
     * @return array Lista de usuarios con información de roles
     */
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

    // ==================== MÉTODOS DE GESTIÓN DE ROLES ====================
    
    /**
     * Actualiza el rol de un usuario específico
     * 
     * @param int $id_usuario ID del usuario
     * @param int $id_rol Nuevo rol del usuario
     * @return bool True si se actualizó correctamente
     */
    public function actualizarRol($id_usuario, $id_rol)
    {
        try {
            // Validar que el rol sea válido
            if (!$this->validarRol($id_rol)) {
                log_message('error', 'Intento de actualizar a un rol inválido: ' . $id_rol);
                return false;
            }

            // Verificar que el usuario existe
            $usuario = $this->find($id_usuario);
            if (!$usuario) {
                log_message('error', 'Usuario no encontrado: ' . $id_usuario);
                return false;
            }

            // Actualizar el rol
            $data = ['id_rol' => $id_rol];
            $result = $this->update($id_usuario, $data);
            
            log_message('debug', 'Resultado de actualizar rol: ' . ($result ? 'true' : 'false'));
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar rol: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un usuario del sistema con validaciones de seguridad
     * 
     * @param int $id_usuario ID del usuario a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function eliminarUsuario($id_usuario)
    {
        try {
            $usuario = $this->find($id_usuario);
            if (!$usuario) {
                log_message('error', 'Usuario no encontrado: ' . $id_usuario);
                return false;
            }

            // Proteger contra la eliminación del último administrador
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

    /**
     * Obtiene la información del rol de un usuario específico
     * 
     * @param int $id_usuario ID del usuario
     * @return array|null Información del rol del usuario
     */
    public function obtenerRolUsuario($id_usuario)
    {
        return $this->db->table('roles')
                       ->join('usuario', 'usuario.id_rol = roles.id_rol')
                       ->where('usuario.id_usuario', $id_usuario)
                       ->get()
                       ->getRowArray();
    }

    /**
     * Valida si un ID de rol existe en la base de datos
     * 
     * @param int $id_rol ID del rol a validar
     * @return bool True si el rol existe, false si no
     */
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

    // ==================== MÉTODOS SOBRESCRITOS ====================
    
    /**
     * Sobrescribe el método update para agregar validaciones adicionales
     * 
     * @param int|null $id ID del usuario a actualizar
     * @param array|null $data Datos a actualizar
     * @return bool True si se actualizó correctamente
     */
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

            // Si se está actualizando el email, verificar que no esté en uso por otro usuario
            if (isset($data['email']) && $data['email'] !== $usuario['email']) {
                $existe = $this->where('email', $data['email'])
                              ->where('id_usuario !=', $id)
                              ->countAllResults();
                
                if ($existe > 0) {
                    log_message('error', 'Email ya está en uso por otro usuario');
                    return false;
                }
            }

            // Actualizar el timestamp automáticamente
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

    /**
     * Sobrescribe el método find para agregar logging
     * 
     * @param int|null $id ID del usuario a buscar
     * @return array|null Datos del usuario encontrado
     */
    public function find($id = null)
    {
        log_message('debug', 'Buscando usuario ID: ' . $id);
        $result = parent::find($id);
        log_message('debug', 'Resultado de la búsqueda: ' . print_r($result, true));
        return $result;
    }
}