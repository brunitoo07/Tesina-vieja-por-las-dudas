<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si el usuario está autenticado
        if (!session()->get('logged_in')) {
            return redirect()->to('/autenticacion/login');
        }

        // Si estamos en una ruta de admin, verificar el rol
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        if (strpos($path, 'admin') === 0) {
            if (session()->get('rol') !== 'admin') {
                return redirect()->to('/')->with('error', 'No tienes permisos para acceder a esta sección');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
} 