<?php 
namespace App\Models;

use CodeIgniter\Model;

class CodigoModel extends Model {
    protected $table      = 'codigo';
    protected $primaryKey = 'id_codigo';
    protected $allowedFields = ['id_usuario','codigo','expiracion'];

    public function insertarCodigo($array)
    {
        return $this->insert($array);
    }

    public function obtenerUsuarioPorCodigo($codigo)
    {
        return $this->where('codigo', $codigo)
                  //  ->where('expiracion')
                    ->first();
    }

    public function eliminarCodigoPorUsuario($idUsuario)
    {
        $this->where('id_usuario', $idUsuario)->delete();
    }
}



