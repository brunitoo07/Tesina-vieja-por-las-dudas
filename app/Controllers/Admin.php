<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;
use App\Models\InvitacionModel;
use App\Controllers\CCorreo;
use App\Models\DispositivoModel;

class Admin extends BaseController
{
    protected $usuarioModel;
    protected $rolesModel;
    protected $db;
    protected $dispositivoModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolesModel = new RolesModel();
        $this->db = \Config\Database::connect();
        $this->dispositivoModel = new DispositivoModel();
        helper('form'); // Si vas a usar el helper de formularios
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
        $idsUsuarios[] = $idAdmin; // Incluir también al admin

        // Obtener información de los usuarios invitados
        $usuarios = $this->usuarioModel->select('usuario.*, roles.nombre_rol as nombre_rol')
                                        ->join('roles', 'roles.id_rol = usuario.id_rol')
                                        ->whereIn('usuario.id_usuario', $idsUsuarios)
                                        ->findAll();

        // Obtener dispositivos de los usuarios invitados
        $dispositivos = $this->dispositivoModel->whereIn('id_usuario', $idsUsuarios)
                                                ->findAll();

        // Obtener los últimos 10 usuarios registrados (solo los invitados)
        $ultimosUsuarios = $this->usuarioModel->select('usuario.*, roles.nombre_rol as nombre_rol')
                                                ->join('roles', 'roles.id_rol = usuario.id_rol')
                                                ->whereIn('usuario.id_usuario', $idsUsuarios)
                                                ->orderBy('usuario.created_at', 'DESC')
                                                ->limit(10)
                                                ->find();

        // Obtener total de dispositivos
        $totalDispositivos = count($dispositivos);

        $data = [
            'usuarios' => $usuarios,
            'dispositivos' => $dispositivos,
            'ultimosUsuarios' => $ultimosUsuarios,
            'totalDispositivos' => $totalDispositivos
        ];

        return view('admin/dashboard', $data);
    }

   // app/Controllers/Admin.php

