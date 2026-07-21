<?= $this->extend('layout/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h5 mb-0">Envoi Multiple</h2>
                    <a href="<?= site_url('client/transfert') ?>" class="btn btn-sm btn-outline-secondary">&laquo; Envoi simple</a>
                </div>
                <p class="text-muted small mb-4">Même opérateur uniquement. Le montant global saisi sera divisé équitablement entre chaque numéro.</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= site_url('client/effectuerTransfertMultiple') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Numéros des destinataires (séparés par des virgules)</label>
                        <textarea name="numeros" class="form-control" rows="3" placeholder="Ex: 0321122233, 0324455566, 0327788899" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Montant Global Total (Ar)</label>
                        <input type="number" step="0.01" min="1" name="montant_total" class="form-control" placeholder="Entrez le montant total à diviser" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill">Confirmer l'envoi multiple</button>
                        <a href="<?= site_url('client') ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>