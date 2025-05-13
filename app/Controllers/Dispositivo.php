<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DispositivoModel;

class Dispositivo extends BaseController
{
    protected $dispositivoModel;

    public function __construct()
    {
        $this->dispositivoModel = new DispositivoModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        log_message('debug', 'ID Usuario: ' . $idUsuario);
        
        $dispositivos = $this->dispositivoModel->obtenerDispositivosUsuario($idUsuario);
        log_message('debug', 'Dispositivos encontrados: ' . print_r($dispositivos, true));

        $data = [
            'dispositivos' => $dispositivos,
            'titulo' => 'Mis Dispositivos'
        ];

        return view('dispositivo/index', $data);
    }

    public function agregar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        return view('dispositivo/agregar');
    }

    public function guardar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }
    
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mac_address' => $this->request->getPost('mac_address'),
            'id_usuario' => session()->get('id_usuario'),
            'estado' => 1, // Valor por defecto
            'created_at' => date('Y-m-d H:i:s') // Campo requerido
        ];
    
        try {
            $this->dispositivoModel->insert($data);
            return redirect()->to('dispositivo')->with('success', 'Dispositivo guardado correctamente');
        } catch (\Exception $e) {
            log_message('error', 'Error al guardar dispositivo: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al guardar el dispositivo');
        }
    }

    public function eliminar($idDispositivo)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');

        if ($this->dispositivoModel->desvincularDispositivo($idDispositivo, $idUsuario)) {
            session()->set('exito', 'Dispositivo desvinculado correctamente');
        } else {
            session()->set('error', 'Error al desvincular el dispositivo');
        }

        return redirect()->to('dispositivo');
    }

    public function configurar()
    {
        if ($this->request->getMethod() === 'post') {
            $nombre = $this->request->getPost('nombre');
            $ssid = $this->request->getPost('ssid');
            $password = $this->request->getPost('password');

            // Aquí puedes agregar lógica para enviar estos datos al ESP32
            // o guardarlos en la base de datos si es necesario.

            return redirect()->to('/dispositivo/configurar')->with('success', 'Configuración guardada correctamente.');
        }

        return view('dispositivo/configurar');
    }
}