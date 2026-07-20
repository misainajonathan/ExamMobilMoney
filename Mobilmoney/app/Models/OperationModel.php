<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'montant', 
        'frais_appliques', 
        'id_client_expediteur', 
        'id_client_destinataire', 
        'id_type_operation'
    ];

    public function ajouterOperation(array $data)
    {
        return $this->insert($data);
    }

    public function getHistoriqueClient(int $idClient)
    {
        return $this->where('id_client_expediteur', $idClient)
                    ->orWhere('id_client_destinataire', $idClient)
                    ->orderBy('date_operation', 'DESC')
                    ->findAll();
    }

    public function getSoldeClient(int $idClient)
    {
        $depots = $this->selectSum('montant')
                       ->where('id_client_expediteur', $idClient)
                       ->where('id_type_operation', 1)
                       ->first();

        $transfertsReçus = $this->selectSum('montant')
                               ->where('id_client_destinataire', $idClient)
                               ->where('id_type_operation', 3)
                               ->first();

        $retraits = $this->select('SUM(montant + frais_appliques) as total')
                         ->where('id_client_expediteur', $idClient)
                         ->where('id_type_operation', 2)
                         ->first();

        $transfertsEnvoyes = $this->select('SUM(montant + frais_appliques) as total')
                                 ->where('id_client_expediteur', $idClient)
                                 ->where('id_type_operation', 3)
                                 ->first();

        $credits = ($depots['montant'] ?? 0.0) + ($transfertsReçus['montant'] ?? 0.0);
        $debits  = ($retraits['total'] ?? 0.0) + ($transfertsEnvoyes['total'] ?? 0.0);

        return $credits - $debits;
    }

    public function getSituationGains()
    {
        $gainsRetrait = $this->selectSum('frais_appliques', 'gains')
                             ->where('id_type_operation', 2)
                             ->first();

        $gainsTransfert = $this->selectSum('frais_appliques', 'gains')
                               ->where('id_type_operation', 3)
                               ->first();

        $totalRetrait   = $gainsRetrait['gains'] ?? 0.0;
        $totalTransfert = $gainsTransfert['gains'] ?? 0.0;

        return [
            'retrait'   => $totalRetrait,
            'transfert' => $totalTransfert,
            'total'     => $totalRetrait + $totalTransfert
        ];
    }
}