<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h2 class="h4 mb-4 fw-bold text-dark">Barèmes de Frais par Tranche</h2>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0 bg-white">
                <thead class="bg-light text-muted uppercase small">
                    <tr>
                        <th class="ps-4">Service</th>
                        <th>Tranche Min</th>
                        <th>Tranche Max</th>
                        <th>Frais Appliqués</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($baremes)): ?>
                        <?php foreach ($baremes as $b): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="text-capitalize fw-semibold <?= $b['id_type_operation'] == 2 ? 'text-danger' : 'text-success' ?>">
                                        <?= $b['type_operation'] ?>
                                    </span>
                                </td>
                                <td><?= number_format($b['montant_min'], 2, '.', ' ') ?> Ariary</td>
                                <td><?= number_format($b['montant_max'], 2, '.', ' ') ?> Ariary</td>
                                <td class="fw-bold text-dark"><?= number_format($b['frais'], 2, '.', ' ') ?> Ariary</td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary border-0" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editFraisModal" 
                                            data-id="<?= $b['id'] ?>"
                                            data-min="<?= $b['montant_min'] ?>"
                                            data-max="<?= $b['montant_max'] ?>"
                                            data-frais="<?= $b['frais'] ?>"
                                            data-service="<?= $b['type_operation'] ?>">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Aucun barème trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Modification -->
<div class="modal fade" id="editFraisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Modifier le barème (<span id="modal-service" class="text-capitalize"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/updateFrais') ?>" method="post">
                <input type="hidden" id="modal-id" name="id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="montant_min" class="form-label text-muted small">Montant Min</label>
                            <input type="number" step="0.01" class="form-control" id="modal-min" name="montant_min" required>
                        </div>
                        <div class="col-6">
                            <label for="montant_max" class="form-label text-muted small">Montant Max</label>
                            <input type="number" step="0.01" class="form-control" id="modal-max" name="montant_max" required>
                        </div>
                        <div class="col-12">
                            <label for="frais" class="form-label text-muted small">Frais Fixe (Ariary)</label>
                            <input type="number" step="0.01" class="form-control" id="modal-frais" name="frais" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-sm">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document  .getElementById('editFraisModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    document.getElementById('modal-id').value = button.getAttribute('data-id');
    document.getElementById('modal-min').value = button.getAttribute('data-min');
    document.getElementById('modal-max').value = button.getAttribute('data-max');
    document.getElementById('modal-frais').value = button.getAttribute('data-frais');
    document.getElementById('modal-service').textContent = button.getAttribute('data-service');
});
</script>
<?= $this->endSection() ?>
