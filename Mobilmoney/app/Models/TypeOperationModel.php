<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'type_operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['type_operation'];

    public function getAllTypes()
    {
        return $this->findAll();
    }

    public function getTypeById(int $id)
    {
        return $this->find($id);
    }
}