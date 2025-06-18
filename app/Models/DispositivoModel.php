<?php namespace App\Models;

use CodeIgniter\Model;

class DispositivoModel extends Model
{
    protected $table = 'dispositivos';
    protected $primaryKey = 'id_dispositivo';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_usuario',
        'nombre',
        'mac_address',       // MAC Simulada
        'mac_real_esp32',    // NUEVO: MAC Física de la ESP32
        'codigo_activacion', // NUEVO: Código para vincular ESP32
        'stock',
        'precio',
        'descripcion',
        'estado',            // Ahora incluirá 'pendiente_configuracion'
        'created_at',
        'updated_at',
        'ultima_lectura'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    // protected $deletedField = 'deleted_at'; // Descomenta si usas soft deletes y tienes la columna

    // Validation
    protected $validationRules = [
        'id_usuario' => 'required|numeric',
        'nombre' => 'required|min_length[3]|max_length[100]',
        'mac_address' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[dispositivos.mac_address]|valid_mac_address',
        'mac_real_esp32' => 'permit_empty|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[dispositivos.mac_real_esp32]|valid_mac_address',
        'codigo_activacion' => 'permit_empty|alpha_numeric|min_length[10]|max_length[32]|is_unique[dispositivos.codigo_activacion]',
        'stock' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'precio' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'descripcion' => 'permit_empty|max_length[255]',
        'estado' => 'required|in_list[pendiente,activo,inactivo,pendiente_configuracion]'
    ];

