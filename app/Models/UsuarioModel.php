<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuario';  // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id_usuario';  // Clave primaria de la tabla
    protected $allowedFields = ['nombre', 'apellido', 'email', 'contrasena'];  // Campos permitidos para la inserción

    /**
     * Inserta un nuevo usuario en la base de datos.
     *
     * @param array $array Datos del usuario a insertar.
     * @return bool|int Devuelve el ID del usuario insertado o false en caso de error.
     */
    public function insertarUsuario($array)
    {
        log_message('debug', 'Datos a insertar: ' . print_r($array, true));
        
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
}
