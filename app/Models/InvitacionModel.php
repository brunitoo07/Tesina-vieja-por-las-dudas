<?php namespace App\Models;

use CodeIgniter\Model;

class InvitacionModel extends Model
{
    protected $table = 'invitaciones';
    protected $primaryKey = 'id_invitacion';
    protected $allowedFields = ['email', 'token', 'id_usuario', 'fecha_expiracion', 'id_rol', 'estado'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function crearInvitacion($email, $idUsuario, $id_rol)
    {
        helper('text');
        $token = random_string('alnum', 32);

        $data = [
            'email' => $email,
            'token' => $token,
            'id_usuario' => (int)$idUsuario,
            'id_rol' => (int)$id_rol,
            'fecha_expiracion' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            'estado' => 'pendiente'
        ];

        // Verificar que ningún valor sea NULL
        foreach ($data as $key => $value) {
            if ($value === null) {
                throw new \Exception("El campo {$key} no puede ser NULL");
            }
        }

        // Construir la consulta SQL manualmente para depuración
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        try {
            $builder->insert($data);
            return $db->insertID();
        } catch (\Exception $e) {
            log_message('error', 'Error al insertar invitación: ' . $e->getMessage());
            throw $e;
        }
    }

    public function validarToken($token)
    {
        return $this->where('token', $token)
                   ->where('fecha_expiracion >', date('Y-m-d H:i:s'))
                   ->where('estado', 'pendiente')
                   ->first();
    }
}