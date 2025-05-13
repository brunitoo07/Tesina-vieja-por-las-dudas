<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLecturaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'dispositivo_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'voltaje' => [
                'type' => 'FLOAT',
                'null' => false
            ],
            'corriente' => [
                'type' => 'FLOAT',
                'null' => false
            ],
            'potencia' => [
                'type' => 'FLOAT',
                'null' => false
            ],
            'kwh' => [
                'type' => 'FLOAT',
                'null' => false
            ],
            'fecha' => [
                'type' => 'DATETIME',
                'null' => false
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('dispositivo_id', 'dispositivos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lectura');
    }

    public function down()
    {
        $this->forge->dropTable('lectura');
    }
}