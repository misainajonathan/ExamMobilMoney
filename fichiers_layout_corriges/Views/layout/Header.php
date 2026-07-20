<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobileMoney</title>
    <!-- Chargement local du CSS Bootstrap -->
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <!-- Icônes Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
    <div class="container">
        <!-- Logo / Nom de l'application -->
        <a class="navbar-brand fw-bold text-primary" href="<?= base_url('client') ?>">
            <i class="bi bi-wallet2 me-2"></i>MobileMoney
        </a>
        
        <!-- Info Client & Déconnexion -->
        <div class="d-flex align-items-center ms-auto">
            <span class="text-muted me-3 d-none d-sm-inline">
                <i class="bi bi-person me-1"></i> <?= esc(session()->get('client_telephone')) ?>
            </span>
            <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-danger border-0">
                <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Quitter</span>
            </a>
        </div>
    </div>
</nav>