<?php

namespace App\Models;

class BaremeModel
{
    /**
     * Retourne le montant des frais applicables pour un type d'opération et un montant donnés,
     * en se basant sur les tranches définies dans bareme_frais.
     * Retourne 0.0 si aucune tranche ne correspond (ex: dépôt, qui n'a pas de barème).
     */
    public function getFrais(string $typeOperation, float $montant): float
    {
        $statement = $this->pdo()->prepare(
            'SELECT b.frais
             FROM bareme_frais b
             INNER JOIN type_operation t ON t.id = b.id_type_operation
             WHERE LOWER(TRIM(t.type_operation)) = LOWER(TRIM(:type_operation))
               AND :montant BETWEEN b.montant_min AND b.montant_max
             LIMIT 1'
        );
        $statement->execute([
            'type_operation' => $typeOperation,
            'montant' => $montant,
        ]);

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if (! is_array($row) || ! isset($row['frais'])) {
            return 0.0;
        }

        return (float) $row['frais'];
    }

    /**
     * Vérifie qu'une tranche de frais existe pour ce type d'opération et ce montant.
     * Permet de distinguer "frais = 0" (tranche trouvée, gratuite) de "aucune tranche
     * ne couvre ce montant" (montant hors limite, doit être rejeté par le contrôleur).
     */
    public function hasTrancheFor(string $typeOperation, float $montant): bool
    {
        $statement = $this->pdo()->prepare(
            'SELECT 1
             FROM bareme_frais b
             INNER JOIN type_operation t ON t.id = b.id_type_operation
             WHERE LOWER(TRIM(t.type_operation)) = LOWER(TRIM(:type_operation))
               AND :montant BETWEEN b.montant_min AND b.montant_max
             LIMIT 1'
        );
        $statement->execute([
            'type_operation' => $typeOperation,
            'montant' => $montant,
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC) !== false;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getBaremesByType(string $typeOperation): array
    {
        $statement = $this->pdo()->prepare(
            'SELECT b.id, b.montant_min, b.montant_max, b.frais
             FROM bareme_frais b
             INNER JOIN type_operation t ON t.id = b.id_type_operation
             WHERE LOWER(TRIM(t.type_operation)) = LOWER(TRIM(:type_operation))
             ORDER BY b.montant_min ASC'
        );
        $statement->execute(['type_operation' => $typeOperation]);

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return is_array($rows) ? $rows : [];
    }

    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database.sqlite');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $pdo;
    }
}
