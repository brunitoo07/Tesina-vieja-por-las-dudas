<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;
use App\Models\UsuarioModel;

class Dispositivos extends BaseController
{
    protected $dispositivoModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        // Verificar si el usuario está autenticado
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Solo permitir acceso a admin y supervisor
        if ($usuario['id_rol'] != 1 && $usuario['id_rol'] != 3) {
            return redirect()->to('dashboard')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        if ($usuario['id_rol'] == 3) {
            // Supervisor: ver todos los dispositivos, con info del usuario dueño y admin que lo invitó
            $db = \Config\Database::connect();
            $builder = $db->table('dispositivos d');
            $builder->select('d.*, a.nombre as nombre_admin, a.email as email_admin');
            $builder->join('usuario a', 'a.id_usuario = d.id_usuario');
            $builder->orderBy('d.created_at', 'DESC');
            $dispositivos = $builder->get()->getResultArray();

            // Para cada dispositivo, obtener los usuarios invitados por el admin dueño
            foreach ($dispositivos as &$disp) {
                $usuariosInvitados = $db->table('usuario')
                    ->select('nombre, apellido, email')
                    ->where('invitado_por', $disp['id_usuario'])
                    ->get()->getResultArray();
                $disp['usuarios_invitados'] = $usuariosInvitados;
            }
            unset($disp);
        } else {
            // Admin: solo ve sus dispositivos
            $dispositivos = $this->dispositivoModel
                ->where('id_usuario', $idUsuario)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }

        $data = [
            'dispositivos' => $dispositivos,
            'titulo' => 'Gestión de Dispositivos',
            'usuario' => $usuario
        ];

        return view('admin/dispositivos/index', $data);
    }

    public function buscar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Solo permitir acceso a admin y supervisor
        if ($usuario['id_rol'] != 1 && $usuario['id_rol'] != 3) {
            return redirect()->to('dashboard')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $data = [
            'titulo' => 'Buscar Dispositivos'
        ];

        return view('admin/dispositivos/buscar', $data);
    }

    public function registrar()
    {
        // ... existing code ...
    }

    public function activar($id)
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $dispositivo = $this->dispositivoModel->find($id);
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'Dispositivo no encontrado');
        }

        if ($this->dispositivoModel->update($id, ['estado' => 'activo'])) {
            return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo activado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al activar el dispositivo');
    }

    public function desactivar($id)
    {
        if (!session()->get('logged_in') || session()->get('rol') !== 'admin') {
            return redirect()->to('/autenticacion/login');
        }

        $dispositivo = $this->dispositivoModel->find($id);
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'Dispositivo no encontrado');
        }

        if ($this->dispositivoModel->update($id, ['estado' => 'inactivo'])) {
            return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo desactivado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al desactivar el dispositivo');
    }

    public function eliminar($id)
    {
        $idUsuario = session()->get('id_usuario');
        $dispositivo = $this->dispositivoModel->where('id_dispositivo', $id)
                                            ->where('id_usuario', $idUsuario)
                                            ->first();

        if (!$dispositivo) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Dispositivo no encontrado o no tienes permiso'
            ]);
        }

        if ($this->dispositivoModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Dispositivo eliminado exitosamente'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Error al eliminar el dispositivo'
        ]);
    }

    public function detalles($id)
    {
        try {
            $idUsuario = session()->get('id_usuario');
            $dispositivo = $this->dispositivoModel->select('dispositivos.*, COALESCE(fecha_actualizacion, created_at) as ultima_conexion')
                                                ->where('id_dispositivo', $id)
                                                ->where('id_usuario', $idUsuario)
                                                ->first();

            if (!$dispositivo) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Dispositivo no encontrado o no tienes permiso'
                ]);
            }

            // Formatear la fecha de última conexión
            if ($dispositivo['ultima_conexion']) {
                $dispositivo['ultima_conexion'] = date('d/m/Y H:i', strtotime($dispositivo['ultima_conexion']));
            } else {
                $dispositivo['ultima_conexion'] = 'Nunca';
            }

            return $this->response->setJSON([
                'status' => 'success',
                'dispositivo' => $dispositivo
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en detalles del dispositivo: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al cargar los detalles del dispositivo'
            ]);
        }
    }
} 