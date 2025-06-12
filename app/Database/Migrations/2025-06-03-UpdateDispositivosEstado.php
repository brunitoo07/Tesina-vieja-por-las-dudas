<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateDispositivosEstado extends Migration
{
    public function up()
    {
        // Primero, modificar la columna estado para incluir el nuevo valor
        $this->db->query("ALTER TABLE dispositivos MODIFY COLUMN estado ENUM('pendiente', 'activo', 'inactivo', 'pendiente_configuracion') DEFAULT 'pendiente'");
    }

    public function down()
    {
        // Revertir el cambio
        $this->db->query("ALTER TABLE dispositivos MODIFY COLUMN estado ENUM('pendiente', 'activo', 'inactivo') DEFAULT 'pendiente'");
    }
} 