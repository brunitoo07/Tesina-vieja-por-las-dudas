<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMacValidationTable extends Migration
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
            'mac_address' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
                'unique' => true,
            ],
            'fabricante' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'tipo_dispositivo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'es_valida' => [
                'type' => 'BOOLEAN',
                'default' => true,
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
        $this->forge->addKey('mac_address');
        $this->forge->addKey('id_usuario');
        
        // Agregar la llave foránea
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('mac_validation');
    }

    public function down()
    {
        $this->forge->dropTable('mac_validation');
    }
} 