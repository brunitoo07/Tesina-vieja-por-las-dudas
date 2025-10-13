<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * MODELO DE DISPOSITIVO - EcoVolt
 * 
 * Este modelo maneja todas las operaciones relacionadas con los dispositivos IoT (ESP32)
 * que monitorean el consumo de energía eléctrica.
 * 
 * FUNCIONALIDADES PRINCIPALES:
 * - Gestión de dispositivos ESP32
 * - Validación de direcciones MAC únicas
 * - Control de stock y precios
 * - Estados de dispositivos (activo/inactivo)
 * - Códigos de activación únicos
 * - Relación con usuarios y lecturas de energía
 * 
 * TIPOS DE MAC:
 * - mac_address: MAC simulada para identificación en el sistema
 * - mac_real_esp32: MAC física real del dispositivo ESP32
 * 
 * ESTADOS:
 * - activo: Dispositivo funcionando y enviando datos
 * - inactivo: Dispositivo deshabilitado o sin conexión
 */
class DispositivoModel extends Model
{
    // ==================== CONFIGURACIÓN DE LA TABLA ====================
    
    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'dispositivos';
    
    /** @var string Clave primaria de la tabla */
    protected $primaryKey = 'id_dispositivo';
    
    /** @var bool Usar auto incremento para la clave primaria */
    protected $useAutoIncrement = true;
    
    /** @var string Tipo de datos que retorna (array, object, etc.) */
    protected $returnType = 'array';
    
    /** @var bool Usar soft deletes (eliminación lógica) */
    protected $useSoftDeletes = false;
    
    /** @var bool Proteger campos automáticamente */
    protected $protectFields = true;
    
    /** @var array Campos permitidos para inserción/actualización */
    protected $allowedFields = [
        'id_usuario',           // ID del usuario propietario
        'nombre',               // Nombre descriptivo del dispositivo
        'mac_address',          // Dirección MAC simulada para identificación
        'mac_real_esp32',       // Dirección MAC física real del ESP32
        'codigo_activacion',    // Código único para activar el dispositivo
        'stock',                // Cantidad en stock
        'precio',               // Precio del dispositivo
        'descripcion',          // Descripción del dispositivo
        'estado',               // Estado actual (activo/inactivo)
        'created_at',           // Fecha de creación
        'updated_at',           // Fecha de última actualización
        'ultima_lectura'        // Timestamp de la última lectura de energía
    ];

    // ==================== CONFIGURACIÓN DE TIMESTAMPS ====================
    
    /** @var bool Usar timestamps automáticos (deshabilitado para control manual) */
    protected $useTimestamps = false;
    
    /** @var string Formato de fecha para timestamps */
    protected $dateFormat = 'datetime';
    
    /** @var string Nombre del campo de fecha de creación */
    protected $createdField = 'created_at';
    
    /** @var string Nombre del campo de fecha de actualización */
    protected $updatedField = 'updated_at';
    
    /** @var string Nombre del campo de fecha de eliminación (soft delete) */
    // protected $deletedField = 'deleted_at'; // Descomenta si usas soft deletes y tienes la columna

    // ==================== REGLAS DE VALIDACIÓN ====================
    
    /** @var array Reglas de validación para los campos del dispositivo */
    protected $validationRules = [
        'id_usuario' => 'required|numeric',                                                                           // Usuario propietario obligatorio
        'nombre' => 'required|min_length[3]|max_length[100]',                                                         // Nombre 3-100 caracteres
        'mac_address' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[dispositivos.mac_address]|valid_mac_address', // MAC simulada única y válida
        'mac_real_esp32' => 'permit_empty|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[dispositivos.mac_real_esp32]|valid_mac_address', // MAC real única y válida
        'codigo_activacion' => 'permit_empty|alpha_numeric|min_length[10]|max_length[32]|is_unique[dispositivos.codigo_activacion]', // Código único 10-32 caracteres
        'stock' => 'permit_empty|numeric|greater_than_equal_to[0]',                                                   // Stock numérico no negativo
        'precio' => 'permit_empty|numeric|greater_than_equal_to[0]',                                                  // Precio numérico no negativo
        'descripcion' => 'permit_empty|max_length[255]',                                                              // Descripción opcional, máximo 255 caracteres
        'estado' => 'required|in_list[activo,inactivo]'                                                               // Estado obligatorio: activo o inactivo
    ];

