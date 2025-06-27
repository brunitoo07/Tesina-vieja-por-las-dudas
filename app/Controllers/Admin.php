<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;
use App\Models\InvitacionModel;
use App\Controllers\CCorreo; // Asegúrate de que esta clase exista y sea usada para emails
use App\Models\DispositivoModel;

class Admin extends BaseController
{
    protected $usuarioModel;
    protected $rolesModel;
    protected $db; // Generalmente no es necesario inyectar directamente la conexión DB si usas Models
    protected $dispositivoModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolesModel = new RolesModel();
        $this->db = \Config\Database::connect(); // Se mantiene por si hay queries directas, pero intenta usar modelos
        $this->dispositivoModel = new DispositivoModel();
        helper('form');
        helper('text'); // Necesario para random_string
    }

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $idAdmin = session()->get('id_usuario');

        // Obtener usuarios invitados por este admin
        $invitacionModel = new InvitacionModel();
        $usuariosInvitados = $invitacionModel->where('invitado_por', $idAdmin)
                                             ->where('estado', 'aceptada')
                                             ->findAll();

        $idsUsuarios = array_column($usuariosInvitados, 'id_usuario');
        $idsUsuarios[] = $idAdmin; // Incluir también al admin logueado

        // Obtener información de los usuarios que el admin puede ver
        $usuarios = $this->usuarioModel->select('usuario.*, roles.nombre_rol as nombre_rol')
                                         ->join('roles', 'roles.id_rol = usuario.id_rol')
                                         ->whereIn('usuario.id_usuario', $idsUsuarios)
                                         ->findAll();

        // Obtener dispositivos de los usuarios que el admin puede ver
        $dispositivos = $this->dispositivoModel->whereIn('id_usuario', $idsUsuarios)
                                              ->findAll();

        // Obtener los últimos 10 usuarios registrados (solo los invitados por este admin)
        $ultimosUsuarios = $this->usuarioModel->select('usuario.*, roles.nombre_rol as nombre_rol')
                                              ->join('roles', 'roles.id_rol = usuario.id_rol')
                                              ->whereIn('usuario.id_usuario', $idsUsuarios)
                                              ->orderBy('usuario.created_at', 'DESC')
                                              ->limit(10)
                                              ->find();

        // Obtener total de dispositivos (filtrado por el admin logueado)
        $totalDispositivos = count($dispositivos);

        $data = [
            'usuarios' => $usuarios,
            'dispositivos' => $dispositivos,
            'ultimosUsuarios' => $ultimosUsuarios,
            'totalDispositivos' => $totalDispositivos
        ];

        return view('admin/dashboard', $data);
    }

    public function enviarInvitacion()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $email = $this->request->getPost('email');
        $idRol = 2; // Asumiendo que '2' es el ID para el rol 'Usuario'

        // 1. Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            session()->setFlashdata('error', 'Email inválido.');
            return redirect()->back()->withInput();
        }

        // --- VALIDACIÓN ADICIONAL: Verificar si el email ya es un usuario registrado ---
        if ($this->usuarioModel->existenteEmail($email)) {
            session()->setFlashdata('error', 'El correo electrónico ' . esc($email) . ' ya está registrado como usuario. No se puede enviar una invitación.');
            return redirect()->back()->withInput();
        }

        // 2. Generar token y datos de la invitación
        $token = random_string('alnum', 32);

        $invitacionModel = new \App\Models\InvitacionModel();
        $dataInvitacion = [
            'email'        => $email,
            'token'        => $token,
            'id_rol'       => $idRol,
            'estado'       => 'pendiente',
            'invitado_por' => session()->get('id_usuario')
        ];

        // Intentar insertar la invitación en la base de datos
        if ($invitacionModel->insert($dataInvitacion)) {
            // 3. Si la invitación se guarda correctamente, intentar enviar el email
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setFrom('medidorinteligente457@gmail.com', 'EcoVolt');
            $emailService->setSubject('Invitación a registrarte en EcoVolt');

            $link = base_url("registro/invitado/$token");
            $mensaje = view('emails/invitacion', ['link' => $link, 'id_rol' => $idRol]);

            $emailService->setMessage($mensaje);

            if ($emailService->send()) {
                session()->setFlashdata('success', 'Invitación enviada correctamente.');
            } else {
                log_message('error', 'Error al enviar email de invitación: ' . $emailService->printDebugger(['headers', 'subject', 'body']));
                session()->setFlashdata('error', 'Error al enviar el email de invitación. Por favor, inténtalo de nuevo.');
            }
        } else {
            session()->setFlashdata('error', 'Error al guardar la invitación. Posiblemente el email ya ha sido invitado o hay un problema en la base de datos.');
        }

        return redirect()->back();
    }

    public function invitar($token = null)
    {
        $isAdmin = session()->get('logged_in') && session()->get('rol') === 'admin';

        if ($token) {
            $model = new \App\Models\InvitacionModel();
            $invitacion = $model->validarInvitacion($token);

            if (!$invitacion) {
                return redirect()->to('/')->with('error', 'Token inválido o expirado');
            }

            return view('admin/invitar_usuario', [
                'email' => $invitacion['email'],
                'id_rol' => $invitacion['id_rol'],
                'token' => $token,
                'isAdmin' => false
            ]);
        }

        if (!$isAdmin) {
            return redirect()->to('/autenticacion/login');
        }

        return view('admin/invitar_usuario', [
            'isAdmin' => true
        ]);
    }

    public function guardarUsuario()
    {
        $usuarioModel = new UsuarioModel();
        $invitacionModel = new InvitacionModel();
        // $correoController = new CCorreo(); // No instanciar un controller aquí, usa servicios o el helper email directamente

        $email = $this->request->getPost('email');
        $token = $this->request->getPost('token');
        $idRol = $this->request->getPost('id_rol');
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $contrasena = $this->request->getPost('contrasena');
        $confirmar_contrasena = $this->request->getPost('confirmar_contrasena');

        // Validaciones
        if ($usuarioModel->where('email', $email)->first()) {
            session()->setFlashdata('error', 'Email ya registrado: ' . $email);
            return redirect()->back()->withInput();
        }

        if ($contrasena !== $confirmar_contrasena) {
            session()->setFlashdata('error', 'Las contraseñas no coinciden');
            return redirect()->back()->withInput();
        }

        if (strlen($contrasena) < 8) {
            session()->setFlashdata('error', 'La contraseña debe tener al menos 8 caracteres');
            return redirect()->back()->withInput();
        }

        // Verificar la invitación
        $invitacion = $invitacionModel->where('token', $token)
                                       ->where('email', $email)
                                       ->where('estado', 'pendiente')
                                       ->first();

        if (!$invitacion) {
            session()->setFlashdata('error', 'Invitación no encontrada o inválida.');
            return redirect()->back();
        }

        // Crear el nuevo usuario
        $usuarioData = [
            'email' => $email,
            'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT), // Hashear la contraseña
            'id_rol' => $idRol,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'invitado_por' => $invitacion['invitado_por'] ?? null
        ];

        try {
            $usuarioModel->insert($usuarioData);
            $idUsuario = $usuarioModel->getInsertID();

            $invitacionModel->update($invitacion['id_invitacion'], [
                'estado' => 'aceptada',
                'id_usuario' => $idUsuario
            ]);

            session()->setFlashdata('success', 'Registro completado exitosamente. Ahora puedes iniciar sesión.');
            return redirect()->to('autenticacion/login');
        } catch (\Exception $e) {
            log_message('error', 'Error al guardar usuario: ' . $e->getMessage());
            session()->setFlashdata('error', 'Error al completar el registro. Por favor, intente nuevamente.');
            return redirect()->back()->withInput();
        }
    }

    public function listarAdmins()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $id_admin_logueado = session()->get('id_usuario');

        $data['usuarios'] = $this->usuarioModel
            ->select('usuario.*, roles.nombre_rol as rol')
            ->join('roles', 'roles.id_rol = usuario.id_rol')
            ->where('usuario.id_rol', 1) // Filtra para obtener solo usuarios con rol de Administrador (ID 1)
            ->where('usuario.invitado_por', $id_admin_logueado) // Solo muestra a los que invitó este admin
            ->findAll();

        return view('admin/gestionarUsuarios', $data);
    }

    public function gestionarUsuarios()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $id_admin = session()->get('id_usuario');

        // El administrador solo ve los usuarios que él invitó (que tienen su id como 'invitado_por')
        $data['usuarios'] = $this->usuarioModel
            ->select('usuario.*, roles.nombre_rol as rol')
            ->join('roles', 'roles.id_rol = usuario.id_rol')
            ->where('usuario.invitado_por', $id_admin)
            ->findAll();

        return view('admin/gestionarUsuarios', $data);
    }

    public function cambiarRol()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $usuario_id = $this->request->getPost('usuario_id');
        $id_rol = $this->request->getPost('id_rol');
        $idAdminLogueado = session()->get('id_usuario');

        $usuario = $this->usuarioModel->find($usuario_id);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        // Asegurarse de que el admin solo pueda cambiar roles de usuarios que él invitó
        if ($usuario['invitado_por'] !== $idAdminLogueado && $usuario['id_usuario'] !== $idAdminLogueado) {
             return redirect()->back()->with('error', 'No tienes permiso para modificar este usuario.');
        }

        $rol = $this->rolesModel->find($id_rol);
        if (!$rol) {
            return redirect()->back()->with('error', 'Rol no válido.');
        }
        
        // Bloquear cambio de rol de administrador (rol 1) a otro rol si es el último admin
        if ($usuario['id_rol'] == 1 && $id_rol != 1) {
            $adminsCount = $this->usuarioModel->where('id_rol', 1)->countAllResults();
            if ($adminsCount <= 1) {
                return redirect()->back()->with('error', 'No se puede quitar el último administrador del sistema.');
            }
        }

        try {
            $this->usuarioModel->update($usuario_id, ['id_rol' => $id_rol]);
            return redirect()->back()->with('success', 'Rol actualizado correctamente.');
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar rol: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el rol.');
        }
    }

    public function eliminarUsuario()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $usuario_id = $this->request->getPost('usuario_id');
        $idAdminLogueado = session()->get('id_usuario');

        $usuario = $this->usuarioModel->find($usuario_id);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        // Asegurarse de que el admin solo pueda eliminar usuarios que él invitó
        if ($usuario['invitado_por'] !== $idAdminLogueado && $usuario['id_usuario'] !== $idAdminLogueado) {
             return redirect()->back()->with('error', 'No tienes permiso para eliminar este usuario.');
        }

        // No permitir que un admin se elimine a sí mismo
        if ($usuario_id == $idAdminLogueado) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Bloquear eliminación del último admin
        if ($usuario['id_rol'] == 1) {
            $adminsCount = $this->usuarioModel->where('id_rol', 1)->countAllResults();
            if ($adminsCount <= 1) {
                return redirect()->back()->with('error', 'No se puede eliminar el último administrador del sistema.');
            }
        }

        try {
            // Antes de eliminar el usuario, desvincular sus dispositivos
            $this->dispositivoModel->where('id_usuario', $usuario_id)->set(['id_usuario' => null, 'estado' => 'inactivo'])->update();

            $this->usuarioModel->delete($usuario_id);
            return redirect()->back()->with('success', 'Usuario y sus dispositivos asociados eliminados/desvinculados correctamente.');
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el usuario.');
        }
    }

    /**
     * Muestra la vista para "adquirir producto" (registrar un nuevo dispositivo simulado).
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response
     */
    public function adquirirProducto()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        return view('admin/adquirir_producto');
    }

    /**
     * Procesa el formulario de "adquirir producto" y registra un nuevo dispositivo simulado.
     * Genera una MAC simulada y un código de activación.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function registrarDispositivoSimulado()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $rules = [
            'nombre_dispositivo' => 'required|min_length[3]|max_length[100]',
            'precio' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|numeric|greater_than_equal_to[1]', // Debe haber al menos 1 para "adquirir"
            'descripcion' => 'permit_empty|max_length[255]',
        ];

        $messages = [
            'nombre_dispositivo' => [
                'required' => 'El nombre del dispositivo es obligatorio.',
                'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                'max_length' => 'El nombre no puede exceder los 100 caracteres.'
            ],
            'precio' => [
                'required' => 'El precio es obligatorio.',
                'numeric' => 'El precio debe ser un número.',
                'greater_than_equal_to' => 'El precio no puede ser negativo.'
            ],
            'stock' => [
                'required' => 'El stock es obligatorio.',
                'numeric' => 'El stock debe ser un número.',
                'greater_than_equal_to' => 'El stock debe ser al menos 1.'
            ],
            'descripcion' => [
                'max_length' => 'La descripción no puede exceder los 255 caracteres.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nombre = $this->request->getPost('nombre_dispositivo');
        $precio = $this->request->getPost('precio');
        $stock = $this->request->getPost('stock');
        $descripcion = $this->request->getPost('descripcion');
        $idUsuario = session()->get('id_usuario'); // El admin que "adquiere" el producto

        // Generar MAC simulada única
        do {
            $macSimulada = '';
            for ($i = 0; $i < 6; $i++) {
                $macSimulada .= sprintf('%02x', rand(0, 255));
                if ($i < 5) {
                    $macSimulada .= ':';
                }
            }
            $macSimulada = strtoupper($macSimulada);
        } while ($this->dispositivoModel->getDispositivoByMacSimulada($macSimulada) !== null);

        // Generar Código de Activación único
        do {
            $codigoActivacion = random_string('alnum', 16); // 16 caracteres alfanuméricos
        } while ($this->dispositivoModel->getDispositivoByCodigoActivacion($codigoActivacion) !== null);


        $data = [
            'id_usuario'        => $idUsuario,
            'nombre'            => $nombre,
            'mac_address'       => $macSimulada,       // MAC simulada
            'mac_real_esp32'    => null,                // Inicialmente nula
            'codigo_activacion' => $codigoActivacion,   // Código de activación
            'stock'             => $stock,
            'precio'            => $precio,
            'descripcion'       => $descripcion,
            'estado'            => 'activo',
        ];

        if ($this->dispositivoModel->insert($data)) {
            session()->setFlashdata('success', 'Producto "' . esc($nombre) . '" adquirido y listo para ser activado. Código de Activación: <strong>' . esc($codigoActivacion) . '</strong>');
            return redirect()->to('/admin/listarDispositivos'); // Redirige a la lista de dispositivos
        } else {
            session()->setFlashdata('error', 'Error al adquirir el producto. Inténtelo de nuevo. Errores de validación: ' . json_encode($this->dispositivoModel->errors()));
            return redirect()->back()->withInput()->with('errors', $this->dispositivoModel->errors());
        }
    }


    /**
     * Muestra la lista de dispositivos que el administrador puede ver.
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response
     */
    public function listarDispositivos()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $idAdmin = session()->get('id_usuario');

        // Obtener IDs de usuarios invitados por este admin
        $invitacionModel = new InvitacionModel();
        $usuariosInvitados = $invitacionModel->where('invitado_por', $idAdmin)
                                             ->where('estado', 'aceptada')
                                             ->findAll();

        $idsUsuarios = array_column($usuariosInvitados, 'id_usuario');
        $idsUsuarios[] = $idAdmin; // Incluir al propio admin

        // Obtener dispositivos filtrados por los IDs de usuario
        $dispositivos = $this->dispositivoModel->whereIn('id_usuario', $idsUsuarios)->findAll();

        // Obtener los nombres de usuario para cada dispositivo
        $usuariosMap = [];
        foreach ($idsUsuarios as $userId) {
            $user = $this->usuarioModel->find($userId);
            if ($user) {
                $usuariosMap[$user['id_usuario']] = $user['nombre'] . ' ' . $user['apellido'];
            }
        }

        // Asignar el nombre del usuario a cada dispositivo
        foreach ($dispositivos as $key => $dispositivo) {
            $dispositivos[$key]['nombre_usuario'] = $usuariosMap[$dispositivo['id_usuario']] ?? 'N/A';
        }

        $data = [
            'dispositivos' => $dispositivos,
        ];

        return view('admin/listar_dispositivos', $data);
    }

    /**
     * Permite al administrador editar la información de un dispositivo.
     * @param int $id El ID del dispositivo a editar.
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response
     */
    public function editarDispositivo($id)
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $dispositivo = $this->dispositivoModel->find($id);

        if (!$dispositivo) {
            session()->setFlashdata('error', 'Dispositivo no encontrado.');
            return redirect()->to('/admin/listarDispositivos');
        }

        // Verificar que el administrador tiene permiso para editar este dispositivo
        $idAdmin = session()->get('id_usuario');
        $invitacionModel = new InvitacionModel();
        $usuariosInvitados = $invitacionModel->where('invitado_por', $idAdmin)
                                             ->where('estado', 'aceptada')
                                             ->findAll();
        $idsUsuariosPermitidos = array_column($usuariosInvitados, 'id_usuario');
        $idsUsuariosPermitidos[] = $idAdmin;

        if (!in_array($dispositivo['id_usuario'], $idsUsuariosPermitidos)) {
            session()->setFlashdata('error', 'No tienes permiso para editar este dispositivo.');
            return redirect()->to('/admin/listarDispositivos');
        }

        if ($this->request->is('post')) {
            $rules = [
                'nombre' => 'required|min_length[3]|max_length[100]',
                'precio' => 'required|numeric|greater_than_equal_to[0]',
                'stock' => 'required|numeric|greater_than_equal_to[0]',
                'descripcion' => 'permit_empty|max_length[255]',
                'estado' => 'required|in_list[pendiente,activo,inactivo,pendiente_configuracion]',
            ];

            $messages = [
                'nombre' => ['required' => 'El nombre es obligatorio.'],
                'precio' => ['required' => 'El precio es obligatorio.', 'numeric' => 'El precio debe ser un número.'],
                'stock' => ['required' => 'El stock es obligatorio.', 'numeric' => 'El stock debe ser un número.'],
                'estado' => ['required' => 'El estado es obligatorio.', 'in_list' => 'Estado no válido.'],
            ];

            if (!$this->validate($rules, $messages)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'nombre' => $this->request->getPost('nombre'),
                'precio' => $this->request->getPost('precio'),
                'stock' => $this->request->getPost('stock'),
                'descripcion' => $this->request->getPost('descripcion'),
                'estado' => $this->request->getPost('estado'),
            ];

            // Si se cambia a inactivo y tenía mac_real_esp32, se resetea
            if ($data['estado'] === 'inactivo' && !empty($dispositivo['mac_real_esp32'])) {
                $data['mac_real_esp32'] = null;
                $data['codigo_activacion'] = null; // También reseteamos el código de activación
            }


            if ($this->dispositivoModel->update($id, $data)) {
                session()->setFlashdata('success', 'Dispositivo actualizado correctamente.');
                return redirect()->to('/admin/listarDispositivos');
            } else {
                session()->setFlashdata('error', 'Error al actualizar el dispositivo. ' . json_encode($this->dispositivoModel->errors()));
                return redirect()->back()->withInput();
            }
        }

        $data['dispositivo'] = $dispositivo;
        return view('admin/editar_dispositivo', $data);
    }

    /**
     * Permite al administrador eliminar un dispositivo.
     * @param int $id El ID del dispositivo a eliminar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function eliminarDispositivo($id)
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $dispositivo = $this->dispositivoModel->find($id);

        if (!$dispositivo) {
            session()->setFlashdata('error', 'Dispositivo no encontrado.');
            return redirect()->to('/admin/listarDispositivos');
        }

        // Verificar que el administrador tiene permiso para eliminar este dispositivo
        $idAdmin = session()->get('id_usuario');
        $invitacionModel = new InvitacionModel();
        $usuariosInvitados = $invitacionModel->where('invitado_por', $idAdmin)
                                             ->where('estado', 'aceptada')
                                             ->findAll();
        $idsUsuariosPermitidos = array_column($usuariosInvitados, 'id_usuario');
        $idsUsuariosPermitidos[] = $idAdmin;

        if (!in_array($dispositivo['id_usuario'], $idsUsuariosPermitidos)) {
            session()->setFlashdata('error', 'No tienes permiso para eliminar este dispositivo.');
            return redirect()->to('/admin/listarDispositivos');
        }

        try {
            // El método desvincularDispositivo en el modelo ya no elimina, sino que desactiva
            // Aquí, si realmente queremos eliminar, lo hacemos directamente
            if ($this->dispositivoModel->delete($id)) {
                session()->setFlashdata('success', 'Dispositivo eliminado correctamente.');
            } else {
                session()->setFlashdata('error', 'Error al eliminar el dispositivo. ' . json_encode($this->dispositivoModel->errors()));
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar dispositivo: ' . $e->getMessage());
            session()->setFlashdata('error', 'Error al eliminar el dispositivo.');
        }

        return redirect()->to('/admin/listarDispositivos');
    }

    // --- Métodos Anteriores que son Redundantes o Cambiados ---

    // `buscarDispositivos()` y `aprobarDispositivo()`:
    // Estos métodos parecen ser para una "búsqueda" de dispositivos en modo AP y una posterior "aprobación".
    // El nuevo flujo no los necesita tal cual. El "adquirir producto" ya crea el dispositivo simulado,
    // y la activación real se hará vía API con el código de activación.
    // Si necesitas simular un "escaneo de MACs" en el admin, puedes adaptar `buscarDispositivos` para
    // buscar MACs físicas que aún no estén vinculadas.
    // Los he eliminado de aquí para limpiar el controlador y enfocarnos en el nuevo flujo.
    // Si realmente los necesitas para otra funcionalidad, házmelo saber.
}