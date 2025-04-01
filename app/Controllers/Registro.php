<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class Registro extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function invitacion($token = null)
    {
        if (!$token) {
            return redirect()->to('/')->with('error', 'Token de invitación no válido.');
        }

        // Buscar la invitación
        $invitacion = $this->db->table('invitaciones')
                              ->where('token', $token)
                              ->where('estado', 'pendiente')
                              ->where('fecha_expiracion >', date('Y-m-d H:i:s'))
                              ->get()
                              ->getRowArray();

        if (!$invitacion) {
            return redirect()->to('/')->with('error', 'La invitación no es válida o ha expirado.');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nombre' => 'required|alpha_space',
                'apellido' => 'required|alpha_space',
                'contrasena' => 'required|min_length[6]',
                'confirmar_contrasena' => 'required|matches[contrasena]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'email' => $invitacion['email'],
                'contrasena' => $this->request->getPost('contrasena'),
                'id_rol' => $invitacion['rol']
            ];

            if ($this->usuarioModel->insertarUsuario($data)) {
                // Actualizar estado de la invitación
                $this->db->table('invitaciones')
                         ->where('token', $token)
                         ->update(['estado' => 'completado']);

                return redirect()->to('/autenticacion/login')
                               ->with('success', 'Registro completado. Por favor, inicia sesión.');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Error al registrar el usuario. Por favor, inténtalo de nuevo.');
            }
        }

        return view('registro/invitacion', ['invitacion' => $invitacion]);
    }
} 