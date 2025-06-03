<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateDispositivosTable extends Migration
{
    public function up()
    {
        // Agregar las columnas usando SQL directo
        $this->db->query("
            ALTER TABLE dispositivos 
            ADD COLUMN IF NOT EXISTS created_at DATETIME NULL,
            ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL
        ");
    }

    public function down()
    {
        // No es necesario hacer nada en el down ya que no queremos eliminar estas columnas
    }
} 