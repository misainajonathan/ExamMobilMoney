<?= $this->extend('layout/client') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-12 col-md-5">
        <div class="card shadow-sm text-center">
            <div class="card-body p-4">
                <p class="text-muted mb-1">Numéro</p>
                <h2 class="h5 mb-3"><?= htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="text-muted mb-1">Solde actuel</p>
                <h1 class="display-6 fw-bold text-primary"><?= number_format((float) $solde, 2, ',', ' ') ?> Ar</h1>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <a href="<?= site_url('client/depot') ?>" class="btn btn-success">Faire un dépôt</a>
            <a href="<?= site_url('client/retrait') ?>" class="btn btn-warning">Faire un retrait</a>
            <a href="<?= site_url('client/transfert') ?>" class="btn btn-info text-white">Faire un transfert</a>
            <a href="<?= site_url('client/transfertMultiple') ?>" class="btn btn-outline-info">Faire un transfert multiple</a>
            <a href="<?= site_url('client/historique') ?>" class="btn btn-outline-secondary">Voir l'historique complet</a>
        </div>
    </div>

    <div class="col-12 col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Dernières opérations</h2>

                <?php if ($operations === []): ?>
                    <p class="text-muted mb-0">Aucune opération pour le moment.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Montant</th>
                                    <th class="text-end">Frais</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($operations as $operation): ?>
                                    <?php
                                        $isExpediteur = (int) $operation['id_client_expediteur'] === (int) $clientId;
                                        $type = htmlspecialchars((string) $operation['type_operation'], ENT_QUOTES, 'UTF-8');
                                        $signe = ($operation['type_operation'] === 'depot' || (! $isExpediteur)) ? '+' : '-';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string) $operation['date_operation'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><span class="badge bg-secondary text-capitalize"><?= $type ?></span></td>
                                        <td class="text-end"><?= $signe ?><?= number_format((float) $operation['montant'], 2, ',', ' ') ?> Ar</td>
                                        <td class="text-end"><?= number_format((float) $operation['frais_appliques'], 2, ',', ' ') ?> Ar</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>