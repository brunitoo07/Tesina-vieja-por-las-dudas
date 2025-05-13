<?php
namespace App\Models;

use CodeIgniter\Model;

class LecturaModel extends Model
{
    protected $table = 'lectura';
    protected $primaryKey = 'id';
    protected $allowedFields = ['dispositivo_id', 'voltaje', 'corriente', 'potencia', 'kwh', 'fecha'];
    protected $useTimestamps = false;
}