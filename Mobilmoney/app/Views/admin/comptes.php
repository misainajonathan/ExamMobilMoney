<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h2 class="h4 mb-4 fw-bold text-dark">Situation des Comptes Clients</h2>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0 bg-white">
                <thead class="bg-light text-muted uppercase small">
                    <tr>
                        <th class="ps-4">ID Client</th>
                        <th>Numéro de Téléphone</th>
                        <th class="text-end pe-4">Solde Actuel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clients)): ?>
                        <?php foreach ($clients as $c): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?= $c['id'] ?></td>
                                <td class="fw-semibold text-dark">
                                    <i class="bi bi-phone me-1 text-muted"></i> <?= $c['telephone'] ?>
                                </td>
                                <td class="text-end pe-4 fw-bold <?= $c['solde'] < 0 ? 'text-danger' : 'text-dark' ?>">
                                    <?= number_format($c['solde'], 2, '.', ' ') ?> Ariary
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Aucun client enregistré sur le réseau.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
