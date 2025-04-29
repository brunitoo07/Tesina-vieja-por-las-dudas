<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\RolesModel;
use App\Models\InvitacionModel;
use App\Models\DispositivoModel;
use App\Models\EnergiaModel;

class Supervisor extends BaseController
{
    protected $usuarioModel;
    protected $rolesModel;
    protected $db;
    protected $dispositivoModel;
    protected $energiaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolesModel = new RolesModel();
        $this->db = \Config\Database::connect();
        $this->dispositivoModel = new DispositivoModel();
        $this->energiaModel = new EnergiaModel();
    }

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'supervisor') {
            return redirect()->to('/autenticacion/login');
        }

        // Obtener todos los usuarios
        $usuarios = $this->usuarioModel->select('usuario.*, roles.nombre_rol as nombre_rol')
                                     ->join('roles', 'roles.id_rol = usuario.id_rol')
                                     ->findAll();
        
        // Obtener todos los dispositivos
        $dispositivos = $this->dispositivoModel->findAll();
        
        // Obtener los últimos 10 usuarios registrados
        $ultimosUsuarios = $this->usuarioModel->select('usuario.*, roles.nombre_rol as nombre_rol')
                                             ->join('roles', 'roles.id_rol = usuario.id_rol')
                                             ->orderBy('usuario.created_at', 'DESC')
                                             ->limit(10)
                                             ->find();
        
        // Obtener total de dispositivos
        $totalDispositivos = count($dispositivos);

        // NUEVO: Obtener arrays de admins y supervisores
        $admins = $this->usuarioModel->where('id_rol', 1)->findAll();
        $supervisores = $this->usuarioModel->where('id_rol', 3)->findAll();

        $data = [
            'usuarios' => $usuarios,
            'dispositivos' => $dispositivos,
            'ultimosUsuarios' => $ultimosUsuarios,
            'totalDispositivos' => $totalDispositivos,
            'admins' => $admins ?? [],
            'supervisores' => $supervisores ?? []
        ];

        return view('supervisor/dashboard', $data);
    }

    public function gestionarUsuarios()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'supervisor') {
            return redirect()->to('/autenticacion/login');
        }
        
        $usuarios = $this->usuarioModel->select('usuario.*, roles.nombre_rol as nombre_rol')
                                     ->join('roles', 'roles.id_rol = usuario.id_rol')
                                     ->findAll();

        return view('supervisor/gestionarUsuarios', ['usuarios' => $usuarios]);
    }

    public function cambiarRol()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'supervisor') {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = $this->request->getPost('id_usuario');
        $nuevoRol = $this->request->getPost('nuevo_rol');

        // Verificar que el usuario existe
        $usuario = $this->usuarioModel->find($idUsuario);
        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Verificar que el rol es válido (puede asignar roles 1 y 2)
        if ($nuevoRol != 1 && $nuevoRol != 2) {
            return redirect()->back()->with('error', 'Rol no válido');
        }

        try {
            $this->usuarioModel->update($idUsuario, ['id_rol' => $nuevoRol]);
            return redirect()->back()->with('success', 'Rol actualizado correctamente');
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar rol: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el rol');
        }
    }

    public function eliminarUsuario()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'supervisor') {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no permitido']);
        }

        $idUsuario = $this->request->getPost('id_usuario');

        // Verificar que el usuario existe
        $usuario = $this->usuarioModel->find($idUsuario);
        if (!$usuario) {
            return $this->response->setJSON(['success' => false, 'message' => 'Usuario no encontrado']);
        }

        // Verificar que no se está eliminando a un administrador
        if ($usuario['id_rol'] == 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'No se puede eliminar a un administrador']);
        }

        try {
            $this->usuarioModel->delete($idUsuario);
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar usuario: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el usuario']);
        }
    }

    public function enviarInvitacion()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'supervisor') {
            return redirect()->to('/autenticacion/login');
        }

        $email = $this->request->getPost('email');
        $idRol = $this->request->getPost('id_rol');

        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            session()->set('error', 'Email inválido');
            return redirect()->back();
        }

        // Validar que el rol sea válido (usuario o admin)
        if ($idRol != 1 && $idRol != 2) {
            session()->set('error', 'Rol no válido');
            return redirect()->back();
        }

        // Generar token único
        helper('text');
        $token = random_string('alnum', 32);

        $invitacionModel = new InvitacionModel();
        $data = [
            'email' => $email,
            'token' => $token,
            'id_rol' => $idRol,
            'estado' => 'pendiente',
            'invitado_por' => session()->get('id_usuario')
        ];

        if ($invitacionModel->insert($data)) {
            // Enviar email con el token
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setFrom('medidorinteligente457@gmail.com', 'EcoVolt');
            $emailService->setSubject('Invitación a registrarte');
            
            $link = base_url("registro/invitado/$token");
            $mensaje = view('emails/invitacion', ['link' => $link]);
            
            $emailService->setMessage($mensaje);
            
            if ($emailService->send()) {
                session()->set('success', 'Invitación enviada correctamente');
            } else {
                session()->set('error', 'Error al enviar el email');
            }
        } else {
            session()->set('error', 'Error al guardar la invitación');
        }

        return redirect()->back();
    }

    public function misUsuarios()
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'supervisor') {
            return redirect()->to('/autenticacion/login');
        }

        $id_supervisor = session()->get('id_usuario');
        
        $data['usuarios'] = $this->usuarioModel
            ->select('usuario.*, roles.nombre_rol as nombre_rol')
            ->join('roles', 'roles.id_rol = usuario.id_rol')
            ->where('usuario.invitado_por', $id_supervisor)
            ->findAll();

        return view('supervisor/misUsuarios', $data);
    }

    public function dispositivosUsuarios($idUsuario)
    {
        // Obtener información del usuario
        $usuario = $this->usuarioModel->find($idUsuario);
        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Obtener dispositivos del usuario
        $dispositivos = $this->dispositivoModel->where('id_usuario', $idUsuario)->findAll();

        // Calcular consumo total en las últimas 24 horas
        $consumoTotal24h = 0;
        $promedioDiario = 0;
        
        if (!empty($dispositivos)) {
            $idsDispositivos = array_column($dispositivos, 'id_dispositivo');
            
            // Consumo total en las últimas 24 horas
            $consumoTotal24h = $this->energiaModel->select('SUM(kwh) as total')
                                                ->whereIn('id_dispositivo', $idsDispositivos)
                                                ->where('fecha >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
                                                ->first()['total'] ?? 0;

            // Promedio diario (últimos 7 días)
            $promedioDiario = $this->energiaModel->select('AVG(consumo_diario) as promedio')
                                               ->from("(
                                                   SELECT id_dispositivo, DATE(fecha) as dia, SUM(kwh) as consumo_diario
                                                   FROM energia
                                                   WHERE id_dispositivo IN (" . implode(',', $idsDispositivos) . ")
                                                   AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                                                   GROUP BY id_dispositivo, DATE(fecha)
                                               ) as consumos_diarios")
                                               ->first()['promedio'] ?? 0;
        }

        // Obtener última lectura para cada dispositivo
        foreach ($dispositivos as &$dispositivo) {
            $ultimaLectura = $this->energiaModel->select('fecha, kwh')
                                              ->where('id_dispositivo', $dispositivo['id_dispositivo'])
                                              ->orderBy('fecha', 'DESC')
                                              ->first();
            
            $dispositivo['ultima_lectura'] = $ultimaLectura ? $ultimaLectura['fecha'] : null;
            $dispositivo['ultimo_consumo'] = $ultimaLectura ? $ultimaLectura['kwh'] : 0;
        }

        return view('supervisor/dispositivosUsuario', [
            'usuario' => $usuario,
            'dispositivos' => $dispositivos,
            'consumoTotal24h' => $consumoTotal24h,
            'promedioDiario' => $promedioDiario
        ]);
    }

    public function cambiarEstadoDispositivo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no permitido']);
        }

        $idDispositivo = $this->request->getJSON()->id_dispositivo;
        $nuevoEstado = $this->request->getJSON()->estado;

        try {
            $this->dispositivoModel->update($idDispositivo, ['estado' => $nuevoEstado]);
            return $this->response->setJSON(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el estado']);
        }
    }

    public function obtenerDispositivo($idDispositivo)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no permitido']);
        }

        $dispositivo = $this->dispositivoModel->find($idDispositivo);
        if (!$dispositivo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dispositivo no encontrado']);
        }

        return $this->response->setJSON($dispositivo);
    }

    public function actualizarDispositivo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso no permitido']);
        }

        $idDispositivo = $this->request->getPost('id_dispositivo');
        $nombre = $this->request->getPost('nombre');
        $macAddress = $this->request->getPost('mac_address');

        try {
            $this->dispositivoModel->update($idDispositivo, [
                'nombre' => $nombre,
                'mac_address' => $macAddress
            ]);
            return $this->response->setJSON(['success' => true, 'message' => 'Dispositivo actualizado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el dispositivo']);
        }
    }
} 