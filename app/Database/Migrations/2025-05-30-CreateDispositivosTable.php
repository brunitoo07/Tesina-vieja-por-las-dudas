<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDispositivosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_dispositivo' => [
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
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'mac_address' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
                'unique' => true,
            ],
            'stock' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'precio' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'estado' => [
                'type' => 'ENUM',
                'constraint' => ['pendiente', 'activo', 'inactivo'],
                'default' => 'pendiente',
            ],
            'fecha_actualizacion' => [
                'type' => 'DATETIME',
                'null' => true,
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

        $this->forge->addKey('id_dispositivo', true);
        $this->forge->addForeignKey('id_usuario', 'usuario', 'id_usuario', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dispositivos');
    }

    public function down()
    {
        $this->forge->dropTable('dispositivos');
    }
} 