public function enviarInvitacion()
{
    if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
        return redirect()->to('/autenticacion/login');
    }

    $email = $this->request->getPost('email');

    // **CAMBIO CLAVE AQUÍ:** Fija el idRol directamente a '2' (o el ID de tu rol 'Usuario')
    // Ya no lo obtenemos del POST porque lo eliminamos del formulario.
    $idRol = 2; // Asumiendo que '2' es el ID para el rol 'Usuario'

    // 1. Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        session()->setFlashdata('error', 'Email inválido.');
        return redirect()->back();
    }
     // --- VALIDACIÓN ADICIONAL: Verificar si el email ya es un usuario registrado ---
     if ($this->usuarioModel->existenteEmail($email)) {
        session()->setFlashdata('error', 'El correo electrónico ' . esc($email) . ' ya está registrado como usuario. No se puede enviar una invitación.');
        return redirect()->back()->withInput();
    }

    // 2. **ELIMINA ESTE BLOQUE DE VALIDACIÓN DEL ROL**
    //    ya que siempre será '2' y no necesitamos la validación.
    /*
    if ($idRol != 2) {
        session()->setFlashdata('error', 'Solo se pueden invitar usuarios con el rol de "Usuario".');
        return redirect()->back();
    }
    */

    // A partir de aquí, las validaciones pasaron. Procedemos con la lógica de negocio.

    // 3. Generar token y datos de la invitación
    helper('text');
    $token = random_string('alnum', 32);

    $invitacionModel = new \App\Models\InvitacionModel();
    $dataInvitacion = [
        'email'        => $email,
        'token'        => $token,
        'id_rol'       => $idRol, // Ya está fijo a '2'
        'estado'       => 'pendiente',
        'invitado_por' => session()->get('id_usuario')
    ];

    // Intentar insertar la invitación en la base de datos
    if ($invitacionModel->insert($dataInvitacion)) {
        // 4. Si la invitación se guarda correctamente, intentar enviar el email
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setFrom('medidorinteligente457@gmail.com', 'EcoVolt');
        $emailService->setSubject('Invitación a registrarte en EcoVolt');

        $link = base_url("registro/invitado/$token");
        // Asegúrate de que tu vista `emails/invitacion` reciba el `id_rol`
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
        // Verificar si el usuario es admin
        $isAdmin = session()->get('logged_in') && session()->get('rol') === 'admin';

        if ($token) {
            // Si hay token, es para el registro del usuario invitado
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

        // Si no hay token y no es admin, redirigir al login
        if (!$isAdmin) {
            return redirect()->to('/autenticacion/login');
        }

        // Si es admin, mostrar el formulario de invitación
        return view('admin/invitar_usuario', [
            'isAdmin' => true
        ]);
    }

    public function guardarUsuario()
    {
        $usuarioModel = new UsuarioModel();
        $invitacionModel = new InvitacionModel();
        $correoController = new CCorreo();

        $email = $this->request->getPost('email');
        $token = $this->request->getPost('token');
        $idRol = $this->request->getPost('id_rol');
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $contrasena = $this->request->getPost('contrasena');
        $confirmar_contrasena = $this->request->getPost('confirmar_contrasena');

        // Verificar si el email ya está registrado
        if ($usuarioModel->where('email', $email)->first()) {
            session()->set('error', 'Email ya registrado: ' . $email);
            return redirect()->back();
        }

        // Verificar que las contraseñas coincidan
        if ($contrasena !== $confirmar_contrasena) {
            session()->set('error', 'Las contraseñas no coinciden');
            return redirect()->back();
        }

        // Verificar la longitud mínima de la contraseña
        if (strlen($contrasena) < 8) {
            session()->set('error', 'La contraseña debe tener al menos 8 caracteres');
            return redirect()->back();
        }

        // Verificar la invitación
        $invitacion = $invitacionModel->where('token', $token)
                                        ->where('email', $email)
                                        ->where('estado', 'pendiente')
                                        ->first();

        if (!$invitacion) {
            session()->set('error', 'Invitación no encontrada o inválida.');
            return redirect()->back();
        }

        // Crear el nuevo usuario
        $usuarioData = [
            'email' => $email,
            'contrasena' => $contrasena,
            'id_rol' => $idRol,
            'nombre' => $nombre,
            'apellido' => $apellido
        ];

        try {
            // Insertar el usuario
            $usuarioModel->insert($usuarioData);
            $idUsuario = $usuarioModel->getInsertID();

            // Actualizar el estado de la invitación
            $invitacionModel->update($invitacion['id_invitacion'], ['estado' => 'aceptada']);

            // Redirigir al login con mensaje de éxito
            session()->set('exito', 'Registro completado exitosamente. Ahora puedes iniciar sesión.');
            return redirect()->to('autenticacion/login');
        } catch (\Exception $e) {
            log_message('error', 'Error al guardar usuario: ' . $e->getMessage());
            session()->set('error', 'Error al completar el registro. Por favor, intente nuevamente.');
            return redirect()->back();
        }
    }

    // app/Controllers/Admin.php

public function listarAdmins()
{
    // Verifica si el usuario está logueado y tiene rol de 'admin'
    if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
        return redirect()->to('/autenticacion/login');
    }

    // Obtener el ID del administrador que está logueado en este momento
    $id_admin_logueado = session()->get('id_usuario');

    // Construye la consulta para obtener solo los administradores
    // que fueron invitados por el administrador logueado
    $data['usuarios'] = $this->usuarioModel
        ->select('usuario.*, roles.nombre_rol as rol')
        ->join('roles', 'roles.id_rol = usuario.id_rol')
        ->where('usuario.id_rol', 1) // Filtra para obtener solo usuarios con rol de Administrador (ID 1)
        ->where('usuario.invitado_por', $id_admin_logueado) // ¡FILTRO CLAVE: Solo muestra a los que invitó este admin!
        ->findAll();

    // Reutiliza la misma vista 'admin/gestionarUsuarios'
    // Asegúrate de que esta vista sea adecuada para mostrar esta lista de usuarios.
    return view('admin/gestionarUsuarios', $data);
}

