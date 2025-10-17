<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipoToCortesLinea extends Migration
{
    public function up()
    {
        // Agregar columna 'tipo' para distinguir 'corte' vs 'prealerta'
        $this->forge->addColumn('cortes_linea', [
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => 'corte',
                'after' => 'id_usuario',
            ],
        ]);

        // Índice auxiliar por rendimiento (opcional)
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_cortes_linea_dispositivo_tipo ON cortes_linea (id_dispositivo, tipo, created_at)');
    }

    public function down()
    {
        $this->forge->dropColumn('cortes_linea', 'tipo');
        // El índice se eliminará con la columna en la mayoría de motores; si no, se puede eliminar explícitamente
        // $this->db->query('DROP INDEX idx_cortes_linea_dispositivo_tipo ON cortes_linea');
    }
}


