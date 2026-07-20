<div class="d-flex flex-column flex-shrink-0 p-3 bg-light border-end" style="width: 280px; height: 100vh; position: fixed; top: 0; left: 0;">
    <!-- Titre Admin -->
    <a href="<?= base_url('admin/dashboard') ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <i class="bi bi-speedometer2 fs-4 me-2 text-primary"></i>
        <span class="fs-5 fw-semibold">Opérateur Panel</span>
    </a>
    <hr>
    
    <!-- Liens de Navigation -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/prefixes') ?>" class="nav-link text-dark active">
                <i class="bi bi-gear me-2"></i> Préfixes
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/frais') ?>" class="nav-link text-dark">
                <i class="bi bi-sliders me-2"></i> Barèmes de frais
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/gains') ?>" class="nav-link text-dark">
                <i class="bi bi-graph-up-arrow me-2"></i> Situation des gains
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= base_url('admin/comptes') ?>" class="nav-link text-dark">
                <i class="bi bi-people me-2"></i> Comptes clients
            </a>
        </li>
    </ul>
    <hr>
    
    <!-- Déconnexion Admin -->
    <div>
        <a href="<?= base_url('auth/logout') ?>" class="nav-link text-danger p-0">
            <i class="bi bi-box-arrow-left me-2"></i> Déconnexion
        </a>
    </div>
</div>

<!-- Conteneur principal pour décaler le contenu des vues vers la droite -->
<div style="margin-left: 280px; min-height: 100vh;" class="bg-light">