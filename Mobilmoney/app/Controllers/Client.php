<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\ClientModel;
use App\Models\OperationModel;
use App\Models\PrefixModel;

class Client extends BaseController
{
    public function index()
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return redirect()->to(site_url('login'));
        }

        $operationModel = new OperationModel();
        $clientModel = new ClientModel();

        $data = [
            'title' => 'Tableau de bord',
            'telephone' => session()->get('telephone') ?? '',
            'solde' => $clientModel->getSolde($clientId),
            'operations' => array_slice($operationModel->findByClientId($clientId), 0, 5),
            'clientId' => $clientId,
        ];

        return view('client/dashboard', $data);
    }

    public function depot()
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return redirect()->to(site_url('login'));
        }

        $data = [
            'title' => 'Dépôt',
            'error' => null,
            'montant' => '',
        ];

        if (strtolower($this->request->getMethod()) !== 'post') {
            return view('client/depot', $data);
        }

        $montant = $this->parseMontant((string) $this->request->getPost('montant'));

        if ($montant === null || $montant <= 0) {
            return view('client/depot', $data + ['error' => 'Veuillez saisir un montant valide et supérieur à 0.']);
        }

        $operationModel = new OperationModel();
        $inserted = $operationModel->insertOperation($montant, 0.0, $clientId, null, 'depot');

        if ($inserted === false) {
            return view('client/depot', $data + [
                'montant' => (string) $montant,
                'error' => "Une erreur est survenue lors de l'enregistrement du dépôt.",
            ]);
        }

        session()->setFlashdata('success', 'Dépôt de ' . number_format($montant, 2, ',', ' ') . ' Ar effectué avec succès.');

        return redirect()->to(site_url('client'));
    }

    public function retrait()
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return redirect()->to(site_url('login'));
        }

        $clientModel = new ClientModel();
        $solde = $clientModel->getSolde($clientId);

        $data = [
            'title' => 'Retrait',
            'error' => null,
            'montant' => '',
            'solde' => $solde,
        ];

        if (strtolower($this->request->getMethod()) !== 'post') {
            return view('client/retrait', $data);
        }

        $montant = $this->parseMontant((string) $this->request->getPost('montant'));

        if ($montant === null || $montant <= 0) {
            return view('client/retrait', $data + ['error' => 'Veuillez saisir un montant valide et supérieur à 0.']);
        }

        $baremeModel = new BaremeModel();

        if (! $baremeModel->hasTrancheFor('retrait', $montant)) {
            return view('client/retrait', $data + [
                'montant' => (string) $montant,
                'error' => "Ce montant ne correspond à aucune tranche de frais configurée pour le retrait.",
            ]);
        }

        $frais = $baremeModel->getFrais('retrait', $montant);
        $total = $montant + $frais;

        if ($total > $solde) {
            return view('client/retrait', $data + [
                'montant' => (string) $montant,
                'error' => 'Solde insuffisant. Montant + frais (' . number_format($total, 2, ',', ' ') . ' Ar) supérieur au solde disponible (' . number_format($solde, 2, ',', ' ') . ' Ar).',
            ]);
        }

        $operationModel = new OperationModel();
        $inserted = $operationModel->insertOperation($montant, $frais, $clientId, null, 'retrait');

        if ($inserted === false) {
            return view('client/retrait', $data + [
                'montant' => (string) $montant,
                'error' => "Une erreur est survenue lors de l'enregistrement du retrait.",
            ]);
        }

        session()->setFlashdata('success', 'Retrait de ' . number_format($montant, 2, ',', ' ') . ' Ar effectué avec succès (frais: ' . number_format($frais, 2, ',', ' ') . ' Ar).');

        return redirect()->to(site_url('client'));
    }

    public function transfert()
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return redirect()->to(site_url('login'));
        }

        $clientModel = new ClientModel();
        $solde = $clientModel->getSolde($clientId);

        $data = [
            'title' => 'Transfert',
            'error' => null,
            'montant' => '',
            'telephone_destinataire' => '',
            'solde' => $solde,
        ];

        if (strtolower($this->request->getMethod()) !== 'post') {
            return view('client/transfert', $data);
        }

        $montant = $this->parseMontant((string) $this->request->getPost('montant'));
        $telephoneDestinataire = trim((string) $this->request->getPost('telephone_destinataire'));

        if ($montant === null || $montant <= 0) {
            return view('client/transfert', $data + [
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => 'Veuillez saisir un montant valide et supérieur à 0.',
            ]);
        }

        if ($telephoneDestinataire === '') {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'error' => 'Veuillez saisir le numéro de téléphone du destinataire.',
            ]);
        }

        $destinataire = $clientModel->findByTelephone($telephoneDestinataire);

        if ($destinataire === null) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => "Aucun client n'est enregistré avec ce numéro de téléphone.",
            ]);
        }

        $destinataireId = (int) $destinataire['id'];

        if ($destinataireId === $clientId) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => 'Vous ne pouvez pas effectuer un transfert vers votre propre compte.',
            ]);
        }

        $baremeModel = new BaremeModel();

        if (! $baremeModel->hasTrancheFor('transfert', $montant)) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => "Ce montant ne correspond à aucune tranche de frais configurée pour le transfert.",
            ]);
        }

        $frais = $baremeModel->getFrais('transfert', $montant);
        $total = $montant + $frais;

        if ($total > $solde) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => 'Solde insuffisant. Montant + frais (' . number_format($total, 2, ',', ' ') . ' Ar) supérieur au solde disponible (' . number_format($solde, 2, ',', ' ') . ' Ar).',
            ]);
        }

        $operationModel = new OperationModel();
        $inserted = $operationModel->insertOperation($montant, $frais, $clientId, $destinataireId, 'transfert');

        if ($inserted === false) {
            return view('client/transfert', $data + [
                'montant' => (string) $montant,
                'telephone_destinataire' => $telephoneDestinataire,
                'error' => "Une erreur est survenue lors de l'enregistrement du transfert.",
            ]);
        }

        session()->setFlashdata('success', 'Transfert de ' . number_format($montant, 2, ',', ' ') . ' Ar vers ' . $telephoneDestinataire . ' effectué avec succès (frais: ' . number_format($frais, 2, ',', ' ') . ' Ar).');

        return redirect()->to(site_url('client'));
    }

    public function historique()
    {
        $clientId = $this->currentClientId();

        if ($clientId === null) {
            return redirect()->to(site_url('login'));
        }

        $operationModel = new OperationModel();

        $data = [
            'title' => 'Historique des opérations',
            'operations' => $operationModel->findByClientId($clientId),
            'clientId' => $clientId,
        ];

        return view('client/historique', $data);
    }

    private function parseMontant(string $raw): ?float
    {
        $raw = trim(str_replace([' ', ','], ['', '.'], $raw));

        if ($raw === '' || ! is_numeric($raw)) {
            return null;
        }

        return (float) $raw;
    }

    private function currentClientId(): ?int
    {
        $id = session()->get('client_id');
        return $id !== null ? (int) $id : null;
    }

    public function checkNumeroOperateur($prefixe)
    {
        $prefixModel = new PrefixModel();
        $p = $prefixModel->getByValeur((string) $prefixe);

        if ($p !== null) {
            return $this->response->setJSON([
                'existe' => true,
                'nom_operateur' => $p['nom_operateur'],
                'est_externe' => (int) $p['est_externe'] === 1
            ]);
        }

        return $this->response->setJSON(['existe' => false]);
    }

    public function effectuerTransfert()
    {
        $session = session();
        $idExpediteur = (int) $session->get('client_id');

        $numeroDestinataire = trim((string) $this->request->getPost('numero_destinataire'));
        $montantSaisi = (float) $this->request->getPost('montant');
        $inclureFraisRetrait = (int) $this->request->getPost('inclure_frais_retrait') === 1 ? 1 : 0;

        if ($numeroDestinataire === '' || $montantSaisi <= 0) {
            $session->setFlashdata('error', 'Données de transfert invalides.');
            return redirect()->to(site_url('client/transfert'));
        }

        $clientModel = new ClientModel();
        $destinataire = $clientModel->getByNumero($numeroDestinataire);

        // Si le destinataire n'existe pas, création automatique du compte client
        if ($destinataire === null) {
            if (!$clientModel->createClient($numeroDestinataire)) {
                $session->setFlashdata('error', 'Impossible de créer le compte du destinataire.');
                return redirect()->to(site_url('client/transfert'));
            }
            $destinataire = $clientModel->getByNumero($numeroDestinataire);
        }

        $idDestinataire = (int) $destinataire['id'];

        if ($idExpediteur === $idDestinataire) {
            $session->setFlashdata('error', 'Vous ne pouvez pas vous envoyer d\'argent à vous-même.');
            return redirect()->to(site_url('client/transfert'));
        }

        // Détection de l'opérateur de destination
        $prefixe = substr($numeroDestinataire, 0, 3);
        $prefixModel = new PrefixModel();
        $p = $prefixModel->getByValeur($prefixe);

        if ($p === null) {
            $session->setFlashdata('error', 'Opérateur destinataire non supporté.');
            return redirect()->to(site_url('client/transfert'));
        }

        $estExterne = (int) $p['est_externe'] === 1;
        $nomOperateurDest = $p['nom_operateur'];

        $baremeModel = new BaremeModel();
        $operationModel = new OperationModel();

        $montantAEnvoyer = $montantSaisi;
        $fraisBaseTransfert = 0.0;
        $fraisCommettantExterne = 0.0;

        if ($estExterne) {
            // Pas de frais de retrait inclus pour l'externe
            $inclureFraisRetrait = 0;
            
            // Calcul des frais de transfert normaux
            $fraisBaseTransfert = $baremeModel->getFraisPourMontant('transfert', $montantAEnvoyer);
            
            // Récupération de la commission additionnelle en %
            $operateurModel = new OperateurModel();
            $commissions = $operateurModel->getCommissions();
            $pctExtra = 0.0;
            foreach ($commissions as $c) {
                if ($c['nom_operateur'] === $nomOperateurDest) {
                    $pctExtra = (float) $c['commission_supplementaire_pct'];
                    break;
                }
            }
            $fraisCommettantExterne = $montantAEnvoyer * ($pctExtra / 100);
        } else {
            // Même opérateur
            if ($inclureFraisRetrait === 1) {
                $fraisRetraitFutur = $baremeModel->getFraisPourMontant('retrait', $montantSaisi);
                $montantAEnvoyer = $montantSaisi + $fraisRetraitFutur;
            }
            $fraisBaseTransfert = $baremeModel->getFraisPourMontant('transfert', $montantAEnvoyer);
        }

        $fraisTotauxAppliques = $fraisBaseTransfert + $fraisCommettantExterne;
        $totalADebiter = $montantAEnvoyer + $fraisTotauxAppliques;

        // Vérification de la provision du compte de l'expéditeur
        $soldeActuel = $operationModel->getBalanceByClientId($idExpediteur);
        if ($soldeActuel < $totalADebiter) {
            $session->setFlashdata('error', 'Solde insuffisant pour cette opération. Requis : ' . number_format($totalADebiter, 2, ',', ' ') . ' Ar');
            return redirect()->to(site_url('client/transfert'));
        }

        $est_promo = false;
        $pct = 0;
        $fraisFinaux = $this->$baremeModel->getFrais($montantSaisis, 'transfert');
        $frais = $fraisFinaux;
        if(!$estExterne){
            $pct = $a;
            $frais = $fraisFinaux * (1-($pct/100));
        }   

        // Insertion du transfert unique
        $succes = $operationModel->insertOperation(
            $montantAEnvoyer,
            $fraisTotauxAppliques,
            $idExpediteur,
            $idDestinataire,
            'transfert',
            $nomOperateurDest,
            $inclureFraisRetrait
        );

        if ($succes) {
            $session->setFlashdata('success', 'Transfert effectué avec succès.');
        } else {
            $session->setFlashdata('error', 'Échec technique de l\'opération.');
        }

        return redirect()->to(site_url('client/transfert'));
    }

    public function transfertMultiple()
    {
        $clientId = $this->currentClientId();
        if ($clientId === null) {
            return redirect()->to(site_url('login'));
        }

        $clientModel = new ClientModel();
        $data = [
            'title' => 'Envoi Multiple',
            'solde' => $clientModel->getSolde($clientId),
        ];

        return view('client/transfert_multiple', $data);
    }

    public function effectuerTransfertMultiple()
    {
        $session = session();
        $idExpediteur = (int) $session->get('client_id');
        $clientModel = new ClientModel();
        $operationModel = new OperationModel();
        $prefixModel = new PrefixModel();
        $baremeModel = new BaremeModel();

        $numerosRaw = trim((string) $this->request->getPost('numeros'));
        $montantTotal = (float) $this->request->getPost('montant_total');

        if ($numerosRaw === '' || $montantTotal <= 0) {
            $session->setFlashdata('error', 'Données d\'envoi invalides.');
            return redirect()->to(site_url('client/transfertMultiple'));
        }

        // Séparer les numéros par virgule et nettoyer
        $numeros = array_unique(array_filter(array_map('trim', explode(',', $numerosRaw))));
        $nombreDestinataires = count($numeros);

        if ($nombreDestinataires === 0) {
            $session->setFlashdata('error', 'Aucun numéro valide saisi.');
            return redirect()->to(site_url('client/transfertMultiple'));
        }

        // Récupérer l'opérateur de l'expéditeur
        $expediteur = $clientModel->find($idExpediteur); // Ou obtenir le numéro depuis la session
        $monNumero = session()->get('telephone') ?? '';
        $monPrefixe = substr($monNumero, 0, 3);
        $monOperateur = $prefixModel->getByValeur($monPrefixe);

        if ($monOperateur === null) {
            $session->setFlashdata('error', 'Impossible de détecter votre opérateur.');
            return redirect()->to(site_url('client/transfertMultiple'));
        }

        // 1. Validation : Même opérateur uniquement
        foreach ($numeros as $num) {
            if ($num === $monNumero) {
                $session->setFlashdata('error', 'Vous ne pouvez pas inclure votre propre numéro.');
                return redirect()->to(site_url('client/transfertMultiple'));
            }

            $prefixeDest = substr($num, 0, 3);
            $opDest = $prefixModel->getByValeur($prefixeDest);

            if ($opDest === null || $opDest['nom_operateur'] !== $monOperateur['nom_operateur'] || (int)$opDest['est_externe'] === 1) {
                $session->setFlashdata('error', "Le numéro $num n'appartient pas au même opérateur que vous.");
                return redirect()->to(site_url('client/transfertMultiple'));
            }
        }

        // 2. Division équitable du montant
        $montantParPersonne = $montantTotal / $nombreDestinataires;
        
        // Calcul des frais par transfert (Même opérateur = frais standard sans commission externe)
        $fraisParTransfert = $baremeModel->getFraisPourMontant('transfert', $montantParPersonne);
        $totalFraisToutesOperations = $fraisParTransfert * $nombreDestinataires;
        $coutTotalDebite = $montantTotal + $totalFraisToutesOperations;

        // Vérification du solde global
        $soldeActuel = $operationModel->getBalanceByClientId($idExpediteur);
        if ($soldeActuel < $coutTotalDebite) {
            $session->setFlashdata('error', 'Solde insuffisant pour le montant global + frais (Requis : ' . number_format($coutTotalDebite, 2, ',', ' ') . ' Ar)');
            return redirect()->to(site_url('client/transfertMultiple'));
        }

        // 3. Exécution des transactions
        $succesGlobal = true;
        foreach ($numeros as $num) {
            $destinataire = $clientModel->getByNumero($num);
            if ($destinataire === null) {
                $clientModel->createClient($num);
                $destinataire = $clientModel->getByNumero($num);
            }

            $inserted = $operationModel->insertOperation(
                $montantParPersonne,
                $fraisParTransfert,
                $idExpediteur,
                (int)$destinataire['id'],
                'transfert',
                $monOperateur['nom_operateur'],
                0
            );
            
            if (!$inserted) {
                $succesGlobal = false;
            }
        }

        if ($succesGlobal) {
            $session->setFlashdata('success', 'Envoi multiple effectué avec succès à ' . $nombreDestinataires . ' destinataires.');
        } else {
            $session->setFlashdata('error', 'Certaines transactions ont échoué lors du traitement.');
        }

        return redirect()->to(site_url('client/dashboard'));
    }


}