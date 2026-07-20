<?php
namespace App\Models;

use CodeIgniter\Model;

class PrefixModel extends Model
{
    protected $table = 'prefixe';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['prefixe'];

    public function insertPrefix($data)
    {
        return $this->insert($data);
    }
    public function getAllPrefixes()
    {
        return $this->findAll();
    }
    public function getPrefixById($id)
    {
        return $this->find($id);
    }
    public function updatePrefix($id, $data)
    {
        return $this->update($id, $data);
    }
    public function deletePrefix($id)
    {
        return $this->delete($id);
    }

}
