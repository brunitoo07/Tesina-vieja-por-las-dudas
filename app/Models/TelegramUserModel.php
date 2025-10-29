<?php

namespace App\Models;

use CodeIgniter\Model;

class TelegramUserModel extends Model
{
    protected $table = 'telegram_users';
    protected $primaryKey = 'id_telegram_user'; // Cambiado para coincidir con tu BD
    protected $allowedFields = [
        'id_usuario', 
        'chat_id', 
        'username', 
        'first_name', 
        'is_active', 
        'notificaciones_activas',
        'fecha_registro',
        'ultima_actividad'
    ];
    
    // Remover timestamps automáticos ya que usas campos personalizados
    protected $useTimestamps = false;
    
    // O usar estos si quieres mantener la compatibilidad
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'ultima_actividad';

    /**
     * Registrar o actualizar usuario de Telegram
     */
    public function registrarUsuario($idUsuario, $chatId, $username = null, $firstName = null, $lastName = null)
    {
        $data = [
            'id_usuario' => $idUsuario,
            'chat_id' => $chatId,
            'username' => $username,
            'first_name' => $firstName,
            'is_active' => 1, // Usar 1 en lugar de true para MySQL
            'notificaciones_activas' => 1,
            'fecha_registro' => date('Y-m-d H:i:s'),
            'ultima_actividad' => date('Y-m-d H:i:s')
        ];

        // Verificar si ya existe
        $existente = $this->where('id_usuario', $idUsuario)->first();
        
        if ($existente) {
            return $this->update($existente['id_telegram_user'], $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Obtener chat_id de un usuario
     */
    public function getChatIdByUsuario($idUsuario)
    {
        $usuario = $this->where('id_usuario', $idUsuario)
                       ->where('is_active', 1)
                       ->where('notificaciones_activas', 1)
                       ->first();
        
        return $usuario ? $usuario['chat_id'] : null;
    }

    /**
     * Obtener todos los usuarios activos de Telegram
     */
    public function getUsuariosActivos()
    {
        return $this->where('is_active', 1)
                   ->where('notificaciones_activas', 1)
                   ->findAll();
    }

    /**
     * Desactivar notificaciones de un usuario
     */
    public function desactivarNotificaciones($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                   ->set('notificaciones_activas', 0)
                   ->set('ultima_actividad', date('Y-m-d H:i:s'))
                   ->update();
    }

    /**
     * Activar notificaciones de un usuario
     */
    public function activarNotificaciones($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                   ->set('notificaciones_activas', 1)
                   ->set('ultima_actividad', date('Y-m-d H:i:s'))
                   ->update();
    }

    /**
     * Verificar si un usuario tiene Telegram configurado
     */
    public function tieneTelegramConfigurado($idUsuario)
    {   
        $usuario = $this->where('id_usuario', $idUsuario)
                       ->where('is_active', 1)
                       ->first();
        
        return $usuario !== null;
    }
}