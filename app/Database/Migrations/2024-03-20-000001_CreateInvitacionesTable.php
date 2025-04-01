<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvitacionesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'rol' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'fecha_expiracion' => [
                'type' => 'DATETIME',
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['pendiente', 'completado', 'expirado'],
                'default'    => 'pendiente',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('token');
        $this->forge->createTable('invitaciones');
    }

    public function down()
    {
        $this->forge->dropTable('invitaciones');
    }
} 