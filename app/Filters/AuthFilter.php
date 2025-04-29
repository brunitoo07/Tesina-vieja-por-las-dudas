<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = $request->getUri()->getPath();
        log_message('debug', 'Ruta actual: ' . $path);
        log_message('debug', 'Datos en sesión: ' . print_r(session()->get(), true));

        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        // Verificar acceso a rutas de admin
        if (strpos($path, 'admin') === 0) {
            log_message('debug', 'Verificando acceso a ruta admin. Rol del usuario: ' . session()->get('rol'));
            if (session()->get('rol') !== 'admin') {
                return redirect()->to('/')->with('error', 'No tienes permisos para acceder a esta sección');
            }
        }

        // Verificar acceso a rutas de supervisor
        if (strpos($path, 'supervisor') === 0) {
            log_message('debug', 'Verificando acceso a ruta supervisor. Rol del usuario: ' . session()->get('rol'));
            if (session()->get('rol') !== 'supervisor') {
                return redirect()->to('/')->with('error', 'No tienes permisos para acceder a esta sección');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
} 