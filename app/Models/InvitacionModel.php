<?php namespace App\Models;

use CodeIgniter\Model;

class InvitacionModel extends Model
{
    protected $table = 'invitaciones';
    protected $primaryKey = 'id_invitacion';
    protected $allowedFields = ['email', 'token', 'id_usuario', 'fecha_expiracion', 'id_rol', 'estado', 'created_at', 'invitado_por'];
    protected $useTimestamps = false;

    public function crearInvitacion($email, $idRol)
    {
        // Generar token único
        $token = bin2hex(random_bytes(32));
        
        // Fecha de expiración (7 días)
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+7 days'));

        $data = [
            'email' => $email,
            'token' => $token,
            'fecha_expiracion' => $fechaExpiracion,
            'id_rol' => $idRol,
            'estado' => 'pendiente',
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->insert($data)) {
            return $token;
        }

        return false;
    }

    public function validarInvitacion($token)
    {
        return $this->where('token', $token)
                   ->where('estado', 'pendiente')
                   ->where('fecha_expiracion >', date('Y-m-d H:i:s'))
                   ->first();
    }

    public function marcarComoAceptada($token)
    {
        return $this->where('token', $token)
                   ->set('estado', 'aceptada')
                   ->update();
    }
}