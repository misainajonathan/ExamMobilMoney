<?php

namespace App\Models;

class OperationModel
{
    private string $databasePath;

    public function __construct()
    {
        $this->databasePath = __DIR__ . '/../../writable/database.sqlite';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findByClientId(int $clientId): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT o.id, o.montant, o.frais_appliques, o.date_operation, o.id_client_expediteur, o.id_client_destinataire, o.id_type_operation, t.type_operation
             FROM operation o
             INNER JOIN type_operation t ON t.id = o.id_type_operation
             WHERE o.id_client_expediteur = :client_id OR o.id_client_destinataire = :client_id
             ORDER BY o.date_operation DESC, o.id DESC'
        );
        $statement->execute(['client_id' => $clientId]);

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return is_array($rows) ? $rows : [];
    }

    public function getBalanceByClientId(int $clientId): float
    {
        $operations = $this->findByClientId($clientId);
        $balance = 0.0;

        foreach ($operations as $operation) {
            $type = strtolower(trim((string) ($operation['type_operation'] ?? '')));
            $montant = (float) ($operation['montant'] ?? 0);
            $frais = (float) ($operation['frais_appliques'] ?? 0);
            $expediteur = (int) ($operation['id_client_expediteur'] ?? 0);
            $destinataire = $operation['id_client_destinataire'] === null ? null : (int) $operation['id_client_destinataire'];

            if ($type === 'depot' && $expediteur === $clientId) {
                $balance += $montant;
                continue;
            }

            if ($type === 'retrait' && $expediteur === $clientId) {
                $balance -= ($montant + $frais);
                continue;
            }

            if ($type === 'transfert') {
                if ($expediteur === $clientId) {
                    $balance -= ($montant + $frais);
                }

                if ($destinataire === $clientId) {
                    $balance += $montant;
                }
            }
        }

        return $balance;
    }

    public function getTypeIdByName(string $typeName): ?int
    {
        $statement = $this->pdo()->prepare('SELECT id FROM type_operation WHERE LOWER(TRIM(type_operation)) = LOWER(TRIM(:type_name)) LIMIT 1');
        $statement->execute(['type_name' => $typeName]);
        $type = $statement->fetch(\PDO::FETCH_ASSOC);

        if (! is_array($type) || ! isset($type['id'])) {
            return null;
        }

        return (int) $type['id'];
    }

    public function insertOperation(float $montant, float $fraisAppliques, int $idClientExpediteur, ?int $idClientDestinataire, string $typeOperation): int|bool
    {
        $idTypeOperation = $this->getTypeIdByName($typeOperation);

        if ($idTypeOperation === null) {
            return false;
        }

        $statement = $this->pdo()->prepare(
            'INSERT INTO operation (montant, frais_appliques, id_client_expediteur, id_client_destinataire, id_type_operation)
             VALUES (:montant, :frais_appliques, :id_client_expediteur, :id_client_destinataire, :id_type_operation)'
        );

        $success = $statement->execute([
            'montant' => $montant,
            'frais_appliques' => $fraisAppliques,
            'id_client_expediteur' => $idClientExpediteur,
            'id_client_destinataire' => $idClientDestinataire,
            'id_type_operation' => $idTypeOperation,
        ]);

        if (! $success) {
            return false;
        }

        return (int) $this->pdo()->lastInsertId();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getOperationsByType(string $typeName): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT o.id, o.montant, o.frais_appliques, o.date_operation, o.id_client_expediteur, o.id_client_destinataire, t.type_operation
             FROM operation o
             INNER JOIN type_operation t ON t.id = o.id_type_operation
             WHERE LOWER(TRIM(t.type_operation)) = LOWER(TRIM(:type_name))
             ORDER BY o.date_operation DESC, o.id DESC'
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
            'SELECT COALESCE(SUM(o.frais_appliques), 0) AS total_fees
             FROM operation o
             INNER JOIN type_operation t ON t.id = o.id_type_operation
             WHERE LOWER(TRIM(t.type_operation)) IN (' . $placeholders . ')'
        );
        $statement->execute(array_map(static fn (string $value): string => strtolower(trim($value)), $typeNames));
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return (float) ($row['total_fees'] ?? 0.0);
    }

    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . $this->databasePath);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $pdo;
    }
}