<?php

namespace App\Controllers;

use App\Models\ClientModel;

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

        $telephone = $this->normalizeTelephone((string) $this->request->getPost('telephone'));

        if ($telephone === '') {
            return view('auth/login', $data + ['error' => 'Le numéro de téléphone est obligatoire.']);
        }

        $validationError = $this->validateTelephone($telephone);

        if ($validationError !== null) {
            return view('auth/login', $data + [
                'telephone' => $telephone,
                'error' => $validationError,
            ]);
        }

        
    }
}