    protected $validationMessages = [
        'id_usuario' => [
            'required' => 'El ID del usuario es requerido',
            'numeric' => 'El ID del usuario debe ser un número'
        ],
        'nombre' => [
            'required' => 'El nombre del dispositivo es requerido',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede tener más de 100 caracteres'
        ],
        'mac_address' => [
            'required' => 'La dirección MAC simulada es requerida',
            'regex_match' => 'La dirección MAC simulada debe tener el formato XX:XX:XX:XX:XX:XX',
            'is_unique' => 'Esta dirección MAC simulada ya está registrada.',
            'valid_mac_address' => 'La dirección MAC no está registrada en la base de datos de MACs válidas.'
        ],
        'mac_real_esp32' => [
            'regex_match' => 'La dirección MAC física debe tener el formato XX:XX:XX:XX:XX:XX',
            'is_unique' => 'Esta dirección MAC física ya está vinculada a otro dispositivo.',
            'valid_mac_address' => 'La dirección MAC no está registrada en la base de datos de MACs válidas.'
        ],
        'codigo_activacion' => [
            'alpha_numeric' => 'El código de activación solo puede contener letras y números.',
            'min_length' => 'El código de activación debe tener al menos 10 caracteres.',
            'max_length' => 'El código de activación no puede tener más de 32 caracteres.',
            'is_unique' => 'Este código de activación ya está en uso.'
        ],
        'stock' => [
            'required' => 'El stock es requerido',
            'numeric' => 'El stock debe ser un número',
            'greater_than_equal_to' => 'El stock no puede ser negativo'
        ],
        'precio' => [
            'required' => 'El precio es requerido',
            'numeric' => 'El precio debe ser un número',
            'greater_than_equal_to' => 'El precio no puede ser negativo'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede tener más de 255 caracteres.'
        ],
        'estado' => [
            'required' => 'El estado es requerido',
            'in_list' => 'El estado debe ser pendiente, activo, inactivo o pendiente_configuracion'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function __construct()
    {
        parent::__construct();
        // **IMPORTANTE**: Eliminar esta línea. Las columnas `created_at` y `updated_at` deben crearse con una migración, no aquí.
        // Si no tienes una migración que las cree, deberías hacerla.
        // $this->db->query("
        //     ALTER TABLE dispositivos
        //     ADD COLUMN IF NOT EXISTS created_at DATETIME NULL,
        //     ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL
        // ");
    }

    // --- Métodos Actualizados y Simplificados ---

    // Este método ya no es necesario aquí, la MAC simulada se genera en el controlador Admin
    // public function generarMacSimulada() { ... }

    // Este método no es necesario, el estado 'configurando' no lo usaremos directamente.
    // public function iniciarConfiguracion($idDispositivo) { ... }

    // Este método tampoco es necesario, la activación la manejará el controlador API directamente.
    // public function activarDispositivo($idDispositivo, $macReal, $ssid) { ... }


    // Obtener dispositivos de un usuario específico (para cualquier rol, se filtra en el controlador)
    public function getDispositivosPorUsuario($userId)
    {
        return $this->where('id_usuario', $userId)->findAll();
    }

    // Obtener todos los dispositivos, con la información del usuario y su rol
    public function getAllDispositivosConUsuario()
    {
        return $this->select('dispositivos.*, usuario.nombre as nombre_usuario, usuario.apellido as apellido_usuario, usuario.email as email_usuario, roles.nombre_rol as nombre_rol_usuario')
                    ->join('usuario', 'usuario.id_usuario = dispositivos.id_usuario', 'left')
                    ->join('roles', 'roles.id_rol = usuario.id_rol', 'left')
                    ->findAll();
    }

    // Obtener todos los dispositivos de un usuario específico
    public function obtenerDispositivosUsuario($idUsuario)
    {
        // Obtener el rol del usuario y el admin que lo invitó
        $db = \Config\Database::connect();
        $builder = $db->table('usuario');
        $builder->select('id_rol, invitado_por');
        $builder->where('id_usuario', $idUsuario);
        $usuario = $builder->get()->getRowArray();

        if ($usuario && $usuario['id_rol'] == 2) { // Si es usuario normal
            // Obtener dispositivos del admin que lo invitó y los propios
            $builder = $db->table('dispositivos d');
            $builder->select('d.*, u.nombre as nombre_usuario, u.email as email_usuario');
            $builder->join('usuario u', 'u.id_usuario = d.id_usuario');
            $builder->groupStart()
                    ->where('u.id_usuario', $usuario['invitado_por']) // Dispositivos del admin que lo invitó
                    ->orWhere('d.id_usuario', $idUsuario) // Dispositivos propios
                    ->groupEnd();
            $dispositivos = $builder->get()->getResultArray();
        } else { // Si es admin o supervisor
            // Obtener todos los dispositivos del admin
            $builder = $db->table('dispositivos d');
            $builder->select('d.*, u.nombre as nombre_usuario, u.email as email_usuario');
            $builder->join('usuario u', 'u.id_usuario = d.id_usuario');
            $builder->where('d.id_usuario', $idUsuario);
            $dispositivos = $builder->get()->getResultArray();
        }

        // Para cada dispositivo, obtener su última lectura
        foreach ($dispositivos as &$dispositivo) {
            $builder = $db->table('energia');
            $builder->select('*');
            $builder->where('id_dispositivo', $dispositivo['id_dispositivo']);
            $builder->orderBy('fecha', 'DESC');
            $builder->limit(1);
            $ultima_lectura = $builder->get()->getRowArray();
            $dispositivo['ultima_lectura'] = $ultima_lectura;
        }

        return $dispositivos;
    }

    // Obtener un dispositivo por su dirección MAC
    public function obtenerPorMac($macAddress)
    {
        return $this->where('mac_address', $macAddress)->first();
    }

    // Verificar si un dispositivo ya está registrado
    public function dispositivoExiste($macAddress)
    {
        return $this->where('mac_address', $macAddress)->countAllResults() > 0;
    }

    // Actualizar el estado del dispositivo
    public function actualizarEstado($idDispositivo, $estado)
    {
        return $this->update($idDispositivo, ['estado' => $estado]);
    }

    // Actualizar la última lectura del dispositivo
    public function actualizarUltimaLectura($idDispositivo, $lectura)
    {
        return $this->update($idDispositivo, ['ultima_lectura' => $lectura]);
    }

    public function obtenerDispositivo($idDispositivo)
    {
        return $this->find($idDispositivo);
    }

    public function getDispositivoByMacSimulada($macAddress)
    {
        return $this->where('mac_address', strtoupper($macAddress))->first();
    }

    public function getDispositivoByMacReal($macAddress)
    {
        return $this->where('mac_real_esp32', strtoupper($macAddress))->first();
    }

    public function getDispositivoByCodigoActivacion($codigo)
    {
        return $this->where('codigo_activacion', strtoupper($codigo))->first();
    }

    public function getDispositivosActivos()
    {
        return $this->where('estado', 'activo')->findAll();
    }

    public function getDispositivoConStock($idDispositivo)
    {
        log_message('debug', '=== BUSCANDO DISPOSITIVO ===');
        log_message('debug', 'ID Dispositivo: ' . $idDispositivo);
        
        // Modificado para devolver el dispositivo y marcarlo como activo
        $dispositivo = $this->where('id_dispositivo', $idDispositivo)->first();
        
        if ($dispositivo) {
            // Aseguramos que el dispositivo esté activo
            $this->update($idDispositivo, ['estado' => 'activo']);
            $dispositivo['estado'] = 'activo';
        }
                    
        log_message('debug', 'Resultado de la búsqueda: ' . json_encode($dispositivo));
        log_message('debug', '=== FIN BUSQUEDA DISPOSITIVO ===');
        
        return $dispositivo;
    }

    public function actualizarStock($idDispositivo, $cantidad)
    {
        $dispositivo = $this->find($idDispositivo);
        if ($dispositivo) {
            // Modificado para no verificar el stock disponible
            $nuevoStock = $dispositivo['stock'] - $cantidad;
            return $this->update($idDispositivo, ['stock' => $nuevoStock]);
        }
        return false;
    }

    // Obtener todas las lecturas de un dispositivo
    public function obtenerLecturasDispositivo($idDispositivo, $fechaInicio = null, $fechaFin = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('energia');
        $builder->where('id_dispositivo', $idDispositivo);
        
        if ($fechaInicio && $fechaFin) {
            $builder->where('fecha >=', $fechaInicio);
            $builder->where('fecha <=', $fechaFin);
        }
        
        $builder->orderBy('fecha', 'ASC');
        return $builder->get()->getResultArray();
    }

    // Obtener la última lectura de un dispositivo
    public function obtenerUltimaLectura($idDispositivo)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('energia');
        $builder->where('id_dispositivo', $idDispositivo);
        $builder->orderBy('fecha', 'DESC');
        $builder->limit(1);
        return $builder->get()->getRowArray();
    }

    // Obtener todas las lecturas de un usuario
    public function obtenerLecturasUsuario($idUsuario, $fechaInicio = null, $fechaFin = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('energia e');
        $builder->select('e.*, d.nombre as nombre_dispositivo');
        $builder->join('dispositivos d', 'd.id_dispositivo = e.id_dispositivo');
        $builder->where('d.id_usuario', $idUsuario);
        
        if ($fechaInicio && $fechaFin) {
            $builder->where('e.fecha >=', $fechaInicio);
            $builder->where('e.fecha <=', $fechaFin);
        }
        
        $builder->orderBy('e.fecha', 'ASC');
        return $builder->get()->getResultArray();
    }

    // Obtener todas las lecturas de un dispositivo en un rango de fechas
    public function obtenerLecturasPorRango($idDispositivo, $fechaInicio, $fechaFin)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('energia');
        $builder->where('id_dispositivo', $idDispositivo);
        $builder->where('fecha >=', $fechaInicio);
        $builder->where('fecha <=', $fechaFin);
        $builder->orderBy('fecha', 'ASC');
        return $builder->get()->getResultArray();
    }

