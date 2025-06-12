<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTables extends Migration
{
    public function up()
    {
        // Modificar tabla usuarios para agregar campos de notificación
        $this->forge->addColumn('usuarios', [
            'email_notificacion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email'
            ],
            'notificaciones_activas' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'email_notificacion'
            ]
        ]);

        // Modificar tabla energia para agregar campo de límite superado
        $this->forge->addColumn('energia', [
            'limite_superado' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'kwh'
            ]
        ]);

        // Crear tabla limites_consumo si no existe
        if (!$this->db->tableExists('limites_consumo')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'id_usuario' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'id_dispositivo' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'limite_consumo' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,4',
                    'default' => 10.0000
                ],
                'email_notificacion' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true
                ],
                'notificacion_enviada' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'ultima_notificacion' => [
                    'type' => 'DATETIME',
                    'null' => true
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ]
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('id_dispositivo', 'dispositivos', 'id_dispositivo', 'CASCADE', 'CASCADE');
            $this->forge->createTable('limites_consumo');
        }
    }

    public function down()
    {
        // Eliminar campos de notificación de usuarios
        $this->forge->dropColumn('usuarios', ['email_notificacion', 'notificaciones_activas']);

        // Eliminar campo de límite superado de energia
        $this->forge->dropColumn('energia', 'limite_superado');

        // Eliminar tabla limites_consumo
        $this->forge->dropTable('limites_consumo', true);
    }
} 