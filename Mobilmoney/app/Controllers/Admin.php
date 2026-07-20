<?php

namespace App\Controllers;

use App\Models\PrefixModel;
use App\Models\BaremeFraisModel;
use App\Models\OperationModel;
use App\Models\ClientModel;

class Admin extends BaseController
{
    protected $prefixModel;
    protected $baremeModel;
    protected $operationModel;
    protected $clientModel;

    public function __construct()
    {
        $this->prefixModel    = new PrefixModel();
        $this->baremeModel    = new BaremeFraisModel();
        $this->operationModel = new OperationModel();
        $this->clientModel    = new ClientModel();
    }

    public function dashboard()
    {
        $data['gains'] = $this->operationModel->getSituationGains();
        $data['total_clients'] = count($this->clientModel->getAllClients());
        $data['total_prefixes'] = count($this->prefixModel->getAllPrefixes());
        
        return view('admin/dashboard', $data);
    }

    public function prefixes()
    {
        $data['prefixes'] = $this->prefixModel->getAllPrefixes();
        return view('admin/prefixes', $data); // [Tâche] Créer la vue de listing et le formulaire d'ajout
    }

    public function addPrefix()
    {
        $prefixe = $this->request->getPost('prefixe');
        if (!empty($prefixe)) {
            $this->prefixModel->insertPrefix(['prefixe' => $prefixe]);
        }
        return redirect()->to(base_url('admin/prefixes'));
    }

    public function deletePrefix($id)
    {
        $this->prefixModel->deletePrefix($id);
        return redirect()->to(base_url('admin/prefixes'));
    }

    public function frais()
    {
        $data['baremes'] = $this->baremeModel->getBaremesComplets();
        return view('admin/frais', $data);
    }

    public function updateFrais()
    {
        $id = $this->request->getPost('id');
        $data = [
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais'       => $this->request->getPost('frais')
        ];
        $this->baremeModel->updateBareme($id, $data);
        return redirect()->to(base_url('admin/frais'));
    }

    public function gains()
    {
        $data['gains'] = $this->operationModel->getSituationGains();
        return view('admin/gains', $data);
    }

    public function comptes()
    {
        $clients = $this->clientModel->getAllClients();
        $comptesComplets = [];

        foreach ($clients as $client) {
            $client['solde'] = $this->operationModel->getSoldeClient($client['id']);
            $comptesComplets[] = $client;
        }

        $data['clients'] = $comptesComplets;
        return view('admin/comptes', $data);
    }
}