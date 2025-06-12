<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStockColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('dispositivos', [
            'stock' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'mac_address'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('dispositivos', 'stock');
    }
} 