<?php

namespace App\Models;

class BaremeModel
{
    private function pdo(): \PDO
    {
        $pdo = new \PDO('sqlite:' . __DIR__ . '/../../writable/database/database.sqlite');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return $pdo;
    }

    /**
     * Retourne le montant des frais applicables pour un type d'opération et un montant donnés,
     * en se basant sur les tranches définies dans bareme_frais.
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
     * Alias réutilisant la méthode getFrais pour éviter la duplication de code.
     */
    public function getFraisPourMontant(string $typeOperation, float $montant): float
    {
        return $this->getFrais($typeOperation, $montant);
    }

    /**
     * Vérifie qu'une tranche de frais existe pour ce type d'opération et ce montant.
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

    /**
     * Récupère tous les barèmes avec le libellé de leur type d'opération.
     */
    public function getBaremesComplets(): array
    {
        $statement = $this->pdo()->query(
            'SELECT b.id, b.montant_min, b.montant_max, b.frais, t.type_operation
             FROM bareme_frais b
             INNER JOIN type_operation t ON t.id = b.id_type_operation
             ORDER BY t.type_operation ASC, b.montant_min ASC'
        );
        $rows = $statement !== false ? $statement->fetchAll(\PDO::FETCH_ASSOC) : [];

        return is_array($rows) ? $rows : [];
    }

    /**
     * Met à jour une tranche de barème.
     */
    public function updateBareme(int $id, array $data): bool
    {
        $statement = $this->pdo()->prepare(
            'UPDATE bareme_frais 
             SET montant_min = :montant_min, montant_max = :montant_max, frais = :frais 
             WHERE id = :id'
        );
        return $statement->execute([
            'id'          => $id,
            'montant_min' => $data['montant_min'],
            'montant_max' => $data['montant_max'],
            'frais'       => $data['frais'],
        ]);
    }

        // public function promotion($montant){
        // $est_promo = false;
        // $pct = 0;
        // $fraisFinaux = $this->getFraisFinaux($montant);
        // $frais = $fraisFinaux;
        // if(!$estExterne){
        //     $pct = $a;
        //     $frais = $fraisFinaux * (1-($pct/100));
        // }        
    //
}