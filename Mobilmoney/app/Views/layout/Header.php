<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
    <div class="container">
        <!-- Logo / Nom de l'application -->
        <a class="navbar-brand fw-bold text-primary" href="<?= base_url('client/dashboard') ?>">
            <i class="bi bi-wallet2 me-2"></i>MobileMoney
        </a>
        
        <!-- Info Client & Déconnexion -->
        <div class="d-flex align-items-center ms-auto">
            <span class="text-muted me-3 d-none d-sm-inline">
                <i class="bi bi-person me-1"></i> <?= session()->get('telephone') ?>
            </span>
            <a href="<?= base_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger border-0">
                <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Quitter</span>
            </a>
        </div>
    </div>
</nav>