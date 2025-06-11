<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnergyDataTable extends Migration
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
            'voltaje' => [
                'type' => 'FLOAT',
                'constraint' => '10,2',
            ],
            'corriente' => [
                'type' => 'FLOAT',
                'constraint' => '10,4',
            ],
            'potencia' => [
                'type' => 'FLOAT',
                'constraint' => '10,2',
            ],
            'kwh' => [
                'type' => 'FLOAT',
                'constraint' => '10,4',
            ],
            'mac_address' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('mac_address');
        $this->forge->addKey('created_at');
        $this->forge->createTable('energy_data');
    }

    public function down()
    {
        $this->forge->dropTable('energy_data');
    }
} 