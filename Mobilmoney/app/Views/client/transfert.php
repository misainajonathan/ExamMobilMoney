<?= $this->extend('layout/client') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-4">Effectuer un transfert de fonds</h2>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= site_url('client/effectuerTransfert') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Numéro du destinataire</label>
                        <input type="text" name="numero_destinataire" id="numero_destinataire" class="form-control" placeholder="Ex: 032XXXXXXX ou 034XXXXXXX" required oninput="verifierOperateur()">
                        <div id="operateur_badge" class="form-text mt-1"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Montant (Ar)</label>
                        <input type="number" step="0.01" min="1" name="montant" class="form-control" placeholder="Entrez le montant" required>
                    </div>

                    <div class="mb-4 form-check" id="container_frais_retrait">
                        <input type="checkbox" class="form-check-input" name="inclure_frais_retrait" id="inclure_frais_retrait" value="1">
                        <label class="form-check-label" for="inclure_frais_retrait">
                            Inclure les frais de retrait pour le destinataire (Même opérateur uniquement)
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Confirmer le transfert</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function verifierOperateur() {
    var numero = document.getElementById('numero_destinataire').value.trim();
    var badge = document.getElementById('operateur_badge');
    var checkboxContainer = document.getElementById('container_frais_retrait');
    var checkbox = document.getElementById('inclure_frais_retrait');

    if (numero.length >= 3) {
        var prefixe = numero.substring(0, 3);
        
        fetch('<?= site_url('client/checkNumeroOperateur/') ?>' + prefixe)
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    badge.innerHTML = 'Opérateur détecté : <strong>' + data.nom_operateur + '</strong> (' + (data.est_externe ? 'Externe' : 'Interne') + ')';
                    if (data.est_externe) {
                        checkboxContainer.style.display = 'none';
                        checkbox.checked = false;
                    } else {
                        checkboxContainer.style.display = 'block';
                    }
                } else {
                    badge.innerHTML = '<span class="text-danger">Préfixe non reconnu</span>';
                    checkboxContainer.style.display = 'block';
                }
            });
    } else {
        badge.innerHTML = '';
        checkboxContainer.style.display = 'block';
    }
}
</script>
<?= $this->endSection() ?>