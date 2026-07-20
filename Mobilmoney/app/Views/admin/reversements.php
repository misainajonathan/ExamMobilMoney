<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 mb-3">Situation des montants cumulés à envoyer à chaque opérateur</h2>
        <p class="text-muted small">Ce tableau récapitule les capitaux initiaux transférés par nos clients vers des réseaux tiers, devant faire l'objet d'une compensation financière.</p>
        
        <?php if ($reversements === []): ?>
            <p class="text-muted mb-0">Aucun montant à envoyer pour le moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Opérateur Destinataire</th>
                            <th class="text-end">Montant Total des Capitaux à Reverser</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reversements as $r): ?>
                            <tr>
                                <td class="fw-bold text-uppercase"><?= htmlspecialchars((string) $r['operateur_destination'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="text-end text-danger fw-bold fs-5"><?= number_format((float) $r['total_montant'], 2, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>