<?= $this->include('layout/Header') ?>

<main class="container py-4">
    <?php $flashSuccess = $successMessage ?? session()->getFlashdata('success'); ?>
    <?php if (! empty($flashSuccess)): ?>
        <div class="alert alert-success"><?= esc($flashSuccess) ?></div>
    <?php endif; ?>

    <?php $flashError = session()->getFlashdata('error'); ?>
    <?php if (! empty($flashError)): ?>
        <div class="alert alert-danger"><?= esc($flashError) ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</main>

<?= $this->include('layout/Footer') ?>

<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