public function gestionarUsuarios()
{
    if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
        return redirect()->to('/autenticacion/login');
    }

    // Obtener el ID del administrador logueado
    $id_admin = session()->get('id_usuario');

    // El administrador solo ve los usuarios que él invitó
    $data['usuarios'] = $this->usuarioModel
        ->select('usuario.*, roles.nombre_rol as rol') // Nota: Aquí usas 'rol' en vez de 'nombre_rol' como alias.
                                                      // Asegúrate de que tu vista use 'rol' para mostrarlo.
        ->join('roles', 'roles.id_rol = usuario.id_rol')
        ->where('usuario.invitado_por', $id_admin) // ¡Esta es la línea clave que añade el filtro!
        ->findAll();

    // La vista 'admin/gestionarUsuarios' debería ser adecuada para mostrar esta lista.
    return view('admin/gestionarUsuarios', $data);
}
    public function cambiarRol()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $usuario_id = $this->request->getPost('usuario_id');
        $id_rol = $this->request->getPost('id_rol');

        // Verificar que el usuario existe
        $usuario = $this->usuarioModel->find($usuario_id);
        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Verificar que el rol existe
        $rol = $this->rolesModel->find($id_rol);
        if (!$rol) {
            return redirect()->back()->with('error', 'Rol no válido');
        }

        // Verificar que no se está cambiando el último admin
        if ($usuario['id_rol'] == 1 && $id_rol != 1) {
            $admins = $this->usuarioModel->where('id_rol', 1)->countAllResults();
            if ($admins <= 1) {
                return redirect()->back()->with('error', 'No se puede quitar el último administrador');
            }
        }

        try {
            $this->usuarioModel->update($usuario_id, ['id_rol' => $id_rol]);
            return redirect()->back()->with('success', 'Rol actualizado correctamente');
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar rol: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el rol');
        }
    }

    public function eliminarUsuario()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $usuario_id = $this->request->getPost('usuario_id');

        // Verificar que el usuario existe
        $usuario = $this->usuarioModel->find($usuario_id);
        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Verificar que no se está eliminando el último admin
        if ($usuario['id_rol'] == 1) {
            $admins = $this->usuarioModel->where('id_rol', 1)->countAllResults();
            if ($admins <= 1) {
                return redirect()->back()->with('error', 'No se puede eliminar el último administrador');
            }
        }

        try {
            $this->usuarioModel->delete($usuario_id);
            return redirect()->back()->with('success', 'Usuario eliminado correctamente');
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el usuario');
        }
    }

    // NUEVA FUNCIÓN PARA BUSCAR DISPOSITIVOS

   public function buscarDispositivos()
   {
       if ($this->request->isAJAX() || $this->request->is('post')) { // Permitir peticiones POST también
           $macAddress = $this->request->getPost('mac_address');

           if ($macAddress) {
               // Aquí podrías guardar la MAC address en una tabla temporal
               // para dispositivos pendientes de aprobación, junto con una
               // marca de tiempo del descubrimiento.

               // Por ahora, simplemente devolvemos la MAC recibida para que
               // aparezca en la lista del administrador.
               $nuevoDispositivo = ['mac_address' => $macAddress];
               return $this->response->setJSON([$nuevoDispositivo]);
           } else {
               return $this->response->setJSON([]); // No se recibió MAC address
           }
       }

       return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
   }
    // NUEVA FUNCIÓN PARA APROBAR DISPOSITIVO
    public function aprobarDispositivo()
    {
        if ($this->request->isAJAX()) {
            $macAddress = $this->request->getVar('mac_address');
            $nombre = $this->request->getVar('nombre');

            if ($macAddress && $nombre) {
                // Verificar si ya existe un dispositivo con esta MAC (opcional, según tu lógica)
                $existingDevice = $this->dispositivoModel->where('mac_address', $macAddress)->first();

                if (!$existingDevice) {
                    // Guardar el nuevo dispositivo en la base de datos
                    $nuevoDispositivo = [
                        'nombre' => $nombre,
                        'mac_address' => $macAddress,
                        'estado' => 'activo', // O el estado que desees
                        // Aquí podrías también asociarlo a un usuario si tienes la lógica
                        // 'id_usuario' => $usuarioId;
                    ];

                    if ($this->dispositivoModel->save($nuevoDispositivo)) {
                        return $this->response->setJSON(['success' => true, 'message' => 'Dispositivo aprobado']);
                    } else {
                        return $this->response->setJSON(['success' => false, 'error' => 'Error al guardar el dispositivo']);
                    }
                } else {
                    return $this->response->setJSON(['success' => false, 'error' => 'Ya existe un dispositivo con esta MAC address']);
                }
            } else {
                return $this->response->setJSON(['success' => false, 'error' => 'Faltan la MAC address o el nombre']);
            }
        }

        return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
    }
}