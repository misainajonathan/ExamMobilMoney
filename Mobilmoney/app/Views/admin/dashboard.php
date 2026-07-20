<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h2 class="h4 mb-4 fw-bold text-dark">Tableau de bord - Opérateur</h2>

    <div class="row g-4 mb-4">
        <!-- Carte Gains Totaux -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary p-3 rounded">
                        <i class="bi bi-currency-exchange fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1 uppercase tracking-wider">Total des Gains</p>
                        <h3 class="fw-bold mb-0 text-dark"><?= number_format($gains['total'], 2, '.', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Total Clients -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success p-3 rounded">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1 uppercase tracking-wider">Clients Enregistrés</p>
                        <h3 class="fw-bold mb-0 text-dark"><?= $total_clients ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Total Préfixes -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning p-3 rounded">
                        <i class="bi bi-hash fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1 uppercase tracking-wider">Préfixes Actifs</p>
                        <h3 class="fw-bold mb-0 text-dark"><?= $total_prefixes ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
