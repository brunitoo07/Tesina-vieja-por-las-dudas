<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIpToDispositivos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('dispositivos', [
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => true,
                'after' => 'mac_address'
            ],
            'ultima_conexion' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'ip_address'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('dispositivos', ['ip_address', 'ultima_conexion']);
    }
}
