<?php

namespace App\Controllers;

//use App\Models\ClientModel;

class Auth extends BaseController
{
    public function login()
    {
        $data = [
            'title' => 'Connexion client',
            'telephone' => '',
            'error' => null,
        ];

        if ($this->request->getMethod() !== 'post') {
            return view('auth/login', $data);
        }
    }
}
      