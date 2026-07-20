<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Faire un retrait</h1>
                <p class="text-muted mb-3">Solde disponible : <strong><?= number_format((float) $solde, 2, ',', ' ') ?> Ar</strong></p>
                <p class="text-muted small">Des frais sont appliqués selon la tranche du montant retiré.</p>

                <?php if (! empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="post" action="/client/retrait">
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant à retirer (Ar)</label>
                        <input
                            type="text"
                            inputmode="decimal"
                            class="form-control"
                            id="montant"
                            name="montant"
                            value="<?= htmlspecialchars($montant ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Ex: 5000"
                            required
                        >
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-fill">Valider le retrait</button>
                        <a href="/client" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
