<?php

namespace App\Models;

use CodeIgniter\Model;

class CorteLineaModel extends Model
{
    protected $table = 'cortes_linea';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_dispositivo',
        'id_usuario', 
        'consumo_actual',
        'limite_configurado',
        'fecha_corte',
        'vista_por_usuario',
        'fecha_vista',
        'resuelto',
        'fecha_resolucion'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'id_dispositivo' => 'required|integer',
        'id_usuario' => 'required|integer',
        'consumo_actual' => 'required|decimal',
        'limite_configurado' => 'required|decimal',
        'fecha_corte' => 'required|valid_date'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Registrar un nuevo corte de línea
     */
    public function registrarCorte($id_dispositivo, $id_usuario, $consumo_actual, $limite_configurado)
    {
        // Verificar si ya existe un corte activo para este dispositivo
        $corteActivo = $this->where('id_dispositivo', $id_dispositivo)
                           ->where('resuelto', 0)
                           ->first();

        if ($corteActivo) {
            // Actualizar el corte existente
            return $this->update($corteActivo['id'], [
                'consumo_actual' => $consumo_actual,
                'limite_configurado' => $limite_configurado,
                'fecha_corte' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Crear nuevo corte
            return $this->insert([
                'id_dispositivo' => $id_dispositivo,
                'id_usuario' => $id_usuario,
                'consumo_actual' => $consumo_actual,
                'limite_configurado' => $limite_configurado,
                'fecha_corte' => date('Y-m-d H:i:s'),
                'vista_por_usuario' => 0,
                'resuelto' => 0
            ]);
        }
    }

    /**
     * Marcar un corte como resuelto
     */
    public function marcarComoResuelto($id_dispositivo)
    {
        return $this->where('id_dispositivo', $id_dispositivo)
                   ->where('resuelto', 0)
                   ->set([
                       'resuelto' => 1,
                       'fecha_resolucion' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }

    /**
     * Obtener cortes pendientes para un usuario
     */
    public function getCortesPendientes($id_usuario)
    {
        return $this->select('cortes_linea.*, dispositivos.nombre as nombre_dispositivo')
                   ->join('dispositivos', 'dispositivos.id_dispositivo = cortes_linea.id_dispositivo', 'left')
                   ->where('cortes_linea.id_usuario', $id_usuario)
                   ->orderBy('cortes_linea.fecha_corte', 'DESC')
                   ->findAll();
    }

    /**
     * Marcar un corte como visto por el usuario
     */
    public function marcarComoVisto($id_corte)
    {
        return $this->update($id_corte, [
            'vista_por_usuario' => 1,
            'fecha_vista' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Obtener estadísticas de cortes para un usuario
     */
    public function getEstadisticasCortes($id_usuario, $fecha_desde = null, $fecha_hasta = null)
    {
        $query = $this->where('id_usuario', $id_usuario);

        if ($fecha_desde) {
            $query->where('DATE(fecha_corte) >=', $fecha_desde);
        }
        if ($fecha_hasta) {
            $query->where('DATE(fecha_corte) <=', $fecha_hasta);
        }

        $cortes = $query->findAll();

        $estadisticas = [
            'total_cortes' => count($cortes),
            'cortes_activos' => 0,
            'cortes_resueltos' => 0,
            'cortes_vistos' => 0,
            'consumo_promedio_corte' => 0,
            'limite_promedio' => 0
        ];

        if (!empty($cortes)) {
            $suma_consumo = 0;
            $suma_limite = 0;

            foreach ($cortes as $corte) {
                if ($corte['resuelto'] == 0) $estadisticas['cortes_activos']++;
                if ($corte['resuelto'] == 1) $estadisticas['cortes_resueltos']++;
                if ($corte['vista_por_usuario'] == 1) $estadisticas['cortes_vistos']++;
                
                $suma_consumo += $corte['consumo_actual'];
                $suma_limite += $corte['limite_configurado'];
            }

            $estadisticas['consumo_promedio_corte'] = $suma_consumo / count($cortes);
            $estadisticas['limite_promedio'] = $suma_limite / count($cortes);
        }

        return $estadisticas;
    }

    /**
     * Obtener historial de cortes para un dispositivo
     */
    public function getHistorialCortes($id_dispositivo, $limite = 10)
    {
        return $this->where('id_dispositivo', $id_dispositivo)
                   ->orderBy('fecha_corte', 'DESC')
                   ->limit((int)$limite)
                   ->findAll();
    }
}
