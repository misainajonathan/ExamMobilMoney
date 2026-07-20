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

        $clientModel = new ClientModel();
        $client = $clientModel->findByTelephone($telephone);

        if ($client === null) {
            $clientId = $clientModel->insert([
                'telephone' => $telephone,
            ], true);

            $client = $clientModel->find((int) $clientId);
        }

        if ($client === null) {
            return view('auth/login', $data + ['error' => 'Impossible de créer ou de retrouver le client.']);
        }

        session()->regenerate(true);
        session()->set([
            'client_id' => (int) $client['id'],
            'client_telephone' => $client['telephone'],
        ]);

        session()->setFlashdata('success', 'Connexion réussie.');

        return redirect()->to('/');
    }

    private function normalizeTelephone(string $telephone): string
    {
        $telephone = trim($telephone);

        return preg_replace('/[\s\-\.\(\)]+/', '', $telephone) ?? '';
    }

    private function validateTelephone(string $telephone): ?string
    {
        if (! preg_match('/^[0-9+][0-9]{7,14}$/', $telephone)) {
            return 'Le format du numéro de téléphone est invalide.';
        }

        $prefixes = $this->getPrefixList();

        if ($prefixes === []) {
            return 'Aucun préfixe autorisé n\'est configuré en base.';
        }

        foreach ($prefixes as $prefix) {
            if (str_starts_with($telephone, (string) $prefix['prefixe'])) {
                return null;
            }
        }

        return 'Le numéro saisi ne correspond à aucun préfixe autorisé.';
    }

    /**
     * @return array<int, array{prefixe: string}>
     */
    private function getPrefixList(): array
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database.sqlite');
        $statement = $pdo->query('SELECT prefixe FROM prefixe ORDER BY LENGTH(prefixe) DESC');

        if ($statement === false) {
            return [];
        }

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return is_array($rows) ? $rows : [];
    }
}