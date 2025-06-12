<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VerifyTables extends Migration
{
    public function up()
    {
        // Verificar si existe la tabla usuarios
        if (!$this->db->tableExists('usuarios')) {
            $this->forge->addField([
                'id_usuario' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'password' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'nombre' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
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
            $this->forge->addKey('id_usuario', true);
            $this->forge->createTable('usuarios');
        }

        // Verificar si existe la tabla dispositivos
        if (!$this->db->tableExists('dispositivos')) {
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
                'mac_address' => [
                    'type' => 'VARCHAR',
                    'constraint' => 17,
                ],
                'nombre' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
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
            $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
            $this->forge->createTable('dispositivos');
        }

        // Verificar si existe la tabla energia
        if (!$this->db->tableExists('energia')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'id_dispositivo' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'id_usuario' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'voltaje' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,4',
                ],
                'corriente' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,4',
                ],
                'potencia' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,4',
                ],
                'kwh' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,4',
                ],
                'fecha' => [
                    'type' => 'DATETIME',
                ],
                'mac_address' => [
                    'type' => 'VARCHAR',
                    'constraint' => 17,
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
            $this->forge->addForeignKey('id_dispositivo', 'dispositivos', 'id_dispositivo', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
            $this->forge->createTable('energia');
        }

        // Verificar si existe la tabla configuraciones
        if (!$this->db->tableExists('configuraciones')) {
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
                'limite_consumo' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,4',
                    'default' => 10.0000,
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
            $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
            $this->forge->createTable('configuraciones');
        }
    }

    public function down()
    {
        // No eliminamos las tablas en el down para evitar pérdida de datos
    }
} 