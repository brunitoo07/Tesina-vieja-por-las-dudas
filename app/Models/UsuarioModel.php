<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuario';  // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id_usuario';  // Clave primaria de la tabla
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nombre', 'apellido', 'email', 'contrasena', 'direccion_id', 'id_rol'];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Inserta un nuevo usuario en la base de datos.
     *
     * @param array $array Datos del usuario a insertar.
     * @return bool|int Devuelve el ID del usuario insertado o false en caso de error.
     */
    public function insertarUsuario($array)
    {
        log_message('debug', 'Datos a insertar: ' . print_r($array, true));
        
        // Validar que el rol existe
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
     *
     * @param string $email Email a verificar.
     * @return bool Retorna true si el email ya existe, false en caso contrario.
     */
    public function existenteEmail($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    /**
     * Obtiene la información del usuario basado en el email.
     *
     * @param string $email Email del usuario a obtener.
     * @return array|null Información del usuario o null si no se encuentra.
     */
    public function obtenerUsuarioEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Actualiza la contraseña del usuario.
     *
     * @param string $hashedContrasena Contraseña hasheada.
     * @param int $idUsuario ID del usuario a actualizar.
     * @return bool Retorna true si la actualización fue exitosa, false en caso contrario.
     */
    public function actualizarContrasena($hashedContrasena, $idUsuario)
    {
        return $this->set('contrasena', $hashedContrasena)
                    ->where('id_usuario', $idUsuario)
                    ->update();
    }

    /**
     * Inserta un código de verificación en la tabla 'codigo'.
     *
     * @param array $data Datos del código a insertar.
     * @return bool Retorna true si la inserción fue exitosa, false en caso contrario.
     */
    public function insertarCodigo($data)
    {
        return $this->db->table('codigo')->insert($data);  // Inserta en la tabla 'codigo'
    }

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['contrasena'])) {
            return $data;
        }

        $data['data']['contrasena'] = password_hash($data['data']['contrasena'], PASSWORD_DEFAULT);
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
        $data['id_rol'] = 1; // Asumiendo que 1 es el ID del rol admin
        return $this->insert($data);
    }

    public function crearUsuarioNormal($data)
    {
        $data['id_rol'] = 2; // Asumiendo que 2 es el ID del rol usuario
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
            // Validar que el rol existe
            if (!$this->validarRol($id_rol)) {
                log_message('error', 'Intento de actualizar a un rol inválido: ' . $id_rol);
                return false;
            }

            // Validar que el usuario existe
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

    public function eliminarUsuario($id_usuario)
    {
        try {
            // Validar que el usuario existe
            $usuario = $this->find($id_usuario);
            if (!$usuario) {
                log_message('error', 'Usuario no encontrado: ' . $id_usuario);
                return false;
            }

            // No permitir eliminar el último administrador
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
            return $this->db->table('roles')
                           ->where('id_rol', $id_rol)
                           ->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error al validar rol: ' . $e->getMessage());
            return false;
        }
    }
}
