<?php 

namespace App\Models;

class EpargneModel
{
    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database/database.sqlite');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $pdo;
    }

    public function getEpargneByClientId(int $clientId): ?array
    {
        $statement = $this->pdo()->prepare('SELECT * FROM epargne WHERE id_client = :id_client LIMIT 1');
        $statement->execute(['id_client' => $clientId]);
        $epargne = $statement->fetch(\PDO::FETCH_ASSOC);

        return $epargne === false ? null : $epargne;
    }

    public function getPourcentageInteret(int $clientId): float
    {
        $epargne = $this->getEpargneByClientId($clientId);
        return $epargne !== null ? (float) $epargne['pourcentage_interet'] : 0.0;
    }

    public function getSoldeEpargne(int $clientId): float
    {
        $epargne = $this->getEpargneByClientId($clientId);
        return $epargne !== null ? (float) $epargne['montant'] : 0.0;
    }

    public function setPourcentageInteret(int $clientId, float $pourcentageInteret): bool
    {
        $epargne = $this->getEpargneByClientId($clientId);

        if ($epargne !== null) {
            $statement = $this->pdo()->prepare('UPDATE epargne SET pourcentage_interet = :pourcentage_interet WHERE id_client = :id_client');
            return $statement->execute([
                'pourcentage_interet' => $pourcentageInteret,
                'id_client' => $clientId
            ]);
        }

        $statement = $this->pdo()->prepare('INSERT INTO epargne (id_client, pourcentage_interet, montant) VALUES (:id_client, :pourcentage_interet, 0.0)');
        return $statement->execute([
            'id_client' => $clientId,
            'pourcentage_interet' => $pourcentageInteret
        ]);
    }

    public function updateEpargne(int $clientId, float $pourcentageInteret): bool
    {
        return $this->setPourcentageInteret($clientId, $pourcentageInteret);
    }

    public function createEpargne(int $clientId, float $pourcentageInteret): bool
    {
        return $this->setPourcentageInteret($clientId, $pourcentageInteret);
    }

    public function ajouterEpargne(int $clientId, float $montant): bool
    {
        $epargne = $this->getEpargneByClientId($clientId);

        if ($epargne !== null) {
            $statement = $this->pdo()->prepare('UPDATE epargne SET montant = montant + :montant WHERE id_client = :id_client');
            return $statement->execute([
                'montant' => $montant,
                'id_client' => $clientId
            ]);
        }

        $statement = $this->pdo()->prepare('INSERT INTO epargne (id_client, montant, pourcentage_interet) VALUES (:id_client, :montant, 0.0)');
        return $statement->execute([
            'id_client' => $clientId,
            'montant' => $montant
        ]);
    }
}