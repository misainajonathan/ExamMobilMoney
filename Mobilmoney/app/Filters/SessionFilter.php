<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SessionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Si l'utilisateur est connecté comme client ou comme admin, on le laisse passer
        if ($session->has('client_id') || $session->has('is_admin')) {
            return null;
        }

        // Sinon, redirection propre vers la page de connexion
        return redirect()->to(base_url('login'));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}