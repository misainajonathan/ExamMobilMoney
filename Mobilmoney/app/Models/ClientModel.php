<?php

namespace App\Models;

class ClientModel
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllClients(): array
    {
        $statement = $this->pdo()->query('SELECT id, telephone, date_creation FROM client ORDER BY date_creation DESC, id DESC');
        $rows = $statement !== false ? $statement->fetchAll(\PDO::FETCH_ASSOC) : [];

        return is_array($rows) ? $rows : [];
    }

    public function findByTelephone(string $telephone): ?array
    {
        $statement = $this->pdo()->prepare('SELECT id, telephone, date_creation FROM client WHERE telephone = :telephone LIMIT 1');
        $statement->execute(['telephone' => $telephone]);
        $client = $statement->fetch(\PDO::FETCH_ASSOC);

        return $client === false ? null : $client;
    }

    public function getByNumero(string $telephone): ?array
    {
        return $this->findByTelephone($telephone);
    }

    public function createClient(string $telephone): bool
    {
        return (bool) $this->insert(['telephone' => $telephone]);
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

    public function getSolde(int $clientId): float
    {
        $operationModel = new OperationModel();

        return $operationModel->getBalanceByClientId($clientId);
    }

    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database/database.sqlite');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $pdo;
    }

    public function getEpargne(int $clientId): ?array
    {
        $epargneModel = new EpargneModel();
        return $epargneModel->getEpargneByClientId($clientId);
    }
}