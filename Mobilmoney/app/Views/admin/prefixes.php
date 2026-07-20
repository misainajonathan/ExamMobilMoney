<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 fw-bold text-dark">Gestion des Préfixes</h2>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPrefixModal">
            <i class="bi bi-plus-lg me-1"></i> Ajouter un préfixe
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0 bg-white">
                <thead class="bg-light text-muted uppercase small">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Préfixe</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($prefixes)): ?>
                        <?php foreach ($prefixes as $p): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= $p['id'] ?></td>
                                <td><span class="badge bg-secondary px-2.5 py-1.5 fs-6"><?= $p['prefixe'] ?></span></td>
                                <td class="text-end pe-4">
                                    <a href="<?= base_url('admin/deletePrefix/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Supprimer ce préfixe ?');">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Aucun préfixe configuré.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="addPrefixModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Nouveau préfixe</h5>
                <button type="button" class="btn-close" data-bs-close="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/addPrefix') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="prefixe" class="form-label text-muted small">Code préfixe (ex: 033)</label>
                        <input type="text" class="form-control" id="prefixe" name="prefixe" placeholder="Ex: 034" required maxlength="3" minlength="3">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>