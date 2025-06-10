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
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    // protected $deletedField = 'deleted_at'; // Descomenta si usas soft deletes y tienes la columna

    // Validation
    protected $validationRules = [
        'id_usuario' => 'required|numeric',
        'nombre' => 'required|min_length[3]|max_length[100]',
        'mac_address' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[dispositivos.mac_address]', // Si la MAC simulada DEBE ser única por cada "producto"
        'mac_real_esp32' => 'permit_empty|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[dispositivos.mac_real_esp32]', // DEBE ser única si se registra. 'permit_empty' porque al inicio es NULL.
        'codigo_activacion' => 'permit_empty|alpha_numeric|min_length[10]|max_length[32]|is_unique[dispositivos.codigo_activacion]', // DEBE ser única. 'permit_empty' porque al inicio es NULL.
        'stock' => 'required|numeric|greater_than_equal_to[0]',
        'precio' => 'required|numeric|greater_than_equal_to[0]',
        'descripcion' => 'permit_empty|max_length[255]', // Añadida validación para descripción
        'estado' => 'required|in_list[pendiente,activo,inactivo,pendiente_configuracion]' // Añadido el nuevo estado
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
            'is_unique' => 'Esta dirección MAC simulada ya está registrada.'
        ],
        'mac_real_esp32' => [
            'regex_match' => 'La dirección MAC física debe tener el formato XX:XX:XX:XX:XX:XX',
            'is_unique' => 'Esta dirección MAC física ya está vinculada a otro dispositivo.'
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
        return $this->select('dispositivos.*, usuarios.nombre as nombre_usuario, usuarios.apellido as apellido_usuario, usuarios.email as email_usuario, roles.nombre_rol as nombre_rol_usuario')
                    ->join('usuarios', 'usuarios.id_usuario = dispositivos.id_usuario', 'left')
                    ->join('roles', 'roles.id_rol = usuarios.id_rol', 'left')
                    ->findAll();
    }

    // El método `actualizarUltimaConexion` usaba `fecha_actualizacion`, que fue eliminada.
    // `updated_at` es manejado automáticamente si `useTimestamps` es true en el `update()` normal.
    // Si necesitas un campo específico para "última conexión" que no sea `updated_at`, añádelo a la DB y al `allowedFields`.
    // public function actualizarUltimaConexion($idDispositivo)
    // {
    //     return $this->update($idDispositivo, [
    //         'updated_at' => date('Y-m-d H:i:s')
    //     ]);
    // }

    // `obtenerDispositivosUsuario` tenía lógica de roles. Esta lógica debe ir en el controlador.
    // El modelo solo debe hacer consultas a la DB.
    // public function obtenerDispositivosUsuario($idUsuario) { ... }

    // `vincularDispositivo` se usaba para crear dispositivos activos directamente.
    // Ahora, los dispositivos se crean como 'pendiente_configuracion' y luego se actualizan.
    // Este método ya no es necesario para el nuevo flujo.
    // public function vincularDispositivo($idUsuario, $macAddress, $nombre) { ... }

    // `desvincularDispositivo` está bien si solo se usa para eliminar.
    // Si la "desvinculación" implica cambiar estado a 'inactivo' o 'pendiente', se podría ajustar.
    public function desvincularDispositivo($idDispositivo, $idUsuario)
    {
        // Verificar si el usuario es el propietario del dispositivo
        $dispositivo = $this->where('id_dispositivo', $idDispositivo)
                            ->where('id_usuario', $idUsuario)
                            ->first();

        if (!$dispositivo) {
            return false;
        }

        // Si quieres solo "desactivar" en lugar de eliminar, cambia esto:
        return $this->update($idDispositivo, ['estado' => 'inactivo', 'mac_real_esp32' => null, 'codigo_activacion' => null]);
        // return $this->delete($idDispositivo); // Para eliminar
    }

    // `findAll` sobrescribía el comportamiento base del modelo con lógica de roles.
    // Se elimina para que `findAll` del modelo devuelva *todos* los registros de la tabla.
    // La lógica de filtrado por rol debe estar en el controlador.
    // public function findAll(?int $limit = null, int $offset = 0) { ... }


    public function actualizarEstado($idDispositivo, $estado)
    {
        return $this->update($idDispositivo, [
            'estado' => $estado,
            // 'updated_at' es manejado automáticamente por el modelo
        ]);
    }

    public function obtenerDispositivo($idDispositivo)
    {
        return $this->find($idDispositivo);
    }

    public function getDispositivoByMacSimulada($macAddress)
    {
        // Renombrado para mayor claridad, busca por la MAC asignada en el sistema
        return $this->where('mac_address', $macAddress)->first();
    }

    public function getDispositivoByMacReal($macReal)
    {
        // NUEVO: Para buscar por la MAC física reportada por la ESP32
        return $this->where('mac_real_esp32', $macReal)->first();
    }

    public function getDispositivoByCodigoActivacion($codigoActivacion)
    {
        // NUEVO: Para buscar por el código de activación
        return $this->where('codigo_activacion', $codigoActivacion)->first();
    }

    public function getDispositivosActivos()
    {
        return $this->where('estado', 'activo')->findAll();
    }

    public function getDispositivoConStock($idDispositivo)
    {
        return $this->where('id_dispositivo', $idDispositivo)
                    ->where('estado', 'activo')
                    ->where('stock >', 0)
                    ->first();
    }

    public function actualizarStock($idDispositivo, $cantidad)
    {
        $dispositivo = $this->find($idDispositivo);
        if ($dispositivo) {
            $nuevoStock = $dispositivo['stock'] - $cantidad;
            if ($nuevoStock >= 0) {
                return $this->update($idDispositivo, ['stock' => $nuevoStock]);
            }
        }
        return false;
    }
}