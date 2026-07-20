<?php

namespace App\Models;

class OperateurModel
{
    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database/database.sqlite');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $pdo;
    }

    public function getCommissions(): array
    {
        $statement = $this->pdo()->query('SELECT id, nom_operateur, commission_supplementaire_pct FROM configuration_operateurs ORDER BY nom_operateur ASC');
        $rows = $statement !== false ? $statement->fetchAll(\PDO::FETCH_ASSOC) : [];
        return is_array($rows) ? $rows : [];
    }

    public function saveCommission(string $nomOperateur, float $pct): bool
    {
        $statement = $this->pdo()->prepare('INSERT INTO configuration_operateurs (nom_operateur, commission_supplementaire_pct) 
            VALUES (:nom, :pct) 
            ON CONFLICT(nom_operateur) DO UPDATE SET commission_supplementaire_pct = :pct');
        return $statement->execute(['nom' => $nomOperateur, 'pct' => $pct]);
    }

    public function syncOperateurs(array $noms): void
    {
        $pdo = $this->pdo();
        foreach ($noms as $nom) {
            $statement = $pdo->prepare('INSERT OR IGNORE INTO configuration_operateurs (nom_operateur, commission_supplementaire_pct) VALUES (:nom, 0.0)');
            $statement->execute(['nom' => $nom]);
        }
    }
}