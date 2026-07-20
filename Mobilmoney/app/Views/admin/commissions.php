<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 mb-3">Configuration des commissions pour les autres opérateurs</h2>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= site_url('admin/updateCommissions') ?>" method="post">
            <div class="table-responsive mb-4">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Opérateur Externe</th>
                            <th>% de Commission Supplémentaire (Transfert)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($commissions === []): ?>
                            <tr>
                                <td colspan="2" class="text-muted text-center">Aucun opérateur externe configuré. Ajoutez d'abord des préfixes externes.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($commissions as $c): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars((string) $c['nom_operateur'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <div class="input-group" style="max-width: 200px;">
                                            <input type="number" step="0.01" min="0" name="commissions[<?= htmlspecialchars((string) $c['nom_operateur'], ENT_QUOTES, 'UTF-8') ?>]" value="<?= htmlspecialchars((string) $c['commission_supplementaire_pct'], ENT_QUOTES, 'UTF-8') ?>" class="form-control" required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($commissions !== []): ?>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <?php endif; ?>
        </form>
    </div>
</div>
<?= $this->endSection() ?>