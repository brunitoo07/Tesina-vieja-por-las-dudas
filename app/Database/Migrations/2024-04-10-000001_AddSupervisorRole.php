<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupervisorRole extends Migration
{
    public function up()
    {
        // Insertar el rol de supervisor
        $this->db->table('roles')->insert([
            'id_rol' => 3,
            'nombre_rol' => 'supervisor',
            'descripcion' => 'Supervisor con acceso a gestión de usuarios y dispositivos',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function down()
    {
        // Eliminar el rol de supervisor
        $this->db->table('roles')->where('id_rol', 3)->delete();
    }
} 