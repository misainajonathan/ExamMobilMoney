<?php

namespace App\Models;

class PrefixModel
{
    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database/database.sqlite');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $pdo;
    }

    public function getAll(): array
    {
        $statement = $this->pdo()->query('SELECT id, prefixe, prefixe AS valeur, est_externe, nom_operateur FROM prefixe ORDER BY nom_operateur ASC, prefixe ASC');
        $rows = $statement !== false ? $statement->fetchAll(\PDO::FETCH_ASSOC) : [];
        return is_array($rows) ? $rows : [];
    }

    public function getAllPrefixes(): array
    {
        return $this->getAll();
    }

    public function insert(string $valeur, int $estExterne, string $nomOperateur): bool
    {
        $statement = $this->pdo()->prepare('INSERT INTO prefixe (prefixe, est_externe, nom_operateur) VALUES (:valeur, :est_externe, :nom_operateur)');
        return $statement->execute([
            'valeur' => $valeur,
            'est_externe' => $estExterne,
            'nom_operateur' => $nomOperateur,
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo()->prepare('DELETE FROM prefixe WHERE id = :id');
        return $statement->execute(['id' => $id]);
    }

    public function getOperateursExternes(): array
    {
        $statement = $this->pdo()->query('SELECT DISTINCT nom_operateur FROM prefixe WHERE est_externe = 1');
        $rows = $statement !== false ? $statement->fetchAll(\PDO::FETCH_COLUMN) : [];
        return is_array($rows) ? $rows : [];
    }

    public function getByValeur(string $valeur): ?array
    {
        $statement = $this->pdo()->prepare('SELECT id, prefixe, prefixe AS valeur, est_externe, nom_operateur FROM prefixe WHERE prefixe = :valeur LIMIT 1');
        $statement->execute(['valeur' => $valeur]);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}