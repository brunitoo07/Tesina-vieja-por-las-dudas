<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddTimestampColumns extends Seeder
{
    public function run()
    {
        $this->db->query("
            ALTER TABLE dispositivos 
            ADD COLUMN IF NOT EXISTS created_at DATETIME NULL,
            ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL
        ");
    }
} 