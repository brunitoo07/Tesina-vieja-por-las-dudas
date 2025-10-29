<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTelegramUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_usuario' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'chat_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'notificaciones_activas' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'tipo_notificaciones' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Array con tipos de notificaciones: alertas, prealertas, cortes, etc.'
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
        $this->forge->addKey('id_usuario');
        $this->forge->addKey('chat_id');
        
        // Agregar la llave foránea
        $this->forge->addForeignKey('id_usuario', 'usuario', 'id_usuario', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('telegram_users');
    }

    public function down()
    {
        $this->forge->dropTable('telegram_users');
    }
}
