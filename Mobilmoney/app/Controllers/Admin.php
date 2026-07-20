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

    public function prefixes()
    {
        $prefixeModel = new PrefixeModel();
        $data = [
            'title' => 'Configuration des préfixes',
            'prefixes' => $prefixeModel->getAll(),
        ];
        return view('admin/prefixes', $data);
    }

    public function addPrefix()
    {
        $valeur = trim((string) $this->request->getPost('valeur'));
        $estExterne = (int) $this->request->getPost('est_externe');
        $nomOperateur = $estExterne === 1 ? trim((string) $this->request->getPost('nom_operateur')) : 'Interne';

        if ($valeur !== '') {
            $prefixeModel = new PrefixeModel();
            $prefixeModel->insert($valeur, $estExterne, $nomOperateur);

            if ($estExterne === 1) {
                $operateurModel = new OperateurModel();
                $operateurModel->syncOperateurs([$nomOperateur]);
            }
        }

        return redirect()->to(site_url('admin/prefixes'));
    }

    public function deletePrefix($id)
    {
        $prefixeModel = new PrefixeModel();
        $prefixeModel->delete((int) $id);
        return redirect()->to(site_url('admin/prefixes'));
    }

    public function commissions()
    {
        $prefixeModel = new PrefixeModel();
        $operateurModel = new OperateurModel();

        $externes = $prefixeModel->getOperateursExternes();
        $operateurModel->syncOperateurs($externes);

        $data = [
            'title' => 'Commissions Opérateurs',
            'commissions' => $operateurModel->getCommissions(),
        ];

        return view('admin/commissions', $data);
    }

    public function updateCommissions()
    {
        $commissions = $this->request->getPost('commissions');
        if (is_array($commissions)) {
            $operateurModel = new OperateurModel();
            foreach ($commissions as $nom => $pct) {
                $operateurModel->saveCommission((string) $nom, (float) $pct);
            }
            session()->setFlashdata('success', 'Commissions mises à jour avec succès.');
        }
        return redirect()->to(site_url('admin/commissions'));
    }
}