<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table            = 'bareme_frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['montant_min', 'montant_max', 'frais', 'id_type_operation'];

    public function getBaremesComplets()
    {
        return $this->select('bareme_frais.*, type_operation.type_operation')
                    ->join('type_operation', 'type_operation.id = bareme_frais.id_type_operation')
                    ->findAll();
    }

    // Trouve les frais correspondants à un montant et un type précis
    public function getFraisPourMontant(float $montant, int $idTypeOperation)
    {
        return $this->where('id_type_operation', $idTypeOperation)
                    ->where('montant_min <=', $montant)
                    ->where('montant_max >=', $montant)
                    ->first();
    }

    public function updateBareme(int $id, array $data)
    {
        return $this->update($id, $data);
    }
}