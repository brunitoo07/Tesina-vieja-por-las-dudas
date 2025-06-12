<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\DispositivoModel;
use App\Models\EnergiaModel;

class CUsuario extends BaseController
{
    protected $usuarioModel;
    protected $dispositivoModel;
    protected $energiaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->energiaModel = new EnergiaModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        log_message('debug', 'Usuario ID: ' . $idUsuario);
        
        // Obtener información del usuario
        $usuario = $this->usuarioModel->find($idUsuario);
        log_message('debug', 'Datos del usuario: ' . print_r($usuario, true));
        
        // Obtener dispositivos del usuario
        $dispositivos = $this->dispositivoModel->obtenerDispositivosUsuario($idUsuario);
        log_message('debug', 'Dispositivos encontrados: ' . print_r($dispositivos, true));
        
        // Obtener consumo total de los últimos 24 horas
        $consumo24h = $this->energiaModel->obtenerConsumo24Horas($idUsuario);
        log_message('debug', 'Consumo 24h: ' . $consumo24h);
        
        // Obtener consumo promedio diario
        $consumoPromedio = $this->energiaModel->obtenerConsumoPromedioDiario($idUsuario);
        log_message('debug', 'Consumo promedio: ' . $consumoPromedio);

        $data = [
            'usuario' => $usuario,
            'dispositivos' => $dispositivos,
            'consumo24h' => $consumo24h,
            'consumoPromedio' => $consumoPromedio
        ];

        log_message('debug', 'Datos enviados a la vista: ' . print_r($data, true));
        return view('usuario/dashboard', $data);
    }

    public function perfil()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        $idUsuario = session()->get('id_usuario');
        log_message('debug', '=== INICIO ACTUALIZACIÓN PERFIL ===');
        log_message('debug', 'ID Usuario: ' . $idUsuario);

        $usuario = $this->usuarioModel->find($idUsuario);
        log_message('debug', 'Datos actuales del usuario: ' . print_r($usuario, true));

        if ($this->request->getMethod() === 'post') {
            log_message('debug', 'Método POST detectado');
            $postData = $this->request->getPost();
            log_message('debug', 'Datos POST recibidos: ' . print_r($postData, true));

            // Validar que los datos no estén vacíos
            if (empty($postData['nombre']) || empty($postData['apellido']) || empty($postData['email'])) {
                session()->setFlashdata('error', 'Todos los campos son requeridos');
                return redirect()->to('/perfil/perfil');
            }

            $rules = [
                'nombre' => 'required|min_length[3]|max_length[50]',
                'apellido' => 'required|min_length[3]|max_length[50]',
                'email' => [
                    'rules' => 'required|valid_email|is_unique[usuario.email,id_usuario,' . $idUsuario . ']',
                    'errors' => [
                        'is_unique' => 'Este email ya está registrado por otro usuario'
                    ]
                ]
            ];

            if ($this->validate($rules)) {
                log_message('debug', 'Validación exitosa');
                
                $data = [
                    'nombre' => trim($postData['nombre']),
                    'apellido' => trim($postData['apellido']),
                    'email' => trim($postData['email']),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                log_message('debug', 'Datos preparados para actualización: ' . print_r($data, true));

                try {
                    // Verificar si hay cambios reales
                    $cambios = false;
                    foreach ($data as $key => $value) {
                        if ($usuario[$key] !== $value) {
                            $cambios = true;
                            break;
                        }
                    }

                    if (!$cambios) {
                        session()->setFlashdata('info', 'No se realizaron cambios en el perfil');
                        return redirect()->to('/perfil/perfil');
                    }

                    $result = $this->usuarioModel->update($idUsuario, $data);
                    log_message('debug', 'Resultado de la actualización: ' . ($result ? 'true' : 'false'));

                    if ($result) {
                        session()->set([
                            'email' => $data['email']
                        ]);
                        session()->setFlashdata('success', 'Perfil actualizado correctamente');
                        log_message('debug', 'Perfil actualizado exitosamente');
                    } else {
                        session()->setFlashdata('error', 'Error al actualizar el perfil');
                        log_message('error', 'Error al actualizar el perfil');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Excepción al actualizar perfil: ' . $e->getMessage());
                    session()->setFlashdata('error', 'Error al actualizar el perfil: ' . $e->getMessage());
                }
            } else {
                log_message('error', 'Error de validación: ' . print_r($this->validator->getErrors(), true));
                session()->setFlashdata('error', 'Error al validar los datos');
            }

            return redirect()->to('/perfil/perfil');
        }

        $data = [
            'usuario' => $usuario,
            'validation' => $this->validator ?? null
        ];

        return view('usuario/perfil', $data);
    }

    public function cambiarContrasena()
    {
        log_message('debug', '=== INICIO CAMBIO CONTRASEÑA ===');
        log_message('debug', 'ID Usuario: ' . session()->get('id_usuario'));

        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $current_password = $this->request->getPost('current_password');
        $new_password = $this->request->getPost('new_password');
        $confirm_password = $this->request->getPost('confirm_password');

        // Validar campos vacíos
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            return redirect()->to('/usuario/perfil')->with('error', 'Todos los campos son obligatorios');
        }

        // Validar que la nueva contraseña cumpla con los requisitos
        if (strlen($new_password) < 6 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[!@#$%]/', $new_password)) {
            return redirect()->to('/usuario/perfil')->with('error', 'La contraseña debe tener al menos 6 caracteres, una mayúscula y un símbolo (!@#$%)');
        }

        // Validar que las contraseñas coincidan
        if ($new_password !== $confirm_password) {
            return redirect()->to('/usuario/perfil')->with('error', 'Las contraseñas no coinciden');
        }

        // Obtener el usuario actual
        $idUsuario = session()->get('id_usuario');
        $usuario = $this->usuarioModel->find($idUsuario);

        // Verificar la contraseña actual
        if (!password_verify($current_password, $usuario['contrasena'])) {
            return redirect()->to('/usuario/perfil')->with('error', 'La contraseña actual es incorrecta');
        }

        // Actualizar la contraseña
        $this->usuarioModel->update($idUsuario, [
            'contrasena' => password_hash($new_password, PASSWORD_DEFAULT)
        ]);

        log_message('debug', '=== FIN CAMBIO CONTRASEÑA ===');
        return redirect()->to('/usuario/perfil')->with('success', 'Contraseña actualizada correctamente');
    }
}