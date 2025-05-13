<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInvitadoPorToUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuarios', [
            'invitado_por' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', 'invitado_por');
    }
}