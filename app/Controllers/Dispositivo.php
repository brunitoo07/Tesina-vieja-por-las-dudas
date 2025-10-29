<?php
/**
* Controlador para la gestión de dispositivos del usuario común.
* Permite ver, buscar, configurar y eliminar los dispositivos propios.
* NO es para administración global.
*/      
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
            log_message('error', 'Intento de guardar dispositivo sin sesión iniciada');
            return redirect()->to('/autenticacion/login');
        }
    
        log_message('info', 'Iniciando guardado de dispositivo');
        log_message('info', 'Datos recibidos: ' . json_encode($this->request->getPost()));
    
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mac_address' => $this->request->getPost('mac_address'),
            'id_usuario' => session()->get('id_usuario'),
            'estado' => 'activo',
            'created_at' => date('Y-m-d H:i:s')
        ];
    
        log_message('info', 'Datos preparados para insertar : ' . json_encode($data));
    
        try {
            // Verificar si la MAC ya existe
            $dispositivoExistente = $this->dispositivoModel->where('mac_address', $data['mac_address'])->first();
            if ($dispositivoExistente) {
                log_message('warning', 'Intento de registrar MAC duplicada: ' . $data['mac_address']);
                session()->setFlashdata('error', 'La dirección MAC ya está registrada');
                return redirect()->back()->withInput();
            }

            log_message('info', 'Intentando insertar dispositivo en la base de datos');
            $resultado = $this->dispositivoModel->insert($data);
            
            if ($resultado) {
                log_message('info', 'Dispositivo guardado exitosamente. ID: ' . $resultado);
                session()->setFlashdata('success', 'Dispositivo guardado correctamente');
                return redirect()->to(base_url('admin/dispositivos'));
            } else {
                log_message('error', 'Error al insertar dispositivo. Errores del modelo: ' . json_encode($this->dispositivoModel->errors()));
                session()->setFlashdata('error', 'Error al guardar el dispositivo: ' . implode(', ', $this->dispositivoModel->errors()));
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al guardar dispositivo: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            session()->setFlashdata('error', 'Error al guardar el dispositivo: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    
    

    /**
     * Vista de control de relé para un dispositivo
     */
   
     
}