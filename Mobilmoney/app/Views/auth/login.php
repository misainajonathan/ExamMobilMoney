<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3 text-center">Connexion client</h1>

                <?php if (! empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('login') ?>">

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Numéro de téléphone</label>
                        <input
                            type="text"
                            class="form-control"
                            id="telephone"
                            name="telephone"
                            value="<?= htmlspecialchars($telephone ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Ex: 0341234567"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>