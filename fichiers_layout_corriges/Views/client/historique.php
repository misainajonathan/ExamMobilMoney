<?= $this->extend('layout/client') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-body p-4">
        <h1 class="h4 mb-4">Historique des opérations</h1>

        <?php if ($operations === []): ?>
            <p class="text-muted mb-0">Aucune opération enregistrée pour le moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Sens</th>
                            <th class="text-end">Montant</th>
                            <th class="text-end">Frais</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($operations as $operation): ?>
                            <?php
                                $isExpediteur = (int) $operation['id_client_expediteur'] === (int) $clientId;
                                $type = htmlspecialchars((string) $operation['type_operation'], ENT_QUOTES, 'UTF-8');
                                $sens = $isExpediteur ? 'Sortant' : 'Entrant';
                                $signe = ($operation['type_operation'] === 'depot' || ! $isExpediteur) ? '+' : '-';
                                $badgeClass = $sens === 'Entrant' ? 'bg-success' : 'bg-danger';
                            ?>
                            <tr>
                                <td><?= htmlspecialchars((string) $operation['date_operation'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><span class="badge bg-secondary text-capitalize"><?= $type ?></span></td>
                                <td><span class="badge <?= $badgeClass ?>"><?= $sens ?></span></td>
                                <td class="text-end"><?= $signe ?><?= number_format((float) $operation['montant'], 2, ',', ' ') ?> Ar</td>
                                <td class="text-end"><?= number_format((float) $operation['frais_appliques'], 2, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <a href="/client" class="btn btn-outline-secondary mt-3">Retour au tableau de bord</a>
    </div>
</div>
<?= $this->endSection() ?>
