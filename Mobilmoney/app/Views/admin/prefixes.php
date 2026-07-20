<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-12 col-md-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Ajouter un préfixe</h2>
                <form action="<?= site_url('admin/addPrefix') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Valeur du préfixe</label>
                        <input type="text" name="valeur" class="form-control" placeholder="Ex: 032" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type d'opérateur</label>
                        <select name="est_externe" id="est_externe" class="form-select" onchange="toggleNomOperateur()">
                            <option value="0">Interne (Propre opérateur)</option>
                            <option value="1">Externe (Autre opérateur)</option>
                        </select>
                    </div>
                    <div class="mb-3" id="group_nom_operateur" style="display:none;">
                        <label class="form-label">Nom de l'opérateur externe</label>
                        <input type="text" name="nom_operateur" id="nom_operateur" class="form-control" placeholder="Ex: Orange">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Liste des préfixes configurés</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Préfixe</th>
                                <th>Type</th>
                                <th>Nom Opérateur</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prefixes as $p): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars((string) $p['valeur'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $p['est_externe'] ? 'warning' : 'success' ?>">
                                            <?= $p['est_externe'] ? 'Externe' : 'Interne' ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars((string) $p['nom_operateur'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-end">
                                        <a href="<?= site_url('admin/deletePrefix/' . $p['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce préfixe ?')">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleNomOperateur() {
    var select = document.getElementById('est_externe');
    var group = document.getElementById('group_nom_operateur');
    var input = document.getElementById('nom_operateur');
    if (select.value === '1') {
        group.style.display = 'block';
        input.required = true;
    } else {
        group.style.display = 'none';
        input.required = false;
        input.value = '';
    }
}
</script>
<?= $this->endSection() ?>