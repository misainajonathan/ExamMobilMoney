<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\ClientModel;
use App\Models\OperationModel;

class Client extends BaseController
{
    public function index(): string
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return $this->redirectToLogin();
        }

        $operationModel = new OperationModel();
        $clientModel = new ClientModel();

        $data = [
            'title' => 'Tableau de bord',
            'telephone' => $_SESSION['client_telephone'] ?? '',
            'solde' => $clientModel->getSolde($clientId),
            'operations' => array_slice($operationModel->findByClientId($clientId), 0, 5),
            'clientId' => $clientId,
        ];

        return view('client/dashboard', $data);
    }

    public function depot(): string
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return $this->redirectToLogin();
        }

        $data = [
            'title' => 'Dépôt',
            'error' => null,
            'montant' => '',
        ];

        if ($this->request->getMethod() !== 'post') {
            return view('client/depot', $data);
        }

        $montant = $this->parseMontant((string) $this->request->getPost('montant'));

        if ($montant === null || $montant <= 0) {
            return view('client/depot', $data + ['error' => 'Veuillez saisir un montant valide et supérieur à 0.']);
        }

        $operationModel = new OperationModel();
        $inserted = $operationModel->insertOperation($montant, 0.0, $clientId, null, 'depot');

        if ($inserted === false) {
            return view('client/depot', $data + [
                'montant' => (string) $montant,
                'error' => "Une erreur est survenue lors de l'enregistrement du dépôt.",
            ]);
        }

        session()->setFlashdata('success', 'Dépôt de ' . number_format($montant, 2, ',', ' ') . ' Ar effectué avec succès.');

        return redirect()->to('/client');
    }

    public function retrait(): string
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return $this->redirectToLogin();
        }

        $clientModel = new ClientModel();
        $solde = $clientModel->getSolde($clientId);

        $data = [
            'title' => 'Retrait',
            'error' => null,
            'montant' => '',
            'solde' => $solde,
        ];

        if ($this->request->getMethod() !== 'post') {
            return view('client/retrait', $data);
        }

        $montant = $this->parseMontant((string) $this->request->getPost('montant'));

        if ($montant === null || $montant <= 0) {
            return view('client/retrait', $data + ['error' => 'Veuillez saisir un montant valide et supérieur à 0.']);
        }

        $baremeModel = new BaremeModel();

        if (! $baremeModel->hasTrancheFor('retrait', $montant)) {
            return view('client/retrait', $data + [
                'montant' => (string) $montant,
                'error' => "Ce montant ne correspond à aucune tranche de frais configurée pour le retrait.",
            ]);
        }

        $frais = $baremeModel->getFrais('retrait', $montant);
        $total = $montant + $frais;

        if ($total > $solde) {
            return view('client/retrait', $data + [
                'montant' => (string) $montant,
                'error' => 'Solde insuffisant. Montant + frais (' . number_format($total, 2, ',', ' ') . ' Ar) supérieur au solde disponible (' . number_format($solde, 2, ',', ' ') . ' Ar).',
            ]);
        }

        $operationModel = new OperationModel();
        $inserted = $operationModel->insertOperation($montant, $frais, $clientId, null, 'retrait');

        if ($inserted === false) {
            return view('client/retrait', $data + [
                'montant' => (string) $montant,
                'error' => "Une erreur est survenue lors de l'enregistrement du retrait.",
            ]);
        }

        session()->setFlashdata('success', 'Retrait de ' . number_format($montant, 2, ',', ' ') . ' Ar effectué avec succès (frais: ' . number_format($frais, 2, ',', ' ') . ' Ar).');

        return redirect()->to('/client');
    }

    public function transfert(): string
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return $this->redirectToLogin();
        }

        $clientModel = new ClientModel();
        $solde = $clientModel->getSolde($clientId);

        $data = [
            'title' => 'Transfert',
            'error' => null,
            'montant' => '',
            'telephone_destinataire' => '',
            'solde' => $solde,
        ];

        if ($this->request->getMethod() !== 'post') {
            return view('client/transfert', $data);
        }

        $montant = $this->parseMontant((string) $this->request->getPost('montant'));
        $telephoneDestinataire = trim((string) $this->request->getPost('telephone_destinataire'));

        if ($montant === null || $montant <= 0) {
            return view('client/transfert', $data + [
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => 'Veuillez saisir un montant valide et supérieur à 0.',
            ]);
        }

        if ($telephoneDestinataire === '') {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'error' => 'Veuillez saisir le numéro de téléphone du destinataire.',
            ]);
        }

        $destinataire = $clientModel->findByTelephone($telephoneDestinataire);

        if ($destinataire === null) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => "Aucun client n'est enregistré avec ce numéro de téléphone.",
            ]);
        }

        $destinataireId = (int) $destinataire['id'];

        if ($destinataireId === $clientId) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => 'Vous ne pouvez pas effectuer un transfert vers votre propre compte.',
            ]);
        }

        $baremeModel = new BaremeModel();

        if (! $baremeModel->hasTrancheFor('transfert', $montant)) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => "Ce montant ne correspond à aucune tranche de frais configurée pour le transfert.",
            ]);
        }

        $frais = $baremeModel->getFrais('transfert', $montant);
        $total = $montant + $frais;

        if ($total > $solde) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => 'Solde insuffisant. Montant + frais (' . number_format($total, 2, ',', ' ') . ' Ar) supérieur au solde disponible (' . number_format($solde, 2, ',', ' ') . ' Ar).',
            ]);
        }

        // Une seule ligne d'opération enregistre le débit expéditeur / crédit destinataire / frais opérateur.
        $operationModel = new OperationModel();
        $inserted = $operationModel->insertOperation($montant, $frais, $clientId, $destinataireId, 'transfert');

        if ($inserted === false) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => "Une erreur est survenue lors de l'enregistrement du transfert.",
            ]);
        }

        session()->setFlashdata('success', 'Transfert de ' . number_format($montant, 2, ',', ' ') . ' Ar vers ' . $telephoneDestinataire . ' effectué avec succès (frais: ' . number_format($frais, 2, ',', ' ') . ' Ar).');

        return redirect()->to('/client');
    }

    public function historique(): string
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return $this->redirectToLogin();
        }

        $operationModel = new OperationModel();

        $data = [
            'title' => 'Historique des opérations',
            'operations' => $operationModel->findByClientId($clientId),
            'clientId' => $clientId,
        ];

        return view('client/historique', $data);
    }

    private function parseMontant(string $raw): ?float
    {
        $raw = trim(str_replace([' ', ','], ['', '.'], $raw));

        if ($raw === '' || ! is_numeric($raw)) {
            return null;
        }

        return (float) $raw;
    }

    private function currentClientId(): ?int
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['client_id'])) {
            return null;
        }

        return (int) $_SESSION['client_id'];
    }

    private function redirectToLogin(): string
    {
        header('Location: /login', true, 302);
        exit;
    }
}
