<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;
use App\Models\UsuarioModel;
use App\Models\MacValidationModel;

class Dispositivos extends BaseController
{
    protected $dispositivoModel;
    protected $usuarioModel;
    protected $macValidationModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->macValidationModel = new MacValidationModel();
    }

    public function index()
    {
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Si es supervisor (id_rol = 3), puede ver todos los dispositivos
        if ($usuario['id_rol'] == 3) {
            $data['dispositivos'] = $this->dispositivoModel->select('dispositivos.*, COALESCE(fecha_actualizacion, created_at) as ultima_conexion')
                                                          ->findAll();
        } 
        // Si es admin (id_rol = 1), solo ve sus dispositivos
        else if ($usuario['id_rol'] == 1) {
            $data['dispositivos'] = $this->dispositivoModel->select('dispositivos.*, COALESCE(fecha_actualizacion, created_at) as ultima_conexion')
                                                          ->where('id_usuario', $idUsuario)
                                                          ->findAll();
        }
        // Para otros roles, redirigir al dashboard
        else {
            return redirect()->to('dashboard')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        return view('admin/dispositivos/index', $data);
    }

    public function buscar()
    {
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Solo permitir acceso a admin y supervisor
        if ($usuario['id_rol'] != 1 && $usuario['id_rol'] != 3) {
            return redirect()->to('dashboard')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        return view('admin/dispositivos/buscar');
    }

    public function registrar()
    {
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Solo permitir acceso a admin y supervisor
        if ($usuario['id_rol'] != 1 && $usuario['id_rol'] != 3) {
            return redirect()->to('dashboard')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        return view('admin/dispositivos/registrar');
    }

    public function guardar()
    {
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Solo permitir acceso a admin y supervisor
        if ($usuario['id_rol'] != 1 && $usuario['id_rol'] != 3) {
            return redirect()->to('dashboard')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'mac_address' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]|is_unique[dispositivos.mac_address]|valid_mac_address'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $macAddress = strtoupper($this->request->getPost('mac_address'));
        
        // Verificar si la MAC está en la tabla de validación
        if (!$this->macValidationModel->esMacValida($macAddress)) {
            return redirect()->back()->withInput()->with('error', 'La dirección MAC no está registrada en la base de datos de MACs válidas');
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mac_address' => $macAddress,
            'id_usuario' => $idUsuario,
            'estado' => 'pendiente',
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->dispositivoModel->insert($data)) {
                return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo registrado correctamente');
            } else {
                return redirect()->back()->withInput()->with('error', 'Error al registrar el dispositivo');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al registrar dispositivo: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al registrar el dispositivo');
        }
    }

    public function activar($id)
    {
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Verificar si el usuario tiene permiso para activar este dispositivo
        $dispositivo = $this->dispositivoModel->find($id);
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'Dispositivo no encontrado');
        }

        // Si es admin, solo puede activar sus propios dispositivos
        if ($usuario['id_rol'] == 1 && $dispositivo['id_usuario'] != $idUsuario) {
            return redirect()->back()->with('error', 'No tienes permiso para activar este dispositivo');
        }

        if ($this->dispositivoModel->update($id, ['estado' => 'activo'])) {
            return redirect()->to('dispositivos')->with('success', 'Dispositivo activado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al activar el dispositivo');
    }

    public function desactivar($id)
    {
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Verificar si el usuario tiene permiso para desactivar este dispositivo
        $dispositivo = $this->dispositivoModel->find($id);
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'Dispositivo no encontrado');
        }

        // Si es admin, solo puede desactivar sus propios dispositivos
        if ($usuario['id_rol'] == 1 && $dispositivo['id_usuario'] != $idUsuario) {
            return redirect()->back()->with('error', 'No tienes permiso para desactivar este dispositivo');
        }

        if ($this->dispositivoModel->update($id, ['estado' => 'inactivo'])) {
            return redirect()->to('dispositivos')->with('success', 'Dispositivo desactivado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al desactivar el dispositivo');
    }

    public function eliminar($id)
    {
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return redirect()->to('autenticacion/login')->with('error', 'Sesión expirada');
        }

        // Verificar si el usuario tiene permiso para eliminar este dispositivo
        $dispositivo = $this->dispositivoModel->find($id);
        if (!$dispositivo) {
            return redirect()->back()->with('error', 'Dispositivo no encontrado');
        }

        // Si es admin, solo puede eliminar sus propios dispositivos
        if ($usuario['id_rol'] == 1 && $dispositivo['id_usuario'] != $idUsuario) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este dispositivo');
        }

        if ($this->dispositivoModel->delete($id)) {
            return redirect()->to('dispositivos')->with('success', 'Dispositivo eliminado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al eliminar el dispositivo');
    }

    public function detalles($id)
    {
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);
        
        if (!$usuario) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesión expirada'
            ]);
        }

        $dispositivo = $this->dispositivoModel->select('dispositivos.*, COALESCE(fecha_actualizacion, created_at) as ultima_conexion')
                                             ->find($id);
        
        if (!$dispositivo) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Dispositivo no encontrado'
            ]);
        }

        // Si es admin, solo puede ver sus propios dispositivos
        if ($usuario['id_rol'] == 1 && $dispositivo['id_usuario'] != $idUsuario) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No tienes permiso para ver este dispositivo'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'dispositivo' => $dispositivo
        ]);
    }
} 