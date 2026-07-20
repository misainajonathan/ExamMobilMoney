<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['telephone'];

    public function getClientParTelephone(string $telephone)
    {
        return $this->where('telephone', $telephone)->first();
    }

    public function inscrireAutomatique(string $telephone)
    {
        $id = $this->insert(['telephone' => $telephone]);
        return $this->find($id);
    }

    public function getAllClients()
    {
        return $this->findAll();
    }
}