    /** @var array Mensajes personalizados para las validaciones */
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
            'in_list' => 'El estado debe ser activo o inactivo'
        ]
    ];

    /** @var bool Saltar validación automática */
    protected $skipValidation = false;
    
    /** @var bool Limpiar reglas de validación automáticamente */
    protected $cleanValidationRules = true;

    // ==================== CONSTRUCTOR ====================
    
    /**
     * Constructor del modelo
     * 
     * NOTA IMPORTANTE: Las columnas `created_at` y `updated_at` deben crearse 
     * con una migración, no aquí. Si no tienes una migración que las cree, 
     * deberías crear una.
     */
    public function __construct()
    {
        parent::__construct();
        
        // **IMPORTANTE**: Eliminar esta línea. Las columnas `created_at` y `updated_at` 
        // deben crearse con una migración, no aquí.
        // Si no tienes una migración que las cree, deberías hacerla.
        // $this->db->query("
        //     ALTER TABLE dispositivos
        //     ADD COLUMN IF NOT EXISTS created_at DATETIME NULL,
        //     ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL
        // ");
    }

    // ==================== MÉTODOS PÚBLICOS PRINCIPALES ====================
    
    /**
     * Obtiene todos los dispositivos de un usuario específico
     * 
     * @param int $userId ID del usuario
     * @return array Lista de dispositivos del usuario
     */
    public function getDispositivosPorUsuario($userId)
    {
        return $this->where('id_usuario', $userId)->findAll();
    }

    /**
     * Obtiene todos los dispositivos con información del usuario y su rol
     * 
     * @return array Lista de dispositivos con información de usuarios y roles
     */
    public function getAllDispositivosConUsuario()
    {
        return $this->select('dispositivos.*, usuario.nombre as nombre_usuario, usuario.apellido as apellido_usuario, usuario.email as email_usuario, roles.nombre_rol as nombre_rol_usuario')
                    ->join('usuario', 'usuario.id_usuario = dispositivos.id_usuario', 'left')
                    ->join('roles', 'roles.id_rol = usuario.id_rol', 'left')
                    ->findAll();
    }

    /**
     * Obtiene dispositivos de un usuario considerando su rol y permisos
     * 
     * LÓGICA DE PERMISOS:
     * - Usuario normal (rol 2): Ve sus dispositivos + dispositivos del admin que lo invitó
     * - Admin/Supervisor (rol 1/3): Ve solo sus propios dispositivos
     * 
     * @param int $idUsuario ID del usuario
     * @return array Lista de dispositivos con última lectura incluida
     */
    public function obtenerDispositivosUsuario($idUsuario)
    {
        $db = \Config\Database::connect();
        
        // Obtener información del usuario (rol y quién lo invitó)
        $builder = $db->table('usuario');
        $builder->select('id_rol, invitado_por');
        $builder->where('id_usuario', $idUsuario);
        $usuario = $builder->get()->getRowArray();

        if ($usuario && $usuario['id_rol'] == 2) { 
            // Usuario normal: puede ver dispositivos del admin que lo invitó + los propios
            $builder = $db->table('dispositivos d');
            $builder->select('d.*, u.nombre as nombre_usuario, u.email as email_usuario');
            $builder->join('usuario u', 'u.id_usuario = d.id_usuario');
            $builder->groupStart()
                    ->where('u.id_usuario', $usuario['invitado_por']) // Dispositivos del admin que lo invitó
                    ->orWhere('d.id_usuario', $idUsuario) // Dispositivos propios
                    ->groupEnd();
            $dispositivos = $builder->get()->getResultArray();
        } else { 
            // Admin o supervisor: solo ve sus propios dispositivos
            $builder = $db->table('dispositivos d');
            $builder->select('d.*, u.nombre as nombre_usuario, u.email as email_usuario');
            $builder->join('usuario u', 'u.id_usuario = d.id_usuario');
            $builder->where('d.id_usuario', $idUsuario);
            $dispositivos = $builder->get()->getResultArray();
        }

        // Agregar la última lectura de energía para cada dispositivo
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

    // ==================== MÉTODOS DE BÚSQUEDA ====================
    
    /**
     * Busca un dispositivo por su dirección MAC simulada
     * @param string $macAddress Dirección MAC a buscar
     * @return array|null Datos del dispositivo encontrado
     */
    public function obtenerPorMac($macAddress)
    {
        return $this->where('mac_address', $macAddress)->first();
    }

    /**
     * Verifica si un dispositivo ya está registrado por su MAC
     * @param string $macAddress Dirección MAC a verificar
     * @return bool True si existe, false si no
     */
    public function dispositivoExiste($macAddress)
    {
        return $this->where('mac_address', $macAddress)->countAllResults() > 0;
    }

    /**
     * Obtiene un dispositivo por su ID
     * @param int $idDispositivo ID del dispositivo
     * @return array|null Datos del dispositivo
     */
    public function obtenerDispositivo($idDispositivo)
    {
        return $this->find($idDispositivo);
    }

    // ==================== MÉTODOS DE ACTUALIZACIÓN ====================
    
    /**
     * Actualiza el estado de un dispositivo
     * @param int $idDispositivo ID del dispositivo
     * @param string $estado Nuevo estado (activo/inactivo)
     * @return bool True si se actualizó correctamente
     */
    public function actualizarEstado($idDispositivo, $estado)
    {
        return $this->update($idDispositivo, ['estado' => $estado]);
    }

    /**
     * Actualiza la última lectura de un dispositivo
     * @param int $idDispositivo ID del dispositivo
     * @param string $lectura Timestamp de la última lectura
     * @return bool True si se actualizó correctamente
     */
    public function actualizarUltimaLectura($idDispositivo, $lectura)
    {
        return $this->update($idDispositivo, ['ultima_lectura' => $lectura]);
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