    // Obtener todas las lecturas de un usuario en un rango de fechas
    public function obtenerLecturasUsuarioPorRango($idUsuario, $fechaInicio, $fechaFin)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('energia e');
        $builder->select('e.*, d.nombre as nombre_dispositivo');
        $builder->join('dispositivos d', 'd.id_dispositivo = e.id_dispositivo');
        $builder->where('d.id_usuario', $idUsuario);
        $builder->where('e.fecha >=', $fechaInicio);
        $builder->where('e.fecha <=', $fechaFin);
        $builder->orderBy('e.fecha', 'ASC');
        return $builder->get()->getResultArray();
    }

    // Obtener todas las lecturas de un dispositivo en un rango de fechas con paginación
    public function obtenerLecturasPorRangoPaginadas($idDispositivo, $fechaInicio, $fechaFin, $porPagina = 10, $pagina = 1)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('energia');
        $builder->where('id_dispositivo', $idDispositivo);
        $builder->where('fecha >=', $fechaInicio);
        $builder->where('fecha <=', $fechaFin);
        $builder->orderBy('fecha', 'ASC');
        
        $total = $builder->countAllResults(false);
        $builder->limit($porPagina, ($pagina - 1) * $porPagina);
        
        return [
            'lecturas' => $builder->get()->getResultArray(),
            'total' => $total,
            'paginas' => ceil($total / $porPagina)
        ];
    }

    // Obtener todas las lecturas de un usuario en un rango de fechas con paginación
    public function obtenerLecturasUsuarioPorRangoPaginadas($idUsuario, $fechaInicio, $fechaFin, $porPagina = 10, $pagina = 1)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('energia e');
        $builder->select('e.*, d.nombre as nombre_dispositivo');
        $builder->join('dispositivos d', 'd.id_dispositivo = e.id_dispositivo');
        $builder->where('d.id_usuario', $idUsuario);
        $builder->where('e.fecha >=', $fechaInicio);
        $builder->where('e.fecha <=', $fechaFin);
        $builder->orderBy('e.fecha', 'ASC');
        
        $total = $builder->countAllResults(false);
        $builder->limit($porPagina, ($pagina - 1) * $porPagina);
        
        return [
            'lecturas' => $builder->get()->getResultArray(),
            'total' => $total,
            'paginas' => ceil($total / $porPagina)
        ];
    }
}