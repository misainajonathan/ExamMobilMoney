<?php

namespace App\Models;

class ClientModel
{
    public function findByTelephone(string $telephone): ?array
    {
        $statement = $this->pdo()->prepare('SELECT id, telephone, date_creation FROM client WHERE telephone = :telephone LIMIT 1');
        $statement->execute(['telephone' => $telephone]);
        $client = $statement->fetch(\PDO::FETCH_ASSOC);

        return $client === false ? null : $client;
    }

    public function insert(array $data, bool $returnInsertId = false): int|bool
    {
        $pdo = $this->pdo();
        $statement = $pdo->prepare('INSERT INTO client (telephone) VALUES (:telephone)');
        $success = $statement->execute([
            'telephone' => $data['telephone'],
        ]);

        if (! $success) {
            return false;
        }

        if (! $returnInsertId) {
            return true;
        }

        return (int) $pdo->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo()->prepare('SELECT id, telephone, date_creation FROM client WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $client = $statement->fetch(\PDO::FETCH_ASSOC);

        return $client === false ? null : $client;
    }

    /**
     * Calcule le solde du client à partir de l'historique de ses opérations
     * (dépôts + transferts reçus - retraits - transferts envoyés - frais).
     */
    public function getSolde(int $clientId): float
    {
        $operationModel = new OperationModel();

        return $operationModel->getBalanceByClientId($clientId);
    }

    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database.sqlite');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $pdo;
    }
}