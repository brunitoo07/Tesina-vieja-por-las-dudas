<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;

class Dispositivos extends BaseController
{
    protected $dispositivoModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
    }

    public function index()
    {
        $data['dispositivos'] = $this->dispositivoModel->findAll();
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

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mac_address' => $this->request->getPost('mac_address'),
            'estado' => 'pendiente'
        ];

        if ($this->dispositivoModel->insert($data)) {
            return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo registrado exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al registrar el dispositivo');
    }

    public function activar($id)
    {
        if ($this->dispositivoModel->update($id, ['estado' => 'activo'])) {
            return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo activado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al activar el dispositivo');
    }

    public function desactivar($id)
    {
        if ($this->dispositivoModel->update($id, ['estado' => 'inactivo'])) {
            return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo desactivado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al desactivar el dispositivo');
    }

    public function eliminar($id)
    {
        if ($this->dispositivoModel->delete($id)) {
            return redirect()->to('admin/dispositivos')->with('success', 'Dispositivo eliminado exitosamente');
        }

        return redirect()->back()->with('error', 'Error al eliminar el dispositivo');
    }

    public function detalles($id)
    {
        $dispositivo = $this->dispositivoModel->find($id);
        
        if (!$dispositivo) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Dispositivo no encontrado'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'dispositivo' => $dispositivo
        ]);
    }
} 