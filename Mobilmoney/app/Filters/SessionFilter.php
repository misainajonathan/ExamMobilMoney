<?php

namespace App\Filters;

class SessionFilter
{
    public function before($request, $arguments = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (! empty($_SESSION['client_id'])) {
            return null;
        }

        header('Location: /login', true, 302);
        exit;
    }

    public function after($request, $response, $arguments = null)
    {
        return null;
    }
}