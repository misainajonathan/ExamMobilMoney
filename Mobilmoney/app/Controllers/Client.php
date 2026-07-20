<?php

namespace App\Controllers;

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
        $data = [
            'title' => 'Tableau de bord client',
            'client_id' => $clientId,
            'telephone' => $_SESSION['client_telephone'] ?? '',
            'solde' => $operationModel->getBalanceByClientId($clientId),
            'operations' => $operationModel->findByClientId($clientId),
        ];

        return $this->renderSimplePage(
            $data['title'],
            'Solde actuel: ' . number_format((float) $data['solde'], 2, ',', ' ') . ' | Operations: ' . count($data['operations'])
        );
    }

    public function depot(): string
    {
        return $this->renderSimplePage('Dépôt client', 'Le formulaire de dépôt sera branché ici.');
    }

    public function retrait(): string
    {
        return $this->renderSimplePage('Retrait client', 'Le formulaire de retrait sera branché ici.');
    }

    public function transfert(): string
    {
        return $this->renderSimplePage('Transfert client', 'Le formulaire de transfert sera branché ici.');
    }

    public function historique(): string
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return $this->redirectToLogin();
        }

        $operationModel = new OperationModel();
        $data = [
            'title' => 'Historique client',
            'operations' => $operationModel->findByClientId($clientId),
        ];

        return $this->renderSimplePage(
            $data['title'],
            'Nombre d\'operations récupérées: ' . count($data['operations'])
        );
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

    private function renderSimplePage(string $title, string $message): string
    {
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        return '<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . $safeTitle . '</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><main class="container py-5"><div class="card shadow-sm"><div class="card-body p-4"><h1 class="h4 mb-3">' . $safeTitle . '</h1><p class="mb-0">' . $safeMessage . '</p></div></div></main></body></html>';
    }
}