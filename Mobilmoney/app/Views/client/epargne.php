<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Epargne</title>
</head>
<body>
    <form action="<?= site_url('client/epargne') ?>" method="post">
        <div class="mot">
            ajouter votre pourcentage d'interet</p>
        </div>
        <input type="number" name="pourcentage_interet" placeholder="Pourcentage d'intérêt" required>
        <button type="submit">Soumettre</button>
    </form>

    <?php 
    if (!empty($error)) {
        echo '<p style="color: red;">Erreur : ' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>';
    }
    if (!empty($success)) {
        echo '<p style="color: green;">' . htmlspecialchars($success, ENT_QUOTES, 'UTF-8') . '</p>';
    }
    ?>

    <a href="<?= site_url('client/dashboard') ?>">Retour au tableau de bord</a>
</body>
</html>