<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm border-success">
            <div class="card-body p-4 text-center">
                <p class="text-muted mb-1">Gains Opérateur Interne</p>
                <h1 class="display-5 fw-bold text-success"><?= number_format($gains_internes, 2, ',', ' ') ?> Ar</h1>
                <small class="text-muted">Total des frais (Dépôts, Retraits, Transferts nationaux)</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Gains via les autres opérateurs</h2>
                <?php if ($gains_externes === []): ?>
                    <p class="text-muted mb-0">Aucun gain généré via les autres opérateurs pour le moment.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Opérateur</th>
                                    <th class="text-end">Frais Collectés</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($gains_externes as $g): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars((string) $g['operateur_destination'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-end text-primary fw-bold"><?= number_format((float) $g['total'], 2, ',', ' ') ?> Ar</td>
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