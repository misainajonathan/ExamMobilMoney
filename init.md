# Suivi des Tâches - Version 1

| Fonctionnalité | Description / Sous-tâche | Fichier / Route | Qui | Avancement | Temps théorique (min) | Temps passé (min) | Temps restant (min) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **BDD & Setup** | Exécuter `composer create-project codeigniter4/appstarter` | Terminal | Étudiant A | Non commencé | 5 | | |
| | Initialiser le dépôt Git (`git init`) | Terminal | Étudiant A | Non commencé | 2 | | |
| | Créer le fichier `.env` à partir de `env` | Terminal / `.env` | Étudiant A | Non commencé | 2 | | |
| | Configurer `CI_ENVIRONMENT = development` dans `.env` | `.env` | Étudiant A | Non commencé | 2 | | |
| | Configurer `app.baseURL` dans `.env` | `.env` | Étudiant A | Non commencé | 2 | | |
| | Configurer le driver SQLite et le chemin de la BDD dans `.env` | `.env` | Étudiant A | Non commencé | 5 | | |
| | Créer le fichier physique de la base SQLite vide | `writable/` | Étudiant A | Non commencé | 2 | | |
| | Créer le fichier `base.sql` à la racine | `base.sql` | Étudiant A | Non commencé | 2 | | |
| | Écrire le script de création de la table `prefixes` | `base.sql` | Étudiant A | Non commencé | 5 | | |
| | Écrire le script de création de la table `types_operations` | `base.sql` | Étudiant A | Non commencé | 5 | | |
| | Écrire le script de création de la table `baremes_frais` | `base.sql` | Étudiant A | Non commencé | 5 | | |
| | Écrire le script de création de la table `clients` | `base.sql` | Étudiant A | Non commencé | 5 | | |
| | Écrire le script de création de la table `operations` | `base.sql` | Étudiant A | Non commencé | 5 | | |
| | Écrire les scripts d'insertion des données de test initiales | `base.sql` | Étudiant A | Non commencé | 5 | | |
| | Intégrer les fichiers CSS et JS de Bootstrap dans le projet | `public/` | Étudiant B | Non commencé | 10 | | |
| | Créer le fichier de template de base (Header, Footer, Main content) | `app/Views/layout/main.php` | Étudiant B | Non commencé | 15 | | |
| **Authentification** | Créer la route GET et POST pour le login automatique | `app/Config/Routes.php` | Étudiant B | Non commencé | 5 | | |
| | Créer le contrôleur `Auth.php` avec la méthode `login()` | `app/Controllers/Auth.php` | Étudiant B | Non commencé | 5 | | |
| | Créer la vue du formulaire de saisie du numéro de téléphone | `app/Views/auth/login.php` | Étudiant B | Non commencé | 10 | | |
| | Développer le modèle `ClientModel` pour interagir avec SQLite | `app/Models/ClientModel.php` | Étudiant B | Non commencé | 10 | | |
| | Développer la validation du format et des préfixes du numéro saisi | `app/Controllers/Auth.php` | Étudiant B | Non commencé | 15 | | |
| | Coder la logique de création auto du client si le numéro est valide | `app/Controllers/Auth.php` | Étudiant B | Non commencé | 15 | | |
| | Enregistrer le numéro et l'ID du client en session CI4 | `app/Controllers/Auth.php` | Étudiant B | Non commencé | 5 | | |
| **Back-Office (Opérateur)** | Créer le groupe de routes `/admin` sécurisé | `app/Config/Routes.php` | Étudiant A | Non commencé | 5 | | |
| | Développer le modèle `PrefixeModel` | `app/Models/PrefixeModel.php` | Étudiant A | Non commencé | 5 | | |
| | Créer la vue de listing et le formulaire d'ajout des préfixes | `app/Views/admin/prefixes.php` | Étudiant A | Non commencé | 15 | | |
| | Coder les méthodes CRUD pour les préfixes dans `Admin.php` | `app/Controllers/Admin.php` | Étudiant A | Non commencé | 20 | | |
| | Développer les modèles `TypeOperationModel` et `BaremeModel` | `app/Models/BaremeModel.php` | Étudiant A | Non commencé | 10 | | |
| | Créer la vue de configuration des frais par tranche | `app/Views/admin/frais.php` | Étudiant A | Non commencé | 20 | | |
| | Coder la logique de mise à jour des barèmes de frais | `app/Controllers/Admin.php` | Étudiant A | Non commencé | 20 | | |
| | Créer la vue pour la situation des gains de l'opérateur | `app/Views/admin/gains.php` | Étudiant A | Non commencé | 15 | | |
| | Coder la requête SQL de somme des frais (retraits et transferts) | `app/Controllers/Admin.php` | Étudiant A | Non commencé | 20 | | |
| | Créer la vue pour la situation des comptes clients | `app/Views/admin/comptes.php` | Étudiant A | Non commencé | 15 | | |
| | Coder la requête de listing des clients avec leur solde actuel | `app/Controllers/Admin.php` | Étudiant A | Non commencé | 20 | | |
| **Front-Office (Client)** | Créer le groupe de routes `/client` avec filtre de session | `app/Config/Routes.php` | Étudiant B | Non commencé | 5 | | |
| | Créer le contrôleur `Client.php` pour les actions client | `app/Controllers/Client.php` | Étudiant B | Non commencé | 5 | | |
| | Développer le modèle `OperationModel` | `app/Models/OperationModel.php` | Étudiant B | Non commencé | 5 | | |
| | Créer la vue du tableau de bord client (Solde + Liens actions) | `app/Views/client/dashboard.php` | Étudiant B | Non commencé | 15 | | |
| | Coder la fonction de calcul du solde à partir de l'historique | `app/Models/ClientModel.php` | Étudiant B | Non commencé | 15 | | |
| | Créer le formulaire pour effectuer un dépôt | `app/Views/client/depot.php` | Étudiant B | Non commencé | 10 | | |
| | Coder la logique d'insertion automatique du dépôt en base | `app/Controllers/Client.php` | Étudiant B | Non commencé | 15 | | |
| | Créer le formulaire pour effectuer un retrait | `app/Views/client/retrait.php` | Étudiant B | Non commencé | 10 | | |
| | Coder l'algorithme de calcul des frais de retrait selon les tranches | `app/Controllers/Client.php` | Étudiant B | Non commencé | 20 | | |
| | Coder l'insertion du retrait et le prélèvement des frais associés | `app/Controllers/Client.php` | Étudiant B | Non commencé | 15 | | |
| | Créer le formulaire pour effectuer un transfert | `app/Views/client/transfert.php` | Étudiant B | Non commencé | 10 | | |
| | Coder l'algorithme de calcul des frais de transfert selon les tranches | `app/Controllers/Client.php` | Étudiant B | Non commencé | 20 | | |
| | Coder la transaction (débit client A, crédit client B, frais opérateur) | `app/Controllers/Client.php` | Étudiant B | Non commencé | 25 | | |
| | Créer la vue de l'historique des opérations du client | `app/Views/client/historique.php` | Étudiant B | Non commencé | 15 | | |
| | Récupérer et afficher les transactions triées par date décroissante | `app/Controllers/Client.php` | Étudiant B | Non commencé | 15 | | |
| **Livraison** | Effectuer les tests unitaires manuels sur chaque fonctionnalité | Navigateur | Étudiant A & B | Non commencé | 20 | | |
| | Nettoyer le code (suppression des `var_dump`, commentaires inutiles) | Tous les fichiers | Étudiant A & B | Non commencé | 10 | | |
| | Mettre à jour le fichier `Taches.md` avec les temps réels | `Taches.md` | Étudiant A & B | Non commencé | 5 | | |
| | Exécuter `git add .` et `git commit -m "Version 1 finale"` | Terminal | Étudiant A | Non commencé | 2 | | |
| | Créer le tag v1 (`git tag v1`) | Terminal | Étudiant A | Non commencé | 2 | | |
| | Pousser les modifications et le tag (`git push origin main --tags`) | Terminal | Étudiant A | Non commencé | 3 | | |