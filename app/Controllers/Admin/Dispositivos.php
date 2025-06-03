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
        $idUsuario = session()->get('id_usuario');
        $data['dispositivos'] = $this->dispositivoModel->where('id_usuario', $idUsuario)->findAll();
        return view('admin/dispositivos/index', $data);
    }

    public function buscar()
    {
        return view('admin/dispositivos/buscar');
    }

    public function registrar()
    {
        return view('admin/dispositivos/registrar');
    }

    public function guardar()
    {
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'mac_address' => 'required|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idUsuario = session()->get('id_usuario');
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mac_address' => $this->request->getPost('mac_address'),
            'estado' => 'pendiente',
            'id_usuario' => $idUsuario
        ];

        if ($this->dispositivoModel->insert($data)) {
            return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo registrado exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al registrar el dispositivo');
    }

    public function activar($id)
    {
        $idUsuario = session()->get('id_usuario');
        $dispositivo = $this->dispositivoModel->where('id_dispositivo', $id)
                                            ->where('id_usuario', $idUsuario)
                                            ->first();

        if (!$dispositivo) {
            return redirect()->back()->with('error', 'Dispositivo no encontrado o no tienes permiso');
        }

        if ($this->dispositivoModel->update($id, ['estado' => 'activo'])) {
            return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo activado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al activar el dispositivo');
    }

    public function desactivar($id)
    {
        $idUsuario = session()->get('id_usuario');
        $dispositivo = $this->dispositivoModel->where('id_dispositivo', $id)
                                            ->where('id_usuario', $idUsuario)
                                            ->first();

        if (!$dispositivo) {
            return redirect()->back()->with('error', 'Dispositivo no encontrado o no tienes permiso');
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