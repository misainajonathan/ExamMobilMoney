<?php

namespace App\Models;

class ClientModel
{
    public function findByTelephone(string $telephone): ?array
    {
        $statement = $this->pdo()->prepare('SELECT id, telephone, date_creation FROM client WHERE telephone = :telephone LIMIT 1');
        $statement->execute(['telephone' => $telephone]);
        $client = $statement->fetch(\PDO::FETCH_ASSOC);

        return $client === null ? null : $client;
    }

    public function insert(array $data, bool $returnInsertId = false): int|bool
    {
        $statement = $this->pdo()->prepare('INSERT INTO client (telephone) VALUES (:telephone)');
        $success = $statement->execute([
            'telephone' => $data['telephone'],
        ]);

        if (! $success) {
            return false;
        }

        if (! $returnInsertId) {
            return true;
        }

        return (int) $this->pdo()->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo()->prepare('SELECT id, telephone, date_creation FROM client WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $client = $statement->fetch(\PDO::FETCH_ASSOC);

        return $client === null ? null : $client;
    }

    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database.sqlite');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $pdo;
    }
}