<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Panel Opérateur', ENT_QUOTES, 'UTF-8') ?></title>
    <!-- Chargement local du CSS Bootstrap -->
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <!-- Icônes Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<?= $this->include('layout/Sidebar') ?>

    <?php $flashSuccess = session()->getFlashdata('success'); ?>
    <?php if (! empty($flashSuccess)): ?>
        <div class="container-fluid pt-4">
            <div class="alert alert-success"><?= esc($flashSuccess) ?></div>
        </div>
    <?php endif; ?>

    <?php $flashError = session()->getFlashdata('error'); ?>
    <?php if (! empty($flashError)): ?>
        <div class="container-fluid pt-4">
            <div class="alert alert-danger"><?= esc($flashError) ?></div>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div><!-- /.conteneur décalé ouvert dans layout/Sidebar.php -->

<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
