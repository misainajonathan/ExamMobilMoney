<?= $this->extend('layout/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Faire un dépôt</h1>
                <p class="text-muted">Le dépôt est instantané et sans frais.</p>

                <?php if (! empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="post" action="/client/depot">
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant à déposer (Ar)</label>
                        <input
                            type="text"
                            inputmode="decimal"
                            class="form-control"
                            id="montant"
                            name="montant"
                            value="<?= htmlspecialchars($montant ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Ex: 10000"
                            required
                        >
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill">Valider le dépôt</button>
                        <a href="/client" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
