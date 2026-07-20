<div class="container-fluid py-4">
    <h2 class="h4 mb-4 fw-bold text-dark">Situation des Gains Opérateur</h2>

    <div class="row g-4 mb-4">
        <!-- Gains Retraits -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-danger bg-opacity-10 text-danger p-3 rounded">
                        <i class="bi bi-box-arrow-down-left fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1 uppercase tracking-wider">Gains sur Retraits</p>
                        <h3 class="fw-bold mb-0 text-dark"><?= number_format($gains['retrait'], 2, '.', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gains Transferts -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success p-3 rounded">
                        <i class="bi bi-arrow-left-right fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted small mb-1 uppercase tracking-wider">Gains sur Transferts</p>
                        <h3 class="fw-bold mb-0 text-dark"><?= number_format($gains['transfert'], 2, '.', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Chiffre d'Affaires -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm p-4 bg-primary text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-20 text-white p-3 rounded">
                        <i class="bi bi-currency-exchange fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-white bg-opacity-70 small mb-1 uppercase tracking-wider">Total des Gains</p>
                        <h3 class="fw-bold mb-0"><?= number_format($gains['total'], 2, '.', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>