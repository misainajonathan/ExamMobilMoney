<?php

namespace App\Models;

class OperationModel
{
    private string $databasePath;

    public function __construct()
    {
        $this->databasePath = __DIR__ . '/../../writable/database/database.sqlite';
    }

    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . $this->databasePath);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $pdo;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findByClientId(int $clientId): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, montant, frais_appliques, date_operation, id_client_expediteur, id_client_destinataire, type_operation, operateur_destination, inclure_frais_retrait
             FROM operations
             WHERE id_client_expediteur = :client_id OR id_client_destinataire = :client_id
             ORDER BY date_operation DESC, id DESC'
        );
        $statement->execute(['client_id' => $clientId]);

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return is_array($rows) ? $rows : [];
    }

    /**
     * Retourne le solde d'un client pour compatibilité avec le contrôleur admin.
     */
    public function getSoldeClient(int $clientId): float
    {
        return $this->getBalanceByClientId($clientId);
    }

    /**
     * Retourne la situation des gains de l'opérateur par type d'opération.
     *
     * @return array{retrait: float, transfert: float, total: float}
     */
    public function getSituationGains(): array
    {
        $retrait = $this->sumFeesByTypes(['retrait']);
        $transfert = $this->sumFeesByTypes(['transfert']);

        return [
            'retrait' => $retrait,
            'transfert' => $transfert,
            'total' => $retrait + $transfert,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getOperationsByType(string $typeName): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT id, montant, frais_appliques, date_operation, id_client_expediteur, id_client_destinataire, type_operation, operateur_destination, inclure_frais_retrait
             FROM operations
             WHERE LOWER(TRIM(type_operation)) = LOWER(TRIM(:type_name))
             ORDER BY date_operation DESC, id DESC'
        );
        $statement->execute(['type_name' => $typeName]);

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return is_array($rows) ? $rows : [];
    }

    public function sumFeesByTypes(array $typeNames = ['retrait', 'transfert']): float
    {
        $typeNames = array_values(array_filter(array_map('strval', $typeNames)));

        if ($typeNames === []) {
            return 0.0;
        }

        $placeholders = implode(',', array_fill(0, count($typeNames), '?'));
        $statement = $this->pdo()->prepare(
            'SELECT COALESCE(SUM(frais_appliques), 0) AS total_fees
             FROM operations
             WHERE LOWER(TRIM(type_operation)) IN (' . $placeholders . ')'
        );
        $statement->execute(array_map(static fn (string $value): string => strtolower(trim($value)), $typeNames));
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return (float) ($row['total_fees'] ?? 0.0);
    }

    public function getBalanceByClientId(int $clientId): float
    {
        $statement = $this->pdo()->prepare('SELECT * FROM operations WHERE id_client_expediteur = :id OR id_client_destinataire = :id');
        $statement->execute(['id' => $clientId]);
        $operations = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $solde = 0.0;
        foreach ($operations as $op) {
            if ($op['type_operation'] === 'depot') {
                $solde += (float) $op['montant'];
            } elseif ($op['type_operation'] === 'retrait') {
                $solde -= ((float) $op['montant'] + (float) $op['frais_appliques']);
            } elseif ($op['type_operation'] === 'transfert') {
                if ((int) $op['id_client_expediteur'] === $clientId) {
                    $solde -= ((float) $op['montant'] + (float) $op['frais_appliques']);
                }
                if ((int) $op['id_client_destinataire'] === $clientId) {
                    $solde += (float) $op['montant'];
                }
            }
        }
        return $solde;
    }

    public function insertOperation(float $montant, float $frais, int $expediteurId, ?int $destinataireId, string $type, ?string $operateurDestination = null, int $inclureFraisRetrait = 0): bool
    {
        $statement = $this->pdo()->prepare('INSERT INTO operations (montant, frais_appliques, id_client_expediteur, id_client_destinataire, type_operation, date_operation, operateur_destination, inclure_frais_retrait) VALUES (:montant, :frais, :expediteur, :destinataire, :type, datetime(\'now\', \'localtime\'), :operateur_destination, :inclure_frais_retrait)');
        return $statement->execute([
            'montant' => $montant,
            'frais' => $frais,
            'expediteur' => $expediteurId,
            'destinataire' => $destinataireId,
            'type' => $type,
            'operateur_destination' => $operateurDestination,
            'inclure_frais_retrait' => $inclureFraisRetrait,
        ]);
    }

    public function getGainsInternes(): float
    {
        $statement = $this->pdo()->query('SELECT SUM(frais_appliques) as total FROM operations WHERE operateur_destination IS NULL OR operateur_destination = \'Interne\'');
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return $row !== false ? (float) ($row['total'] ?? 0.0) : 0.0;
    }

    public function getGainsExternesGroupes(): array
    {
        $statement = $this->pdo()->query('SELECT operateur_destination, SUM(frais_appliques) as total FROM operations WHERE operateur_destination IS NOT NULL AND operateur_destination != \'Interne\' GROUP BY operateur_destination ORDER BY operateur_destination ASC');
        $rows = $statement !== false ? $statement->fetchAll(\PDO::FETCH_ASSOC) : [];
        return is_array($rows) ? $rows : [];
    }

    public function getMontantsAEnvoyer(): array
    {
        $statement = $this->pdo()->query('SELECT operateur_destination, SUM(montant) as total_montant FROM operations WHERE type_operation = \'transfert\' AND operateur_destination IS NOT NULL AND operateur_destination != \'Interne\' GROUP BY operateur_destination ORDER BY operateur_destination ASC');
        $rows = $statement !== false ? $statement->fetchAll(\PDO::FETCH_ASSOC) : [];
        return is_array($rows) ? $rows : [];
    }
}